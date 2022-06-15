<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'balance' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Balance</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Balance </h3>
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
                    <label for="department">Account Department</label>
                   <select class="form-control" required name="department" v-model="form.department">
                      <option value="" selected disabled >Select Account Department </option>
                      <option value="mohasagor.com">mohasagor.com</option>
                      <option value="boost">Boost Service</option>
                      <option value="mit">MIT</option>
                      <option value="tourism">Tourism</option>
                      <option value="properties">Properties</option>
                   </select>
                  </div>
                  <div class="form-group">
                        <label>Balance Name</label>
                        <input
                          v-model="form.name"
                          type="text"
                          name="name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('name') }"
                          autofocus
                          autocomplete="off"
                          placeholder="Ex: Bkash"
                        />
                        <has-error :form="form" field="name"></has-error>
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
  name: "Add",
  data() {
    return {
      form: new Form({
        name: "",
        department:'',
      }),
      error: "",
    };
  },

  methods: {
    addPrintHouse() {
      this.form
        .post("/api/balance/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "balance" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 3000,
            });
          }

          if(resp.data.status=='taken'){
             this.$toasted.show(resp.data.message, {
              type: "error",
              position: "top-center",
              duration: 6000,
            });
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
