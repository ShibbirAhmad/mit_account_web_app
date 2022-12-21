
<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="goBack" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </a>

          <a @click="showModal" class="btn btn-primary">
            <i class="fa fa-plus"></i> add advertise account
          </a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>reseller</a>
          </li>
          <li class="active">Boost</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row over_view_row">
            <div class="col-md-4">
              <h5 class="heading">
                Name : <strong>{{ boost_agency_reseller.name }} </strong>
              </h5>
              <h5 class="heading">
                Company:
                <strong>{{ boost_agency_reseller.company_name }} </strong>
              </h5>
              <h5 class="heading">
                Phone : <strong> {{ boost_agency_reseller.phone }}</strong>
              </h5>
              <h5 class="heading">
                Address :
                <strong>
                  {{
                    boost_agency_reseller.address
                      ? boost_agency_reseller.address
                      : "empty"
                  }}
                </strong>
              </h5>
            </div>

            <div class="col-md-1"></div>
            <div class="col-md-6">
              <div class="account_container">
                <ul class="list-group">
                  <li
                    v-for="(account, index) in boost_agency_reseller.accounts"
                    :key="index"
                    class="list-group-item"
                  >
                    account - {{ index + 1 }} :
                    <strong> {{ account.name }} <sup style="font-size:13px;" >(previous limit : {{ account.previous_dollar  }}  + current limit : {{ account.total_dollar  }} = {{ parseInt(account.previous_dollar) + parseInt(account.total_dollar )  }} )</sup></strong>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row data_table_row">
            <div class="col-md-12 col-sm-12">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <a
                    class="btn btn_conditional"
                    @click="dollarMode = true"
                    :class="{ active_border: dollarMode == true }"
                  >
                    Dollar History
                  </a>
                  <a
                    class="btn btn_conditional"
                    @click="dollarMode = false"
                    :class="{ active_border: dollarMode == false }"
                  >
                    Payment History
                  </a>
                </div>

                <div v-if="dollarMode == true">
                  <div class="box-body">
                    <table
                      class="
                        table table-hover table-striped
                        text-center
                        table-bordered
                      "
                    >
                      <tr>
                        <th>#</th>
                        <th>Advertise Account</th>
                        <th>Page Name</th>
                        <th>Dollar</th>
                        <th>Amount</th>
                        <th>Action</th>
                      </tr>
                      <tbody>
                        <tr v-for="(item, index) in accounts" :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>
                            <button
                              v-on:click="displayAccountDetails(item)"
                              style="font-size: 16px"
                              class="btn_account"
                            >
                              {{ item.name }}
                            </button>
                          </td>
                          <td>
                            {{ item.page_name ? item.page_name : "empty" }}
                          </td>
                          <td>{{ item.total_dollar }}</td>
                          <td>{{ item.total_amount }}</td>
                          <td>
                            <a
                              @click="showDollarTransferModal(item)"
                              class="btn btn-xs btn-primary"
                            >
                              <i class="fa fa-exchange"></i> Dollar Transfer
                            </a>
                            <a
                              :href="
                                '/api/download/pdf/boost/reseller/ad-account/' +
                                item.id
                              "
                              class="btn btn-xs btn-success"
                            >
                              <i class="fa fa-download"></i> PDF</a
                            >
                          </td>
                        </tr>
                        <tr>
                          <td colspan="3">In Total</td>
                          <td>
                            <span class="badge badge-success">
                              <i class="fa fa-dollar"></i> {{ overAllDollar() }}
                            </span>
                          </td>
                          <td>
                            <span class="badge badge-success">
                              &#2547; {{ overAllAmount() }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div v-if="dollarMode != true">
                  <div class="box-body">
                    <table
                      class="
                        table table-hover table-striped
                        text-center
                        table-bordered
                      "
                    >
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Comment</th>
                        <th>Paid By</th>
                        <th>Amount</th>
                      </tr>
                      <tbody>
                        <tr v-for="(item, index) in payments.data" :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>{{ item.created_at }}</td>
                          <td>{{ item.comment ? item.comment : "none" }}</td>
                          <td>{{ item.paid_by }}</td>
                          <td>{{ item.amount }}</td>
                        </tr>
                        <tr>
                          <td colspan="4">In Total Paid</td>
                          <td>
                            <span class="badge badge-success">
                              &#2547; {{ payment_total }}
                            </span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="box-footer">
                    <div class="row">
                      <div class="col-sm-12">
                        <pagination
                          :data="payments"
                          @pagination-change-page="transactionDetails"
                          :limit="3"
                        >
                        </pagination>
                      </div>
                      <div class="col-sm-12">
                        <p>
                          Showing <strong>{{ payments.from }}</strong> to
                          <strong>{{ payments.to }}</strong> of total
                          <strong>{{ payments.total }}</strong> entries
                        </p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- start payment store modal  -->
    <modal name="account_details_modal" :width="700" :height="500">
      <div class="card" style="padding: 20px">
        <div class="card-header text-center">
          <h4 class="heading">{{ account_name }}</h4>
        </div>
        <div class="card-body">
          <table
            class="table table-hover table-striped text-center table-bordered"
          >
            <tr>
              <th>#</th>
              <th>Date</th>
              <th>Dollar</th>
              <th>BDT(rate)</th>
              <th>Amount</th>
            </tr>
            <tbody>
              <tr v-for="(item, index) in account_details" :key="index">
                <td>{{ index + 1 }}</td>
                <td>{{ item.created_at }}</td>
                <td>{{ item.dollar }}</td>
                <td>{{ item.rate }}</td>
                <td>{{ item.amount }}</td>
              </tr>

              <tr>
                <td colspan="2"></td>
                <td>
                  <span class="badge badge-success">
                    <i class="fa fa-dollar"></i> {{ detailsTotalDollar }}
                  </span>
                </td>
                <td></td>
                <td>
                  <span class="badge badge-success">
                    &#2547; {{ detailsTotalAmount }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </modal>
    <!-- end payment store modal  -->

    <!-- start payment store modal  -->
    <modal name="add_account_modal" :width="350" :height="250">
      <form @submit.prevent="addResellerAdAccount">
        <div class="card" style="padding: 20px">
          <div class="card-body">
            <div class="form-group">
              <label>Advertise Account Name</label>
              <input
                type="text"
                name="name"
                required
                v-model="accountForm.name"
                class="form-control"
                placeholder="abc"
              />

            </div>

            <div class="form-group">
              <label>Page Name</label>
              <input
                type="text"
                name="page_name"
                v-model="accountForm.page_name"
                class="form-control"
                placeholder="xyz"
                required
              />
            </div>

           <div class="form-group">
              <label>Previous Limit (optional)</label>
              <input
                type="text"
                name="page_name"
                v-model="accountForm.previous_dollar"
                class="form-control"
                placeholder="0000"
                required
              />
            </div>
            <br />
            <div class="form-group text-center">
              <button type="submit" class="btn btn-sm btn-success">
                Submit
              </button>
            </div>
            <br />
          </div>
        </div>
      </form>
    </modal>
    <!-- end payment store modal  -->

    <!-- start payment store modal  -->
    <modal name="dollar_transfer_modal" :width="450" :height="400">
      <form @submit.prevent="dollarTransfer">
        <div class="card" style="padding: 20px">
          <div class="card-body">
            <div class="form-group">
              <label>Select Reseller </label>
              <select
                name="boost_agency_reseller_id"
                v-model="DollarTransferForm.boost_agency_reseller_id"
                :class="{
                  'is-invalid': DollarTransferForm.errors.has(
                    'boost_agency_reseller_id'
                  ),
                }"
                class="form-control"
              >
                <option disabled>Select One</option>
                <option
                  v-for="reseller in boost_resellers"
                  :value="reseller.id"
                  :key="reseller.id"
                >
                  {{ reseller.company_name }}
                </option>
              </select>
              <has-error
                :form="DollarTransferForm"
                field="boost_agency_reseller_id"
              >
              </has-error>
            </div>

            <div class="form-group">
              <label>Advertise Account Name</label>
              <input
                type="text"
                name="name"
                required
                v-model="DollarTransferForm.name"
                :class="{ 'is-invalid': DollarTransferForm.errors.has('name') }"
                class="form-control"
                placeholder="abc"
              />
              <has-error :form="DollarTransferForm" field="name"> </has-error>
            </div>

            <div class="form-group">
              <label>Page Name</label>
              <input
                type="text"
                name="page_name"
                v-model="DollarTransferForm.page_name"
                :class="{
                  'is-invalid': DollarTransferForm.errors.has('page_name'),
                }"
                class="form-control"
                placeholder="xyz"
                required
              />
              <has-error :form="DollarTransferForm" field="page_name">
              </has-error>
            </div>

            <div class="form-group">
              <label>Transaction Dollar </label>
              <input
                type="number"
                name="dollar"
                required
                v-model="DollarTransferForm.dollar"
                :class="{
                  'is-invalid': DollarTransferForm.errors.has('dollar'),
                }"
                class="form-control"
                placeholder="000"
              />
              <has-error :form="DollarTransferForm" field="dollar"> </has-error>
            </div>

            <br />
            <div class="form-group text-center">
              <button type="submit" class="btn btn-sm btn-success">
                Submit
              </button>
            </div>
            <br />
          </div>
        </div>
      </form>
    </modal>
    <!-- end payment store modal  -->
  </div>
