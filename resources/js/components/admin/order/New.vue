<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
     <div class="order_statistic">

            <router-link :to="{ name: 'NewOrder' }" class="statistic_item " >
              <h2> {{ order_count.new_order }} </h2>
               <p> New </p>
           </router-link >

        <router-link :to="{ name: 'PendingOrder' }" class="statistic_item " >
            <h2> {{ order_count.pending_order }} </h2>
            <p>Pending</p>
        </router-link>


        <router-link :to="{ name: 'ApprovedOrder' }" class="statistic_item " >
         <h2>  {{ order_count.approved_order }} </h2>
         <p> Approved </p>
        </router-link>


        <router-link :to="{ name: 'ShipmentOrder' }" class="statistic_item " >
         <h2>  {{ order_count.shipment_order }} </h2>
         <p> Shipment  </p>
        </router-link>


        <router-link :to="{ name: 'DeliveredOrder' }" class="statistic_item " >
         <h2>  {{ order_count.delivered_order }} </h2>
         <p> Delivered  </p>
        </router-link>


        <router-link :to="{ name: 'ReturnOrder' }" class="statistic_item " >
         <h2>  {{ order_count.return_order }} </h2>
         <p> Return  </p>
        </router-link>

        <router-link :to="{ name: 'CancelOrder' }" class="statistic_item " >
         <h2>  {{ order_count.cancel_order }} </h2>
         <p> Cancel  </p>
        </router-link>

        <router-link :to="{ name: 'order' }" class="statistic_item " >
         <h2>  {{ order_count.total }} </h2>
         <p> All  </p>
        </router-link>



        </div>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-11">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row" style="margin-bottom:3px;">
                    <div class="col-lg-3">
                      <select name="" id="" v-model="bulkActionType" class="form-control">
                        <option  value="0" selected  disabled>Select Action</option>

                           <option value="LABEL PRINT">Label Print</option>
                         <option value="INVOICE PRINT">Invoice Print</option>
                        <option value="PENDING ALL">Pending All</option>
                        <option value="APPROVED ALL">Approved All </option>
                        <option value="CANCEL ALL">Cencel ALl</option>


                    </select>
                    </div>
                     <div class="col-lg-2">
                         <router-link class="btn btn-success" :to="{ name: 'addOrder'}">Add New Order</router-link>
                    </div>
                    <div class="col-lg-4 orders-heading">
                      <h3 class="box-title">{{ heading }}</h3>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-2">
                      <select
                        class="form-control"
                        v-model="type"
                        @change="ordersList"
                      >
                        <option value="all">All type</option>
                        <option value="2">Onely Admin</option>
                        <option value="1">Only Customer</option>
                        <option value="3">Whole Sale</option>
                        <option value="4">Only Reseller</option>
                      </select>
                    </div>
                    <div class="col-lg-2">
                      <select
                        class="form-control"
                        v-model="status"
                        @change="ordersList"
                      >
                        <option value="all">All</option>
                        <option value="1">New</option>
                        <option value="2">Pending</option>
                        <option value="3">Approved</option>
                        <option value="4">Shipment</option>
                        <option value="5">Delivired</option>
                        <option value="7">Retrun</option>
                        <option value="6">Cancel</option>
                      </select>
                    </div>
                    <div class="col-lg-2">
                      <input
                        class="form-control"
                        @keyup="orderSearch"
                        v-model="search"
                        placeholder="Enter Invoice,Cutomer_phone"
                      />
                    </div>
                    <div class="col-lg-4">
                      <form @submit.prevent="filterOrder">
                        <div class="row">
                          <div class="col-lg-4">
                            <date-picker
                              autocomplete="off"
                              v-model="start_date"
                              placeholder="start-date"
                              :config="options"
                            ></date-picker>
                          </div>
                          <div class="col-lg-4" >
                            <date-picker
                              autocomplete="off"
                              v-model="end_date"
                              placeholder="end-date"
                              :config="options"
                            ></date-picker>
                          </div>
                          <div class="col-lg-4">
                           <select class="form-control" v-model="courier_id" style="width:120px;">
                             <option value="" selected disabled>Select Courier</option>
                             <option v-for="courier in couriers" :value="courier.id" :key="courier.id">{{courier.name}}</option>
                           </select>
                          </div>
                        </div>
                      </form>
                    </div>
                    <div class="col-lg-1">
                      <button
                        @click="resetAll"
                        style="margin-left: 45px"
                        type="button"
                        class="btn btn-primary btn-sm"
                      >
                        <i class="fa fa-refresh"></i>
                      </button>
                    </div>

                    <div class="col-lg-1">
                      <select
                        class="form-control"
                        v-model="item"
                        v-if="start_date.length > 0"
                        @change="filterOrder"
                       >
                        <option value="10">10</option>
                        <option value="20">20</option>
                            <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="150">150</option>
                        <option value="200">200</option>
                        <option value="250">250</option>
                        <option value="300">300</option>
                        <option value="350">350</option>
                        <option value="400">400</option>
                        <option value="500">500</option>
                      </select>
                       <select  class="form-control" v-model="item" v-else @change="ordersList">

                        <option value="10">10</option>
                        <option value="20">20</option>
                            <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="150">150</option>
                        <option value="200">200</option>
                        <option value="250">250</option>
                        <option value="300">300</option>
                        <option value="350">350</option>
                        <option value="400">400</option>
                        <option value="500">500</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <table class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th width="5%" >
                          <input type="checkbox" @click="selectAll" />
                        </th>
                        <th width="10%" >Customer</th>
                        <th width="15%" >Address</th>
                        <th width="15%" >Product</th>
                        <th width="5%" >Invoice</th>
                        <th width="15%" >Total</th>
                        <th width="10%" >Created</th>
                        <th width="5%" >Order_place</th>
                        <th width="5%" >Order_date</th>
                        <th width="5%" >Action</th>
                        <th width="10%" >Comment</th>
                      </tr>
                    </thead>
                     <tbody>
                      <h1 v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>

                      <tr
                        v-else
                        v-for="(order, index) in orders.data"
                        :key="index"
                      >
                        <td >
                          <input
                            type="checkbox"
                            class="select-all"
                            v-model="select_order_id"
                            :value="order.id"
                          />
                        </td>
                        <td>
                          {{ order.customer ? order.customer.name : "" }}
                          <br>
                           {{ order.cutomer_phone }}
                        </td>

                        <td>
                          {{ order.customer ? order.customer.address : "" }}
                        </td>
                        <td>
                            <div v-if="order.order_item.length > 0" >
                                <img v-if="order.order_item[0].product" style="width:65px" height="65px" :src="base_url+order.order_item[0].product.thumbnail_img"  >
                               <p v-if="order.order_item[0].product" > {{ order.order_item[0].product.name.substring(0,35).concat('...')+"-"+order.order_item[0].product.product_code }}  </p>
                            </div>
                       </td>
                        <td >{{ order.invoice_no }}</td>
                        <td >
                          <b>
                            <strong>
                              Total:
                              {{
                                parseInt(order.total) -
                                parseInt(order.discount) +
                                parseInt(order.shipping_cost)
                              }}
                            </strong>
                            <br>
                            <strong> Paid: {{ parseInt(order.paid) }} </strong>
                            <br>
                            <strong>
                              Due:
                              {{
                                parseInt(order.total) -
                                (parseInt(order.discount) +
                                  parseInt(order.paid)) +
                                parseInt(order.shipping_cost)
                              }}
                            </strong>
                          </b>
                        </td>
                        <td>
                          <p v-if="order.order_type == 1">customer</p>
                          <p v-if="order.order_type == 2">
                            Admin ||
                            <strong>{{ order.create_admin.name }}</strong>
                          </p>
                          <p v-if="order.order_type == 3">
                            Whole sale ||
                            <strong>{{ order.create_admin.name }}</strong>
                          </p>
                          <p v-if="order.order_type == 4">
                            Reseller
                            <strong v-if="order.reseller.username">{{
                              order.reseller.username
                            }}</strong>
                          </p>
                        </td>
                        <td >
                          <span class="badge" v-if="order.status == 1"
                            >New</span
                          >
                          <span class="badge" v-if="order.status == 2"
                            >Pending</span
                          >

                          <span
                            class="badge badge-success"
                            v-if="order.status == 3"
                            >Approved</span
                          >
                          <span
                            class="badge badge-success"
                            v-if="order.status == 4"
                            >Shipment</span
                          >
                          <span
                            class="badge badge-warning"
                            v-if="order.status == 5"
                            >Delivered</span
                          >
                          <span
                            class="badge badge-danger"
                            v-if="order.status == 6"
                            >Cancel</span
                          >
                          <span
                            class="badge badge-danger"
                            v-if="order.status == 7"
                            >Return</span
                          >
                        </td>
                        <td class="two-percent">{{ order.created_at }}</td>
                        <td>

                       <button @click="orderAction(order.id)" class="btn btn-success"> -- <i class="fa fa-bars"></i> -- </button>

                         <div :id="'order_action_'+order.id" class="action_container">

                          <button
                            class="btn btn-sm btn-success action-btn"
                            v-if="
                              order.status == 2 ||
                              order.status == 1 ||
                              order.status == 6
                            "
                            @click="approved(order, index)"
                          >
                            Approved
                          </button>
                          <button
                            class="btn btn-sm btn-info action-btn"
                            v-if="order.status == 1 || order.status == 7"
                            @click="pending(order, index)"
                          >
                            Pending
                          </button>
                          <button
                            class="btn btn-sm btn-success action-btn"
                            v-if="order.status == 4 && $can('Delivered Order')"
                            @click="delivered(order, index)"
                          >
                            Deliverd
                          </button>
                          <button
                            class="btn btn-sm btn-primary action-btn"
                            v-if="order.status == 3 && $can('Shipment Order')"
                            @click="shipment(order, index)"
                          >
                            Shipment
                          </button>
                          <button
                            class="btn btn-sm btn-danger action-btn"
                            v-if="
                              order.status == 1 ||
                              order.status == 2 ||
                              order.status == 3
                            "
                            @click="cancel(order, index)"
                          >
                            Cancel
                          </button>
                          <button
                            class="btn btn-sm btn-warning action-btn"
                            v-if="order.status == 4 && $can('Return  Order')"
                            @click="returnOrder(order, index)"
                          >
                            Return
                          </button>

                          <router-link
                            class="btn btn-sm btn-warning"
                            style="width:70px;"
                            :to="{
                              name: 'orderEdit',
                              params: { id: order.id },
                            }"
                            v-if="order.status != 5 && order.status != 4"
                            >Edit</router-link
                          >

                          <router-link
                            class="btn btn-sm btn-primary action-btn"
                            style="color: #fff"
                            :to="{
                              name: 'viewOrder',
                              params: { id: order.id },
                            }"
                            >View</router-link
                          >

                         </div>

                        </td>

                        <td>
                          <small v-if="order.comment">{{
                            order.comment
                          }}</small>
                          <a
                            href="#"
                            @click="comment(order.id, index, order.comment)"
                            >CO</a
                          >
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-lg-6">


                      <pagination
                        :data="orders"
                        @pagination-change-page="ordersList"
                        :limit="5"
                      ></pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing
                        <strong>{{ orders.from }}</strong> to
                        <strong>{{ orders.to }}</strong> of total
                        <strong>{{ orders.total }}</strong> entries
                      </p>
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
   this.ordersList();
  },
  data() {
    return {
      orders: {},
      loading: true,
      item: 30,
      courier: {
        order_id: "",
        courier_id: "",
        memo_no: "",
        order_index: "",
      },
      couriers: "",
      comments:'',
      search: "",
      start_date: "",
      end_date: "",

      //date picker options ..........
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      status: 1,
      type: "all",
      page: 1,
      selected: false,

      //for biblk action
      select_order_id: [],
      bulk_status: "all",
      //heading in table
      heading: "New Orders",
      bulkActionType:"0",
      //for filtaring order
      courier_id:'',
      order_count:'',
      base_url: this.$store.state.thumbnail_img_base_link,
    };
  },
  methods: {

   orderAction(id){
      console.log(id);
      document.getElementById('order_action_'+id).classList.toggle('toggle_order_action');

   },

   getOrderStatistic(){
     axios.get('/api/get/order/statistic')
     .then((resp)=>{
          this.order_count = resp.data.order_count ;
     })
  },

    //get order list
    ordersList(page = 1) {
      //start progress bar
      this.$Progress.start();
      axios
        .get("/orders?page=" + page, {
          params: {
            //send data
            status: this.status,
            item: this.item,
            type: this.type,
            start_date:this.start_date,
            end_date:this.end_date,
            courier_id:this.courier_id,
          },
        })
        .then((resp) => {
          console.log(resp);
          // console.log(resp);
          //finish progress bar after resp
          this.$Progress.finish();
          //only success resp
          if (resp.data.status == "SUCCESS") {
            this.orders = resp.data.orders;
            this.loading = false;
            this.page = this.page + 1;
            this.order_count = resp.data.order_count;
            this.couriers = resp.data.couriers;
            this.comments = resp.data.comments;
            this.loading=false;
          }

          //else show a error
          else {
            this.$toasted.show("something went  to wrong", {
              type: "error",
              position: "top-center",
              duration: 5000,
            });
          }
        })
        .catch((error) => {
          //finish progress bar after resp
          this.$Progress.finish();
          this.$toasted.show("something went  to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },


    //initial method for order approved
    approved(order, index) {
      /////index initial for update order from orderLit or order arrow.

      //start progress bar
      this.$Progress.start();
      axios
        .get("/approved/order/" + order.id)
        .then((resp) => {
          //end progress bar after resp
          this.$Progress.finish();

          //if resp success then....
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            this.orders.data[index].status = 3;
            this.getOrderStatistic();
          }
          //not resp success.....
          else {
            this.$toasted.show("something went  to wrong", {
              type: "error",
              position: "top-center",
              duration: 2000,
            });
          }
        })
        .catch((error) => {
          //end progress bar after resp
          this.$toasted.show("something went  to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },

    //initial method for order cancel

    cancel(order, index) {
      /////index initial for update order from orderLit or order arrow.

      //start progress bar
      this.$Progress.start();
      axios
        .get("/cancel/order/" + order.id)
        .then((resp) => {
          //end progress bar after resp
          this.$Progress.finish();

          //only success resp .......
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            this.orders.data[index].status = 6;
             this.getOrderStatistic();
          }
          //for any kind of error resp .......
          else {
            this.$toasted.show("something went  to wrong", {
              type: "error",
              position: "top-center",
              duration: 2000,
            });
          }
        })
        .catch((error) => {
          //end progress bar after resp
          this.$toasted.show("something went  to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },

    //initial method for order return

    returnOrder(order, index) {
      /////index initial for update order from orderLit or order arrow.

      //start progress bar
      this.$Progress.start();
      axios
        .get("/return/order/" + order.id)
        .then((resp) => {
          console.log(resp);
          //end progress bar after resp
          this.$Progress.finish();

          //only success resp .......
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            this.orders.data[index].status = 7;
             this.getOrderStatistic();
          }
          //for any kind off error resp
          else {
            this.$toasted.show("something went  to wrong", {
              type: "error",
              position: "top-center",
              duration: 2000,
            });
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show("something went  to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },

   delivered(order, index) {
      if (confirm("Are you sure delivered this order?")) {
        Swal.fire({
          title: "CHECK IT",
          html: `

            <div class="form-group">
              <label>Credit Amount</label>
              <input class="form-control" readonly value="${
                parseInt(order.total) -
                parseInt(order.paid) -
                parseInt(order.discount) +
                parseInt(order.shipping_cost)
              }">
            </div>

              <div class="form-group">
              <label>Credit In</label>
              <select class="form-control" id="credit_id">
               <option value="Cash">Cash</option>
                      <option value="Bkash(personal)">Bkash(personal)</option>
                      <option value="Bkash(merchant)">Bkash(merchant)</option>
                       <option value="Bank(SIBL)">Bank(SIBL)</option>
                       <option value="Bank(AIBL)">Bank(AIBL)</option>
              </select>
            </div>

        `,
        }).then((result) => {
          if (result.value) {
            let credit_in = document.getElementById("credit_id").value;
            // console.log(credit_in)
           this.$Progress.start();
            axios
              .get("/delivered/order/" + order.id, {
                params: {
                  credit_in,
                },
              })
              .then((resp) => {
                console.log(resp);
                //end progress bar after resp
                this.$Progress.finish();

                //only success resp .......
                if (resp.data.status == "SUCCESS") {
                  this.$toasted.show(resp.data.message, {
                    type: "success",
                    position: "top-center",
                    duration: 2000,
                  });
                  location.reload();
                }
                //any kind of error resp
                else {
                  this.$toasted.show(resp.data, {
                    type: "error",
                    position: "top-center",
                    duration: 2000,
                  });
                }
              })
              .catch((error) => {
                console.log(error);
                this.$toasted.show("something went  to wrong", {
                  type: "error",
                  position: "top-center",
                  duration: 4000,
                });
              });

            console.log(credit_in);
          } else {
            console.log("jhgj");
          }
        });

        console.log(order);
        ///index initial for update order from orderLit or order arrow.

        //start progress bar
      }
    },

    shipment(order, index) {
      /////index initial for update order from orderLit or order arrow.

      //start progress bar
      this.$Progress.start();
      axios
        .get("/shipment/order/" + order.id)
        .then((resp) => {
          console.log(resp);
          //end progress bar after resp
          this.$Progress.finish();

          //only success resp .......
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            this.orders.data[index].status = 4;
             this.getOrderStatistic();
          }
          //any kind of error resp
          else {
            this.$Progress.finish();

            this.$toasted.show("something went  to wrong", {
              type: "error",
              position: "top-center",
              duration: 2000,
            });
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show("something went  to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },

    pending(order, index) {
      /////index initial for update order from orderLit or order arrow.

      //start progress bar
      this.$Progress.start();
      axios
        .get("/pending/order/" + order.id)
        .then((resp) => {
          console.log(resp);
          //end progress bar after resp
          this.$Progress.finish();

          //only success resp .......
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            this.orders.data[index].status = 2;
             this.getOrderStatistic();
          }
          //any kibd off error resp
          else {
            this.$Progress.finish();

            this.$toasted.show("something went  to wrong", {
              type: "error",
              position: "top-center",
              duration: 2000,
            });
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show("something went  to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },

    //method initial for order search
    orderSearch(page = 1) {
      //if search lenght minimum 2
      if (this.search.length > 1) {
        //show loader
        this.loading = true;

        //fetch data
        axios
          .get("/order/search/" + this.search + "?page=" + page)

          .then((resp) => {
            //if success resp
            if (resp.data.status == "SUCCESS") {
              this.orders = resp.data.orders;
              this.loading = false;
            }
          })
          //for any kind of error
          .catch((error) => {
            console.log(error);
            alert("something went  wrong");
          });
      }
      //if search lenght smaller then 2, then excute orderist method .......
      else {
        this.loading = false;
        this.ordersList();
      }
    },

    //method initial for filter order, data to date, and single data......
    filterOrder(page = 1) {
      //start progressbar
      this.$Progress.start();
      this.loading = true;
      axios
        .get("/order/filter?page=" + page, {
          //send data
          params: {
            start_date: this.start_date,
            end_date: this.end_date,
            item:this.item,
            status:this.status
          },
        })
        .then((resp) => {
          //only success resp ........
          this.$Progress.finish();
          this.loading = false;
          if (resp.data.status == "SUCCESS") {
            this.orders = resp.data.orders;
            this.order_count = resp.data.order_count;
            this.couriers = resp.data.couriers;
            this.comments = resp.data.comments;
            this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    //method initial for rest all data, or order arrow
    resetAll() {
     location.reload();
    },

    //method inital for select all
    selectAll() {
      //first idnetify select true or false
      //we need a tooglee all select box

      //if select true we make selected false, or select true
      if (this.selected == true) {
        this.selected = false;
      } else {
        this.selected = true;
      }

      //elemnt find by the class name
      let checkBoxClass = document.getElementsByClassName("select-all");

      for (let i = 0; i < checkBoxClass.length; i++) {
        //if select active then element set attribute check==true
        //element set attribute check==false
        if (this.selected == true) {
          checkBoxClass[i].checked = true;
        } else {
          checkBoxClass[i].checked = false;
        }
      }

      //at last push order id in selected_order_id arrow....
      //and agin check selected true or false.....
      if (this.selected == true) {
        for (let i = 0; i < this.orders.data.length; i++) {
          this.select_order_id.push(this.orders.data[i].id);
        }
      } else {
        this.select_order_id = [];
      }
    },

    //method inital for a bulk action
    selectBulkAction() {
     if(this.select_order_id.length<=0){
       Swal.fire({
         type:'warning',
         text:'Please select at least one row'
       })
       return ;

     }
     let action_type=this.bulkActionType;
      if(action_type=='LABEL PRINT'){
          window.open('/order/label/print/'+this.select_order_id,'_blank')
      }
       if(action_type=='INVOICE PRINT'){
          window.open('/order/invoice/print/'+this.select_order_id,'_blank')
      }

     if(action_type=='PENDING ALL'){
        if(confirm("are you sure")){
           this.pendingAll(this.select_order_id);
        }
        return;
     }

      if(action_type=="APPROVED ALL"){
         if(confirm("are you sure")){
         this.apprvedAll(this.select_order_id);
        }
        return;
      }



     if(action_type=='CANCEL ALL'){
        if(confirm("are you sure")){
           this.cancelAll(this.select_order_id);
        }
        return;
     }

   },

    labelPrint(){
      window.open('','_self',"width=600,height=600");
    },

  apprvedAll(order_id){
    axios.get('/approved/all/order/'+order_id)
         .then(resp=>{
            if(resp.data){

              this.$toasted.show(resp.data, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            this.$router.push({name:"ApprovedOrder"})
            }
         })
         .catch(error=>{
           console.log(e);
         })
  },
   pendingAll(order_id){
    axios.get('/pending/all/order/'+order_id)
         .then(resp=>{
            if(resp.data){
               this.$toasted.show(resp.data, {
              type: "success",
              position: "top-center",
              duration: 2000,
              });
            this.$router.push({name:"PendingOrder"})

            }
         })

  },



   cancelAll(order_id){
    axios.get('/cancel/all/order/'+order_id)
         .then(resp=>{
            if(resp.data){
               this.$toasted.show(resp.data, {
              type: "success",
              position: "top-center",
              duration: 2000,
              });
               this.$router.push({name:"CancelOrder"})
            }
         })

  },
    comment(order_id, order_index, comment) {
      console.log(comment);
      let options = {};
      this.comments.forEach((element) => {
        options[element.name] = element.name;
      });

      let sSelect = document.getElementsByClassName("swal2-select");
      Swal.fire({
        title: comment ? comment : "Select a comment",
        input: "select",
        inputOptions: options,
        inputPlaceholder: "Select or change a comment",
        showCancelButton: true,
      }).then((result) => {
        if (result.value == "others") {
          Swal.fire({
            input: "text",
          }).then((other) => {
            if (other.value) {
              axios
                .get("/api/order/comment", {
                  params: {
                    order_id,
                    comment: other.value,
                  },
                })
                .then((resp) => {
                  console.log(resp);
                  if (resp.data.status == "OK") {
                    location.reload();
                    this.$toasted.show(resp.data.message, {
                      type: "success",
                      duration: 4000,
                      position: "top-center",
                    });
                  }
                })

            }
          });
        }

        if (result.value) {
          axios
            .get("/api/order/comment", {
              params: {
                order_id,
                comment: result.value,
              },
            })
            .then((resp) => {
              console.log(resp);
            })
            .catch((e) => {
              console.log(e);
            });
        } else {
          console.log("Ok");
        }
      });
    },




  },

  watch: {
    status: function (value) {
      if (value == 1) {
        this.heading = "New Orders";
      } else if (value == 2) {
        this.heading = "Pending Orders";
      } else if (value == 3) {
        this.heading = "Approved Orders";
      } else if (value == 4) {
        this.heading = "Shipment Orders";
      } else if (value == 5) {
        this.heading = "Delivered Orders";
      } else if (value == 7) {
        this.heading = "Return Orders";
      } else if (value == 6) {
        this.heading = "Cancel Orders";
      } else {
        this.heading = "All Orders";
      }
    },

    start_date:function(value){
        if(value.length>1){
          this.ordersList();
        }
    },
    end_date:function(value){
      if(value.length>1){
          this.ordersList();
        }
    },
     bulkActionType:function(value){
      this.selectBulkAction();
    },
    courier_id:function(value){
      this.ordersList();
    }

  },


};
</script>

<style>
.orders-heading {
  text-align: center;
  text-transform: uppercase;
  border-bottom: 2px solid #000;
  margin-bottom: 10px;
}
</style>
