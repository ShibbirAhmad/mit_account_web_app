<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'compnaySale' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">{{ company.name }}</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">

          <div class="row ">
             <div class="col-lg-11 col-md-11">


              <div class="row">
                <div
                  class="col-lg-12"
                  style="
                    background: #fff;
                    margin-bottom: 13px;
                    font-weight: bolder;
                    text-align: center;
                    box-shadow: 3px 3px 3px #ddd;
                  "
                >

                  <h3 class="text-uppercase" style="font-weight: bold">
                    Company:{{ company.name }}
                  </h3>
                </div>
              </div>


           <div class="row total_row">


             <div class="col-md-4">
                  <div class="box_container">
                   <h3 class="c-name text-uppercase">Total Sales Amount</h3>
                    <hr />
                    <h3 class="c-name text-uppercase">
                     {{ total_purchase }}
                    </h3>
                  </div>
             </div>


             <div class="col-md-4">
                  <div class="box_container">
                   <h3 class="c-name text-uppercase">Total Paid Amount</h3>
                    <hr />
                    <h3 class="c-name text-uppercase">
                        {{ paid_amount }}
                    </h3>
                  </div>
             </div>

             <div class="col-md-4">
                  <div class="box_container">
                   <h3 class="c-name text-uppercase">Total Due Amount</h3>
                    <hr />
                    <h3 class="c-name text-uppercase">
                      {{    parseFloat(total_purchase) - ( parseFloat(paid_amount) + parseFloat(total_discount) ) }}
                    </h3>
                  </div>
             </div>

           </div>


              <div class="box box-primary">
                <div class="row">

                  <div class="col-md-8">
                     <button @click="salesHistory" :class="{ border_active: sales_history==true }"  class="btn btn_paid_purchase"> Purchase History </button>
                      <button @click="paidHistory" :class="{ border_active: paid_history==true }"  class="btn btn_paid_purchase"> Paid History </button>
                      <button style="margin-left:20px;" @click="showModal" class="btn btn-success "> Get Paid   </button>
                  </div>


                  <div class="col-md-4">

                  </div>

                </div>
                <div v-if="sales_history==true">
                   <div class="box-body">
                    <table class="table  table-hover table-striped table-bordered">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Date</th>
                          <th scope="col">Invoice</th>
                          <th scope="col">Purchase </th>
                          <th scope="col">sale Paid </th>
                          <th scope="col">Due</th>
                          <th scope="col">Status</th>
                          <th scope="col">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <h1 v-if="loading">
                          <i class="fa fa-spin fa-spinner"></i>
                        </h1>
                        <tr
                          v-for="(sale, index) in sales.data"
                          :key="index"
                          v-else
                        >
                          <td scope="row">{{ index + 1 }}</td>

                          <td>{{ sale.created_at  }}</td>
                          <td>{{ sale.invoice_no  }}</td>
                          <td> &#2547; {{ sale.total  }}</td>
                          <td> &#2547; {{ sale.paid  }}</td>
                          <td> &#2547; {{ ( parseInt(sale.total) - ( parseInt(sale.paid) + parseInt(sale.discount) ) )  }}</td>
                            <td>
                            <span
                              class="badge badge-primary"
                              v-if="sale.status == 1"
                              >Initial</span
                            >
                            <span
                              class="badge badge-success"
                              v-if="sale.status == 2"
                              >Paid</span
                            >

                            <span
                              class="badge badge-danger"
                              v-if="sale.status == 3"
                              >Retruned</span
                            >
                          </td>
                          <td>
                            <router-link
                              :to="{
                                name: 'ViewSale',
                                params: { id: sale.id },
                              }"
                              class="btn btn-primary btn-sm"
                              ><i class="fa fa-eye"></i
                            ></router-link>

                            <!-- <a
                              class="btn btn-success"
                              @click.prevent="paid(sale.id, index)"
                              v-if="sale.status == 1"
                              >Paid</a
                            >
                            <a
                              class="btn btn-danger"
                              v-if="sale.status == 1"
                              @click.prevent="returned(sale.id, index)"
                              >Returend</a
                            > -->
                          </td>

                        </tr>
                        <tr>

                          <!-- <td colspan="3" >{{ totalPaid() }} </td> -->
                          <td colspan="3" > </td>
                          <td> <span class="badge badge-success"> &#2547;{{ totalPurchase() }}</span></td>
                          <td> <span class="badge badge-warning"> &#2547;{{ totalPaid() }} </span> </td>
                          <td> <span class="badge badge-warning"> &#2547;{{ totalDue() }} </span> </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="box-footer">
                    <div class="row">
                      <div class="col-lg-6">
                        <pagination
                          :data="sales"
                          @pagination-change-page="saleList"
                        ></pagination>
                      </div>
                      <div
                        class="col-lg-6 mt-1"
                        style="margin-top: 25px; text-align: right"
                      >
                        <p>
                          Showing <strong>{{ sales.from }}</strong> to
                          <strong>{{ sales.to }}</strong> of total
                          <strong>{{ sales.total }}</strong> entries
                        </p>
                      </div>
                    </div>
                  </div>
                </div>


                <div  v-if="paid_history == true">
                 <div class="box-body">
                  <table class="table  table-hover table-striped table-bordered">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Date</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Paid By</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-for="(payment, index) in paid_records.data"
                        :key="index"
                        v-else
                      >
                        <td >{{ index + 1 }}</td>
                        <td>{{ payment.date  }}</td>
                        <td>{{ payment.comment  }}</td>
                        <td>{{ payment.credit_in  }}</td>
                        <td> &#2547; {{  payment.amount  }}</td>
                      </tr>
                      <tr>

                        <td colspan="4" > </td>
                        <td> <span class="badge badge-success"> &#2547; {{ totalPartialPayment() }}</span></td>

                      </tr>
                    </tbody>
                  </table>
                </div>

                </div>

              </div>
            </div>
          </div>
        </div>
      </section>
    </div>



    <modal name="payment_modal" :width="400" :height="370">
      <form @submit.prevent="getPayment">
        <div class="card" style="padding: 20px">
          <div class="card-body">
            <div class="form-group">
              <label>Date</label>
              <date-picker
                :class="{ 'is-invaid': form.errors.has('date') }"
                v-model="form.date"
                required
                :config="options"
              >
              </date-picker>
              <has-error :form="form" field="date"></has-error>
            </div>

            <div class="form-group">
              <label>Amount</label>
              <input
                type="text"
                v-model="form.amount"
                required
                class="form-control"
              />
            </div>

            <div class="form-group">
              <label> Credit In </label>
              <select
                required
                name="credit_in"
                class="form-control"
                v-model="form.credit_in"
                :class="{ 'is-invalid': form.errors.has('credit_in') }"
              >

                <option  v-for="(balance,index) in balance" :value="balance.id" :key="index">{{balance.name}}</option>
              </select>
              <has-error :form="form" field="credit_in"></has-error>
            </div>

            <div class="form-group">
              <label>Comment</label>
              <input type="text" v-model="form.comment" class="form-control" />
            </div>

            <button type="submit" class="btn btn-success btn-block">
              Submit
            </button>
          </div>
        </div>
      </form>
    </modal>

  </div>
