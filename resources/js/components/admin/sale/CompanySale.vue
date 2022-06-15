<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'AddCompanySale' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Company Sales</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-11 col-md-11">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <div class="row">
                    <div class="col-md-4">
                      <button
                        @click="activeList"
                        :class="{ active_border: status == 1 }"
                        class="btn btn-success"
                      >
                        Active
                      </button>

                      <button
                        @click="deActiveList"
                        :class="{ active_border: status == 0 }"
                        class="btn btn-warning"
                      >
                        DeActive
                      </button>

                       <button @click="exportDueListPdf" class="btn btn-success btn-sm">Export Due PDF</button>
                    </div>
                    <div class="col-md-2">
                      <h3 style="margin-top: 10px" class="box-title heading">
                        Company Sales
                      </h3>
                    </div>
                    <div class="col-md-5">
                      <input
                        type="text"
                        v-model="search"
                        v-on:keyup="searchCompany"
                        placeholder="search by phone or company-name"
                        class="form-control"
                      />
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <table class="table table-hover table-striped table-bordered">
                    <thead>
                      <tr>
                        <th width="10%">#</th>
                        <th width="10%">Name</th>
                        <th width="10%">Phone</th>
                        <th width="25%">Address</th>
                        <th width="15%">Total</th>
                        <th width="10%">Paid</th>
                        <th width="10%">Due</th>
                        <th width="10%">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-for="(company, index) in companies" :key="index">
                        <td > <h5> {{ index + 1 }} </h5> </td>

                        <td>
                          <h5>
                          <router-link
                            style="color: blue !important; font-size: 16px;"
                            :to="{
                              name: 'company_sale_details',
                              params: { id: company.id },
                            }"
                          >
                            {{ company.name }}
                          </router-link>
                           </h5>
                        </td>
                        <td> <h5>  {{ company.phone }}</h5></td>
                        <td> <h5> {{ company.address }} </h5></td>

                        <td>
                          <h5 >  <span style="min-width:60px;padding:5px 0px;"
                            class="badge">{{ totalPurchase(company.sales) }} </span>  </h5>
                        </td>
                        <td> <h5 >  <span style="min-width:60px;padding:5px 0px;"
                            class="badge badge-success"> {{ totalPaid(company) }}  </span>   </h5> </td>
                        <td>
                          <h5>
                          <span
                            style="min-width:60px;padding:5px 0px;"
                            class="badge badge-danger"
                          >
                            {{  dueNow(company) }}
                          </span>
                           </h5>
                        </td>

                        <td>
                           <h5>

                          <!-- <router-link
                            :to="{
                              name: 'company_payment_details',
                              params: { id: company.id },
                            }"
                            class="btn btn-primary btn-xs"
                          >
                            Payment Details
                          </router-link> -->

                          <a
                            class="btn btn-xs  btn-warning"
                            title="De-active"
                            @click="deActive(company)"
                            v-if="company.status == 1"
                            ><i class="fa fa-ban"></i
                          ></a>
                          <a
                            class="btn btn-xs  btn-primary"
                            title="active"
                            @click="active(company)"
                            v-else
                            ><i class="fa fa-check"></i
                          ></a>
                          </h5>
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
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
Vue.component(HasError.name, HasError);
export default {
  created() {
    this.companyList();
  },
  data() {
    return {
      companies: {},
      loading: true,

      search: "",
      status: 1,
      options: {
        format: "YYYY-MM-DD",
        useCurrent: true,
      },
    };
  },
  methods: {


    searchCompany() {
      if (this.search.length > 2) {
        this.companyList();
      } else {
        this.companyList();
      }
    },

    activeList() {
      this.status = 1;
      this.companyList();
    },

    deActiveList() {
      this.status = 0;
      this.companyList();
    },

    companyList() {
      axios
        .get("/api/company/with/sales/records", {
          params: {
            search: this.search,
            status: this.status,
          },
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.companies = resp.data.companies;
            this.loading = false;
          }
        });
    },

    totalPurchase($sales) {
      if ($sales) {
        let total = 0;
        $sales.forEach((sale) => {
          total += parseInt(sale.total);
        });
        return total;
      }
    },

    totalPaid(company) {
      if (company) {
        let total_partial_paid = 0;
        let total_sale_paid = 0;
        company.payments.forEach((payment) => {
          total_partial_paid += parseInt(payment.amount);
        });

        company.sales.forEach((sale) => {
          total_sale_paid += parseInt(sale.paid);
        });

        let total_paid = parseInt(total_sale_paid) + parseInt(total_partial_paid);
        return total_paid;
      }
    },



    dueNow(company) {
      if (company) {

        let total_partial_paid = 0;
        let total_sale_discount = 0;
        let total_sale_paid = 0;
        let total_sale = 0;

        company.sales.forEach((sale) => {
          total_sale += parseInt(sale.total);
        });

        company.payments.forEach((payment) => {
          total_partial_paid += parseInt(payment.amount);
        });

        company.sales.forEach((sale) => {
          total_sale_paid += parseInt(sale.paid);
        });

       company.sales.forEach((sale) => {
          total_sale_discount += parseInt(sale.discount);
        });

        let total_paid =  parseInt(total_sale_paid) + parseInt(total_partial_paid);
        let due = total_sale - ( total_paid + total_sale_discount ) ;
        return due;
      }
    },





    deActive(company) {
      Swal.fire({
        title: "Are you sure?",
        text: "You wan't de-active this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/company/de-active/status/" + company.id)
            .then((resp) => {
              //  console.log(resp)
              if (resp.data.status == "SUCCESS") {
                this.companyList();
                this.$toasted.show(resp.data.message, {
                  position: "top-center",
                  type: "success",
                  duration: 4000,
                });
              } else {
                this.$toasted.show("something went to wrong", {
                  position: "top-center",
                  type: "error",
                  duration: 4000,
                });
              }
            })
            .catch((error) => {
              // console.log(error)
              this.$toasted.show("something went to wrong", {
                position: "top-center",
                type: "error",
                duration: 4000,
              });
            });
        } else {
          this.$toasted.show("OK ! no action here", {
            position: "top-center",
            type: "info",
            duration: 3000,
          });
        }
      });
    },

    active(company) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't active this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/company/active/status/" + company.id)
            .then((resp) => {
              if (resp.data.status == "SUCCESS") {
                this.companyList();
                this.$toasted.show(resp.data.message, {
                  position: "top-center",
                  type: "success",
                  duration: 4000,
                });
              } else {
                this.$toasted.show("something went to wrong", {
                  position: "top-center",
                  type: "error",
                  duration: 4000,
                });
              }
            })
            .catch((error) => {
              this.$toasted.show("something went to wrong", {
                position: "top-center",
                type: "error",
                duration: 4000,
              });
            });
        } else {
          this.$toasted.show("Ok ! no action here", {
            position: "top-center",
            type: "info",
            duration: 3000,
          });
        }
      });
    },



    exportDueListPdf(){
       window.open('/api/company/sale/due/list','_blank');
    }


  },
  components: { HasError },
};
</script>

<style scoped>
.active_border {
  border-bottom: 2px solid #ff4d03;
}

h5{
  font-size: 16px;
}

h5>a {
  font-size:14px;
}
</style>
