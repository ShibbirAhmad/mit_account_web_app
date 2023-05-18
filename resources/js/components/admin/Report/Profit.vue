<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">report</li>
        </ol>
      </section>
      <br />
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-11 col-md-11">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title">Gross PROFIT</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-lg-12 col-md-12">
                      <div class="row">
                        <div class="col-lg-4">
                          <date-picker
                          style="height:38px;"
                            v-model="start_date"
                            placeholder="start-date"
                            :config="options"
                          ></date-picker>
                        </div>
                        <div class="col-lg-4" style="margin-left: -20px">
                          <date-picker
                          style="height:38px;"
                            v-model="end_date"
                            placeholder="end-date"
                            :config="options"
                          ></date-picker>
                        </div>
                        <div class="col-lg-4">
                          <div style="display:flex">
                            <button
                              style="height:38px;"
                              type="submit "
                              class="btn btn-success btn-sm"
                              @click.prevent="getReport"
                            >
                              <i class="fa fa-filter"></i> Filter By
                              Date
                            </button>

                            <select
                              @change="filterOrderAccordingToDays"
                              v-model="filtering_date_value"
                              class="form-control"
                              style="height:38px;margin-left:20px"
                            >
                              <option value="" disabled>Filter By Days</option>
                              <option value="today">Today</option>
                              <option value="yesterday">Yesterday</option>
                              <option value="last 7 days">Last 7 Days</option>
                              <option value="last 30 days">Last 30 Days</option>
                              <option value="this month">This Month</option>
                              <option value="this year">This Year</option>
                              <option value="last year">Last Year</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div v-if="loading" class="row">
                      <div class="col-md-12 text-center">
                        <h3><i class="fa fa-spin fa-spinner "></i></h3>
                      </div>
                    </div>
                    <div v-if="display==true">
                      <div class="col-md-6">
                        <div class="debit_lists">
                      
                          <ul
                            style="margin-left:-40px;margin-top:50px;"
                            class="list"
                          >
                          <li>
                            <h4>Expense List</h4>
                          </li>
                            <li
                              class="list-group-item"
                              v-for="item in expense_list"
                              :key="item.id"
                            >
                              <div class="row">
                                <div class="col-md-4">{{ item.text }} - <small> 
                                  <span v-if="item.is_expense ==1">actual expense</span>
                                  <span v-else>other expense</span>
                                </small>
                                 </div>
                                <div class="col-md-8">
                                  <strong> {{ item.amount }} </strong>
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div>

                      
                      </div>

                      <div class="col-md-6">
                        <div class="debit_lists">
                          
                          <ul
                            style="margin-left:-40px;margin-top:50px;"
                            class="list"
                          >
                          <li>
                            <h4>Income List</h4>
                          </li>
                            <li
                              class="list-group-item"
                              v-for="item in income_list"
                              :key="item.id"
                            >
                              <div class="row">
                                <div class="col-md-4">{{ item.name }}</div>
                                <div class="col-md-8">
                                  <strong> {{ item.amount }} </strong>
                                </div>
                              </div>
                            </li>
                          </ul>
                        </div>

                      
                      </div>

                      <div class="col-md-12">
                        <div class="total_container_section">
                          <h4>Total Income  <b> {{ total_income }}</b> </h4>
                          <h4>Actual Expense  <b>  {{ actual_expense }} </b></h4>
                          <small v-for="item in expense_list" :key="item.id" > <span v-if="item.is_expense ==1" > {{ item.text }}, </span> </small>

                          <h4>Other Expense <b>{{ actual_expense }} </b>  </h4>
                       
                            <small v-for="item in expense_list" :key="item.id" > <span v-if="item.is_expense ==0" > {{ item.text }}, </span> </small>
                          
                         
                        </div>
                        <div class="summary_container">
                        
                          <h4>Gross Profit <b> {{ parseInt(total_income) - parseInt(actual_expense)  }}</b> </h4>
                          <h4>Net Profit  <b> {{ parseInt(total_income) - ( parseInt(actual_expense) + parseInt(other_expense) )   }}</b> </h4>
                        </div>
                      </div>
                      

          
                    </div>
                  </div>
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
    setTimeout(() => {
      this.loading = false;
    }, 2000);
  },
  data() {
    return {
      loading: true,
      display: false,
      income_list: 0,
      expense_list: 0,
      filtering_date_value: "",
      start_date: "",
      end_date: "",
      total_income: 0,
      other_expense: 0,
      actual_expense: 0,
      debit_purposes: "",
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
    };
  },
  methods: {
    getReport() {
      this.loading = true;
      axios
        .get("/api/profit/report", {
          params: {
            start_date: this.start_date,
            end_date: this.end_date,
          },
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true) {
            this.income_list = resp.data.income_list;
            this.expense_list = resp.data.expense_list;
            this.other_expense = resp.data.other_expense;
            this.actual_expense = resp.data.actual_expense;
            this.total_income = resp.data.total_income;
            this.loading = false;
            this.display = true;

          }
        })
        .catch((error) => {
          console.log(error.response.data.message);
        });
    },


    filterOrderAccordingToDays() {
      if (this.filtering_date_value == "today") {

        this.start_date = this.currentDate();
        this.end_date = this.currentDate();
        this.getReport();

      } else if (this.filtering_date_value == "yesterday") {
        
        this.start_date =this.previousDate(new Date(Date.now() - 1 * 24 * 60 * 60 * 1000));
        this.end_date = this.previousDate(new Date(Date.now() - 1 * 24 * 60 * 60 * 1000));
        this.getReport();

      }else if (this.filtering_date_value == "last 7 days") {
        this.start_date =this.previousDate(new Date(Date.now() - 7 * 24 * 60 * 60 * 1000));
        this.end_date = this.currentDate();
        this.getReport();

      }else if (this.filtering_date_value == "last 30 days") {
        
        this.start_date =this.previousDate(new Date(Date.now() - 30 * 24 * 60 * 60 * 1000));;
        this.end_date = this.currentDate();
        this.getReport();

      }else if (this.filtering_date_value == "this month") {

        let d = new Date();
        let month = d.getMonth() + 1;
        let first_day_of_month = d.getFullYear() +"-" +(("" + month).length < 2 ? "0" : "") + month + "-01" ;
        this.start_date =first_day_of_month;
        this.end_date = this.currentDate();
        this.getReport();

      }else if (this.filtering_date_value == "this year") {

        let d = new Date();
        let first_month_of_year = d.getFullYear() +"-01-01" ;
        this.start_date =first_month_of_year;
        this.end_date = this.currentDate();
        this.getReport();

      }else if (this.filtering_date_value == "last year") {

        let date = new Date();
        this.start_date =date.getFullYear() - 1  +"-01-01" ;
        this.end_date = date.getFullYear() - 1  +"-12-31" ;
        this.getReport();

        }

    },

    currentDate() {
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
      return output;
    },

    previousDate(date) {

      let month = date.getMonth() + 1;
      let day =date.getDate()  ;
      let output = date.getFullYear() +"-" +(("" + month).length < 2 ? "0" : "") + month + "-" + (("" + day).length < 2 ? "0" : "") + day ;
      return output;

    },


  },
};
</script>

<style scoped>
ul{
  list-style-type: none;
}
.summary_container {
  margin-top: 20px;
  display: flex;
}

.summary_container > h4 {
  margin-right: 10%;
}
</style>
