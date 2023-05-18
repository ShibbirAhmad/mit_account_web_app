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
          <li class="active">It Clients </li>
        </ol>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-11 col-md-11 ">
              <div class="box box-primary">
                <div class="box-header with-border">
                     <div class="row">
                    <div class="col-md-4 text-right ">
                            <h3 style="margin-top:10px;" class="box-title heading"> Total Due BDT {{ parseInt(total_due) }} </h3>
                    </div>
                       <div class="col-md-8">
                           <input type="text" style="height:38px !important" v-model="search" v-on:keyup="searchClient" placeholder="search by name, phone or company-name" class="form-control">
                       </div>
                  </div>
                </div>
                <div class="box-body">

                  <table class="table  table-striped table-centered table-bordered table-hover">
                    <thead>
                      <tr>
                        <th width="5%">#</th>
                        <th width="20%"> Name </th>
                        <th width="15%"> Company Name </th>
                        <th width="10%"> Phone </th>
                        <th width="15%"> Amount </th>
                        <th width="10%"> Paid </th>
                        <th width="10%"> Due </th>
                        <th width="15%"> Action </th>
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

                         <router-link style="font-size:15px;color:blue" :to="{name:'service_client_and_payment',params:{client_id:item.id},}"> {{ item.company_name }} </router-link>

                        </td>
                          <td>{{ item.phone }} </td>

                          <td> <span class="">  {{ item.total_amount }}  </span> </td>
                          <td> <span class="">  {{ item.total_paid_amount }}  </span> </td>
                          <td> <span v-if="(parseInt(item.total_amount) - parseInt(item.total_paid_amount) ) > 0" style="background:rgb(225 68 68);min-width:80px;" class="badge ">  {{ parseInt(item.total_amount) - parseInt(item.total_paid_amount)    }}  </span> </td>
                          <td>
                           <router-link :to="{name:'service_client_edit',params:{id:item.id},}" class="btn btn-xs btn-success"  > <i class="fa fa-edit"></i> </router-link>
                          </td>
                      </tr>
                      <tr>
                        <td colspan="4"> Total Getable Amount</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                  <div class="box-footer">
                      <div class="row">
                          <div class="col-lg-6">
                              <pagination :data="clients"
                               @pagination-change-page="getServiceClients"></pagination>
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


        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  created() {
    this.getServiceClients();
  },
  data() {
    return {
      clients:{},
      search:"",
      total_due:0,
      loading: true,
      basePath: this.$store.getters.image_base_link,
    };
  },
  methods: {


    getServiceClients(page=1) {
      axios
        .get("/api/service/clients?page="+page,{
          params : {
              id : this.$route.params.id,
              search : this.search ,
            }
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.clients = resp.data.service_clients;
             this.total_due = resp.data.total_due;
             this.loading = false;
          }
        })

    },

   searchClient(){
       if (this.search.length > 3) {
           this.getServiceClients() ;
       }else{
         this.getServiceClients();
       }
    },


 },


};
</script>

<style scoped>

.badge {
  padding: 6px 8px !important;
}


.total_style {
  border: 2px solid #ddd;
  padding: 4px 25px;
}
.total_row {
  padding-bottom: 20px;
}
</style>
