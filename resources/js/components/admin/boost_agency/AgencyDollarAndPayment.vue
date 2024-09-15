<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="goBack" class="btn btn-success">
            <i class="fa fa-arrow-left"></i>
          </a>


          <a @click="$modal.show('payment_modal')" class="btn btn-suc cess">
            <i class="fa fa-plus"></i> Add Payment
          </a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>dollar</a>
          </li>
          <li class="active">details</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row ">
            <div class="col-md-3 total_container">
                <h4>Total Dollar {{ total_dollars }} </h4>
            </div>

            <div class="col-md-3 total_container">
                <h4>Total Amount {{ supplier_dollar_amount_total }} </h4>
            </div>
            <div class="col-md-3 total_container">
                <h4>Total Paid {{ payment_total }} </h4>
            </div>
            <div class="col-md-3 total_container">
                <h4> Due {{ parseInt(supplier_dollar_amount_total) - parseInt(payment_total) }} </h4>
            </div>
        
          </div>
          <div class="row">
            <div class="col-lg-12 col-md-12">
                      <div class="row filtering_row">
                        
                        <div class="col-lg-3">
                <h4> Profit {{  parseInt(total_dollar_amount) -   parseInt(supplier_dollar_amount_total)  }} </h4>
            </div>

                        <div class="col-lg-3">
                          <date-picker
                          style="height:44px !important;"
                            v-model="start_date"
                            placeholder="start-date"
                            :config="options"
                          ></date-picker>
                        </div>
                        <div class="col-lg-3" >
                          <date-picker
                          style="height:44px !important;"
                            v-model="end_date"
                            placeholder="end-date"
                            :config="options"
                          ></date-picker>
                        </div>
                        <div class="col-lg-3">
                          <div style="display:flex">
                            <button
                              style="height:44px !important;"
                              type="submit "
                              class="btn btn-success btn-sm"
                              @click.prevent="transactionDetails"
                            >
                              <i class="fa fa-filter"></i> Filter By
                              Date
                            </button>

                            <select
                              @change="filterOrderAccordingToDays"
                              v-model="filtering_date_value"
                              class="form-control"
                              style="height:44px !important;margin-left:20px"
                            >
                              <option value="" disabled>Filter By Days</option>
                              <option value="today">Today</option>
                              <option value="yesterday">Yesterday</option>
                              <option value="last 7 days">Last 7 Days</option>
                              <option value="last 30 days">Last 30 Days</option>
                              <option value="this month">This Month</option>
                              <option value="this year">This Year</option>
                              <option value="last year">Last Year</option>
                            </select>
                          </div>
                        </div>
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
                      class="table table-hover table-striped table-centered table-bordered"
                    >
                     <thead>
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Account</th>
                        <th>Supplier Rate</th>
                        <th>Sale Rate</th>
                        <th>Dollar</th>
                        <th>Amount</th>
                      
                      </tr>
                     </thead>
                      <tbody>
                        <tr
                          v-for="(item, index) in dollar_transactions.data"
                          :key="index"
                        >
                          <td>{{ index + 1 }}</td>
                          <td>{{ item.created_at }}</td>
                          <td>
                            <p>{{ item.account.name }}</p>
            
                          </td>
                          <td>{{ item.supplier_rate }}</td>
                          <td>{{ item.rate }}</td>
                          <td>{{ item.dollar }}</td>
                          <td>{{ item.amount }}</td>
                  
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>

                <div v-if="dollarMode != true">
                 
                <div class="box-body">
                  <table class="table table-striped table-centered  table-bordered">
                    <thead>
                      <tr>
                      <th>#</th>
                      <th width="20%" >Date</th>
                      <th width="40%">Comment</th>
                      <th>Paid By</th>
                      <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in payments.data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.comment }}</td>
                        <td>{{ item.balance.name  }}</td>
                        <td>{{ item.amount }}</td>
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
                    <div class="col-sm-12 ">
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
    <modal name="payment_modal" :width="400" :height="350">
      <form @submit.prevent="storePayment">
        <div class="card" style="padding: 20px">
          <div class="card-body">
            <div class="form-group">
              <label>Amount</label>
              <input
                type="number"
                v-model="paymentForm.amount"
                class="form-control"
                placeholder="0"
              />
            </div>

            <div class="form-group">
              <label for="balance_id">Paid By</label>
              <select
                required
                class="form-control"
                v-model="paymentForm.balance_id"
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
                v-model="paymentForm.comment"
                class="form-control"
                placeholder="boost reseller payment"
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
      filtering_date_value: "",
      start_date: "",
      end_date: "",
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      dollarMode: true,
      paymentMode: false,
      dollar_supplier: "",
      total_dollar_amount: "",
      supplier_dollar_amount_total: "",
      total_dollars: "",
      dollar_transactions: {},
      payments: {},
      payment_total: "",
      loading: true,
      dollar_edit_item_id: null,
      balance: "",
      paymentForm: new Form({
        boost_agency_id: this.$route.params.id,
        balance_id: "",
        amount: "",
        comment: "",
      }),
    };
  },
  methods: {
    transactionDetails(page=1) {
      axios
        .post(
          "/api/boost/agency/dollar/and/payment/details?page=" + page,{
              agency_id : this.$route.params.id,
              start_date: this.start_date,
              end_date: this.end_date,
          }
            
        )
        .then((resp) => {
          console.log(resp);
          this.dollar_supplier = resp.data.supplier;
          this.payments = resp.data.payments;
          this.dollar_transactions = resp.data.dollar_transactions;
          this.payment_total = resp.data.payment_total;
          this.total_dollar_amount = resp.data.total_dollar_amount;
          this.total_dollars = resp.data.total_dollars;
          this.supplier_dollar_amount_total = resp.data.supplier_dollar_amount_total;
          this.loading = false;
        });
    },



    storePayment() {
      this.paymentForm
        .post("/api/pay/boost/agency/payment", {
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
            this.paymentForm.amount = "";
            this.paymentForm.comment = "";
            this.paymentForm.balance_id = "";
            this.$modal.hide("payment_modal");
            this.transactionDetails();
          } else {
            this.$toastr.s(resp.data.message);
          }
        })
        .catch((error) => {
          this.$toastr.e(error.response.data.message);
        });
    },




    balanceList() {
      axios.get("/api/balance/list/boost")
      .then((resp) => {
        this.balance = resp.data.balance;
      });
    },

    calculateAmount() {
      this.form.amount = parseInt(this.form.dollar) * parseInt(this.form.rate);
    },


    filterOrderAccordingToDays() {
      if (this.filtering_date_value == "today") {

        this.start_date = this.currentDate();
        this.end_date = this.currentDate();
        this.transactionDetails();

      } else if (this.filtering_date_value == "yesterday") {
        
        this.start_date =this.previousDate(new Date(Date.now() - 1 * 24 * 60 * 60 * 1000));
        this.end_date = this.previousDate(new Date(Date.now() - 1 * 24 * 60 * 60 * 1000));
        this.transactionDetails();

      }else if (this.filtering_date_value == "last 7 days") {
        this.start_date =this.previousDate(new Date(Date.now() - 7 * 24 * 60 * 60 * 1000));
        this.end_date = this.currentDate();
        this.transactionDetails();

      }else if (this.filtering_date_value == "last 30 days") {
        
        this.start_date =this.previousDate(new Date(Date.now() - 30 * 24 * 60 * 60 * 1000));;
        this.end_date = this.currentDate();
        this.transactionDetails();

      }else if (this.filtering_date_value == "this month") {

        let d = new Date();
        let month = d.getMonth() + 1;
        let first_day_of_month = d.getFullYear() +"-" +(("" + month).length < 2 ? "0" : "") + month + "-01" ;
        this.start_date =first_day_of_month;
        this.end_date = this.currentDate();
        this.transactionDetails();

      }else if (this.filtering_date_value == "this year") {

        let d = new Date();
        let first_month_of_year = d.getFullYear() +"-01-01" ;
        this.start_date =first_month_of_year;
        this.end_date = this.currentDate();
        this.transactionDetails();

      }else if (this.filtering_date_value == "last year") {

        let date = new Date();
        this.start_date =date.getFullYear() - 1  +"-01-01" ;
        this.end_date = date.getFullYear() - 1  +"-12-31" ;
        this.transactionDetails();

        }

    },

    currentDate() {
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
      return output;
    },

    previousDate(date) {

      let month = date.getMonth() + 1;
      let day =date.getDate()  ;
      let output = date.getFullYear() +"-" +(("" + month).length < 2 ? "0" : "") + month + "-" + (("" + day).length < 2 ? "0" : "") + day ;
      return output;

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

.filtering_row{

  margin-bottom: 15px;

}

.total_container {
    min-height: 60px;
    box-shadow: 1px 1px 2px 1px #ddd;
    margin-bottom: 15px;
    background: white;
    width: 24%;
    margin-left: 1%;
}

.total_container>h4{
  margin-top:17px;
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
