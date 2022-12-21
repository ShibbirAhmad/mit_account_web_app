<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
           <router-link :to="{ name: 'mohasagor_sms'}" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
          <router-link :to="{ name: 'sms_client_add',params:{id:this.$route.params.id} }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          >Add client</router-link>

        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">sms client </li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-11 col-md-11 ">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                   <div class="row">
                       <div class="col-md-6">
                          <h3 class="box-title heading">SMS Clients</h3>
                          </div>
                       <div class="col-md-6">
                           <input type="text" v-model="search" v-on:keyup="searchClient" placeholder="search by name, phone or company-name" class="form-control">
                       </div>
                   </div>
                </div>
                <div class="box-body">

                  <table class="table text-center table-striped  table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col"> Name </th>
                        <th scope="col"> Company Name </th>
                        <th scope="col"> Phone </th>
                        <th scope="col"> Amount </th>
                        <th scope="col"> Paid </th>
                        <th scope="col"> Due </th>
                        <th scope="col"> Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(item, index) in clients.data"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                            {{ item.name }}
                          </td>
                        <td>

                          <router-link style="font-size:15px;color:blue" :to="{name:'sms_client_transactions',params:{id:item.id}}"> {{ item.company_name }} <sup style="color:black;font-weight:bold"> {{ item.accounts.length }} </sup> </router-link>
                        </td>
                          <td>{{ item.phone }} </td>
                          <td> <span class=""> <i class="fa fa-money"></i> {{ clientTotalAmount(item.transactions) }}  </span> </td>
                          <td> <span class=""> <i class="fa fa-money"></i> {{ clientTotalPayment(item.payments) }}  </span> </td>
                          <td> <span class="badge badge-warning"> <i class="fa fa-money"></i> {{ clientTotalDue(item.transactions,item.payments)}}  </span> </td>
                          <td>
                              <button class="btn btn-xs btn-primary" @click="showsmsStoreModal(item.company_name,item.id,item.accounts)" > Add sms </button>
                              <button class="btn btn-xs btn-success" @click="showMoneyPaidModal(item.company_name,item.id)" > Get Paid </button>
                              <router-link class="btn btn-xs btn-success" :to="{name:'sms_client_edit',params:{id:item.id}}"><i class="fa fa-edit"></i></router-link>
                          </td>
                      </tr>
                      <tr>
                        <td colspan="4"> </td>

                        <td> <span class="badge badge-success"> <i class="fa fa-money"></i> {{ totalAmountDistributed() }}  </span> </td>
                        <td> <span class="badge badge-success"> <i class="fa fa-money"></i> {{ totalAmountPaidFromAll() }}  </span> </td>
                        <td> <span class="badge badge-warning"> <i class="fa fa-money"></i> {{ totalAmountDueFromAll() }}  </span> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="box-footer">
                      <div class="row">
                          <div class="col-lg-6">
                              <pagination :data="clients"
                               @pagination-change-page="getClients"></pagination>
                          </div>
                          <div class="col-lg-6 mt-1" style="margin-top: 25px;text-align:right;">
                              <p>Showing <strong>{{clients.from}}</strong> to
                                  <strong>{{clients.to}}</strong> of total
                                  <strong>{{clients.total}}</strong> entries
                              </p>
                          </div>
                      </div>
                </div>
              </div>
            </div>
          </div>

        <!-- start sms store modal  -->
         <modal name="store" :width="400" :height="350">
           <form @submit.prevent="insertClientSms">
              <div class="card" style="padding: 20px">
                 <div class="card-header text-center">
                    <h4 style="font-weight:bold" class="card-title"> store sms into {{ client_company_name }}</h4>
                </div>
              <div class="card-body">

               <div class="form-group">
                  <label for="account">Select  Account</label>
                  <select required class="form-control" v-model="sms_store_form.sms_client_account_id">
                      <option  v-for="(account,index) in advertise_accounts" :value="account.id" :key="index">{{account.website}}</option>
                    </select>
                </div>


                <div class="form-group">
                  <label>Amount</label>
                  <input
                    type="number"
                    name="amount"
                    v-model="sms_store_form.amount"
                    :class="{ 'is-invalid': sms_store_form.errors.has('amount') }"
                    class="form-control"
                    placeholder="0"
                  />
                  <has-error :form="sms_store_form" field="amount" > </has-error>
                </div>

                <br>
                <div class="form-group text-center">
                  <button  type="submit"
                    class="btn btn-success ">
                    Submit
                  </button>
                </div>
                <br>
              </div>
            </div>
           </form>
          </modal>
     <!-- end sms store modal  -->

   <!-- start payment store modal  -->
         <modal name="paid" :width="400" :height="350">
           <form @submit.prevent="getclientPayment">
              <div class="card" style="padding: 20px">
                 <div class="card-header text-center">
                    <h4 style="font-weight:bold" class="card-title"> get payment of  {{ client_company_name }}</h4>
                </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Amount</label>
                  <input
                    type="number"
                    name="amount"
                    v-model="payment_paid_form.amount"
                    :class="{ 'is-invalid': payment_paid_form.errors.has('amount') }"
                    class="form-control"
                    placeholder="0"
                  />
                  <has-error :form="payment_paid_form" field="amount" > </has-error>
                </div>

                <div class="form-group">
                  <label for="credit_in">Credit In</label>
                  <select required class="form-control" v-model="payment_paid_form.credit_in">
                      <option value="" disabled selected>Select Balance</option>
                      <option  v-for="(balance,index) in balance" :value="balance.id" :key="index">{{balance.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                  <label for="comment">comment</label>
                    <input
                    type="text"
                    name="comment"
                    v-model="payment_paid_form.comment"
                    class="form-control"
                    placeholder="sms client payment"
                  />
                </div>


                <br>
                <div class="form-group text-center">
                  <button  type="submit"
                    class="btn btn-success ">
                    Submit
                  </button>
                </div>
                <br>
              </div>
            </div>
           </form>
          </modal>
   <!-- end payment store modal  -->

        </div>
      </section>
    </div>
  </div>
</template>

<script>

import Vue from "vue";
import { Form, HasError, AlertError } from "vform";

export default {
  mounted() {
    this.getClients();
    this.balanceList();
  },
  data() {
    return {
      clients:{},
      advertise_accounts:"",
      search:"",
      client_company_name:"",
      loading: true,
      basePath: this.$store.getters.image_base_link,
      total_investment: "",
      total_profit_paid: "",
      total_due_amount: 0,
      sms_store_form: new Form({
        sms_client_id: "",
        sms_client_account_id: "",
        amount: "",
      }),
      payment_paid_form: new Form({
        sms_client_id: "",
        credit_in:"",
        amount: "",
        comment: "",
      }),
      balance:'',
    };
  },
  methods: {

     balanceList() {
      axios
        .get("/api/balance/list/mit")
        .then((resp) => {
            this.balance = resp.data.balance;
        })
    },

    getClients(page=1) {
      axios
        .get("/api/sms/client/list?page=" + page,{
            params : {
              id : this.$route.params.id,
              search : this.search ,
            }
        } )
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.clients = resp.data.sms_clients;
             this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    searchClient(){
       if (this.search.length > 3) {
           this.getClients() ;
       }else{
         this.getClients();
       }
    },

    showsmsStoreModal(company_name,id,accounts) {
      this.client_company_name = company_name ;
      this.advertise_accounts = accounts ;
      this.sms_store_form.sms_client_id = id ;
      this.$modal.show("store");
    },
     showMoneyPaidModal(company_name,id) {
      this.client_company_name = company_name ;
      this.payment_paid_form.sms_client_id = id ;
      this.$modal.show("paid");
    },

    insertClientSms(){
       this.sms_store_form
        .post("/api/store/client/sms", {
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
            this.client_company_name='';
            this.sms_store_form.amount='';
            this.sms_store_form.sms_client_id='';
            this.getClients();
            this.$modal.hide('store');
          }else {
            this.$toasted.show(resp.data.message,{
                type: "error",
                position: "top-center",
                duration: 4000,
            });
          }
        })
        .catch((e) => {
          this.errors = [];
          this.errors.push(e.response.data.errors);
        });

    },

    getclientPayment(){
       this.payment_paid_form
        .post("/api/store/sms/client/payment", {
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
            this.client_company_name='';
            this.payment_paid_form.amount='';
            this.payment_paid_form.comment='';
            this.payment_paid_form.sms_client_id='';
            this.payment_paid_form.credit_in=0;
            this.getClients();
            this.$modal.hide('paid');
          }else {
            this.$toasted.show(resp.data.message,{
                type: "error",
                position: "top-center",
                duration: 4000,
            });
          }
        })
        .catch((e) => {
          this.errors = [];
          this.errors.push(e.response.data.errors);
        });

    },


   totalAmountPaidFromAll(){
     if (this.clients.data.length > 0 ) {
         let  x= 0 ;
         this.clients.data.forEach(client=>{
              client.payments.forEach(item => {
                x += parseInt(item.amount) ;
              }) ;
         });
         return x ;
     }

    },

    totalAmountDueFromAll(){
            let  x= 0 ;
            let y = 0 ;
            if (this.clients.data.length > 0 ) {
            this.clients.data.forEach(client=>{
                  client.payments.forEach(item => {
                    x += parseInt(item.amount) ;
                  }) ;
            });
            }

          if (this.clients.data.length > 0 ) {
           this.clients.data.forEach(client=>{
                  client.transactions.forEach(item => {
                    y += parseInt(item.amount) ;
                  }) ;
            });
          }
             let z = y - x ;
            return z ;
        },

   totalsmsDistributed(){
     if (this.clients.data.length > 0 ) {
         let  x= 0 ;
         this.clients.data.forEach(client=>{
              client.transactions.forEach(item => {
                x += parseInt(item.sms) ;
              }) ;
         });
         return x ;
     }
    },


   totalAmountDistributed(){
      if (this.clients.data.length > 0 ) {
         let  x= 0 ;
         this.clients.data.forEach(client=>{
              client.transactions.forEach(item => {
                x += parseInt(item.amount)  ;
              }) ;
         });
         return x ;
      }
    },

    clientTotalAmount($amounts){
            let  x= 0 ;
            $amounts.forEach(transaction=>{
                x += parseInt(transaction.amount) ;
            });
            return x ;
        },

    clientTotalPayment($payments){
            let  x= 0 ;
            $payments.forEach(payment=>{
                x +=  parseInt(payment.amount) ;
            });
            return x ;
        },

    clientTotalDue($sms,$payments){
            let  x= 0 ;
            let  y= 0 ;
            $payments.forEach(payment=>{
                x += parseInt(payment.amount)  ;
            });

           $sms.forEach(sms=>{
                y += parseInt(sms.amount) ;
            });
           let due = y - x ;
            return due ;
        },

  },
  components:{
    HasError
  }
};
</script>

<style scoped>

.badge {
  padding: 6px 15px !important;
}
span {
  font-size: 14px ;
}


.total_style {
  border: 2px solid #ddd;
  padding: 4px 25px;
}
.total_row {
  padding-bottom: 20px;
}
</style>
