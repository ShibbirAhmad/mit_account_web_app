
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
            <i class="fa fa-plus"></i> add SMS account
          </a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Client</a>
          </li>
          <li class="active">Boost</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row over_view_row">
            <div class="col-md-4">
              <h5 class="heading">
                Name : <strong>{{ sms_client.name }} </strong>
              </h5>
              <h5 class="heading">
                Comapany:
                <strong>{{ sms_client.company_name }} </strong>
              </h5>
              <h5 class="heading">
                Phone : <strong> {{ sms_client.phone }}</strong>
              </h5>
              <h5 class="heading">
                Address :
                <strong>
                  {{
                    sms_client.address
                      ? sms_client.address
                      : "empty"
                  }}
                </strong>
              </h5>
            </div>

            <div class="col-md-1"></div>
            <div class="col-md-6">
              <ul class="list-group">
                <li
                  v-for="(account, index) in sms_client.accounts"
                  :key="index"
                  class="list-group-item"
                >
                  account- {{ index + 1 }} :
                  <strong> {{ account.website }}</strong>
                </li>
              </ul>
            </div>
          </div>
          <div class="row data_table_row">
            <div class="col-md-6 col-sm-6">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title">sms History</h3>
                </div>

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
                      <th>Website</th>
                      <th>Amount</th>
                    </tr>
                    <tbody>
                      <tr v-for="(item, index) in accounts" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td> <button v-on:click="displayAccountDetails(item)" class="btn btn-xs btn-primary btn_account "> {{ item.website }} </button> </td>

                        <td>{{ accountTotalAmount(item.transactions) }}</td>
                      </tr>
                      <tr>
                        <td colspan="2">In Total </td>

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
            </div>

            <div class="col-md-6 col-sm-6">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title">Payment History</h3>
                </div>

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
      </section>
    </div>


    <!-- start payment store modal  -->
    <modal name="account_details_modal" :width="700" :height="auto" >
      <form @submit.prevent="addClientAccount">
        <div class="card" style="padding: 20px;">
          <div class="card-header text-center">
               <h4 class="heading">  {{ website }} </h4>
          </div>
          <div  class="card-body">

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
                      <th>Amount</th>
                    </tr>
                    <tbody>
                      <tr v-for="(item, index) in account_details" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.amount }}</td>
                      </tr>

                      <tr>
                        <td colspan="1"> </td>
                        <td> </td>
                        <td>  <span class="badge badge-success"> &#2547; {{ detailsTotalAmount }}  </span> </td>
                      </tr>

                    </tbody>
                  </table>

          </div>
        </div>
      </form>
    </modal>
    <!-- end payment store modal  -->

    <!-- start payment store modal  -->
    <modal name="add_account_modal" :width="300" :height="300">
      <form @submit.prevent="addClientAccount">
        <div class="card" style="padding: 20px;">
          <div class="card-body">
            <div class="form-group">
              <label>Website</label>
              <input
                type="text"
                name="website"
                v-model="form.website"
                :class="{ 'is-invalid': form.errors.has('website') }"
                class="form-control"
                placeholder="example.com"
                required
              />
              <has-error :form="form" field="website"> </has-error>
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
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
export default {
  created() {
    this.transactionDetails();
  },
  data() {
    return {
      sms_client: "",
      accounts: "",
      website: "",
      account_details: "",
      detailsTotalAmount: "",
      payments: {},
      payment_total: "",
      loading: true,
      form: new Form({
        sms_client_id: this.$route.params.id,
        website: "",
      }),
    };
  },
  methods: {

    displayAccountDetails(account){
       this.website = account.website ;
       this.account_details = account.transactions ;
       let amount =0 ;
        account.transactions.forEach(item=> {
           amount += parseInt(item.amount) ;
       });
       this.detailsTotalAmount=amount ;
       this.$modal.show('account_details_modal');
    },


    accountTotalAmount($transactions) {
      let x = 0;
      $transactions.forEach((item) => {
        x += parseInt(item.amount);
      });
      return x;
    },


    overAllAmount() {
      if (this.accounts.length > 0) {
        let x = 0;
        this.accounts.forEach((item) => {
          item.transactions.forEach((element) => {
            x += parseInt(element.amount);
          });
        });
        return x;
      }
    },

    addClientAccount() {
      this.form
        .post("/api/sms/client/account/add", {
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
            this.form.website = "";
            this.transactionDetails();
            this.$modal.hide("add_account_modal");
          } else {
            this.$toasted.show(resp.data.message, {
              type: "error",
              position: "top-center",
              duration: 4000,
            });
          }
        });
    },

    showModal() {
      this.$modal.show("add_account_modal");
    },

    transactionDetails(page = 1) {
      axios
        .get(
          "/api/sms/client/transaction/details/" +
            this.$route.params.id +
            "?page=" +
            page
        )
        .then((resp) => {
          console.log(resp);
          this.sms_client = resp.data.sms_client;
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
  components: {
    HasError,
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

.btn_account{
    padding: 3px 20px ;
}


.btn_account:hover {
   transition:3s;
   background: linear-gradient(green,blue)  ;
   color:#fff ;
}


</style>
