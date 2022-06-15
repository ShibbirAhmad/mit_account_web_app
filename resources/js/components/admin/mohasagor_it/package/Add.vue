<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'service_clients' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Client</li>
        </ol>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row">
              <div class="service_container col-lg-7 col-md-7 col-xs-12">
                <form
                  @submit.prevent="addClientPackage"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>

                  <div class="form-group">
                    <label>Client Phone Number</label>

                    <input
                      v-model="client_phone"
                      type="text"
                      autofocus
                      name="phone"
                      placeholder="01xxxxxxxxx"
                      class="form-control"
                      maxlength="11"
                      minlength="11"
                      autocomplete="off"
                      @keyup="searchClient"
                    />
                    <has-error :form="form" field="phone"></has-error>
                  </div>
                  <div v-if="client">
                    <div class="form-group">
                      <label> Select Service</label>
                      <select
                        name="service_id"
                        v-model="form.service_id"
                        required
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('service_id') }"
                      >
                        <option value="" disabled>select one</option>
                        <option
                          v-for="(service, index) in services"
                          :key="index"
                          :value="service.id"
                        >
                          {{ service.name }}
                        </option>
                      </select>
                      <has-error :form="form" field="service_id"></has-error>
                    </div>

                    <div class="form-group">
                      <label> Service Type</label>
                      <select
                        required
                        name="is_monthly"
                        v-model="form.is_monthly"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('is_monthly') }"
                      >
                        <option value="2">Contractual</option>
                        <option value="1">Monthly</option>
                      </select>
                      <has-error :form="form" field="is_monthly"></has-error>
                    </div>

                    <div v-show="form.is_monthly == 1" class="row">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                          <label>Start Date </label>
                          <date-picker
                            autocomplete="off"
                            :class="{
                              'is-invalid': form.errors.has('start_date'),
                            }"
                            v-model="form.start_date"
                            :config="options"
                          >
                          </date-picker>
                          <has-error
                            :form="form"
                            field="start_date"
                          ></has-error>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                          <label>End Date </label>
                          <date-picker
                            autocomplete="off"
                            :class="{
                              'is-invalid': form.errors.has('end_date'),
                            }"
                            v-model="form.end_date"
                            :config="options"
                          >
                          </date-picker>
                          <has-error :form="form" field="end_date"></has-error>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                          <label>Total Amount </label>

                          <input
                            v-model="form.amount"
                            type="number"
                            name="amount"
                            @keyup="dueCalculate"
                            class="form-control"
                            :class="{
                              'is-invalid': form.errors.has('amount'),
                            }"
                            placeholder="0"
                          />
                          <has-error :form="form" field="amount"></has-error>
                        </div>
                      </div>

                      <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                          <label>Paid </label>
                          <input
                            v-model="form.paid"
                            type="number"
                            name="paid"
                            class="form-control"
                            @keyup="dueCalculate"
                            :class="{
                              'is-invalid': form.errors.has('paid'),
                            }"
                            placeholder="0"
                          />
                          <has-error :form="form" field="paid"></has-error>
                        </div>
                      </div>

                      <div
                        v-show="this.form.is_monthly == 1"
                        class="col-md-12 col-lg-12"
                      >
                        <div class="form-group">
                          <label> Average bill of every month </label>
                          <input
                            v-model="form.monthly_bill"
                            type="text"
                            readonly
                            class="form-control"
                          />
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="credit_in">Credit In</label>
                          <select class="form-control" v-model="form.credit_in">
                            <option value="" disabled selected>
                              Select Balance
                            </option>
                            <option
                              v-for="(balance, index) in balance"
                              :value="balance.id"
                              :key="index"
                            >
                              {{ balance.name }}
                            </option>
                          </select>
                          <button
                            type="button"
                            v-if="
                              form.paid > 0 && form.partials_payment_amount <= 0
                            "
                            title="Partial payment"
                            class="btn btn-sm btn-info"
                            @click="partialsPayment"
                          >
                            Partial Payment
                          </button>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Due Amount </label>
                          <input
                            v-model="form.due"
                            type="text"
                            readonly
                            name="due"
                            class="form-control"
                            placeholder="0"
                          />
                        </div>
                      </div>
                    </div>

                    <div class="form-group">
                      <input
                        v-model="form.note"
                        type="text"
                        name="note"
                        class="form-control"
                        placeholder="Write Comment/Note(if required)"
                      />
                    </div>

                    <div class="form-group">
                      <label
                        >Next payment date as per commitment (if required)
                      </label>
                      <date-picker
                        autocomplete="off"
                        v-model="form.payment_date"
                        :config="options"
                      >
                      </date-picker>
                    </div>

                    <div class="form-group text-center">
                      <button
                        :disabled="form.busy"
                        type="submit"
                        class="btn btn-primary"
                      >
                        <i class="fa fa-spin fa-spinner" v-if="form.busy"></i
                        >Submit
                      </button>
                    </div>
                  </div>
                </form>
              </div>

              <div class="service_container col-lg-4 col-md-4 col-xs-12">
                <div class="row">
                  <div class="col-lg-12 col-md-12">
                      <h4 style="text-align: center" class="heading">
                        Client Information
                      </h4>
                      <ul class="client_info_list" v-if="client">
                        <li>Name : {{ client.name }}</li>
                        <li>Company Name : {{ client.company_name }}</li>
                        <li>Phone : {{ client.phone }}</li>
                        <li>Email : {{ client.email ? client.email : "" }}</li>
                        <li>Address : {{ client.address }}</li>
                      </ul>
                  </div>
                  <div class="col-lg-12 col-md-12">
                       <h4 style="text-align: center" class="heading">
                           Service Preview
                              <ul class="service_info_list" v-if="form.service_id">
                                <li v-if="service.id==form.service_id" v-for="(service,index) in services" :key="index">Service Name : {{ service.name }}  </li>
                                <li> Service Type : {{ form.is_monthly ==1 ? 'Monthly'  : 'Contractual' }}</li>
                                <li v-if="form.is_monthly==1">Total Month  : {{ form.total_service_month }}</li>
                                <li>Total Amount  : {{ form.amount }}</li>
                                <li>Total Paid : {{ form.paid }}</li>
                                <li>Total Due : {{ form.due }}</li>
                              </ul>
                      </h4>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>


