<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'service_client_add'}" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          >Add Clients</router-link>
        </h1>

        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active"> Clients </li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-11 col-md-11 ">
              <div class="box box-primary">
                <div class="box-header with-border ">
                     <div class="row">
                    <div class="col-md-6 text-center">
                            <h3 class="box-title heading"> {{ service.name }} Clients </h3>
                    </div>
                       <div class="col-md-6">
                           <input type="text" v-model="search" v-on:keyup="searchClients" placeholder="search by name, phone or company-name" class="form-control">
                       </div>
                  </div>
                </div>
                <div class="box-body">

                  <table class="table  table-striped  table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col"> Name </th>
                        <th scope="col"> Company Name </th>
                        <th scope="col"> Phone </th>
                        <th scope="col"> Amount </th>
                        <th scope="col"> Paid </th>
                        <th scope="col"> Due </th>
                      </tr>
                    </thead>
                    <tbody>
                   <h1 style="text-align:center;" v-if="loading"><i class="fa fa-spinner fa-spin"></i></h1>
                      <tr v-else
                        v-for="(item, index) in clients"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                            {{ item.client_info.name }}
                          </td>
                        <td>
                         <router-link style="font-size:15px;color:blue" :to="{name:'service_client_and_payment',params:{client_id:item.client_info.id}}"> {{ item.client_info.company_name }} </router-link>
                        </td>
                          <td>{{ item.client_info.phone }} </td>

                          <td> <span> <i class="fa fa-money"></i> {{ item.total_amount }}  </span> </td>
                          <td> <span> <i class="fa fa-money"></i> {{ item.total_paid }}  </span> </td>
                          <td> <span> <i class="fa fa-money"></i> {{ item.total_amount - item.total_paid  }}  </span> </td>

                      </tr>
                      <tr>
                        <td colspan="4"> Total Getable Amount</td>
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


import { Form, HasError, AlertError } from "vform";
export default {
  created() {
    this.getServiceDetails();
  },
  data() {
    return {
      clients:{},
      service:"",
      search:"",
      loading: true,
      basePath: this.$store.getters.image_base_link,
      clients_company_name:"",
      balance:'',
    };
  },
  components:{
    HasError
  },
  methods: {



    getServiceDetails() {
      axios
        .get("/api/service/details/and/clients/"+this.$route.params.id,{
          params:{
            search: this.search
          }
        })
        .then((resp) => {
          console.log(resp);
             this.service = resp.data.service;
             this.clients = resp.data.clients;
             this.loading = false;
        })

    },

   searchClients(){
       if (this.search.length > 3) {
           this.getServiceDetails() ;
       }else{
         this.getServiceDetails();
       }
    },


 },


};
</script>

<style scoped>

.badge {
  padding: 6px 8px !important;
}


.box-primary {
  overflow-x: scroll;
}

.total_style {
  border: 2px solid #ddd;
  padding: 4px 25px;
}
.total_row {
  padding-bottom: 20px;
}
</style>
