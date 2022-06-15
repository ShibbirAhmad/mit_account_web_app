
<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'mohasagor_it' }" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">mit</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row  over_view_row text-center">
             <div class="col-md-4"> <h4 class="heading">Total Received : <strong style="color:green"> {{ total_payment }} </strong> </h4>  </div>
             <div class="col-md-4"> <h4> Total Cost : <strong style="color:#f39c12" > {{ monthly_total }} </strong> </h4>   </div>
             <div class="col-md-4"> <h4> Total Profit/Loss : <strong style="color:red"> {{ total_payment -  monthly_total }} </strong> </h4>   </div>
          </div>
          <div class="row data_table_row">
            <div class="col-md-12 col-md-12">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <div class="row">
                    <div class="col-md-4"> <h3 class="box-title"> Clients Payment History </h3> </div>
                    <div class="col-md-3">
                         <date-picker
                              autocomplete="off"
                              v-model="start_date"
                              placeholder="start-date"
                              :config="options"
                            ></date-picker>
                      </div>
                    <div class="col-md-3">
                         <date-picker
                              autocomplete="off"
                              v-model="end_date"
                              placeholder="end-date"
                              :config="options"
                            ></date-picker>
                     </div>
                     <div class="col-md-1">
                        <button @click="paymentDetails" class="btn btn-sm btn-success" > Analys </button>
                      </div>
                      <div class="col-md-1"> </div>
                  </div>
                </div>

                <div class="box-body">
                  <table class="table text-center table-striped table-hover table-bordered">
                    <tr>
                      <th width="5%">#</th>
                      <th width="20%" >Date</th>
                      <th width="40%">Comment</th>
                      <th width="15%" >Paid By</th>
                      <th width="20%">Amount</th>
                    </tr>
                    <tbody>
                      <tr v-for="(item, index) in clients_payments.data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.comment }}</td>
                        <td>{{ item.paid_by }}</td>
                        <td>{{ item.amount }}</td>
                      </tr>
                      <tr>
                        <td colspan="4">In Total</td>
                        <td> <span class="badge badge-success">{{ totalPaymentCalculate() }}</span> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                 <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-12">
                      <pagination
                        :data="clients_payments"
                        @pagination-change-page="paymentDetails"
                        :limit="3"
                      >
                      </pagination>
                    </div>
                    <div class="col-sm-12 ">
                      <p>
                        Showing <strong>{{ clients_payments.from }}</strong> to
                        <strong>{{ clients_payments.to }}</strong> of total
                        <strong>{{ clients_payments.total }}</strong> entries
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-12 col-md-12">
              <div class="box">
                <div class="box-header with-border text-center">
                    <h4> Monthly Analysis </h4>
                </div>
                <div class="box-body">
                  <table class="table text-center table-striped table-hover table-bordered">
                    <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="15%">Date</th>
                        <th width="10%">Month</th>
                        <th width="15%">Received</th>
                        <th width="15%">Cost</th>
                        <th width="10%">Profit/Loss</th>
                        <th width="20%">Comment</th>
                         </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(per_month, index) in monthly_records.data"
                        :key="index"
                      >
                        <td style="width:20px;">{{ index+1}}</td>

                     <td>
                       {{ per_month.created_at }}
                     </td>
                         <td>
                      <strong>
                         {{ per_month.month }}
                      </strong>
                     </td>
                         <td>
                     <strong>
                         {{ per_month.received_amount }}
                     </strong>
                     </td>
                      <td>
                       {{ per_month.cost_amount }}
                     </td>

                      <td>
                       {{ per_month.received_amount - per_month.cost_amount }}
                     </td>

                      <td>{{ per_month.comment }} </td>

                      </tr>

                    </tbody>
                  </table>

                  <button
                    style="margin-top: 10px"
                    @click="showModal"
                    class="btn btn-success"
                  >
                    Add Monthwise Record
                  </button>
                </div>
                    <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-12">
                      <pagination
                        :data="monthly_records"
                        @pagination-change-page="getMonthlyRecord"
                        :limit="3"
                      >
                      </pagination>
                    </div>
                    <div class="col-sm-12 ">
                      <p>
                        Showing <strong>{{ monthly_records.from }}</strong> to
                        <strong>{{ monthly_records.to }}</strong> of total
                        <strong>{{ monthly_records.total }}</strong> entries
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

      <modal name="example" :width="500" :height="320">
        <form @submit.prevent="addPerMonthRecod" class="form">
          <div class="card" style="padding: 20px">
            <div class="card-body">
              <div class="form-group">
                <label>Month</label>
                <select  :class="{ 'is-invalid': form.errors.has('month') }" class="form-control" v-model="form.month">
                  <option  disabled>select a month</option>
                  <option value="January">January</option>
                  <option value="February">February</option>
                  <option value="March">March</option>
                  <option value="April">April</option>
                  <option value="May">May</option>
                  <option value="Jun">Jun</option>
                  <option value="July">July</option>
                  <option value="Augest">Augest</option>
                  <option value="September">September</option>
                  <option value="Nomeber">Nomeber</option>
                  <option value="December">December</option>
                </select>
                <has-error :form="form" field="month" > </has-error>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                      <label>Received Amount</label>
                      <input type="number" @keyup="calculateProfit" :class="{ 'is-invalid': form.errors.has('received_amount') }"  v-model="form.received_amount" class="form-control" />
                      <has-error :form="form" field="received_amount" > </has-error>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                      <label>Cost Amount</label>
                      <input type="number" @keyup="calculateProfit" :class="{ 'is-invalid': form.errors.has('cost_amount') }"  v-model="form.cost_amount" class="form-control" />
                      <has-error :form="form" field="cost_amount" > </has-error>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="cost_lost">Profit/Loss</label>
                  <div class="form-group">
                    <input class="form-control"  v-model="profit_analysis" readonly type="text">
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label>Comment</label>
                <input type="text" v-model="form.comment" class="form-control" />
              </div>
              <div class="form-group text-center">
                  <button class="btn btn-success " >
                    Submit
                  </button>
              </div>
            </div>
          </div>
        </form>
    </modal>

  </div>
