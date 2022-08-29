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
        <div class="container-fluid">
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

                  <table class="table text-center  table-striped  table-bordered table-hover">
                    <thead>
                      <tr>
                        <th >#</th>
                        <th > Name </th>
                        <th > Company Name </th>
                        <th > Phone </th>
                        <th > Total Amount </th>
                        <th v-if="service.type==1" > Monthly Charge </th>
                        <th > Total Paid </th>
                        <th > Total Due </th>
                        <th > Status </th>
                        <th > Action </th>
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
                         <router-link style="font-size:15px;color:blue" :to="{name:'service_client_and_payment',params:{client_id:item.client_info.id},}"> {{ item.client_info.company_name }} </router-link>
                        </td>
                          <td>{{ item.client_info.phone }} </td>

                          <td> <span>  {{ item.total_amount }}  </span> </td>
                          <td v-if="service.type==1"> <span>  {{ item.monthly_charge }}  </span> </td>
                          <td> <span> {{ item.total_paid }}  </span> </td>
                          <td> <span v-if="(parseInt(item.total_amount) - parseInt(item.total_paid) ) > 0" style="background:rgb(225 68 68);min-width:80px;" class="badge ">   {{ item.total_amount - item.total_paid  }}  </span> </td>
                         <td>
                             <a v-if="item.status == 1" class="btn btn-success btn-xs">Active</a>
                             <a v-else  class="btn btn-warning btn-xs">De-Active</a>
                          </td>
                          <td>
                             <a v-if="service.type == 1" @click.prevent="showMonthlyChargeModal(index,item.id,item.client_info.id,item.monthly_charge,item.client_info.company_name)"  class="btn btn-success btn-xs"> change charge </a>
                             <a v-if="item.status == 0" @click="changeServiceStatus(item.id)"  class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>
                             <a v-else @click="changeServiceStatus(item.id)"  class="btn btn-warning btn-xs"><i class="fa fa-ban"></i></a>

                          </td>
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
     <modal name="monthly_charge_modal" :width="350" :height="200">
        <form @submit.prevent="changeMonthlyCharge" class="form">
          <div class="card" style="padding: 20px">
            <div class="card-body">
              <div class="form-group">
                <h4> {{ form.company_name }} </h4>
              </div>
              <div class="form-group">
                 <input type="number" class="form-control" v-model="form.monthly_charge" required >
              </div>
              <div class="form-group text-center">
                  <button class="btn btn-success " >
                    Save
                  </button>
              </div>
            </div>
          </div>
        </form>
    </modal>
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
      form : new Form({
        index : null,
        service_id : null,
        client_id : null,
        company_name : null,
        monthly_charge : 0,
      })
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

   async changeMonthlyCharge(){

     await this.form.post("/api/client/monthly/service/charge/change")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true ) {
            this.$toastr.s(resp.data.message);
            this.clients[this.form.index].monthly_charge = this.form.monthly_charge ;
            this.$modal.hide('monthly_charge_modal');
              this.form.index = null ;
              this.form.service_id = null ;
              this.form.client_id = null ;
              this.form.company_name = null ;
              this.form.monthly_charge = 0 ;
          }
        })
        .catch(error => {
           this.$toastr.e(error.response.data.message);
        })


    },


    showMonthlyChargeModal(index,id,client_id,monthly_charge,company_name){
        this.form.index = index ;
        this.form.service_id = id ;
        this.form.client_id = client_id ;
        this.form.company_name = company_name ;
        this.form.monthly_charge = monthly_charge ;
        this.$modal.show('monthly_charge_modal');
    },


    async changeServiceStatus(id) {
     await axios
        .get("/api/client/service/status/change/"+id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true ) {
            this.$toastr.s(resp.data.message);
            this.getServiceDetails();
          }
        })
        .catch(error => {
           this.$toastr.e(error.response.data.message);
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