<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
import datePicker from "vue-bootstrap-datetimepicker";
Vue.component(HasError.name, HasError, datePicker);
export default {
  created() {
    this.getServices();
    this.balanceList();
    this.todayDate();
  },
  data() {
    return {
      form: new Form({
        client_id: null,
        service_id: "",
        credit_in: "",
        amount: null,
        paid: 0,
        due: null,
        is_monthly: 2,
        total_service_month: 0,
        monthly_bill: 0,
        note: "",
        start_date: null,
        end_date: null,
        payment_date: null,
        partials_paid_by: "",
        partials_payment_amount: 0,
      }),
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      error: "",
      balance: "",
      services: "",
      client: "",
      client_phone: null,
    };
  },

  methods: {
    async addClientPackage() {
      //checking payment balance if paid
      if (this.form.paid > 0 && this.form.credit_in == "") {
        alert("select credit balance");
        return;
      }
      //checking date if package is monthly
      if (
        (  this.form.is_monthly == 1 && this.form.start_date == null ) ||
         ( this.form.is_monthly == 1 && this.form.end_date == null) ) {
        alert("set date limit of service package");
        return;
      }
      await this.form
        .post("/api/service/client/package/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == 1) {
            this.$router.push({
              name: "service_details",
              params: { id: this.form.service_id },
            });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          } else {
            this.error = resp.data.message;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    monthDiff() {
      let months;
      let end_date = new Date(this.form.end_date);
      let start_date = new Date(this.form.start_date);
      months = (end_date.getFullYear() - start_date.getFullYear()) * 12;
      months -= start_date.getMonth();
      months += end_date.getMonth();
      return months <= 0 ? 0 : months + 1;
    },

    todayDate() {
      //current date
      let d = new Date();
      //current mount
      //current day
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
      this.form.start_date = output;
    },

    dueCalculate() {
      //checking monthly condition
      if (this.form.is_monthly == 1) {
        if (this.form.end_date == null) {
          alert("select end of the package date");
          this.form.amount = 0;
          return;
        }
        this.averageBillCalculate();
      }

      let amount = this.form.amount.length <= 0 && this.form.amount <= 0 ? 0 : parseFloat(this.form.amount);
      let paid = this.form.paid.length <= 0 && this.form.paid <= 0 ? 0 : parseFloat(this.form.paid);
      if (paid > amount) {
        alert("Paid Amount is grater than the total amount ");
        this.form.paid = amount;
        return;
      }
      return (this.form.due = amount - paid);
    },

    averageBillCalculate() {

      let amount = this.form.amount.length <= 0 && this.form.amount <= 0 ? 0 : parseFloat(this.form.amount);
      let months = parseFloat(this.monthDiff())
      let average = amount / (months + 1)  ;
      this.form.total_service_month=months;
      this.form.monthly_bill = average.toFixed(2);
       return ;
    },

    partialsPayment() {
      let inputOptions = {};
      this.balance.forEach((ele) => {
        if (ele.id != this.form.credit_in) {
          inputOptions[ele.name] = ele.name;
        }
      });
      Swal.fire({
        title: "Partials Payment",
        input: "select",
        inputOptions: inputOptions,
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: "Amount",
            input: "number",
            showCancelButton: false,
          }).then((amount) => {
            if (amount.value) {
              this.$toasted.show(
                `partials payment.${result.value} : ${amount.value}`,
                {
                  type: "info",
                  position: "top-center",
                  duration: 4000,
                }
              );
              this.form.partials_paid_by = result.value;
              this.form.partials_payment_amount = amount.value;
              console.log(typeof amount.value);
              this.form.paid =
                parseFloat(amount.value) + parseFloat(this.form.paid);
              this.form.due =
                parseFloat(this.form.amount) - parseFloat(this.form.paid);
            }
          });
        }
      });
    },

    getServices() {
      axios.get("/api/services").then((resp) => {
        console.log(resp);
        this.services = resp.data.services;
      });
    },

    balanceList() {
      axios.get("/api/balance/list/mit").then((resp) => {
        this.balance = resp.data.balance;
      });
    },

    async searchClient() {
      if (this.client_phone.length == 11) {
        this.client = "";
        this.client_id = null;
        await axios
          .get("/api/service/client/search/" + this.client_phone)
          .then((resp) => {
            console.log(resp);
            if (resp.data.status == 1 && resp.data.client) {
              this.form.client_id = resp.data.client.id;
              this.client = resp.data.client;
              this.$toasted.show(resp.data.message, {
                type: "success",
                position: "top-right",
                duration: 4000,
              });
            } else {
              this.$toasted.show("this client isn't exist ", {
                type: "error",
                position: "top-right",
                duration: 4000,
              });
              return this.$router.push({ name: "service_client_add" });
            }
          });
      }
    },

  },
  computed:{
      end_date: function (value) {
      if (value.length > 1) {
        console.log(value);
        this.dueCalculate();
      }
    },
  }

};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}

input {
  height: 40px !important;
}

input[type="select"] {
  height: 44px !important;
}

.service_container {
  background: #fff;
  border-radius: 5px;
  box-shadow: 0 1pt 3pt rgb(150 165 237);
  padding: 30px;
  margin: 20px 5px;
  min-height: 350px;
}

.client_info_list li {
  padding: 10px 0;
  margin-left: -10%;
  width: 100%;
  display: flex;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  transition: all 0.2s ease;
}

.service_info_list li {
  padding: 10px 0;
  margin-left: -10%;
  width: 100%;
  display: flex;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  font-size: 14px;
}


</style>