</template>

<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";

export default {
  created() {
    this.paymentDetails();
    this.getMonthlyRecord();
  },
  data() {
    return {
      loading: true,
      clients_payments:{},
      total_payment: "",
      monthly_records:{},
      monthly_total: "",
      start_date: "",
      end_date: "",
      form: new Form({
        mit_id: this.$route.params.id,
        month: "select a month",
        received_amount: "",
        cost_amount: "",
        comment: "",
      }),
      profit_analysis:"",
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
    };
  },
  methods: {

    addPerMonthRecod(){
      if (this.form.month=='select a month') {
         alert('selecet a month');
         return ;
      }
        this.form.post("/api/mit/monthly/record/add", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then(resp=>{
          if (resp.data.status == "OK") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.form.month='';
            this.form.received_amount='';
            this.form.cost_amount='';
            this.form.comment='';
            this.profit_analysis='';
            this.getMonthlyRecord();
            this.paymentDetails();
            this.$modal.hide('example');
          }else {
            this.$toasted.show(resp.data.message,{
                type: "error",
                position: "top-center",
                duration: 4000,
            });
          }
        })
    },

    calculateProfit(){
        let received = this.form.received_amount
        let cost =  this.form.cost_amount ;
        this.profit_analysis = parseFloat(received) - parseFloat(cost) ;
    },

    getMonthlyRecord(page=1) {
      axios
        .get("/api/get/monthly/records/"+this.$route.params.id,'?page='+page)
        .then((resp) => {
          console.log(resp);
          this.monthly_records = resp.data.monthly_records;
          this.monthly_total = resp.data.monthly_total;
        })
     },

    paymentDetails(page=1) {
      axios
        .get("/api/mit/clients/payment/records?page="+page, {
           params : {
                 start_date : this.start_date ,
                 end_date : this.end_date ,
           }
        })
        .then((resp) => {
          console.log(resp);
          this.clients_payments = resp.data.clients_payments;
          this.total_payment = resp.data.total_payment;
        })
     },

     showModal() {
      this.$modal.show("example");
    },

    totalPaymentCalculate(){
      if (this.clients_payments.data.length > 0) {
           let amount = 0 ;
           this.clients_payments.data.forEach(item =>{
               amount += parseFloat(item.amount) ;
           });
           return amount.toFixed(2) ;
      }
    },

     cDate() {
      //current date
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
      this.salary_date = output;
    },

  },
  components : {
    HasError
  }
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

.data_table_row{
  width: 95%;
  overflow-x: scroll;
}

.over_view_row{
    width: 92%;
    height: 60px;
    margin-left: 0%;
    box-shadow: 1px 1px 2px 1px #ddd;
    margin-bottom: 20px;
    padding-top: 15px;

}

</style>
