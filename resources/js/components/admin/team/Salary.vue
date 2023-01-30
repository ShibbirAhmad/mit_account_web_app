<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'team_member' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">member</li>
        </ol>
      </section>

      <section class="content">
        <div class="container">
          <div class="row summery_row">
             <div class="col-md-4 col-sm-4">
                <div class="member-info">
              <img
                :src="
                  member.avator
                    ? basePath + member.avator
                    : basePath + 'images/static/user2-160x160.jpg'
                "
                class="member-image"
                :alt="member.name"
              />
              <h4 style="line-height: 0">{{ member.name }}</h4>
              <h6>{{ member.designation }}</h6>
              <h4>Phone: {{ member.phone }}</h4>
            </div>
             </div>
             <div class="col-md-3 col-sm-3">

               <div class="custom-box">
                Total Taken Amount : <strong>{{parseInt(total_taken_amount)}}</strong>
              </div>

              <div class="custom-box">
                Total Paid Amount : <strong>{{parseInt(total_paid_amount)}}</strong>
              </div>

               <div class="custom-box">Due/Advance Amount :
                            <strong>{{parseInt(total_taken_amount)-parseInt(total_paid_amount)}} </strong>
                </div>

             </div>
             <div class="col-md-4 col-sm-4"> </div>
          </div>


          <div class="row">
            <div class="col-lg-5 col-md-5">
              <h1 class="text-center" v-if="loading">
                <i class="fa fa-spin fa-spinner"></i>
              </h1>
              <div class="box box-primary" v-else>
                <div class="box-header with-border"></div>

                <div class="box-body">
                  <table
                    class="table table-striped table-bordered text-center"
                    v-if="Object.keys(salaryList).length"
                  >
                    <thead>
                      <tr>
                        <th scope="col">Date</th>
                         <th width="45%" scope="col">Comment</th>
                        <th scope="col">Amount</th>

                      </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(salary, index) in salaryList" :key="index">
                        <td> {{ salary.date }} </td>
                        <td>{{ salary.comment }}</td>
                        <td>{{ salary.amount }}</td>
                      </tr>

                      <tr>
                        <td colspan="2"> Total Taken  </td>
                        <td>
                          <strong>{{ parseInt(total_taken_amount) }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <div v-else>
                    <h4 class="text-uppercase text-center">
                      <strong>
                        No Salary preview AGAINST {{ member.name }}</strong
                      >
                    </h4>
                    <router-link
                      :to="{ name: 'team_member' }"
                      class="btn btn-primary btn-block"
                      ><i class="fa fa-arrow-right"></i
                    ></router-link>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-5 col-md-5">
              <div class="box">
                <div class="box-header with-border"></div>

                <div class="box-body">
                  <table class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Month</th>
                        <th>Amount</th>
                        <th>Comment</th>
                         </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(paid_salary, index) in paid_salaryies"
                        :key="index"
                      >
                        <td style="width:20px;">{{ index+1}}</td>

                     <td>
                       {{ paid_salary.date }}
                     </td>
                         <td>
                      <strong>
                         {{ paid_salary.month }}
                      </strong>
                     </td>
                         <td>
                     <strong>
                         {{ paid_salary.amount }}
                     </strong>
                     </td>
                      <td>
                       {{ paid_salary.comment }}
                     </td>


                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>
                          <strong> = {{ totalSalary() }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <button
                    style="margin-top: 10px"
                    @click="showModal"
                    class="btn btn-success"
                  >
                    Add Salary
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <modal name="example" :width="500" :height="400">
      <div class="card" style="padding: 20px">
        <div class="card-body">
          <div class="form-group">
            <label>Date</label>
            <date-picker
              autocomplete="off"
              v-model="salary_date"
              :config="options"
            ></date-picker>
          </div>
          <div class="form-group">
            <label>Month</label>
            <select class="form-control" v-model="month">
              <option value="" disabled>Select A Month</option>
              <option value="January">January</option>
              <option value="February">February</option>

              <option value="March">March</option>

              <option value="April">April</option>

              <option value="May">May</option>

              <option value="Jun">Jun</option>

              <option value="July">July</option>

              <option value="August">August</option>

              <option value="September">September</option>
              <option value="October">October</option>
              <option value="November">November</option>
              <option value="December">December</option>
            </select>
          </div>

          <div class="form-group">
            <label>Amount</label>
            <input type="text" v-model="salary_amount" class="form-control" />
          </div>
       <div class="form-group">
            <label>Comment</label>
            <input type="text" v-model="salary_comment" class="form-control" />
          </div>

          <button class="btn btn-success btn-block" @click="emloyeeSalaryPaid">
            Submit
          </button>
        </div>
      </div>
    </modal>

  </div>
</template>




<script>
export default {
  mounted() {
    this.getMemberSalary();
    this.cDate();
  },

  data() {
    return {
      loading: true,
      member: "",
      salaryList: "",
      basePath: this.$store.getters.image_base_link,
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      salary_date: "",
      salary_amount: "",
      salary_comment: "Salary Off Month-",
      paid_salaryies: "",
      basic_salary:"",
      month: "",
      total_taken_amount:0,
      total_paid_amount:0
    };
  },

  methods: {
    getMemberSalary() {
      axios
        .get("/api/member/salary/list/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          this.member = resp.data.member;
          this.salaryList = resp.data.salary;
          this.paid_salaryies = resp.data.paid_salary;

          this.total_taken_amount=resp.data.total_taken_amount;
          this.total_paid_amount=resp.data.total_paid_amount;

          this.loading = false;
        })
        .catch((e) => {
          console.log(e);
        });
    },
    dateFiltaring(date) {
      console.log(date);
      const d = new Date(date);
      const year = new Intl.DateTimeFormat("en", { year: "numeric" }).format(d);
      const mounth = new Intl.DateTimeFormat("en", { month: "long" }).format(d);
      const day = new Intl.DateTimeFormat("en", { d: "2-digit" }).format(d);
      let outpuDate = `${day}-${mounth}-${year}`;
      return outpuDate;
    },
    total() {
      if (this.salaryList.length > 0) {
        let total = 0;
      this.salaryList.forEach((element) => {
        total += parseInt(element.amount);
      });
      return total;
      }
    },

    showModal() {
      this.$modal.show("example");
    },
    emloyeeSalaryPaid() {
      if (this.salary_date.length < 1) {
        alert("Please Check Date Filed");
        return;
      }

      if (this.month.length < 1) {
        alert("Please Select A Month");
        return;
      }

      if (this.salary_amount.length < 1) {
        alert("Amount filed is empty");
        return;
      }
      if (this.salary_amount < 1) {
        alert("Amount Can not be smaller 1");
        return;
      }

      axios
        .get("/api/employee/salary/paid/", {
          params: {
            date: this.salary_date,
            amount: this.salary_amount,
            employee_id: this.member.id,
            comment: this.salary_comment,
            month: this.month,

          },
        })
        .then((resp) => {
          if (resp.data) {
            this.$toasted.show(resp.data, {
              type: "warining",
              position: "top-center",
              duration: 4000,
            });
            this.getMemberSalary();
            this.$modal.hide("example");
          }
        })
        .catch((e) => {
          console.log(e);
        });
    },
    totalSalary() {
      if (this.paid_salaryies.length > 0) {
        let total = 0;
        this.paid_salaryies.forEach((element) => {
          total += parseInt(element.amount);
        });
        return total;
      }
    },
    cDate() {
      //current date
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
      this.salary_date = output;
    },

  },
  watch: {
    month: function (value) {
      this.salary_comment = "Salary Off Month-" + value;
    },

  },
};
</script>



<style scoped>
.dropbtn {
  width: 100% !important;
  margin-bottom: 5px !important;
}
.dropbtn-active {
  display: block !important;
}
.dropdown-action {
  display: none;
  width: 90px;
  position: absolute;
}

.summery_row{
  margin-top: -40px;
}
img.member-image {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 3px solid #222d32;
  padding: 3px;
  line-height: 0;
}
.member-info {
  text-align: center;
}
.custom-box {
    height: 40px;
    background: #fff;
    padding: 10px 20px;
    text-align: center;
    font-size: 14px;
    margin-bottom: 15px;
    box-shadow: 3px 3px 3px #ddd;
}
</style>
