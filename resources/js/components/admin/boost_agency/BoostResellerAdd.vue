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
          <div class="col-lg-8  col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Boost client</h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="boostResellerAdd"
                
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
              
                          class="form-control"
     
                          placeholder="Ex: mohammad "
                        />
                    
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label> Company Name</label>
                        <input
                          v-model="form.company_name"
                          type="text"
                          required
           
                          class="form-control"
                          placeholder="Ex: MIT"
                        />
        
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
                            :class="{ 'is-invalid': form.errors.has('phone') }"
                          required
                          maxlength="11"
                          minlength="11"
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

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Password </label>

                        <input
                          v-model="form.password"
                          type="password"
                          autocomplete="off"
                          class="form-control"
                          required
                 
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
import { Form, HasError } from "vform";
Vue.component(HasError.name, HasError);
export default {
  created(){
    this.balanceList();
  },
  data() {
    return {
      form: new Form({
        password: '',
        name: "",
        company_name: "",
        address: "",
        phone: "",
      }),
      error: "",
    };
  },

  methods: {



    async boostResellerAdd() {

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



  },


};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}

input{
  height: 44px !important;
}


</style>
