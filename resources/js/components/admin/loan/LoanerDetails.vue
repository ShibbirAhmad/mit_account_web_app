<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="loanModal" class="btn btn-primary"
            ><i class="fa fa-plus"></i> add loan
          </a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Loaner</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-1"></div>
            <div class="col-lg-3 __c_box">
              <h4>
                Total Loan Amount:

                {{ totalLoanAmount() }}
              </h4>
            </div>
            <div class="col-lg-3 __c_box">
              <h4>
                Total Paid Amount:

                {{ totalPaidAmount() }}
              </h4>
            </div>

            <div class="col-lg-3 __c_box">
              <h4>
                Total Due Amount:

                {{ totalLoanAmount() - totalPaidAmount() }}
              </h4>
            </div>
          </div>
          <div class="row justify-content-center">
            <div class="col-lg-11 ">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row">
                    <div class="col-md-3">
                      <h3
                        class="box-title"
                        @click="loanMode = true"
                        :class="{ bb: loanMode }"
                      >
                        Loan History
                      </h3>
                      <a
                        :href="
                          '/api/loan/history/download/pdf/' + $route.params.id
                        "
                        target="_blank"
                        class="btn btn-success"
                      >
                        <i class="fa fa-download"> </i>
                      </a>
                    </div>

                    <div class="col-md-4">
                      <h3
                        class="box-title"
                        @click="loanMode = false"
                        :class="{ bb: !loanMode }"
                      >
                        Payment History
                      </h3>
                      <a
                        :href="
                          '/api/loand/paid/history/download/pdf/' +
                            $route.params.id
                        "
                        target="_blank"
                        class="btn btn-success"
                      >
                        <i class="fa fa-download"> </i>
                      </a>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <table v-if="loanMode" class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Purpose</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-for="(items, index) in loans" v-bind:key="index">
                        <td scope="row">{{ index + 1 }}</td>
                        <td>{{ items.date }}</td>
                        <td>{{ items.purpose }}</td>
                        <td>{{ items.amount }}</td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>
                          <strong> ={{ totalLoanAmount() }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <table v-else class="table">
                    <thead>
                      <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Paid By</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-for="item in payment_records" :key="item.id"
                                
                      >
                        <td>{{ item.date }}</td>
                        <td>{{ item.comment }}</td>
                        <td>{{ item.paid_by }}</td>
                        <td>{{ item.amount }}</td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>
                          <strong> ={{ totalPaidAmount() }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    <modal name="loan_modal" :width="400" :height="300">
      <div style="padding: 10px" class="card">
        <div class="card-body">
          <form @submit.prevent="storeLoan">
            <div class="form-group ">
              <label> Amount </label>
              <input
                v-model.number="form.amount"
                required
                type="number"
                class="form-control"
              />
            </div>

            <div class="form-group">
              <label> purpose </label>
              <input
                required
                v-model="form.purpose"
                type="text"
                class="form-control"
              />
            </div>

            <div class="form-group">
              <select required class="form-control" v-model="form.balance_id">
                <option value="" disabled>Select Balance</option>
                <option
                  v-for="(balance, index) in balance"
                  :key="index"
                  :value="balance.id"
                >
                  {{ balance.name }}
                </option>
              </select>
            </div>

            <div class="form-group text-center">
              <button type="submit" class="btn btn-success btn-block">
                submit
              </button>
            </div>
          </form>
        </div>
      </div>
    </modal>
  </div>
</template>

<script>
import Form from "vform";
export default {
  created() {
    this.getLoans();
  },
  data() {
    return {
      form: new Form({
        loaner_id: this.$route.params.id,
        balance_id: "",
        purpose: "",
        amount: 0,
      }),
      balance: "",
      loan: "",
      loading: true,
      basePath: this.$store.getters.image_base_link,
      item: "",
      search: "",
      loanMode: true,
      loans: "",
      payment_records: "",
    };
  },
  methods: {
    getLoans() {
      axios
        .get("/api/loaners/details/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          this.loans = resp.data.loans;
          this.payment_records = resp.data.paid;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    totalLoanAmount() {
      if (this.loans) {
        let total = 0;
        this.loans.forEach((element) => {
          total += parseInt(element.amount);
        });

        return total;
      }
    },
    totalPaidAmount() {
      if (this.payment_records) {
        let total = 0;

        this.payment_records.forEach((element) => {
          total += parseInt(element.amount);
        });

        return total;
      }
    },

    loanModal() {
      axios.get("/api/balance/list/mit").then((resp) => {
        this.balance = resp.data.balance;
        this.$modal.show("loan_modal");
      });
    },

    async storeLoan() {
      await this.form
        .post("/api/loan/store")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true) {
            this.$toastr.e(resp.data.message);
            this.getLoans();
          }
        })
        .catch((error) => {
          this.$toastr.e(error.response.data.message);
        });
    },
  },
};
</script>

<style>
.col-lg-3.__c_box {
  padding: 10px;
  background: #fff;
  width: 250px;
  height: 85px;
  margin-right: 15px;
  margin-bottom: 20px;
  text-align: center;
  box-shadow: 3px 3px 3px #ddd;
}
.box-title {
  padding: 5px 6px;
  cursor: pointer;
}
.bb {
  border-bottom: 2px solid #000;
}
</style>