</template>

<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
export default {
  created() {
    this.saleList();
    this.balanceList();
  },
  data() {
    return {
      sales: {},
      company:'',
      loading: true,
      item: 20,
      sale_type: 2,
      paid_amount: 0,
      total_purchase: 0,
      total_discount: 0,
      paid_records: {},
      start_date: "",
      end_date: "",
      data_search: "",
      sales_history:true,
      paid_history:false,
       form: new Form({
        date: "",
        company_id: this.$route.params.id,
        credit_in: "",
        comment: "",
        amount: "",
      }),
      balance: "",
      //date picker options ..........
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
    };
  },

  methods: {
    balanceList() {
      axios
        .get("/api/balance/list")
        .then((resp) => {
            this.balance = resp.data.balance;

        })
    },
    showModal() {
      this.$modal.show("payment_modal");
    },

     salesHistory(){
       this.sales_history=true ;
       this.paid_history=false ;
    },

     paidHistory(){
       this.sales_history=false ;
       this.paid_history=true ;
    },

    totalPartialPayment(){
      if (this.paid_records.data.length > 0) {
          var partial_paid = 0 ;
          this.paid_records.data.forEach(item =>{
              partial_paid += parseInt(item.amount) ;
          });
          return partial_paid ;
      }
    },

    saleList(page=1){
        axios.get('/api/company/sale/details/'+this.$route.params.id+'?page='+page)
        .then(resp=>{
            console.log(resp);
            this.sales=resp.data.sales ;
            this.company=resp.data.company ;
            this.total_purchase=resp.data.total_purchase ;
            this.total_discount=resp.data.total_discount ;
            this.paid_amount=resp.data.paid_amount ;
            this.paid_records=resp.data.paid_records ;
            this.loading=false ;
        })
    },

  getPayment() {
      this.form
        .post("/api/get/company/sale/payment", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.form.date = "";
            this.form.amount = "";
            this.form.comment = "";
            this.$modal.hide("payment_modal");
            this.saleList();
          }
        });
    },


    saleSearch(page = 1) {
      if (this.data_search.length > 1) {
        this.loading = true;
        axios.get("/api/company/sale/search/data/" + this.data_search + "?page=" + page)
          .then((resp) => {
            if (resp.data.status == "OK") {
                this.sales = resp.data.sales;
                this.loading = false;
            }
          })
          .catch((error) => {
            console.log(error);
            alert("something went wrong");
          });
      }else{
        this.saleList();
      }
    },

    filterCompanySale(page = 1) {
      //fetch data
      axios
        .get("/api/company/sale/date/wise/filter?page=" + page, {
          //send data
          params: {
            start_date: this.start_date,
            end_date: this.end_date,
            item: this.item,
          },
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.sales = resp.data.sales;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

   totalPaid(){
       let sale_paid=0;
       this.sales.data.forEach(sale=>{
           sale_paid += parseInt(sale.paid) ;
       });
       return sale_paid ;
   },


   totalPurchase(){
       let total=0;
       this.sales.data.forEach(sale=>{
           total += parseInt(sale.total) ;
       });
       return total ;
   },


 totalDue(){
       let due=0;
       this.sales.data.forEach(sale=>{
           due += parseInt(sale.total) - (parseInt(sale.paid) + parseInt(sale.discount)) ;
       });
       return due ;
   },
    //method iniitial for sale paid

    paid(sale_id, index) {
      Swal.fire({
        title: "Are you sure?",
        text: "You wan't make this paid",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/sale/paid/" + sale_id)
            .then((resp) => {
              if (resp.data.success == "OK") {
                this.sales.data[index].status = 2;
                this.$toasted.show(resp.data.message, {
                  position: "top-center",
                  type: "success",
                  duration: 2000,
                });
              }
              //  console.log(sale_id);
            })
            .catch((error) => {
              this.$toasted.show("some thing went to wrong", {
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

    returned(sale_id, index) {
      Swal.fire({
        title: "Are you sure?",
        text: "You wan't make this return",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/sale/returned/" + sale_id)
            .then((resp) => {
              // console.log(resp)
              if (resp.data.success == "OK") {
                this.sales.data[index].status = 2;
                this.$toasted.show(resp.data.message, {
                  position: "top-center",
                  type: "success",
                  duration: 2000,
                });
              }
            })
            .catch((error) => {
              // console.log(error)
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
  },
  watch:{

    start_date: function (value) {
      if (value.length > 1) {
        this.filterCompanySale();
      }
    },
    end_date: function (value) {
      if (value.length > 1) {
        this.filterCompanySale();
      }
    },

  }
};
</script>

<style scoped>
.box_container {
  background: #fff;
  padding: 10px;
  box-shadow: 3px 3px 3px #ddd;
  border-radius: 5px;
  height: 200px;
}
.c-name {
  text-align: center;
  height: 50px;
  font-weight: bold;
}

.total_row {
  margin-bottom: 50px;
}

.btn_paid_purchase {
    margin: 5px 20;
    padding: 10px 50px;
    font-size: 16px;
    font-weight: bold;
}

.border_active{
  border-bottom: 2px dashed #000;
  background: #fff;
}

</style>
