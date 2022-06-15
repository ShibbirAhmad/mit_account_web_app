<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'service_clients',params:{id:1} }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active"> service Client </li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Edit Client Information </h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="serviceClientUpdate"
                  @keydown="form.onKeydown($event)"
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

                          placeholder="Ex: http pool"
                        />
                        <has-error :form="form" field="name"></has-error>
                      </div>
                     </div>
                     <div class="col-md-6">

                      <div class="form-group">
                        <label> Company  Name</label>
                        <input
                          v-model="form.company_name"
                          type="text"
                          required
                          name="company_name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('company_name') }"

                          placeholder="Ex: example"
                        />
                        <has-error :form="form" field="company_name"></has-error>
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
                      :class="{
                        'is-invalid': form.errors.has('phone'),
                      }"
                      placeholder="01xxxxxxxxx"
                      maxlength="11"
                    />
                    <has-error :form="form" field="phone"></has-error>
                  </div>
                    </div>
                    <div class="col-md-6">
                       <div class="form-group">
                    <label>Email </label>
                    <input
                      v-model="form.email"
                      type="email"
                      name="email"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('email'),
                      }"
                    />
                    <has-error :form="form" field="email"></has-error>
                  </div>
                    </div>
                  </div>


                  <div class="form-group">
                    <label>Address </label>

                    <input
                      v-model="form.address"
                      type="text"
                      name="address"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('address'),
                      }"
                      placeholder="Dhaka"
                    />
                    <has-error :form="form" field="address"></has-error>
                  </div>


                  <div class="form-group text-center">
                    <button
                      :disabled="form.busy"
                      type="submit"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-spin fa-spinner" v-if="form.busy"></i
                      >Save
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
     this.getClient();
   },
  data() {
    return {
      form: new Form({
        service_id:this.$route.params.id,
        name: "",
        company_name: "",
        address: "",
        phone: "",
        email: "",
      }),
      error: "",
    };
  },

  methods: {
    getClient(){
      axios.get('/api/get/service/client/'+this.$route.params.id)
      .then(resp => {
        if (resp.data.status="OK") {
            this.form.name = resp.data.client.name ;
            this.form.company_name = resp.data.client.company_name ;
            this.form.email = resp.data.client.email ;
            this.form.phone  = resp.data.client.phone ;
            this.form.address  = resp.data.client.address ;
        }
      })
    },
    serviceClientUpdate() {
      this.form
        .post("/api/service/client/info/edit/"+this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "service_clients"});
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          } else {
            this.error = "something went to wrong";
          }
        })
        .catch((error) => {
          console.log(error);
          this.error = "something went to wrong";
        });
    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
