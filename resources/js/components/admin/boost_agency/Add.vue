<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'boost_agency' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active"> boost </li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Boost Agency </h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="addPrintHouse"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>
                    <div class="form-group">
                        <label>Company Name</label>
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
                      autocomplete="off"
                      placeholder="01xxxxxxxxx"
                    />
                    <has-error :form="form" field="phone"></has-error>
                  </div>


                  <div class="form-group">
                    <label>Email </label>

                    <input
                      v-model="form.email"
                      type="text"
                      name="email"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('email'),
                      }"
                      autocomplete="off"
                      placeholder="example@gmail.com"
                    />
                    <has-error :form="form" field="email"></has-error>
                  </div>

                  <div class="form-group">
                    <label>BDT (rate) </label>
                    <input
                      v-model="form.rate"
                      type="text"
                      name="email"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('rate') }"
                      autocomplete="off"
                      placeholder="rate"
                    />
                    <has-error :form="form" field="rate"></has-error>
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
        name: "",
        email: "",
        phone: "",
        rate: "",
      }),
      error: "",
    };
  },

  methods: {
   async addPrintHouse() {
    await  this.form
        .post("/api/boost/agency/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "boost_agency" });
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
</style>
