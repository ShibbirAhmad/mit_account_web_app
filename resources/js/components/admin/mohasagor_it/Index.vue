<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">mit </li>
        </ol>
      </section>
      <br>
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-11 col-md-11 ">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading"> Mohasagor It </h3>
                </div>
                <div class="box-body">

                  <table class="table text-center table-striped  table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">  Name </th>
                        <th scope="col">Client</th>
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
                        v-for="(item, index) in mit"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                          <router-link style="color:blue;font-size:16px"
                            :to="{
                              name: 'mit_clients_payment',
                              params: { id: item.id },
                            }"
                            >{{ item.name }}
                          </router-link>

                          </td>
                        <td>
                           <router-link style="color:blue;font-size:16px"
                            :to="{
                              name: 'mit_clients',
                              params: { id: item.id },
                            }">
                             <i class="fa fa-users"></i>  <sup>{{ item.clients.length }}</sup>
                           </router-link>
                        </td>

                          <td>
                                <span class="badge badge-warning"> <i class="fa fa-money"></i>  {{ totalEarnedAmount(item.clients) }} </span>
                         </td>

                         <td>
                             <span class="badge badge-success"> <i class="fa fa-money"></i>  {{ totalPaymentPaid(item.clients) }} </span>
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
export default {
  created() {
    this.getMit();
  },
  data() {
    return {
      mit:"",
      loading: true,
      basePath: this.$store.getters.image_base_link,
    };
  },
  methods: {

    getMit() {
      axios
        .get("/api/mit")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.mit = resp.data.mit;
             this.loading = false;
          }
        })

    },

    totalEarnedAmount(clients) {
          let total = 0;
          clients.forEach(client => {
                client.tasks.forEach(item => {
                    total +=parseFloat(item.amount);
                });
          });
         return this.formatToCurrency(total);
    },

    totalPaymentPaid(clients){
          let  x= 0 ;
          clients.forEach(client=>{
              client.payments.forEach(item => {
                x += parseFloat(item.amount);
              })
          });
          return this.formatToCurrency(x) ;
    },

    totalDueAmount(clients){
          let  x= 0 ;
          let y= 0 ;

         clients.forEach(client => {
                client.tasks.forEach(item => {
                    x += parseFloat(item.amount);
                });
          });

          clients.forEach(client=>{
              client.payments.forEach(item => {
                y += parseFloat(item.amount);
              })
          });

          let due = parseFloat(x) - y ;

          return this.formatToCurrency(due) ;
    },

  formatToCurrency(amount){
    return (amount).toFixed(2);
  }

  },


};
</script>

<style scoped>


.badge {
  padding: 8px 20px !important;
}

</style>
