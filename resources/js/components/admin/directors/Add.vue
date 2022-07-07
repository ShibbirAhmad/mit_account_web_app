<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link
            :to="{ name: 'directors' }"
            class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">director</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add New Director</h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="directorAdd"
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
                          placeholder="Ex:Mohammad"
                        />
                        <has-error :form="form" field="name"></has-error>
                      </div>
                    </div>
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
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Share Qty </label>

                        <input
                          v-model.number="form.share_qty"
                          type="number"
                          class="form-control"
                          required
                          @keyup="totalSum"
                          placeholder="00"
                        />
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Share Value </label>

                        <input
                          v-model.number="form.share_value"
                          type="number"
                          @keyup="totalSum"
                          class="form-control"
                          required
                          placeholder="00"
                        />
                      </div>
                    </div>

                    <div class="col-md-12">
                      <div class="form-group">
                        <input
                          v-model="form.total"
                          type="text"
                          disabled
                          class="form-control"
                          placeholder="total amount 00"
                        />
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-group">
                      <input
                        v-model="form.email"
                        type="email"
                        name="email"
                        class="form-control"
                        :class="{
                          'is-invalid': form.errors.has('email'),
                        }"
                        placeholder="example@gmail.com"
                      />
                      <has-error :form="form" field="email"></has-error>
                    </div>
                  </div>

                  <div class="form-group">
                    <input
                      v-model="form.address"
                      type="text"
                      name="address"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('address'),
                      }"
                      placeholder="address "
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
        name: "",
        share_value: null,
        share_qty: null,
        address: "",
        phone: "",
        email: "",
        total:0 ,
      }),
      error: "",
    };
  },

  methods: {
    directorAdd() {
      this.form
        .post("/api/director/add")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "directors" });
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


    totalSum(){
       let qty = this.form.share_qty <= 0 ? 0 : this.form.share_qty ;
       let value = this.form.share_value <= 0 ? 0 : this.form.share_value ;
       this.form.total = parseInt(qty) * parseInt(value) ;
    },

  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
