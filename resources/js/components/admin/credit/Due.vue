<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'creditAdd' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          ></router-link>

          <router-link :to="{ name: 'debit' }" class="btn btn-info"
            >Debit</router-link
          >

         <button @click="exportDueList" class="btn btn-success"> <i class="fa fa-download"></i> Export Due List</button>

        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Credit</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-11 col-md-11 col-sm-11">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row">
                    <div class="col-lg-4">
                      <h3 class="box-title">Due Table</h3>
                    </div>
                    <div class="col-lg-4"></div>
                    <div class="col-lg-4">
                      <input
                        type="text"
                        v-model="search"
                        @keyup="searchDueList"
                        class="form-control"
                        placeholder="Search Here"
                      />
                    </div>

                  </div>
                </div>
                <div class="box-body">
                  <table class="table table-bordered table-striped table-hover ">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th>Date</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Mobile No</th>
                        <th>Memo Number</th>

                        <th scope="col">Amount</th>

                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-for="(due, index) in credit_dues.data" :key="index">
                        <td scope="row">{{ index + 1 }}</td>
                        <td>{{ due.created_at }}</td>

                        <td>{{ due.customer_name }}</td>
                        <td>{{ due.customer_mobile_no }}</td>
                        <td>
                          <router-link
                            :to="{
                              name: 'ViewSale',
                              params: { id: due.memo_no },
                            }"
                          >
                            {{ "S-"+due.sale_id }}
                          </router-link>
                        </td>

                        <td  >
                            <button style="width:60px;font-size:14px;" class="btn btn-danger btn-xs" @click="displayPaymentDetails(due)" > {{ due.amount }} </button>
                          </td>
                        <!-- <td><strong v-if="due.order_invoice_no">{{ due.order_invoice_no }}</strong></td> -->

                        <td>
                          <a
                            id="paid"
                            style="width:60px;font-size:13px;"
                            class="btn btn-xs btn-success"
                            @click="getPaid(due.id, index)"
                            >Get Paid</a
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
                        :data="credit_dues"
                        @pagination-change-page="dueList"
                      >
                      </pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing <strong>{{ credit_dues.from }}</strong> to
                        <strong>{{ credit_dues.to }}</strong> of total
                        <strong>{{ credit_dues.total }}</strong> entries
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


    <!-- start payment store modal  -->
     <modal name="account_details_modal" :width="400" :height="300" >
        <div class="card" style="padding: 20px;">
          <div class="card-header text-center">
               <h4 class="heading">  {{ customer_name }} </h4>
               <h4 class="heading">  {{ customer_phone }} </h4>
          </div>
        <div  class="card-body">
           <table class="table table-hover table-striped text-center table-bordered " >
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Amount</th>
                      </tr>
                   <tbody>
                        <tr v-for="(item, index) in paid_history" :key="index">
                          <td>{{ index + 1 }}</td>
                          <td>{{ item.created_at }}</td>
                          <td>{{ item.amount }}</td>
                        </tr>
                 </tbody>
               </table>
            </div>

        </div>
    </modal>
    <!-- end payment store modal  -->

  </div>
</template>

<script>
export default {
  created() {
    setTimeout(() => {
      this.dueList();
    }, 100);
    // this.getResults();
  },
  data() {
    return {
      credit_dues: {},
      loading: true,
      current_date: "",
      item: 20,
      search: "",
      status: "all",
      paid_history:"",
      customer_phone:"",
      customer_name:"",
      //for date picker
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      start_date: "",
      end_date: "",
      paid_by: "",
    };
  },
  methods: {
   displayPaymentDetails(due){
       this.customer_name= due.customer_name ;
       this.customer_phone= due.customer_mobile_no ;
       axios.get('/api/get/due/customer/payment/history/'+due.customer_mobile_no)
       .then(resp => {
         this.paid_history = resp.data.payments ;
       })
       this.$modal.show('account_details_modal');
    },

   exportDueList(){
        window.open('/api/export/due/customer','_blank');
    },

    dueList(page = 1) {
      this.loading = true;
      axios
        .get("/api/credit/due?page=" + page, {
          params: {
            item: this.item,
          },
        })
        .then((resp) => {
          console.log(resp);
          this.credit_dues = resp.data;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    searchDueList() {
      if (this.search.length > 3) {
        axios.get("/api/due/search/" + this.search).then((resp) => {
          if (resp.data.length>0) {
            console.log(resp.data)
            this.credit_dues = resp.data;
          }else{
            this.dueList();
          }
        });
      }
    },
      getPaid(id, index) {
      axios
        .get("/api/balance/list")
        .then((resp) => {
          console.log(resp)
          let options = {};
          resp.data.balance.forEach((element) => {
            options[element.name] = element.name ;
          });
          Swal.fire({
            title: "Select a balance",
            input: "select",
            inputOptions: options,
            inputPlaceholder: "Select One",
            showCancelButton: true,
          }).then((result) => {

            if (result.value) {
              this.paid_by= result.value;

               Swal.fire({
        // title:'Get  Paid',
        html: `
          <label>Amount</label>
          <input id="amount" value=${this.credit_dues.data[index].amount} class="swal2-input">
          `,
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          let amount = document.getElementById("amount").value;
          let paid_by = this.paid_by
          if (amount > 0 && amount.length > 0 && paid_by.length > 0) {
            //send a request
            axios
              .get("/api/due/to/paid/" + id, {
                params: {
                  paid_by: paid_by,
                  amount: amount,
                },
              })
              .then((resp) => {
                console.log(resp);
                //if resp success from serve
                if (resp.data.status == "SUCCESS") {
                  Swal.fire({
                    type: "info",
                    text: resp.data.message,
                  });
                  //if amount full paid
                  //remove form dom
                  //else update amount
                  if (resp.data.due.status == 1) {
                    this.credit_dues.data.splice(index, 1);
                  } else {
                    console.log(this.credit_dues.data[index].amount);
                    this.credit_dues.data[index].amount = resp.data.due.amount;
                  }
                } else {
                  Swal.fire({
                    type: "warning",
                    text: resp.data.message,
                  });
                }
              })

          }
        }
        console.log(result);
      });

            } else {
              this.paid_by = "";

            }
          });
        })


    },

    clearAll() {
      this.$Progress.start();
      this.status = "all";
      (this.search = ""),
        (this.start_date = ""),
        (this.end_date = ""),
        this.dueList();
      this.$Progress.finish();
    },
  },
};
</script>

<style scoped>
label {
  position: absolute;
  text-align: left;
  margin-bottom: 0px;
}
</style>
