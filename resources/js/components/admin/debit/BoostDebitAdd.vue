<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'boost_debit' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Debit</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-10 col-lg-offset-1">
            <div class="box box-primary">
              <div class="box-header text-center with-border">
                <h3 class="box-title">Add Debit</h3>
              </div>
              <div class="box-body">
                <h1 v-if="loading"><i class="fa fa-spinner fa-spin"></i></h1>
                <form
                  v-else
                  @submit.prevent="addDebit"
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
                    <select
                      class="form-control"
                      v-model="form.purpose"
                      name="purpose"
                      @change="selectPurpose"
                      :class="{ 'is-invalid': form.errors.has('purpose') }"
                    >
                      <option value="" selected disabled>Select Purpose</option>
                      <option
                        v-for="purpose in purposes"
                        :key="purpose.id"
                        :value="purpose.id"
                      >
                        {{ purpose.text }}
                      </option>
                    </select>
                    <has-error :form="form" field="purpose"></has-error>
                  </div>
                  <div class="form-group">
                    <label>Amount</label>
                    <input
                      type="text"
                      name="amount"
                      v-model="form.amount"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('amount') }"
                      autocomplete="off"
                    />
                    <has-error :form="form" field="amount"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Comment</label>
                    <input
                      type="text"
                      name="comment"
                      class="form-control"
                      v-model="form.comment"
                      :class="{ 'is-invalid': form.errors.has('comment') }"
                      autocomplete="off"
                    />
                    <has-error :form="form" field="comment"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Debit From</label>
                    <select
                      name="debit_from"
                      required
                      class="form-control"
                      v-model="form.debit_from"
                      :class="{ 'is-invalid': form.errors.has('debit_from') }"
                    >
                     <option value="" disabled selected>Select Balance</option>
                     <option  v-for="(balance,index) in balance" :value="balance.id" :key="index">{{balance.name}}</option>
                    </select>
                    <has-error :form="form" field="debit_from"></has-error>
                  </div>

                  <br />
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
import sig from "vue-signature";

