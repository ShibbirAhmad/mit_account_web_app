
<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="goBack" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </a>
          <router-link  :to="{name:'service_package_add',params:{client_phone:client.phone },}" class="btn btn-primary">
            <i class="fa fa-plus"></i> add new service
          </router-link>

        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>service</a>
          </li>
          <li class="active">client</li>
        </ol>
      </section>
      <section class="content">
        <h1 style="text-align:center" v-if="loading"> <i class="fa fa-spinner fa-spin"></i> </h1>
        <div v-else class="container-fluid">
          <div class="row over_view_row">
            <div class="col-md-6 col-xs-12">
              <h5 class="heading">
                Name : <strong>{{ client.name }} </strong>
              </h5>
              <h5 class="heading">
                Company:
                <strong>{{ client.company_name }} </strong>
              </h5>
              <h5 class="heading">
                Phone : <strong> {{ client.phone }}</strong>
              </h5>
              <h5 class="heading">
                Address :
                <strong>
                  {{ client.address ? client.address : "empty" }}
                </strong>
              </h5>
            </div>

            <div class="col-md-6 col-xs-12">
              <div class="account_container">
                <ul class="list-group">
                  <li class="list-group-item">
                    Total Service Amount :
                    <strong> {{ package_amount_total }}</strong>
                  </li>
                  <li class="list-group-item">
                    Total Paid Amount : <strong> {{ payment_total }}</strong>
                  </li>
                  <li class="list-group-item">
                    Total Due Amount :
                    <strong>
                      {{
                        parseInt(package_amount_total) - parseInt(payment_total)
                      }}</strong
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row data_table_row">
            <div class="col-lg-12 col-xl-12 col-md-12 col-sm-12">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <a
                    class="btn btn_conditional"
                    @click="contractualMode = true; monthlyMode=false ; paymentMode =false; "
                    :class="{ active_border: contractualMode == true }"
                  >
                    Contractual Service
                  </a>

                  <a
                    class="btn btn_conditional"
                    @click="contractualMode = false; monthlyMode=true ; paymentMode =false; "
                    :class="{ active_border: monthlyMode == true }"
                  >
                    Monthly Service
                  </a>

                  <a
                    class="btn btn_conditional"
                    @click="contractualMode = false; monthlyMode=false ; paymentMode =true; "
                    :class="{ active_border: paymentMode == true }"
                  >
                    Payment History
                  </a>
                </div>

                <div v-if="contractualMode == true">
                  <div class="box-body">
                    <table
                      class="
                        table table-hover table-striped
                       
                        table-bordered
                        table-centered
                      "
                    >
                     <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="10%">Date</th>
                        <th width="25%">Service Name</th>
                        <th width="10%">Amount</th>
                        <th width="10%">Paid</th>
                        <th width="10%">Due</th>
                        <th width="10%">Status</th>
                        <th width="10%">Created By</th>
                        <th width="10%">Action</th>
                      </tr>
                     </thead>
                      <tbody>
                        <tr
                          v-for="(item, index) in contractual_packages"
                          :key="index"
                        >
                          <td>{{ index + 1 }}</td>
                          <td>{{ new Date(item.created_at).toLocaleDateString()  }}</td>
                          <td> <p>{{ item.service.name }} </p> 
                               <span>{{ item.note }}</span>
                          </td>
                          <td>{{ item.amount }}</td>
                          <td>{{ item.paid }}</td>
                          <td>
                            {{ parseInt(item.amount) - parseInt(item.paid) }}
                          </td>
                          <td>
                            <span
                              v-if="item.is_paid == 1"
                              class="badge badge-success"
                              >Paid
                            </span>
                            <span v-else class="badge badge-warning">Due </span>
                          </td>
                          <td>{{ item.created_by.name }}</td>
                          <td>
                            <button
                              @click="
                                showMoneyPaidModal(
                                  item.id,
                                  parseInt(item.amount) - parseInt(item.paid), item.is_monthly
                                )
                              "
                              v-if="item.is_paid == 0"
                              class="btn btn-xs btn-success"
                            >
                              Get Payment
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>


                <div v-if="monthlyMode == true">
                  <div class="box-body">
                    <table
                      class="
                        table table-hover table-striped
                        text-center
                        table-bordered
                        table-centered
                      "
                    >
                      <tr>
                        <th width="5%">#</th>
                        <th width="10%">Name</th>
                        <th width="55%">Bills</th>
                        <th width="10%">Amount</th>
                        <th width="5%">Status</th>
                        <th width="8%">Created By</th>
                        <th width="7%">Action</th>
                      </tr>
                      <tbody>
                        <tr
                          v-for="(item, index) in monthly_packages"
                          :key="index"
                        >
                          <td>{{ index + 1 }}</td>
                          <td>{{ item.service.name }}</td>
                          <td>
                            <table class="table table-hover table-bordered table-centered table-striped">

                              <tbody>
                                <tr>
                                  <th>Start Date</th>
                                  <th>End Date</th>
                                  <th>Amount</th>
                                  <th>Paid</th>
                                  <th>Due</th>
                                  <th>Status</th>
                                </tr>

                                <tr v-for="(bill,index) in item.bills" :key="index">
                                  <td>{{ bill.start_date }}</td>
                                  <td>{{ bill.end_date }}</td>
                                  <td>{{ parseFloat(bill.amount).toFixed(0) }}</td>
                                  <td>{{ parseFloat(bill.paid).toFixed(0) }}</td>
                                  <td>{{ ( parseFloat(bill.amount) - parseFloat(bill.paid) ).toFixed(0) }}</td>
                                  <td>
                                   <span class="badge badge-success"  v-if="bill.status==1">Paid</span>
                                   <span  class="badge badge-warning" v-else>Due</span>
                                  </td>

                                </tr>

                              </tbody>
                            </table>
                          </td>
                          <td>
                            <div>
                              <p> total : {{ parseInt(item.amount)  }} </p>
                              <p> paid : {{ parseInt(item.paid)  }} </p>
                              <p> due :  {{ parseInt(item.amount) - parseInt(item.paid) }} </p>
                            </div>
                            </td>

                          <td>
                            <span
                              v-if="item.is_paid == 1"
                              class="badge badge-success"
                              >Paid
                            </span>
                            <span v-else class="badge badge-warning">Due </span>
                          </td>
                          <td>{{ item.created_by.name }}</td>
                          <td>
                            <button
                              @click="
                                showMoneyPaidModal(
                                  item.id,
                                  parseInt(item.amount) - parseInt(item.paid),item.is_monthly
                                )
                              "
                              v-if="item.is_paid == 0"
                              class="btn btn-xs btn-success"
                            >
                              Get Payment
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>


                <div v-if="paymentMode == true">
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
                        <th>Inserted By</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                      </tr>
                      <tbody>
                        <tr v-for="(item, index) in payments" :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>{{  new Date(item.created_at).toLocaleDateString() }}</td>
                          <td>{{ item.comment ? item.comment : "" }}</td>
                          <td>{{ item.created_by ? item.created_by.name : ''  }}</td>
                          <td>{{ item.trx_id ? item.trx_id : '' }}</td>
                          <td>{{ item.amount }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <!-- start payment store modal  -->
    <modal name="paid" :width="450" :height="500">
      <form @submit.prevent="getClientsPayment">
        <div class="card" style="padding: 20px">
          <div class="card-header text-center">
            <div class="alert-danger alert" v-if="errors">
                    {{ errors }}
             </div>
            <h4 style="font-weight: bold" class="card-title">
              Maximum receive able amount is {{ form.due }}
            </h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <label>Amount<b class="text-danger">*</b></label>
              <input
                type="number"
                name="amount"
                v-model="form.amount"
                :class="{ 'is-invalid': form.errors.has('amount') }"
                class="form-control"
                placeholder="0"
                required
                min="1"
              />
              <has-error :form="form" field="amount"> </has-error>
            </div>

            <div class="form-group">
              <label for="credit_in"
                >Credit In<b class="text-danger">*</b></label
              >
              <select  class="form-control" v-model="form.credit_in">
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
              <label for="is_regular"
                >Payment Type <b class="text-danger">*</b>
              </label>
              <select required class="form-control" v-model="form.is_regular">
                <option disabled value="">select payment type</option>
                <option value="0">Regular</option>
                <option value="1">Discount/Loss Payment</option>
              </select>
            </div>

            <div class="form-group">
              <label for="trx_id">Transaction ID</label>
              <input
                type="text"
                name="trx_id"
                v-model="form.trx_id"
                class="form-control"
                placeholder="DS45-4DDS-4532"
              />
            </div>

            <div class="form-group">
              <label for="comment">comment</label>
              <input
                type="text"
                name="comment"
                v-model="form.comment"
                class="form-control"
                placeholder="clients payment"
              />
            </div>

            <br />
            <div class="form-group text-center">
              <button type="submit" class="btn btn-success">Submit</button>
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
    this.balanceList();
    setTimeout(() => {
      this.loading=false;
    }, 1000);
  },
  data() {
    return {
      contractualMode: true,
      monthlyMode: false,
      paymentMode: false,
      client: "",
      monthly_packages: "",
      account_details: "",
      package_amount_total: "",
      contractual_packages: "",
      payments: {},
      payment_total: "",
      loading: true,
      form: new Form({
        client_id: this.$route.params.client_id,
        service_package_id: "",
        credit_in: "",
        amount: "",
        is_monthly: null,
        is_regular: '',
        comment: "",
        trx_id: "",
        due: "",
      }),
      balance: "",
      errors:'',
    };
  },
  methods: {
  async  getClientsPayment() {

      if (this.form.amount > this.form.due) {
        alert(
          "Paid amount is grater than the due amount. maximum receive able amount is " +
            this.form.due
        );
        this.form.amount = 0;
        return;
      }

      if(this.form.amount > 0 && this.form.is_regular == 0 && this.form.credit_in == '') {
        alert('select balance');
        return;
      }

   await   this.form
        .post("/api/store/client/payment", {
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
            this.form.amount = "";
            this.form.comment = "";
            this.form.service_package_id = "";
            this.form.credit_in = "";
            this.form.is_regular = 0;
            this.form.due = "";
            this.form.trx_id = "";
            this.transactionDetails();
            this.$modal.hide("paid");
          } else {
            this.$toastr.e(resp.data.message);
          }
        })
        .catch((error) => {
           this.$toastr.e(error.response.data.message);
        });
    },

    showMoneyPaidModal(id, due,is_monthly) {
      this.form.service_package_id = id;
      this.form.is_monthly = is_monthly;
      this.form.due = due;
      this.$modal.show("paid");
    },

    balanceList() {
      axios.get("/api/balance/list/mit").then((resp) => {
        this.balance = resp.data.balance;
      });
    },

    transactionDetails() {
      axios
        .get(
          "/api/client/service/details/and/payment/" +
            this.$route.params.client_id
        )
        .then((resp) => {
          console.log(resp);
          this.client = resp.data.client;
          this.payments = resp.data.payments;
          this.payment_total = resp.data.payment_total;
          this.package_amount_total = resp.data.package_amount_total;
          this.contractual_packages = resp.data.contractual_packages;
          this.monthly_packages = resp.data.monthly_packages;
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
  margin: 0px 20px;
}
</style>
