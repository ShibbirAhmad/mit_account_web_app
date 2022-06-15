<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-3">
              <label for="">Start Date</label>
              <date-picker
                autocomplete="off"
                v-model="start_date"
                :config="options"
                placeholder="start_date"
                @change="reportList"
              ></date-picker>
            </div>
            <div class="col-lg-3">
              <label for="">End Date</label>
              <date-picker
                autocomplete="off"
                v-model="end_date"
                :config="options"
                placeholder="end_date"
                @change="reportList"
              ></date-picker>
            </div>

            <div
              class="col-lg-6"
              v-if="!loading"
              style="background: #fff; padding: 20px; border-radius: 5px"
            >
              <div>
                <h4>
                  <b>Total Credit={{ total_credit() }}</b>
                </h4>
                <h4>
                  <b>Total Debit={{ total_debit() }}</b>
                </h4>
                <hr />
                <h4 style="margin-top: -18px; margin-left: 85px">
                  <b>={{ total_credit() - total_debit() }}</b>
                </h4>
              </div>
            </div>
          </div>
          <br />
          <div class="row justify-content-center">
            <div class="col-lg-6">
              <div class="box box-primary">
                <div class="box-header">
                  <h5>CREDIT</h5>
                </div>
                <div class="box-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Purpose</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>

                      <tr
                        v-else
                        v-for="(credit, dix) in report.credits"
                        :key="dix"
                      >
                        <td>
                          {{ dix }}
                        </td>
                        <td>
                          {{ credit }}
                        </td>
                      </tr>
                      <tr v-if="!loading && Object.keys(report).length > 0">
                        <td colspan="1"></td>
                        <td>
                          <b>= {{ total_credit() }}</b>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="box box-primary">
                <div class="box-body">
                  <div class="box-header">
                    <h5>DEBIT</h5>
                  </div>
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">Purpose</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-else
                        v-for="(debit, dix) in report.debits"
                        :key="dix"
                      >
                        <td>
                          {{ dix }}
                        </td>
                        <td>
                          {{ debit }}
                        </td>
                      </tr>
                      <tr v-if="!loading && Object.keys(report).length > 0">
                        <td colspan="1"></td>
                        <td>
                          <b>= {{ total_debit() }}</b>
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
  </div>
</template>

<script>
export default {
  created() {
    this.c_date();
    this.l_date();

    setTimeout(() => {
      this.reportList();
    }, 100);
    // this.getResults();
  },
  data() {
    return {
      credits: {},
      loading: true,
      item: 20,
      search: "",

      //for date picker
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },

      start_date: "",
      end_date: "",

      report: "",
    };
  },
  methods: {
    reportList() {
      this.loading = true;
      axios
        .get("/account/monlthly/report", {
          params: {
            item: this.item,
            start_date: this.start_date,
            end_date: this.end_date,
          },
        })
        .then((resp) => {
          console.log(resp);
          this.report = resp.data;
          //   this.credits = resp.data;
          this.loading = false;
         

          
       
        })
        .catch((error) => {
          console.log(error);
        });
    },

    //method initial for  get current date
    c_date() {
      //current date
      let d = new Date();

      //current mount
      //current day
      console.log(d.getMonth());
      console.log("get month ...........");
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
      console.log(output);
      this.end_date = output;
    },
    l_date() {
      //current date
      let d = new Date();

      //current mount
      //current day
      console.log(d.getMonth());
      console.log("get month ...........");
      let month = d.getMonth();
      let day = d.getDate();
      let output =
        d.getFullYear() +
        "-" +
        (("" + month).length < 2 ? "0" : "") +
        month +
        "-" +
        (("" + day).length < 2 ? "0" : "") +
        day;
      console.log(output);
      this.start_date = output;
    },

    totalAmount() {
      let total = 0;

      this.credits.data.forEach((element) => {
        total += parseInt(element.amount);
      });

      return total;
    },
    total_debit() {
      let debits = this.report.debits;
      let total = 0;
      Object.keys(debits).forEach(function (key) {
        total += debits[key];

        // console.log();
      });

      return parseFloat(total);
    },
    total_credit() {
      let credits = this.report.credits;
      let total = 0;
      Object.keys(credits).forEach(function (key) {
        total += credits[key];

        // console.log();
      });

      return parseFloat(total);
    },
  },

  watch: {
    start_date: function (value) {
      this.reportList();
    },
    end_date: function (value) {
      this.reportList();
    },
  },
};
</script>

<style scoped>
</style>
