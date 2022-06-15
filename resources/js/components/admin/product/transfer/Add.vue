<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'product_transfer' }" class="btn btn-primary"
            ><i class="fa fa-arrow-right"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Credit</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Product Transfer</h3>
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
                    <label>From Product Code</label>
                    <input
                      type="text"
                      name="purpose"
                      v-model="form.from_product_code"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('from_product_code'),
                      }"
                      autocomplete="off"
                    />
                    <has-error
                      :form="form"
                      field="from_product_code"
                    ></has-error>
                  </div>
                  <div class="form-group">
                    <label>To Product Code</label>
                    <input
                      type="text"
                      name="to_product_code"
                      v-model="form.to_product_code"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('to_product_code'),
                      }"
                      autocomplete="off"
                    />
                    <has-error :form="form" field="to_product_code"></has-error>
                  </div>
                  <div class="form-group">
                    <label>Quantity</label>
                    <input
                      type="text"
                      name="quantity"
                      v-model="form.quantity"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('quantity') }"
                      autocomplete="off"
                    />
                    <has-error :form="form" field="quantity"></has-error>
                  </div>
                  <div class="form-group">
                    <label>Comment</label>
                    <input
                      type="text"
                      name="comment"
                      v-model="form.comment"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('comment') }"
                      autocomplete="off"
                    />
                    <has-error :form="form" field="comment"></has-error>
                  </div>

                  <br />
                  <button
                    :disabled="form.busy"
                    type="submit"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-spin fa-spinner" v-if="form.busy"></i>Submit
                  </button>
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
    setTimeout(() => {
      this.loading = false;
    }, 100);
  },
  data() {
    return {
      form: new Form({
        to_product_code: "",
        quantity: "",
        from_product_code: "",
        comment: "",
      }),

      loading: true,
      error: "",

      //for date picker
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
    };
  },

  methods: {
    add() {
      this.form;
      if (confirm("Please Check Everthing Before Submit")) {
        this.form
          .post("/api/product/stockin/from/anather/product")
          .then((resp) => {
            console.log(resp);
            if (resp.data.status == "SUCCESS") {
            this.$router.push({ name: "product_transfer" });
              this.$toasted.show(resp.data.message, {
                type: "success",
                position: "top-center",
                duration: 2000,
              });
            } else {
              this.$toasted.show(resp.data, {
                type: "warning",
                position: "top-center",
                duration: 2000,
              });
            }
          })
          .catch((error) => {
            //  console.log(error)
            this.error = "some thing want to wrong";
          });
      }
    },

    //method initial for  get current date
    pDate() {
      //current date
      let d = new Date();

      //current mount
      //current day
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
    },
  },
  mounted() {
    this.pDate();
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
