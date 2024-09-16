<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>

          <router-link
            :to="{
              name: 'boost_agency_reseller_add'
            }"
            class="btn btn-primary"
            ><i class="fa fa-plus"></i>Add User</router-link
          >
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">boost users</li>
        </ol>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row ">
            <div class="col-lg-11 col-md-11">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <div class="row">
                    <div class="col-md-3">
                      <button
                        @click="activeList"
                        :class="{ active_border: status == 1 }"
                        class="btn bnt-sm btn-success"
                      >
                        Active
                      </button>

                      <button
                        @click="deActiveList"
                        :class="{ active_border: status == 0 }"
                        class="btn bnt-sm btn-warning"
                      >
                        DeActive
                      </button>

                      <button
                        @click="displayFilteringModal"
                        class="btn bnt-sm btn-success"
                      >
                        <i class="fa fa-filter"></i> Filter
                      </button>
                    </div>
                    <div class="col-md-5">
                      <h3 style="margin-top: 10px" class="box-title heading">
                        Advertise account users
                      </h3>
                    </div>
                    <div class="col-md-4">
                      <input
                        type="text"
                        v-model="search"
                        v-on:keyup="searchReseller"
                        placeholder="search by name, phone or company-name"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <h1 style="text-align: center" v-if="loading">
                    <i class="fa fa-spinner fa-spin"></i>
                  </h1>
                  <table
                    v-else
                    class="
                      table
                      table-centered
                      table-striped table-bordered table-hover
                    "
                  >
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Company Name</th>
      
                        <th scope="col">Dollar</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Paid</th>
                        <th scope="col">Due</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-else
                        v-for="(item, index) in resellers"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                          {{ item.name }}
                        </td>
                        <td>
                          <router-link
                            style="font-size: 15px; color: blue"
                            :to="{
                              name: 'boost_reselller_dollar_payment',
                              params: { id: item.id },
                            }"
                          >
                            {{ item.company_name }}
                            <sup style="color: black; ">
                              {{ item.accounts.length }}
                            </sup>
                          </router-link>
                        </td>
                        
                        <td>
                          <span>
                            <i class="fa fa-dollar"></i>
                            {{ item.total_dollar }}
                          </span>
                        </td>
        
                        <td>
                          <span>
                            <i class="fa fa-money"></i>
                            {{ item.total_amount }}
                          </span>
                        </td>
                        <td>
                          <span>
                            <i class="fa fa-money"></i>
                            {{ item.total_paid }}
                          </span>
                        </td>
                        <td>
                          <span
                            style="min-width: 100px"
                            class="badge badge-warning"
                          >
                            <i class="fa fa-money"></i>
                            {{
                              parseInt(item.total_amount) - parseInt(item.total_paid)
                            }}
                          </span>
                        </td>
                        <td>
                          <button
                            @click="actionBtn(item.id)"
                            class="btn btn-xs btn-success"
                          >
                            -- <i class="fa fa-bars"></i> --
                          </button>
                          <div
                            :id="'boost_action_' + item.id"
                            class="action_container"
                          >
                          <br>
                            <button
                              class="btn btn-xs btn-primary"
                              @click="
                                showDollarStoreModal(
                                  item.company_name,
                                  item.id,
                                  item.accounts
                                )
                              "
                            >
                              Add Dollar
                            </button>
                            <br>
                            <button
                              style="width: 65px; margin-top: 5px"
                              class="btn btn-xs btn-success"
                              @click="
                                showMoneyPaidModal(item.company_name, item.id)
                              "
                            >
                              Get Paid
                            </button>
                            <br>
                            <router-link
                              style="width: 65px; margin-top: 5px"
                              class="btn btn-xs btn-success"
                              :to="{
                                name: 'boost_agency_reseller_edit',
                                params: { id: item.id },
                              }"
                              ><i class="fa fa-edit"></i>Edit</router-link
                            >
                            <br>
                            <a
                              style="width: 65px; margin-top: 5px"
                              target="_blank"
                              @click="showPdfModal(item.company_name, item.id)"
                              class="btn btn-xs btn-success"
                            >
                              <i class="fa fa-download"></i> PDF</a
                            >
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">Total Distributed Dollar</td>
                        <td>
                          <span class="badge badge-success">
                            <i class="fa fa-dollar"></i>
                             {{ total_dollar }}
                          </span>
                        </td>
                      
                        <td>
                          <span class="badge badge-success">
                            <i class="fa fa-money"></i>
                            {{ total_amount }}
                          </span>
                        </td>
                        <td>
                          <span class="badge badge-success">
                            <i class="fa fa-money"></i>
                            {{ total_paid }}
                          </span>
                        </td>
                        <td>
                          <span class="badge badge-danger">
                            <i class="fa fa-money"></i>
                            {{ 
                            parseInt(total_amount) - parseInt(total_paid)

                            }}
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
       
              </div>
            </div>
          </div>

          <!-- start dollar store modal  -->
          <modal name="store" :width="400" :height="600">
            <form @submit.prevent="insertResellerDollar">
              <div class="card" style="padding: 20px">
                <div class="card-header text-center">
                  <h4 style="font-weight: bold" class="card-title">
                    store dollar into {{ reseller_company_name }}
                  </h4>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="account">Select Advertise Account</label>
                    <select
                      required
                      class="form-control"
                      @change="selectRate()"
                      v-model="
                        dollar_store_form.boost_agency_reseller_ad_account_id
                      "
                    >
                      <option value="" disabled>
                        select advertise account</option
                      >
                      <option
                        v-for="(account, index) in advertise_accounts"
                        :value="account.id"
                        :key="index"
                      >
                        {{ account.name }}
                      </option>
                    </select>
                  </div>
            
                  <div class="form-group">
                    <label>Dollar</label>
                    <input
                      @keyup="calculateAmount"
                      type="number"
                      name="dollar"
                      v-model="dollar_store_form.dollar"
                      class="form-control"
                      :class="{
                        'is-invalid': dollar_store_form.errors.has('dollar'),
                      }"
                      placeholder="200"
                    />
                    <has-error
                      :form="dollar_store_form"
                      field="dollar"
                    ></has-error>
                  </div>

                  <div class="form-group">
                    <label>Amount</label>
                    <input
                      type="number"
                      name="amount"
                      v-model="dollar_store_form.amount"
                      :class="{
                        'is-invalid': dollar_store_form.errors.has('amount'),
                      }"
                      class="form-control"
                      placeholder="0"
                    />
                    <has-error :form="dollar_store_form" field="amount">
                    </has-error>
                  </div>

                  <div class="form-group">
                    <label>Paid Amount</label>
                    <input
                      type="number"
                      name="amount"
                      v-model="dollar_store_form.paid"
                      class="form-control"
                      placeholder="0"
                    />
                  </div>

                  <div class="form-group">
                    <label for="credit_in">Credit In</label>
                    <select
                      class="form-control"
                      v-model="dollar_store_form.credit_in"
                    >
                      <option value="" disabled selected>Select Balance</option>
                      <option
                        v-for="(balance, index) in balance"
                        :value="balance.id"
                        :key="index"
                      >
                        {{ balance.name }}
                      </option>
                    </select>
                  </div>

                  <br />
                  <div class="form-group text-center">
                    <button type="submit" class="btn btn-success">
                      Submit
                    </button>
                  </div>
                  <br />
                </div>
              </div>
            </form>
          </modal>
          <!-- end dollar store modal  -->

          <!-- start payment store modal  -->
          <modal name="paid" :width="400" :height="350">
            <form @submit.prevent="getResellerPayment">
              <div class="card" style="padding: 20px">
                <div class="card-header text-center">
                  <h4 style="font-weight: bold" class="card-title">
                    get payment of {{ reseller_company_name }}
                  </h4>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Amount</label>
                    <input
                      type="number"
                      name="amount"
                      v-model="payment_paid_form.amount"
                      :class="{
                        'is-invalid': payment_paid_form.errors.has('amount'),
                      }"
                      class="form-control"
                      placeholder="0"
                    />
                    <has-error :form="payment_paid_form" field="amount">
                    </has-error>
                  </div>

                  <div class="form-group">
                    <label for="credit_in">Credit In</label>
                    <select
                      required
                      class="form-control"
                      v-model="payment_paid_form.credit_in"
                    >
                      <option value="" disabled selected>Select Balance</option>
                      <option
                        v-for="(balance, index) in balance"
                        :value="balance.id"
                        :key="index"
                      >
                        {{ balance.name }}
                      </option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="comment">comment</label>
                    <input
                      type="text"
                      name="comment"
                      v-model="payment_paid_form.comment"
                      class="form-control"
                      placeholder="boost reseller payment"
                    />
                  </div>

                  <br />
                  <div class="form-group text-center">
                    <button type="submit" class="btn btn-success">
                      Submit
                    </button>
                  </div>
                  <br />
                </div>
              </div>
            </form>
          </modal>
          <!-- end payment store modal  -->

          <!-- start pdf download modal  -->
          <modal name="pdf_form" :width="400" :height="300">
            <form>
              <div class="card" style="padding: 20px">
                <div class="card-header text-center">
                  <h4 style="font-weight: bold" class="card-title">
                    get pdf record of {{ reseller_company_name }}
                  </h4>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Start Date</label>
                    <date-picker
                      autocomplete="off"
                      v-model="pdf_form.start_date"
                      name="start_date"
                      :config="options"
                      required
                    ></date-picker>
                  </div>

                  <div class="form-group">
                    <label>End Date</label>
                    <date-picker
                      autocomplete="off"
                      v-model="pdf_form.end_date"
                      name="end_date"
                      required
                      :config="options"
                    ></date-picker>
                  </div>

                  <br />
                  <div class="form-group text-center">
                    <button
                      @click="getPdfRecordWithDate"
                      type="submit"
                      class="btn btn-success"
                    >
                      Download With Date
                    </button>

                    <button
                      @click="getPdfRecordAll"
                      type="submit"
                      class="btn btn-success"
                    >
                      Download All
                    </button>
                  </div>
                  <br />
                </div>
              </div>
            </form>
          </modal>
          <!-- end pdf download modal  -->
        </div>
      </section>

    </div>

    <!-- start filtering and pdf   -->
    <modal name="filtering" :width="400" :height="400">
      <form>
        <div class="card" style="padding: 20px">
          <div class="card-header text-center">
            <h4 style="font-weight: bold" class="card-title">
              Filter According To Date
            </h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>Start Date</label>
              <date-picker
                autocomplete="off"
                v-model="filter_form.start_date"
                name="start_date"
                :config="options"
                required
              ></date-picker>
            </div>

            <div class="form-group">
              <label>End Date</label>
              <date-picker
                autocomplete="off"
                v-model="filter_form.end_date"
                name="end_date"
                required
                :config="options"
              ></date-picker>
            </div>

            <br />
            <div class="form-group text-center">
              <button
                @click="downloadFilteredData"
                type="submit"
                class="btn btn-success"
              >
                <i class="fa fa-download"></i> Download With Date
              </button>
            </div>
            <br />
          </div>
        </div>
      </form>
    </modal>
    <!-- end filtering and pdf  -->
  </div>
