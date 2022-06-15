<template>
  <div>
    <h1 v-if="loading" style="text-align: center; font-size: 50px">
      <i class="fa fa-spinner fa-spin"></i>
    </h1>

    <div class="row" v-if="$can('Manage accounts') && !loading">
      <h2 style="text-align: center; font-family: serif">
        ACCOUNTS
      </h2>
      <div class="col-lg-4">
        <div class="custom-box">
          <div class="custom-box-body">
            <h4
              v-for="(balance, c_index) in balance"
              title="click to see details"
              @click="displayTodayMitCreditAccountDetails(balance)"
              :key="c_index"
            >
              In {{ balance.name }} :
              <strong>{{ parseInt(todayMitCreditBalance(balance)) }}</strong>
            </h4>
            <h4>
              In Total : <strong>{{ todayMitTotalCredit() }} </strong>
            </h4>
          </div>

          <div class="custom-box-footer">
            <h3 class="text-center text-uppercase">today credit</h3>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="custom-box">
          <div class="custom-box-body">
            <h4
              v-for="(balance, c_index) in balance"
              @click="displayTodayMitDebitAccountDetails(balance)"
              title="click to see details"
              :key="c_index"
            >
              In {{ balance.name }} :
              <strong>{{ parseInt(todayMitDebitBalance(balance)) }}</strong>
            </h4>

            <h4>
              In Total : <strong>{{ todayMitTotalDebit() }} </strong>
            </h4>
          </div>

          <div class="custom-box-footer">
            <h3 class="text-center text-uppercase">today debit</h3>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="custom-box">
          <div class="custom-box-body">
            <h4
              v-for="(balance, c_index) in balance"
              @click="displayMitAccountDetails(balance)"
              title="click to see details"
              :key="c_index"
            >
              In {{ balance.name }} :
              <strong>{{ parseInt(debitMitCreditSubstraction(balance)) }}</strong>
            </h4>
            <h4>
              In Total <strong> {{ totalMitBalance() }} </strong>
            </h4>
          </div>

          <div class="custom-box-footer">
            <h3 class="text-center text-uppercase">total balance</h3>
          </div>
        </div>
      </div>
    </div>

    <!-- start today credit payment store modal  -->
    <modal name="account_today_mit_debit_details_modal" :width="800" :height="400">
      <div class="card" style="padding: 20px">
        <div class="card-header text-center">
          <h4 class="heading">{{ account_name }}</h4>
        </div>
        <div style="border: 3px dotted" class="row">
          <div class="col-md-12 col-xs-12">
            <div class="card-header text-center">
              <h4 class="heading">Debit History</h4>
            </div>
            <div style="overflow-x: scroll !important" class="card-body">
              <table
                class="
                  table table-hover table-striped
                  text-center
                  table-bordered
                "
              >
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Comment</th>
                  <th>Amount</th>
                </tr>
                <tbody>
                  <tr
                    v-for="(item, index) in account_today_debit_history"
                    :key="index"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.date }}</td>
                    <td>{{ item.comment ? item.comment : "empty" }}</td>
                    <td>{{ item.amount }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </modal>
    <!-- end today credit payment store modal  -->

    <!-- start today credit payment store modal  -->
    <modal name="account_today_mit_credit_details_modal" :width="800" :height="400">
      <div class="card" style="padding: 20px">
        <div class="card-header text-center">
          <h4 class="heading">{{ account_name }}</h4>
        </div>
        <div style="border: 3px dotted" class="row">
          <div class="col-md-12 col-xs-12">
            <div class="card-header text-center">
              <h4 class="heading">Credit History</h4>
            </div>
            <div style="overflow-x: scroll !important" class="card-body">
              <table
                class="
                  table table-hover table-striped
                  text-center
                  table-bordered
                "
              >
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Comment</th>
                  <th>Amount</th>
                </tr>
                <tbody>
                  <tr
                    v-for="(item, index) in account_today_credit_history"
                    :key="index"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.date }}</td>
                    <td>{{ item.comment ? item.comment : "empty" }}</td>
                    <td>{{ item.amount }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </modal>
    <!-- end today credit payment store modal  -->

    <!-- start all payment store modal  -->
    <modal name="account_mit_details_modal" :width="800" :height="400">
      <div class="card" style="padding: 20px">
        <div class="card-header text-center">
          <h4 class="heading">{{ account_name }}</h4>
        </div>
        <div style="border: 3px dotted" class="row">
          <div class="col-md-6 col-xs-12">
            <div class="card-header text-center">
              <h4 class="heading">Credit History</h4>
            </div>
            <div style="overflow-x: scroll !important" class="card-body">
              <table
                class="
                  table table-hover table-striped
                  text-center
                  table-bordered
                "
              >
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Comment</th>
                  <th>Amount</th>
                </tr>
                <tbody>
                  <tr
                    v-for="(item, index) in account_credit_history"
                    :key="index"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.date }}</td>
                    <td>{{ item.comment ? item.comment : "empty" }}</td>
                    <td>{{ item.amount }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-6 col-xs-12">
            <div class="card-header text-center">
              <h4 class="heading">Debit History</h4>
            </div>
            <div style="overflow-x: scroll !important" class="card-body">
              <table
                class="
                  table table-hover table-striped
                  text-center
                  table-bordered
                "
              >
                <tr>
                  <th>#</th>
                  <th>Date</th>
                  <th>Comment</th>
                  <th>Amount</th>
                </tr>
                <tbody>
                  <tr
                    v-for="(item, index) in account_debit_history"
                    :key="index"
                  >
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.date }}</td>
                    <td>{{ item.comment ? item.comment : "empty" }}</td>
                    <td>{{ item.amount }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </modal>
    <!-- end all payment store modal  -->
  </div>
</template>

<script>
export default {
  data() {
    return {
      balance: {},
      loading: true,
      account_name: "",
      account_today_credit_history: "",
      account_credit_history: "",
      account_today_debit_history: "",
      account_debit_history: "",
    };
  },

  created() {
    this.getBalance();
  },
  methods: {
    getBalance() {
      axios
        .get("/api/every/accounts/department", {
          params: {
            department: "mit",
          },
        })
        .then((resp) => {
          //  console.log(resp);
          this.balance = resp.data.balance;
          this.loading = false;
        });
    },

    displayTodayMitDebitAccountDetails(balance) {
      this.account_name = balance.name;
      let credit_history = [];
      balance.today_debit_balance.forEach((item, index) => {
        if (index > 19) {
          return;
        }
        credit_history.push(item);
      });

      this.account_today_debit_history = credit_history;
      this.$modal.show("account_today_mit_debit_details_modal");
    },

    displayTodayMitCreditAccountDetails(balance) {
      this.account_name = balance.name;
      let credit_history = [];
      balance.today_credit_balance.forEach((item, index) => {
        if (index > 19) {
          return;
        }
        credit_history.push(item);
      });

      this.account_today_credit_history = credit_history;
      this.$modal.show("account_today_mit_credit_details_modal");
    },

    displayMitAccountDetails(balance) {
      this.account_name = balance.name;
      let credit_history = [];
      let debit_history = [];
      balance.total_credit_balance.forEach((item, index) => {
        if (index > 19) {
          return;
        }
        credit_history.push(item);
      });
      balance.total_debit_balance.forEach((item, index) => {
        if (index > 19) {
          return;
        }
        debit_history.push(item);
      });
      this.account_credit_history = credit_history;
      this.account_debit_history = debit_history;
      this.$modal.show("account_mit_details_modal");
    },

    //today credit balance counter
    todayMitCreditBalance(balance) {
      let amount = 0;
      balance.today_credit_balance.forEach((element) => {
        amount += parseFloat(element.amount);
      });
      return amount.toFixed(2);
    },
    //today total credit balance counter
    todayMitTotalCredit() {
      if (this.balance.length > 0) {
        let amount = 0;
        this.balance.forEach((item) => {
          item.today_credit_balance.forEach((credit) => {
            amount += parseFloat(credit.amount);
          });
        });
        return amount.toFixed(2);
      }
    },
    //today debit balance counter
    todayMitDebitBalance(balance) {
      let amount = 0;
      balance.today_debit_balance.forEach((element) => {
        amount += parseFloat(element.amount);
      });
      return amount.toFixed(2);
    },
    //today total debit balance counter
    todayMitTotalDebit() {
      if (this.balance.length > 0) {
        let amount = 0;
        this.balance.forEach((item) => {
          item.today_debit_balance.forEach((debit) => {
            amount += parseFloat(debit.amount);
          });
        });
        return amount.toFixed(2);
      }
    },
    // afet debit credit substruction
    debitMitCreditSubstraction(balance) {
      if (this.balance.length > 0) {
        let debit_amount = 0;
        let credit_amount = 0;

        balance.total_debit_balance.forEach((debit) => {
          debit_amount += parseFloat(debit.amount);
        });

        balance.total_credit_balance.forEach((credit) => {
          credit_amount += parseFloat(credit.amount);
        });

        let amount = parseInt(credit_amount) - parseInt(debit_amount);
        return amount.toFixed(2);
      }
    },
    //total balance
    totalMitBalance() {
      if (this.balance.length > 0) {
        let debit_amount = 0;
        let credit_amount = 0;

        this.balance.forEach((item) => {
          item.total_debit_balance.forEach((debit) => {
            debit_amount += parseFloat(debit.amount);
          });
        });

        this.balance.forEach((item) => {
          item.total_credit_balance.forEach((credit) => {
            credit_amount += parseFloat(credit.amount);
          });
        });

        let amount = parseInt(credit_amount) - parseInt(debit_amount);
        return amount.toFixed(2);
      }
    },
  },

};
</script>

<style scoped >

.custom-box {
  background: #fff;
  padding: 13px;
  min-height: 280px;
  box-shadow: 3px 3px 3px #ddd;
  border-radius: 6px;
  margin-bottom: 10px;
}
.custom-box-body strong {
  position: absolute;
  right: 10%;
  color: blue;
}
.custom-box-footer {
  background: #00a65a;
  color: #fff;
}
.custom-box-body > h4 {
  cursor: pointer;
}
</style>