</template>

<script>

import { Form, HasError, AlertError } from "vform";

export default {
  created() {
    this.transactionDetails();
  },
  data() {
    return {
      dollarMode: true,
      paymentMode: false,
      boost_agency_reseller: "",
      boost_resellers: "",
      accounts: "",
      account_name: "",
      account_details: "",
      detailsTotalAmount: "",
      detailsTotalDollar: "",
      payments: {},
      payment_total: "",
      loading: true,
      accountForm: new Form({
        boost_agency_reseller_id: this.$route.params.id,
        name: "",
        page_name: "",
        previous_dollar: "",
      }),
      DollarTransferForm: new Form({
        boost_agency_reseller_id: "",
        name: "",
        page_name: "",
        transfer_account_id: "",
        dollar: "",
      }),
    };
  },
  methods: {


  async addResellerAdAccount() {

     await this.accountForm
        .post("/api/boost/reseller/advertise/account/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true ) {
            this.accountForm.name = "";
            this.accountForm.page_name = "";
            this.accountForm.previous_dollar = "";
            this.transactionDetails();
            this.$modal.hide("add_account_modal");
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            })
          }
        })
        .catch((error) => {
          this.$toasted.show(error.response.data.message, {
            type: "error",
            position: "top-center",
            duration: 4000,
          })
        });
    },

    resellerList() {
      axios.get("/api/boost/reseller/just/list")
       .then((resp) => {
        this.boost_resellers = resp.data.resellers;
      });
    },

    displayAccountDetails(account) {
      this.account_name = account.name;
      this.account_details = account.transactions;
      let dollar = 0;
      let amount = 0;
      account.transactions.forEach((item) => {
        dollar += parseFloat(item.dollar);
      });

      account.transactions.forEach((item) => {
        amount += parseFloat(item.amount);
      });
      this.detailsTotalAmount = amount;
      this.detailsTotalDollar = dollar;
      this.$modal.show("account_details_modal");
    },

    accountTotalDollar($transactions) {
      let x = 0;
      $transactions.forEach((item) => {
        x += parseFloat(item.dollar);
      });
      return x;
    },

    accountTotalAmount($transactions) {
      let x = 0;
      $transactions.forEach((item) => {
        x += parseFloat(item.amount);
      });
      return x.toFixed(2);
    },

    overAllDollar() {
      if (this.accounts.length > 0) {
        let x = 0;
        this.accounts.forEach((item) => {
          x += parseFloat(item.total_dollar);
        });
        return x.toFixed(2);
      }
    },

    overAllAmount() {
      if (this.accounts.length > 0) {
        let x = 0;
        this.accounts.forEach((item) => {
          x += parseFloat(item.total_amount);
        });
        return x.toFixed(2);
      }
    },



    showModal() {
      this.$modal.show("add_account_modal");
    },

    showDollarTransferModal(item) {
      this.DollarTransferForm.name = item.name;
      this.DollarTransferForm.transfer_account_id = item.id;
      this.resellerList();
      this.$modal.show("dollar_transfer_modal");
    },

    dollarTransfer() {
      this.DollarTransferForm.post("/api/dollar/transfer/from/reseller", {
        transformRequest: [
          function (data, headers) {
            return objectToFormData(data);
          },
        ],
      })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.DollarTransferForm.boost_agency_reseller_id = "";
            this.DollarTransferForm.transfer_account_id = "";
            this.DollarTransferForm.dollar = "";
            this.DollarTransferForm.name = "";
            this.DollarTransferForm.page_name = "";
            this.transactionDetails();
            this.$modal.hide("dollar_transfer_modal");
          }
        })
        .catch((error) => {
          this.$toasted.show(error.response.data.message, {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },

    transactionDetails(page = 1) {
      axios
        .get(
          "/api/boost/reseller/transaction/details/" +
            this.$route.params.id +
            "?page=" +
            page
        )
        .then((resp) => {
          console.log(resp);
          this.boost_agency_reseller = resp.data.boost_agency_reseller;
          this.accounts = resp.data.accounts;
          this.payments = resp.data.payments;
          this.payment_total = resp.data.payment_total;
          this.loading = false;
        });
    },

    goBack() {
      window.history.back();
    },
  },

};
</script>

<style>
.col-lg-3.__c_box {
  padding: 10px;
  background: #fff;
  width: 250px;
  height: 85px;
  margin-right: 15px;
  margin-bottom: 20px;
  text-align: center;
  box-shadow: 3px 3px 3px #ddd;
}

.box-title {
  padding: 5px 6px;
  cursor: pointer;
}

.data_table_row {
  width: 95%;
  overflow-x: scroll;
}

.over_view_row {
  width: 92%;
  min-height: 120px;
  margin-left: 0%;
  box-shadow: 1px 1px 2px 1px #ddd;
  margin-bottom: 20px;
}

.account_container {
  height: 150px;
  margin-bottom: 20px;
  overflow-y: auto;
}
.btn_account {
  padding: 3px 20px;
  border: none;
  min-width: 225px;
  background: #eee;
}

.btn_account:hover {
  transition: 3s;
  background: blueviolet;
  color: #fff;
}

.active_border {
  color: #000;
  border-bottom: 2px dashed #000;
}

.btn_conditional {
  box-shadow: 0 1pt 12pt rgb(150 165 237);
  font-size: 16px;
  font-weight: bold;
  font-family: serif;
  margin: 0px 10px;
}
.vm--modal {
  position: relative;
  overflow: hidden;
  box-sizing: border-box;
  background-color: white;
  border-radius: 3px;
  box-shadow: 0 20px 60px -2px rgba(27, 33, 58, 0.4);
  overflow-y: scroll !important;
}
</style>