</template>

<script>
import { Form, HasError } from "vform";

export default {
  mounted() {
    this.getBoostAgencyResellers();
    this.balanceList();
  },
  data() {
    return {
      loading: true,
      resellers: '',
      advertise_accounts: "",
      search: null,
      reseller_company_name: "",
      loading: true,
      total_amount: "",
      total_dollar: "",
      total_paid: "",
      dollar_store_form: new Form({
        boost_agency_reseller_id: "",
        boost_agency_reseller_ad_account_id: "",
        dollar: "",
        amount: "",
        paid: "",
        credit_in: "",
        rate: "",
      }),
      payment_paid_form: new Form({
        boost_agency_reseller_id: "",
        credit_in: "",
        amount: "",
        comment: "",
      }),
      pdf_form: new Form({
        boost_agency_reseller_id: "",
        start_date: "",
        end_date: "",
      }),
      filter_form: new Form({
        boost_agency_id: this.$route.params.id,
        start_date: "",
        end_date: "",
      }),
      balance: "",
      status: 1,
      options: {
        format: "YYYY-MM-DD",
        useCurrent: true,
      },
    };
  },
  methods: {
    
    calculateAmount() {
      let dollar = this.dollar_store_form.dollar;
      let rate = this.dollar_store_form.rate;
      this.dollar_store_form.amount = parseInt(dollar) * parseInt(rate);
    },

    selectRate(){
         this.advertise_accounts.forEach(item => {
              if (item.id == this.dollar_store_form.boost_agency_reseller_ad_account_id) {
                  this.dollar_store_form.rate = item.dollar_rate ;
              }
         });
    },

    actionBtn(id) {
      document
        .getElementById("boost_action_" + id)
        .classList.toggle("toggle_order_action");
    },

    balanceList() {
      axios.get("/api/balance/list/boost").then((resp) => {
        this.balance = resp.data.balance;
      });
    },

    activeList() {
      this.status = 1;
      this.getBoostAgencyResellers();
    },

    deActiveList() {
      this.status = 0;
      this.getBoostAgencyResellers();
    },

    downloadFilteredData() {
      window.open(
        "/api/download/pdf/boost/agency/resellers/date-wise/" +
          this.filter_form.start_date +
          "/" +
          this.filter_form.end_date +
          "/" +
          this.filter_form.boost_agency_id
      );
    },

    getPdfRecordWithDate() {
      window.open(
        "/api/download/pdf/boost/reseller/account/date-wise/" +
          this.pdf_form.start_date +
          "/" +
          this.pdf_form.end_date +
          "/" +
          this.pdf_form.boost_agency_reseller_id
      );
    },

    getPdfRecordAll() {
      window.open(
        "/api/download/pdf/boost/reseller/account/all/" +
          this.pdf_form.boost_agency_reseller_id
      );
    },

    getBoostAgencyResellers() {
      axios
        .get("/api/boost/agency/resellers/list", {
          params: {
            search: this.search,
            status: this.status,
          },
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
            this.resellers = resp.data.agency_resellers;
            this.total_amount = resp.data.total_amount ;
            this.total_paid = resp.data.total_paid ;
            this.total_dollar = resp.data.total_dollar ;
            this.loading = false;
          }
        });
    },

    searchReseller() {
      if (this.search.length > 3) {
        this.loading = true;
        this.getBoostAgencyResellers();
      } else {
        this.loading = true;
        this.getBoostAgencyResellers();
      }
    },

    displayFilteringModal() {
      this.todayDate();
      this.$modal.show("filtering");
    },

    showDollarStoreModal(company_name, id, accounts) {
      this.reseller_company_name = company_name;
      this.advertise_accounts = accounts;
      this.dollar_store_form.boost_agency_reseller_id = id;
      this.$modal.show("store");
    },

    showMoneyPaidModal(company_name, id) {
      this.reseller_company_name = company_name;
      this.payment_paid_form.boost_agency_reseller_id = id;
      this.$modal.show("paid");
    },

    showPdfModal(company_name, id) {
      this.reseller_company_name = company_name;
      this.pdf_form.boost_agency_reseller_id = id;
      this.todayDate();
      this.$modal.show("pdf_form");
    },

    async insertResellerDollar() {
      if (
        this.dollar_store_form.paid > 0 &&
        this.dollar_store_form.credit_in == ""
      ) {
        alert("select payment balance");
        return;
      }
      this.loading = true;
      await this.dollar_store_form
        .post("/api/store/boost/reseller/dollar", {
          transformRequest: [
            function(data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true) {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.reseller_company_name = "";
            this.dollar_store_form.dollar = "";
            this.dollar_store_form.rate = "";
            this.dollar_store_form.amount = "";
            this.dollar_store_form.paid = "";
            this.dollar_store_form.credit_in = "";
            this.dollar_store_form.boost_agency_reseller_id = "";
            this.getBoostAgencyResellers();
            this.$modal.hide("store");
          }
        })
        .catch((error) => {
          this.$toasted.show(error.response.data.message, {
            type: "error",
            position: "top-right",
            duration: 4000,
          });
        });
    },

    async getResellerPayment() {
      this.loading = true;
      await this.payment_paid_form
        .post("/api/store/boost/reseller/payment", {
          transformRequest: [
            function(data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.reseller_company_name = "";
            this.payment_paid_form.amount = "";
            this.payment_paid_form.comment = "";
            this.payment_paid_form.boost_agency_reseller_id = "";
            this.payment_paid_form.credit_in = "";
            this.getBoostAgencyResellers();
            this.$modal.hide("paid");
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
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


    todayDate() {
      let d = new Date();
      let month = d.getMonth() + 1;
      let day = d.getDate();
      let output =
        d.getFullYear() +
        "-" +
        (("" + month).length < 2 ? "0" : "") +
        month +
        "-" +
        (("" + day).length < 2 ? "0" : "") +
        day;
      this.pdf_form.end_date = output;
      this.filter_form.end_date = output;
    },
  },
  components: {
    HasError,
  },
};
</script>

<style scoped>


.badge {
  padding: 6px 15px !important;
}
span {
  font-size: 15px;
}

.total_style {
  border: 2px solid #ddd;
  padding: 4px 25px;
}
.total_row {
  padding-bottom: 20px;
}
.active_border {
  border-bottom: 2px solid #ff4d03;
}
</style>
