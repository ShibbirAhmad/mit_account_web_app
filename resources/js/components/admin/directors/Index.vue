<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'director_add'}" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          >Add Director</router-link>

        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active"> director </li>
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
                            <h3 class="box-title heading"> Director Information</h3>
                    </div>
                       <div class="col-md-6">
                           <input type="text" v-model="search" v-on:keyup="searchClient" placeholder="search by name, phone or name" class="form-control">
                       </div>
                  </div>
                </div>
                <div class="box-body">

                  <table class="table table-centered text-center table-striped  table-bordered table-hover">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col"> Name </th>
                        <th scope="col"> Phone </th>
                        <th scope="col"> Total Amount </th>
                        <th scope="col"> Total Paid </th>
                        <th scope="col"> Total Refund </th>
                        <th scope="col"> Payable Amount </th>
                        <th scope="col"> Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(item, index) in director.data"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                           <router-link style="font-size:15px;color:blue" :to="{name:'director_payment_details',params:{id:item.id},}">  {{ item.name }}   </router-link>
                          </td>

                          <td>{{ item.phone }} </td>

                          <td> <span class="">  {{ item.total_amount }}  </span> </td>
                          <td> <span class="">  {{ item.total_paid_amount }}  </span> </td>
                          <td> <span class="">  {{ item.total_refund_amount    }}  </span> </td>
                          <td> <span class="">  {{ item.total_amount - item.total_paid_amount   }}  </span> </td>
                          <td>
                           <router-link :to="{name:'director_edit',params:{id:item.id},}" class="btn btn-xs btn-success"  > <i class="fa fa-edit"></i> </router-link>
                          </td>
                      </tr>
                      <tr>
                        <td colspan="3"> In Total </td>
                        <td> ={{ total_amount }} </td>
                        <td> ={{ total_paid_amount }} </td>
                        <td> ={{ total_refund_amount }} </td>
                        <td> ={{ total_amount - total_paid_amount  }} </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                  <div class="box-footer">
                      <div class="row">
                          <div class="col-lg-6">
                              <pagination :data="director"
                               @pagination-change-page="getDirectors"></pagination>
                          </div>
                          <div class="col-lg-6 mt-1" style="margin-top: 25px;text-align:right;">
                              <p>Showing <strong>{{director.from}}</strong> to
                                  <strong>{{director.to}}</strong> of total
                                  <strong>{{director.total}}</strong> entries
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
    this.getDirectors();
  },
  data() {
    return {
      director:{},
      search:"",
      total_amount:0,
      total_paid_amount:0,
      total_refund_amount:0,
      loading: true,
      basePath: this.$store.getters.image_base_link,
    };
  },
  methods: {


    getDirectors(page=1) {
      axios
        .get("/api/directors?page="+page,{
          params : {
              id : this.$route.params.id,
              search : this.search ,
            }
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.director = resp.data.director;
             this.total_amount = resp.data.total_amount ;
             this.total_paid_amount = resp.data.total_paid_amount ;
             this.total_refund_amount = resp.data.total_refund_amount ;
             this.loading = false;
          }
        })

    },

   searchClient(){
       if (this.search.length > 3) {
           this.getDirectors() ;
       }else{
         this.getDirectors();
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
