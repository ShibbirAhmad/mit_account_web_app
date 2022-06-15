<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="goBack" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active"> sms client </li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Edit  Client Info. </h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="smsclientUpdate"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>
                    <div class="form-group">
                        <label> Name</label>
                        <input
                          v-model="form.name"
                          type="text"
                          required
                          name="name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('name') }"

                        />
                        <has-error :form="form" field="name"></has-error>
                      </div>

                        <div class="form-group">
                        <label> Company Name</label>
                        <input
                          v-model="form.company_name"
                          type="text"
                          required
                          name="company_name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('company_name') }"
                        />
                        <has-error :form="form" field="company_name"></has-error>
                      </div>


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
                      maxlength="11"
                      placeholder="01xxxxxxxxx"
                    />
                    <has-error :form="form" field="phone"></has-error>
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
                      autocomplete="off"
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
   this.getclient() ;
 },
  data() {
    return {
      form: new Form({
        sms_id:this.$route.params.id,
        name: "",
        company_name: "",
        address: "",
        phone: "",
      }),
      error: "",
    };
  },

  methods: {
    goBack(){
      window.history.back();
    },
    getclient(){
     axios.get('/api/get/sms/client/'+this.$route.params.id)
      .then(resp => {
        if (resp.data.status="OK") {
            this.form.name = resp.data.client.name ;
            this.form.company_name = resp.data.client.company_name ;
            this.form.phone  = resp.data.client.phone ;
            this.form.address  = resp.data.client.address ;
        }
      })
    },
    smsclientUpdate() {
      this.form
        .post("/api/get/sms/client/edit/"+this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            window.history.back();
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          } else {
            this.error = "something went to wrong";
          }
        })

    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
