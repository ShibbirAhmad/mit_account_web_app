<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'sms_clients',params:{id:this.$route.params.id} }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active"> SMS Client </li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add SMS Client </h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="SMSClientAdd"
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

                          placeholder="Ex: Mohammad"
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

                          placeholder="Ex: example"
                        />
                        <has-error :form="form" field="company_name"></has-error>
                      </div>



                  <div class="form-group">
                    <label>Mobile Number</label>

                    <input
                      v-model="form.phone"
                      type="text"
                      name="phone"
                      maxlength="11"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('phone'),
                      }"
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
import { Form, HasError, AlertError } from "vform";
Vue.component(HasError.name, HasError);
export default {

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
    SMSClientAdd() {
      this.form
        .post("/api/sms/client/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "sms_clients",params:{id:this.$route.params.id}});
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
