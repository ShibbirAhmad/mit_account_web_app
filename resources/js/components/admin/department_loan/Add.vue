<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link
            :to="{ name: 'department_loan' }"
            class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Loan Transaction </li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-lg-offset-1">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title"> Loan Transaction </h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="transferLoand"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>
                  <div class="row">

                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label>Department From</label>
                        <select
                          @change="departmentFromBalance(form.department_from)"
                          required
                          name="department_from"
                          class="form-control"
                          v-model="form.department_from"
                          :class="{ 'is-invalid': form.errors.has('department_from') }"
                        >
                          <option value="" selected disabled>
                            Select Department
                          </option>
                         <option    v-if="form.department_to != from_department" v-for="(from_department,from_index) in departments" :value="from_department" :key="from_index">{{ from_department }}</option>
                        </select>
                        <has-error :form="form" field="department_from"></has-error>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                        <label>Department To</label>
                        <select
                          @change="departmentToBalance(form.department_to)"
                          required
                          name="department_to"
                          class="form-control"
                          v-model="form.department_to"
                          :class="{ 'is-invalid': form.errors.has('department_to') }"
                        >
                          <option value="" selected disabled>
                            Select Department
                          </option>
                         <option  v-if="form.department_from != to_department" v-for="(to_department,to_index) in departments" :value="to_department" :key="to_index">{{ to_department }}</option>
                        </select>
                        <has-error :form="form" field="department_to"></has-error>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label>Balance From</label>
                        <select
                          name="balance_from"
                          class="form-control"
                          v-model="form.balance_from"
                          :class="{ 'is-invalid': form.errors.has('balance_from') }"
                        >
                          <option value="" selected disabled>
                            Select Balance
                          </option>
                          <option
                            v-for="(balance, index) in department_from_balance"
                            :value="balance.id"
                            :key="index"
                          >
                            {{ balance.name }}
                          </option>
                        </select>
                        <has-error :form="form" field="balance_from"></has-error>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label>Balance To</label>
                        <select
                          name="balance_to"
                          class="form-control"
                          v-model="form.balance_to"
                          :class="{ 'is-invalid': form.errors.has('balance_to') }"
                        >
                          <option value="" selected disabled>
                            Select Balance
                          </option>
                          <option
                            v-for="(balance, index) in department_to_balance"
                            :value="balance.id"
                            :key="index"
                          >
                            {{ balance.name }}
                          </option>
                        </select>
                        <has-error :form="form" field="balance_to"></has-error>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6 col-lg-6">
                      <div class="form-group">
                        <label>Amount</label>
                        <input
                          v-model="form.amount"
                          type="number"
                          name="amount"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('amount') }"
                          autofocus
                          autocomplete="off"
                          placeholder="amount"
                        />
                        <has-error :form="form" field="amount"></has-error>
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <div class="form-group">
                        <label for="">Comment</label>
                        <input
                          v-model="form.comment"
                          type="text"
                          class="form-control"
                          autocomplete="off"
                          placeholder="Comment"
                        />
                      </div>
                    </div>
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
        department_from: "",
        balance_from: "",
        department_to: "",
        balance_to: "",
        amount: 0,
        comment: "",
      }),
      departments:{
        'mohasagor.com' : 'mohasagor.com',
        'boost' : 'boost',
        'mit' : 'mit',
        'tourism' : 'tourism',
        'properties' : 'properties',
      },
      department_from_balance: "",
      department_to_balance: "",
      error: "",
    };
  },

  methods: {

    transferLoand() {
      this.form
        .post("/api/department/loan/transfer", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "department_loan" });
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



      departmentFromBalance(department) {
        axios.get("/api/department/wise/balance/list/"+department)
        .then((resp) => {
          this.department_from_balance = resp.data.balance;
        });
      },


      departmentToBalance(department) {
        axios.get("/api/department/wise/balance/list/"+department)
        .then((resp) => {
          this.department_to_balance = resp.data.balance;
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
