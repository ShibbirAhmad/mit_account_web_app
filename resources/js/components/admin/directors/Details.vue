
<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="goBack" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>director</a>
          </li>
          <li class="active">director</li>
        </ol>
      </section>
      <section class="content">
        <h1 style="text-align: center" v-if="loading">
          <i class="fa fa-spinner fa-spin"></i>
        </h1>
        <div v-else class="container">
          <div class="row over_view_row">
            <div class="col-md-6 col-xs-12">
              <h5 class="heading">
                Name : <strong>{{ director.name }} </strong>
              </h5>
              <h5 class="heading">
                Email :
                <strong>{{ director.email ? director.email : "" }} </strong>
              </h5>
              <h5 class="heading">
                Phone : <strong> {{ director.phone }}</strong>
              </h5>
              <h5 class="heading">
                Address :
                <strong>
                  {{ director.address ? director.address : "" }}
                </strong>
              </h5>
            </div>

            <div class="col-md-6 col-xs-12">
              <div class="account_container">
                <ul class="list-group">
                  <li class="list-group-item">
                    Total Amount :
                    <strong> {{ director.total_amount }}</strong>
                  </li>
                  <li class="list-group-item">
                    Total Paid Amount :
                    <strong> {{ director.total_paid_amount }}</strong>
                  </li>
                  <li class="list-group-item">
                    Total Refund Amount :
                    <strong> {{ director.total_refund_amount }}</strong>
                  </li>
                  <li class="list-group-item">
                    Total Payable Amount :
                    <strong>
                      {{
                        parseInt(director.total_amount) -
                        parseInt(director.total_paid_amount)
                      }}</strong
                    >
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="row data_table_row">
            <div class="col-md-12 col-sm-12">
              <div class="box box-primary">
                <div class="box-header with-border ">

                   <a
                    class="btn btn-sm btn-success btn_payment"
                    @click="$modal.show('paid_modal')"

                  >
                  <i class="fa fa-plus"></i> Receive   Payment
                  </a>

                  <a
                    class="btn btn-sm btn-warning  btn_payment"
                    @click="$modal.show('refund_modal')"

                  >
                  <i class="fa fa-minus"></i> Refund   Payment
                  </a>


                  <a
                    class="btn btn_conditional"
                    @click="
                      refundMode = false;
                      paymentMode = true;
                    "
                    :class="{ active_border: paymentMode == true }"
                  >
                    Payment History
                  </a>


                  <a
                    class="btn btn_conditional"
                    @click="
                      refundMode = true;
                      paymentMode = false;
                    "
                    :class="{ active_border: refundMode == true }"
                  >
                    Refund History
                  </a>

                </div>

                <div v-if="refundMode == true">
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
                        <th>Debit From </th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                      </tr>
                      <tbody>
                        <tr v-for="(item, index) in refunds" :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>
                            {{ new Date(item.created_at).toLocaleDateString() }}
                          </td>
                          <td>{{ item.comment ? item.comment : "" }}</td>

                          <td>
                            {{ item.created_by ? item.created_by.name : "" }}
                          </td>
                           <td>
                              {{  balanceName(item.balance_id) }}
                          </td>
                          <td>{{ item.trx_id ? item.trx_id : "" }}</td>
                          <td>{{ item.amount }}</td>
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
                        <th>Credit In</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                      </tr>
                      <tbody>
                        <tr v-for="(item, index) in payments" :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>
                            {{ new Date(item.created_at).toLocaleDateString() }}
                          </td>
                          <td>{{ item.comment ? item.comment : "" }}</td>
                          <td>
                            {{ item.created_by ? item.created_by.name : "" }}
                          </td>
                           <td>
                              {{  balanceName(item.balance_id) }}
                          </td>
                          <td>{{ item.trx_id ? item.trx_id : "" }}</td>
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
    <!-- start payment refund modal  -->
    <modal name="refund_modal" :width="450" :height="420">
      <form @submit.prevent="refundDirectorsPayment">
        <div class="card" style="padding: 20px">
          <div class="card-header text-center">
            <div class="alert-danger alert" v-if="errors">
              {{ errors }}
            </div>

          </div>
          <div class="card-body">
            <div class="form-group">
              <label>Amount<b class="text-danger">*</b></label>
              <input
                type="number"
                name="amount"
                v-model="refund_form.amount"
                :class="{ 'is-invalid': refund_form.errors.has('amount') }"
                class="form-control"
                placeholder="0"
                required
                min="1"
              />
              <has-error :form="refund_form" field="amount"> </has-error>
            </div>

            <div class="form-group">
              <label for="balance_id"
                >Debit From<b class="text-danger">*</b></label
              >
              <select required class="form-control" v-model="refund_form.debit_from">
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
              <label for="trx_id">Transaction ID</label>
              <input
                type="text"
                name="trx_id"
                v-model="refund_form.trx_id"
                class="form-control"
                placeholder="DS45-4DDS-4532"
              />
            </div>

            <div class="form-group">
              <label for="comment">comment</label>
              <input
                type="text"
                name="comment"
                v-model="refund_form.comment"
                class="form-control"
                placeholder="directors payment"
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
    <!-- end payment refund modal  -->

    <!-- start payment store modal  -->
    <modal name="paid_modal" :width="450" :height="420">
      <form @submit.prevent="storeDirectorsPayment">
        <div class="card" style="padding: 20px">
          <div class="card-header text-center">
            <div class="alert-danger alert" v-if="errors">
              {{ errors }}
            </div>

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
              <label for="balance_id"
                >Credit In<b class="text-danger">*</b></label
              >
              <select required class="form-control" v-model="form.credit_in">
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
                placeholder="directors payment"
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
import { Form, HasError, AlertError } from "vform";
export default {
  created() {
    this.transactionDetails();
    this.balanceList();
  },
  data() {
    return {
      refundMode: false,
      paymentMode: true,
      director: "",
      payments: "",
      refunds: "",
      loading: true,
      form: new Form({
        director_id: this.$route.params.id,
        credit_in: "",
        amount: "",
        comment: "",
        trx_id: "",
      }),
      refund_form: new Form({
        director_id: this.$route.params.id,
        debit_from: "",
        amount: "",
        comment: "",
        trx_id: "",
      }),
      balance: "",
      errors: "",
    };
  },
  methods: {

  async  storeDirectorsPayment() {
     await this.form
        .post("/api/store/director/payment", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$toastr.s(resp.data.message);
            this.form.amount = "";
            this.form.comment = "";
            this.form.credit_in = "";
            this.form.trx_id = "";
            this.transactionDetails();
            this.$modal.hide("paid_modal");
          } else {
            this.$toastr.e(resp.data.message);
          }
        })
        .catch((e) => {
          this.errors = [];
          this.errors.push(e.response.data.errors);
          this.$toastr.e(e.response.data.message);
        });
    },


   async refundDirectorsPayment() {
    await  this.refund_form
        .post("/api/refund/director/payment", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$toastr.s(resp.data.message);
            this.refund_form.amount = "";
            this.refund_form.comment = "";
            this.refund_form.debit_from = "";
            this.refund_form.trx_id = "";
            this.transactionDetails();
            this.$modal.hide("refund_modal");
          } else {
            this.$toastr.s(resp.data.message);
          }
        })
        .catch((e) => {
          this.errors = [];
          this.errors.push(e.response.data.errors);
          this.$toastr.e(e.response.data.message);
        });
    },

    balanceName(balance_id){
        let b_name = '' ;
        if (this.balance.length > 0) {
            this.balance.forEach(element => {
               if (element.id == balance_id) {
                  b_name = element.name ;
               }
            });
        }

        return b_name ;
    },

    balanceList() {
      axios.get("/api/balance/list/mit")
      .then((resp) => {
        this.balance = resp.data.balance;
      });
    },

    transactionDetails() {
      axios.get("/api/get/director/" + this.$route.params.id)
      .then((resp) => {
        console.log(resp);
        this.director = resp.data.director;
        this.payments = resp.data.director.payments;
        this.refunds = resp.data.director.refunds;
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
