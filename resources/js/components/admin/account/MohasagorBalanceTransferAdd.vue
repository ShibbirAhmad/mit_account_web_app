<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link
            :to="{ name: 'mohasagor_fund_transfer' }"
            class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Mohasagor.com Fund Transfer</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Mohasagor Fund Transfer</h3>
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
                  <div class="row">
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label>From</label>
                        <select
                          name="from"
                          class="form-control"
                          v-model="form.from"
                          :class="{ 'is-invalid': form.errors.has('from') }"
                        >
                          <option value="" selected disabled>
                            Select Balance
                          </option>
                          <option
                            v-for="(balance, index) in balance"
                            :value="balance.id"
                            :key="index"
                          >
                            {{ balance.name }}
                          </option>
                        </select>
                        <has-error :form="form" field="from"></has-error>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label>To</label>
                        <select
                          name="to"
                          class="form-control"
                          v-model="form.to"
                          :class="{ 'is-invalid': form.errors.has('to') }"
                        >
                          <option value="" selected disabled>
                            Select Balance
                          </option>
                          <option
                            v-for="(balance, index) in balance"
                            :value="balance.id"
                            :key="index"
                          >
                            {{ balance.name }}
                          </option>
                        </select>
                        <has-error :form="form" field="to"></has-error>
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
                          placeholder="name"
                          @change="amountTransfer"
                          @keyup="amountTransfer"
                        />
                        <has-error :form="form" field="amount"></has-error>
                      </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                      <div class="form-group">
                        <label>Cost(%)</label>
                        <input
                          v-model="form.cost"
                          type="text"
                          name="cost"
                          class="form-control"
                          @keyup="amountTransfer"
                          @change="amountTransfer"
                          :class="{ 'is-invalid': form.errors.has('cost') }"
                          autofocus
                          autocomplete="off"
                          placeholder="cost"
                        />
                        <has-error :form="form" field="cost"></has-error>
                      </div>
                    </div>
                  </div>

                  <div class="form-group">
                    <label>Transfer Amount <small>(amount-cost)</small></label>
                    <input
                      @keyup="amountTransfer"
                      v-model="form.transfer_amount"
                      type="number"
                      readonly
                      class="form-control"
                      autocomplete="off"
                      placeholder="cost"
                    />
                  </div>

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
  created() {
    this.balanceList();
  },

  data() {
    return {
      form: new Form({
        from: "",
        to: "",
        amount: 0,
        cost: 0,
        transfer_amount: 0,
        comment: "",
        department: "mohasagor.com",
      }),
      balance: "",
      loading: false,
      error: "",
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
        .post("/api/fund/transfer/store", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "mohasagor_fund_transfer" });
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
    amountTransfer() {
      let amount = parseFloat(this.form.amount);
      let cost = parseFloat(this.form.cost);

      this.form.transfer_amount = amount;

      if (cost > 0) {
        let cost_amount = (amount / parseInt(100)) * cost;
        let transfer_amount = amount - cost_amount;
        this.form.transfer_amount = transfer_amount;
      }
    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
