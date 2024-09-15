<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link
            :to="{
              name: 'boost_agency_resellers'
            }"
            class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">boost user</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-11">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Boost client</h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="boostResellerAdd"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label> Name</label>
                        <input
                          v-model="form.name"
                          type="text"
                          required
                          name="name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('name') }"
                          placeholder="Ex: mohammad "
                        />
                        <has-error :form="form" field="name"></has-error>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label> Company Name</label>
                        <input
                          v-model="form.company_name"
                          type="text"
                          required
                          name="company_name"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('company_name'),
                          }"
                          placeholder="Ex: example"
                        />
                        <has-error
                          :form="form"
                          field="company_name"
                        ></has-error>
                      </div>
                    </div>

                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Mobile Number</label>

                        <input
                          v-model="form.phone"
                          type="text"
                          name="phone"
                          class="form-control"
                          required
                          placeholder="01xxxxxxxxx"
                        />
                          <has-error :form="form" field="phone"></has-error>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Address </label>

                        <input
                          v-model="form.address"
                          type="text"
                          name="address"
                          class="form-control"
                          required
                          placeholder="Dhaka"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="row">

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Advertise Account Name </label>

                        <input
                          v-model="form.add_account_name"
                          type="text"
                          class="form-control"
                          placeholder="abc"
                        />
                      </div>
                    </div>

       <div class="col-md-6">
                      <div class="form-group">
                        <label>Facebook Page Name (optional) </label>
                        <input
                          v-model="form.page_name"
                          type="text"
                          class="form-control"
                          placeholder="xyz"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="row">
                <div class="col-md-4">
                      <div class="form-group">
                        <label>Dollar Rate </label>

                        <input
                          v-model.number="form.dollar_rate"
                          type="number"
                          @keyup="amount"
                          class="form-control"
                          required
                          placeholder="00"
                        />
                      </div>
                    </div>
     
                    <div class="col-md-4">
                       <div class="form-group">
                        <label>Dollars </label>

                        <input
                          v-model.number="form.dollar"
                          type="number"
                          @keyup="amount"
                          class="form-control"
                          placeholder="000"
                        />
                      </div>
                    </div>

                      <div class="col-md-4">
                       <div class="form-group">
                        <label>Amounts </label>

                        <input
                          v-model.number="form.amount"
                          type="number"
                          readonly
                          class="form-control"
                          placeholder="0"
                        />
                      </div>
                    </div>

                      <div class="col-md-4">
                       <div class="form-group">
                        <label>Paid Amounts </label>

                        <input
                          v-model.number="form.paid"
                          type="number"
                          @keyup="dueAmount"
                          class="form-control"
                          placeholder="0"
                        />
                      </div>
                    </div>

                    <div class="col-md-4">
                           <div class="form-group">
                    <label for="credit_in">Credit In</label>
                    <select

                      class="form-control"
                      v-model="form.balance_id"
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


                    </div>

                    <div class="col-md-4">
                       <div class="form-group">
                        <label>Due Amounts </label>

                        <input
                          v-model.number="form.due"
                          type="number"
                          readonly
                          class="form-control"
                          placeholder="0"
                        />
                      </div>
                    </div>



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
                </form>
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
Vue.component(HasError.name, HasError);
export default {
  created(){
    this.balanceList();
  },
  data() {
    return {
      form: new Form({
        boost_agency_id: this.$route.params.id,
        name: "",
        company_name: "",
        address: "",
        phone: "",
        dollar_rate: "",
        dollar: "",
        paid: "",
        balance_id: "",
        due: "",
        amount: "",
        add_account_name: "",
        page_name: "",
      }),
      error: "",
      balance:'',
    };
  },

  methods: {

      balanceList() {
      axios.get("/api/balance/list/boost").then((resp) => {
        this.balance = resp.data.balance;
      });
    },


    async boostResellerAdd() {

      if (this.form.amount < 0  ) {
        alert('total amount can not be less than zero ');
        return ;
     }

     if (this.form.paid > 0 && this.form.balance_id == '' ) {
        alert('select payment method ');
        return ;
     }

      await this.form
        .post("/api/boost/agency/reseller/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true ) {
            this.$router.push({
              name: "boost_agency_resellers",
            });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
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

     amount (){
        let rate  =  this.form.dollar_rate.length <= 0 ? 0 : parseInt(this.form.dollar_rate)  ;
        let dollar  = this.form.dollar.length <=0 ? 0 : parseInt(this.form.dollar) ;
        this.form.amount = rate * dollar ;
     },

    dueAmount (){
        let total_amount  = this.form.amount.length <=0 ? 0 : parseInt(this.form.amount) ;
        let paid =  this.form.paid.length <= 0 ? 0 :  parseInt( this.form.paid)
        this.form.due =  total_amount - paid ;
     }


  },


};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
