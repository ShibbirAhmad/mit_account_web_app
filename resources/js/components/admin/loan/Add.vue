<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'loan' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Loan</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Loaner</h3>
              </div>
              <div class="box-body">
                <h1 v-if="loading"><i class="fa fa-spinner fa-spin"></i></h1>
                <form
                  v-else
                  @submit.prevent="add"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>

                  <div class="form-group">
                    <label>Name</label>

                    <input
                      v-model="form.name"
                      type="text"
                      class="form-control"
                      required
                      placeholder="phone"
                    />
                  </div>

                  <div class="form-group">
                    <label>Mobile Number</label>

                    <input
                      v-model="form.mobile_no"
                      type="text"
                      name="mobile_no"
                      maxlength="11"
                      :class="{ 'is-invalid': form.errors.has('mobile_no') }"
                      class="form-control"
                      required
                      placeholder="phone"
                    />
                    <has-error :form="form" field="mobile_no"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Address</label>
                    <input
                      v-model="form.address"
                      type="text"
                      class="form-control"
                      required
                      placeholder="address"
                    />
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
  created() {
    this.cDate();
  },
  data() {
    return {
      form: new Form({
        name: "",
        mobile_no: "",
        address: "",
        date: "",
        purpose: "",
        amount: "",
      }),

      loading: true,
      error: "",
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },

      loaners: "",
    };
  },

  methods: {
    add() {
      this.form
        .post("/api/loaner/store")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true) {
            this.$router.push({ name: "loan" });
            this.$toastr.s(resp.data.message);
          }
        })
        .catch((error) => {
          this.$toastr.e(error.response.data.message);
        });
    },
    cDate() {
      let d = new Date();
      let month = d.getMonth() + 1;
      let day = d.getDate();
      let output =
        d.getFullYear() +
        "-" +
        (("" + month).length < 2 ? "0" : "") +
        month +
        "-" +
        (("" + day).length < 2 ? "0" : "") +
        day;
      this.form.date = output;

      this.loading = false;
    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
