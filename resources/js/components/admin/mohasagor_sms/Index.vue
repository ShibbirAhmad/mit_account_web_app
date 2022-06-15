<template>

  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="goBack" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          >Back</a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">sms </li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-11 ">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading">Mohasagor SMS</h3>
                </div>
                <div class="box-body">

                  <table class="table text-center table-striped  table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col"> Company Name </th>
                        <th scope="col"> Clients & Accounts </th>
                        <th scope="col"> Amount </th>
                        <th scope="col"> Paid </th>
                        <th scope="col"> Due </th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(item, index) in sms"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                          <router-link style="color:blue;font-size:16px"
                            :to="{
                              name: 'sms_clients',
                              params: { id: item.id },
                            }"
                            >{{ item.name }}
                          </router-link>

                          </td>

                          <td>   <span class="badge badge-success"> <i class="fa fa-usrs"></i>   <router-link style="color:#fff;font-size:16px"
                            :to="{
                              name: 'sms_clients',
                              params: { id: item.id },
                            }"
                            >{{ item.clients.length }} <sup>{{ total_sms_account }}</sup>
                          </router-link> </span> </td>
                          <td>
                                <span class="badge badge-warning"> <i class="fa fa-money"></i>  {{ totalDistibutedAmount(item.clients) }} </span>
                         </td>

                         <td>
                             <span class="badge badge-warning"> <i class="fa fa-money"></i>  {{ totalPaymentPaid(item.clients) }} </span>
                        </td>
                         <td>
                           <span class="badge badge-danger"> <i class="fa fa-money"></i>  {{ totalDueAmount(item.clients) }} </span>
                         </td>

                      </tr>
                    </tbody>
                  </table>
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
export default {
  created() {
    this.getSms();
    this.balanceList();
  },
  data() {
    return {
      sms:"",
      sms_name:"",
      total_sms_account:"",
      loading: true,
      basePath: this.$store.getters.image_base_link,
      total_due_amount: 0,
      balance:"",
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

    getSms() {
      axios
        .get("/api/mohasagor/sms")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.sms = resp.data.sms;
             this.total_sms_account = resp.data.total_sms_account;
             this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },


    totalDistibutedAmount(clients) {
          let total = 0;
          clients.forEach(client => {
                client.transactions.forEach(item => {
                    total += parseInt(item.amount) ;
                });
          });
         return this.formatToCurrency(total);
    },

    totalPaymentPaid(clients){
          let  x= 0 ;
           clients.forEach(client => {
                client.payments.forEach(item => {
                    x += parseInt(item.amount) ;
                });
          });
          return this.formatToCurrency(x) ;
    },

  totalDueAmount(clients){
          let  x= 0 ;
          let  y= 0 ;
          clients.forEach(client => {
                client.transactions.forEach(item => {
                    y += parseInt(item.amount );
                });
          });

          clients.forEach(client => {
                client.payments.forEach(item => {
                    x += parseInt(item.amount) ;
                });
          });

          let due = y - x ;
          return this.formatToCurrency(due) ;
    },

  formatToCurrency(amount){
    return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
  },

   goBack(){
       window.history.back();
   },

  },

  components:{
    HasError,
  }
};
</script>

<style scoped>
.box-primary {
  overflow-x: scroll;
}

.badge {
  font-size:14px !important ;
  padding: 6px 15px !important;
}
.total_style {
  border: 2px solid #ddd;
  padding: 4px 25px;
}
.total_row {
  padding-bottom: 20px;
}
</style>