Vue.component(HasError.name, HasError);
export default {
  created() {
    this.accountPurpose();
    this.balanceList() ;
  },
  data() {
    return {
      form: new Form({
        department: "boost",
        purpose: "",
        amount: "",
        date: "",
        comment: "",
        employee_id: "",
        debit_from: "",
        supplier_id: "",
        signature: null,
        signature_write: false,
        loaner_id: "",
        investor_id: "",
        print_house_id: "",
        profit_month: "",
        old_signature: "",
        bill_statement_id: "",
      }),

      old_signature: true,
      loading: true,
      error: "",
      purposes: "",
      balance:"",
      //for date picker
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      months: {
        January: "January",
        February: "February",
        March: "March",
        April: "April",
        May: "May",
        June: "June",
        July: "July",
        August: "August",
        September: "September",
        October: "October",
        November: "November",
        December: "December",
      },
      //signature pad.......
      option: {
        penColor: "rgb(0, 0, 0)",
        backgroundColor: "yellow",
      },
      disabled: false,
      base_link: this.$store.state.image_base_link,
    };
  },

  methods: {

         balanceList() {
                axios.get("/api/balance/list/boost")
                    .then((resp) => {
                        this.balance = resp.data.balance;
                    });
            },

            addDebit() {
                this.form
                    .post("/api/debit/store")
                    .then((resp) => {
                        if (resp.data.status == "SUCCESS") {
                            this.$router.push({
                                name: "boost_debit"
                            });
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
                         console.log(error)
                        this.error = "something  went to wrong";
                    });
            },

    accountPurpose() {
      axios
        .get("/api/account/purpose")
        .then((resp) => {
          console.log(resp);
          if (resp.data) {
            this.purposes = resp.data;
            setTimeout(() => {
              console.log(this.$store.getters.admin.signature);
              if (this.$store.getters.admin.signature) {
                this.form.old_signature = this.$store.getters.admin.signature;
                this.old_signature = true;
              } else {
                this.old_signature = false;
              }
              this.loading = false;
            }, 1000);
          }
        })
        .catch((e) => {
          console.log(e);
        });
    },
    selectPurpose() {
      let value = this.form.purpose;
      if (value == 1) {
        this.employeeList();
      } else if (value == 9) {
        this.supplierList();
      } else if (value == 11) {
        this.loanerList();
      } else if (value == 12) {
        this.investorList();
      } else if (value == 10) {
        this.printHoueList();
      } else if (value == 21) {
        this.billStatementList();
      } else if (value == 22) {
        this.returnInvestorList();
      } else {
        this.form.employee_id = "";
        this.form.supplier_id = "";
      }
    },
    employeeList() {
      axios
        .get("/api/employee/list")
        .then((resp) => {
          let options = {};
          resp.data.forEach((element) => {
            options[element.id] = element.name + "-" + element.designation;
          });
          Swal.fire({
            title: "Select a employee",
            input: "select",
            inputOptions: options,
            inputPlaceholder: "Select One",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.form.employee_id = result.value;
            } else {
              this.form.purpose = "";
              this.form.employee_id = "";
            }
          });
        })
        .catch((e) => {
          console.log(e);
        });
    },
    billStatementList() {
      axios.get("/api/bill/statement/list",{
        params:{
          status:1
        }
      })
      .then((resp) => {
        console.log(resp);
        let options = {};
        resp.data.bills.forEach((element) => {
          options[element.id] = element.name;
        });
        Swal.fire({
          title: "Select a bill",
          input: "select",
          inputOptions: options,
          inputPlaceholder: "Select One",
          showCancelButton: true,
        }).then((result) => {
          if (result.value) {
            this.form.bill_statement_id = result.value;
          } else {
            this.form.purpose = "";
            this.form.bill_statement_id = "";
          }
        });
      });
    },

    loanerList() {
      axios
        .get("/api/loaners")
        .then((resp) => {
          console.log(resp);
          let options = {};
          resp.data.forEach((element) => {
            options[element.id] = element.name + "-" + element.mobile_no;
          });
          Swal.fire({
            title: "Select a Loaner",
            input: "select",
            inputOptions: options,
            inputPlaceholder: "Select One",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.form.loaner_id = result.value;
            } else {
              this.form.purpose = "";
              this.form.loaner_id = "";
            }
          });
        })
        .catch((e) => {
          console.log(e);
        });
    },

    supplierList() {
      axios
        .get("/api/supplier/list")
        .then((resp) => {
          console.log(resp);
          let options = {};
          resp.data.forEach((element) => {
            options[element.id] = element.company_name + "-" + element.name;
          });
          Swal.fire({
            title: "Select a supplier",
            input: "select",
            inputOptions: options,
            inputPlaceholder: "Select One",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.form.supplier_id = result.value;
            } else {
              this.form.supplier_id = "";
            }
          });
        })
        .catch((e) => {
          console.log(e);
        });
    },


    investorList() {
      axios
        .get("/api/company/investor/list")
        .then((resp) => {
          console.log(resp);
          let options = {};
          resp.data.forEach((element) => {
            options[element.id] = element.name + "-" + element.mobile_no;
          });
          Swal.fire({
            title: "Select a Investor",
            input: "select",
            inputOptions: options,
            inputPlaceholder: "Select One",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.form.investor_id = result.value;

              Swal.fire({
                title: "Select Profit Month",
                input: "select",
                inputOptions: this.months,
                inputPlaceholder: "Select One",
                showCancelButton: true,
              }).then((month) => {
                if (month.value) {
                  this.form.profit_month = month.value;
                } else {
                  this.form.profit_month = "";
                }
              });
            } else {
              this.form.purpose = "";
              this.form.investor_id = "";
            }
          });
        })
        .catch((e) => {
          console.log(e);
        });
    },

    printHoueList() {
      axios.get("/api/print/house/list").then((resp) => {
        console.log(resp);
        let options = {};
        resp.data.forEach((element) => {
          options[element.id] = element.company_name + "-" + element.mobile_no;
        });
        Swal.fire({
          title: "Select a print-house",
          input: "select",
          inputOptions: options,
          inputPlaceholder: "Select One",
          showCancelButton: true,
        }).then((result) => {
          if (result.value) {
            this.form.print_house_id = result.value;
          } else {
            this.form.purpose = "";
            this.form.print_house_id = "";
          }
        });
      });
    },

    returnInvestorList() {
      axios
        .get("/api/company/investor/list")
        .then((resp) => {
          console.log(resp);
          let options = {};
          resp.data.forEach((element) => {
            options[element.id] = element.name + "-" + element.mobile_no;
          });
          Swal.fire({
            title: "Select a Investor",
            input: "select",
            inputOptions: options,
            inputPlaceholder: "Select One",
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.form.investor_return_id = result.value;
            } else {
              this.form.purpose = "";
              this.form.investor_return_id = "";
            }
          });
        })
        .catch((e) => {
          console.log(e);
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
      let output = d.getFullYear() + "-" + (("" + month).length < 2 ? "0" : "") +  month + "-" + (("" + day).length < 2 ? "0" : "") + day;
      this.form.date = output;
    },

    save() {
      var _this = this;
      var png = _this.$refs.signature.save();
      this.form.signature = png;
    },

    clear() {
      var _this = this;
      _this.$refs.signature.clear();
      this.form.signature_write = false;
    },
    sigantureFocus() {
      let element = document.getElementById("signatur-pad");
      element.classList.add("focus-signature");
    },
    checkOldSignature() {
      if (this.old_signature.length <= 0) {
        alert("You Did Not Write Any Signature Of Our Records");
        return;
      } else {
        this.old_signature_preview = true;
      }
    },
  },
  mounted() {
    this.pDate();
  },
  components: {
    sig,
  },
  watch: {
    old_signature: function (value) {
      if (value == false) {
        this.form.old_signature = "";
        console.log(value);
      }
    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
#signatur-pad {
  height: 300px;
}
</style>
