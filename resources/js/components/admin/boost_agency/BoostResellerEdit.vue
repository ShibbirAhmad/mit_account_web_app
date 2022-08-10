<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link
            :to="{
              name: 'boost_agency_reselllers',
              params: { id: this.$route.params.id },
            }"
            class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">boost reseller</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Edit Boost Reseller Info.</h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="boostResellerUpdate"
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
                      placeholder="Ex: http pool"
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

                  <div class="form-group">
                    <label>Dollar Rate </label>

                    <input
                      v-model="form.dollar_rate"
                      type="number"
                      name="dollar_rate"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('dollar_rate'),
                      }"
                    />
                    <has-error :form="form" field="dollar_rate"></has-error>
                  </div>

                  <div class="form-group">
                    <label> Status </label>
                    <select
                      name="status"
                      class="form-control"
                      v-model="form.status"
                    >
                      <option value="1">Active</option>
                      <option value="0">DeActive</option>
                    </select>
                    <has-error :form="form.status" field="status"></has-error>
                  </div>

                  <div class="form-group text-center">
                    <button
                      :disabled="form.busy"
                      type="submit"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-spin fa-spinner" v-if="form.busy"></i>Save
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
  created() {
    this.getReseller();
  },
  data() {
    return {
      form: new Form({
        boost_agency_id: this.$route.params.id,
        name: "",
        company_name: "",
        address: "",
        phone: "",
        dollar_rate: "",
        status: "",
      }),
      error: "",
    };
  },

  methods: {
    getReseller() {
      axios
        .get("/api/get/boost/agency/reseller/" + this.$route.params.id)
        .then((resp) => {
          if ((resp.data.status = "OK")) {
            this.form.name = resp.data.reseller.name;
            this.form.company_name = resp.data.reseller.company_name;
            this.form.phone = resp.data.reseller.phone;
            this.form.address = resp.data.reseller.address;
            this.form.dollar_rate = resp.data.reseller.dollar_rate;
            this.form.status = resp.data.reseller.status;
          }
        });
    },
    async boostResellerUpdate() {
      await this.form
        .post("/api/get/boost/agency/reseller/edit/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            window.history.back();
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
