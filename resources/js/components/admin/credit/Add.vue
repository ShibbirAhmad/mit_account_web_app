<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'credit' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
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
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Credit</h3>
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
                    <label>Date</label>

                    <date-picker
                      autocomplete="off"
                      v-model="form.date"
                      :config="options"
                      :class="{ 'is-invalid': form.errors.has('date') }"
                    ></date-picker>

                    <has-error :form="form" field="date"></has-error>
                  </div>
                  <div class="form-group">
                    <label>Purpose</label>
                    <input
                      type="text"
                      name="purpose"
                      v-model="form.purpose"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('purpose') }"
                      autocomplete="off"
                    />
                    <has-error :form="form" field="purpose"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Amount</label>
                    <input
                      type="number"
                      v-model="form.amount"
                      class="form-control"
                     required
                     min="1"
                   
                    />
                  </div>

                  <div class="form-group">
                    <label>Comment</label>
                    <input
                      type="text"
                      class="form-control"
                      v-model="form.comment"
              
                    />
                  </div>

                  <div class="form-group">
                    <label>Credit In</label>
                    <select
                      name="credit_in"
                      required
                      class="form-control"
                      v-model="form.credit_in"
                      :class="{ 'is-invalid': form.errors.has('credit_in') }"
                    >
                      <option value="" disabled >Select Balance</option>
                      <option
                        v-for="(balance, index) in balance"
                        :value="balance.id"
                        :key="index"
                      >
                        {{ balance.name }}
                      </option>
                    </select>
                    <has-error :form="form" field="credit_in"></has-error>
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
    this.balanceList();
    setTimeout(() => {
      this.loading = false;
    }, 100);
  },
  data() {
    return {
      form: new Form({
        department: "mohasagor.com",
        purpose: "",
        amount: "",
        date: "",
        comment: "",
        credit_in:"",
      }),
      loading: true,
      error: "",
      balance: "",
      //for date picker
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
    };
  },

  methods: {
    balanceList() {
      axios.get("/api/balance/list").then((resp) => {
        this.balance = resp.data.balance;
      });
    },
    add() {
      this.form
        .post("/api/credit/store")
        .then((resp) => {
          if (resp.data.status == "SUCCESS") {
            this.$router.push({ name: "credit" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
          } else {
            this.error = "something  went to wrong";
          }
        })
        .catch((error) => {
          //  console.log(error)
          this.error = "something  went to wrong";
        });
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
