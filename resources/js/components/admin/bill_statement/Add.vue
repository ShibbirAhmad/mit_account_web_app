<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'bill_statement' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Print House</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Bill Statement </h3>
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
                        <label>Bill Name</label>
                        <input
                          v-model="form.name"
                          type="text"
                          name="name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('name') }"
                          autofocus
                          autocomplete="off"
                          placeholder="Ex: Internet Bill"
                        />
                        <has-error :form="form" field="name"></has-error>
                      </div>
           
                      <div class="form-group">
                        <label>Company Name</label>
                        <input
                          v-model="form.company_name"
                          type="text"
                          name="company_name"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('company_name'),
                          }"
                          autofocus
                          autocomplete="off"
                          placeholder="Ex: Multi-Link "
                        />
                        <has-error
                          :form="form"
                          field="company_name"
                        ></has-error>
                      </div>
              
               
<!-- 
                  <div class="form-group">
                    <label>Mobile Number</label>

                    <input
                      v-model="form.mobile_no"
                      type="text"
                      name="mobile_no"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('mobile_no'),
                      }"
                      autocomplete="off"
                      placeholder="01xxxxxxxxx"
                    />
                    <has-error :form="form" field="mobile_no"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Address</label>
                    <input
                      v-model="form.address"
                      type="text"
                      name="email"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('address') }"
                      autocomplete="off"
                      placeholder="address"
                    />
                    <has-error :form="form" field="address"></has-error>
                  </div> -->

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
  name: "Add",
  data() {
    return {
      form: new Form({
        name: "",
        company_name: "",
        mobile_no: "",
        address: "",
      }),
      error: "",
    };
  },

  methods: {
    addPrintHouse() {
      this.form
        .post("/api/bll/statement/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "bill_statement" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          } else {
            this.error = "some thing want to wrong";
          }
        })
        .catch((error) => {
          console.log(error);
          this.error = "some thing want to wrong";
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
