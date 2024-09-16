<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="goBack" class="btn btn-success">
            <i class="fa fa-arrow-left"></i>
          </a>

        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>dollar</a>
          </li>
          <li class="active">details</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row ">
        

            <div @click="filter(0)" class="col-md-3 total_container">
              <h4> Pending  {{  total_pending_request }} </h4>
            </div>

            
            <div @click="filter(1)" class="col-md-3 total_container">
              <h4> Processing  {{ total_processing_request}} </h4>
            </div>

            <div @click="filter(2)" class="col-md-3 total_container">
              <h4> Completed  {{ total_completed_request}} </h4>
            </div>

            <div @click="filter(3)"  class="col-md-3 total_container">
                <h4> Rejected {{ total_rejected_request }} </h4>
            </div>

        
          </div>
            
                
          <div class="row data_table_row">
            <div class="col-md-12 col-sm-12">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h4>Dollar Request</h4>
    
                </div>

                <div>
                  <div class="box-body">

                    <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>

                    <table v-else
                      class="table table-hover table-striped table-centered table-bordered"
                    >
                     <thead>
                      <tr>
                        <th width="4%">#</th>
                        <th width="11%">Date</th>
                        <th width="15%">Account</th>
                        <th width="10%">Dollar Rate</th>
                        <th width="10%">Dollar</th>
                        <th width="10%">Amount</th>
                        <th width="10%">Attachment</th>
                        <th width="15%">Status</th>
                        <th width="10%">Action</th>
                      
                      </tr>
                     </thead>
                      <tbody>
                        <tr
                          v-for="(item, index) in dollar_transactions.data"
                          :key="index"
                        >
                          <td>{{ index + 1 }}</td>
                          <td>{{ item.created_at }}</td>
                          <td>
                            <p>{{ item.account.name }}</p>
            
                          </td>

                          <td>{{ item.rate }}</td>
                          <td>{{ item.dollar }}</td>
                          <td>{{ item.amount }}</td>
                     
                          <td>
                              <a download :href="base_link + item.image "> <i style="font-size: 16px;color: green;" class="fa fa-file"></i> </a>
                          </td>
                          <td>
                            <span v-if="item.status == 0" class="badge badge-warning">pending</span>
                            <span v-if="item.status == 1" class="badge badge-primary">processing</span>
                            <span v-if="item.status == 2" class="badge badge-success">completed</span>
                            <span v-if="item.status == 3" class="badge badge-danger">rejected</span>
                          </td>

                          <td>
                            <button  v-if="item.status !=2 "
                            @click="actionBtn(item.id)"
                            class="btn btn-xs btn-success"
                          >
                            -- <i class="fa fa-bars"></i> --
                          </button>
                       

                          <div style="margin-top:5px" 
                            :id="'boost_action_' + item.id"
                            class="action_container"
                          >
                          
                            <button v-if="item.status == 0 || item.status == 3"
                              class="btn btn-xs btn-primary "
                              @click="
                                changeStatus(
                                  item.id,
                                  1,index
                                )
                              "
                            >
                              Approved
                            </button>

                           

                            <button v-if="item.status == 1"
                              class="btn btn-xs btn-success"
                              @click="
                                changeStatus(
                                  item.id,
                                  2,index
                                )
                              "
                            >
                              Completed 
                            </button>
                            
                            <button style="margin-top:5px" v-if="item.status == 0"
                              class="btn btn-xs btn-danger"
                              @click="
                                changeStatus(
                                  item.id,
                                  3,index
                                )
                              "
                            >
                              Rejected  
                            </button>
                       
                        
                          </div>
                          </td>
                  
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="box-footer">
                  <div class="row">
                    <div class="col-lg-6">
                      <pagination
                        :data="dollar_transactions"
                        @pagination-change-page="transactionDetails"
                      ></pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing <strong>{{ dollar_transactions.from }}</strong> to
                        <strong>{{ dollar_transactions.to }}</strong> of total
                        <strong>{{ dollar_transactions.total }}</strong> entries
                      </p>
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
    this.transactionDetails();
  },
  data() {
    return {
      base_link : this.$store.getters.image_base_link,
      dollar_transactions: {},
      loading: true,
      dollar_edit_item_id: null,
      status: "",
      total_pending_request: 0,
      total_rejected_request: 0,
      total_completed_request: 0,
      total_processing_request: 0,
    };
  },
  methods: {

    transactionDetails(page=1) {
      axios
        .get(
          "/api/boost/agency/reseller/dollar/transactions?page=" + page,{
              params:{
                status: this.status,
              }
          }
            
        )
        .then((resp) => {
          console.log(resp);
          this.dollar_transactions = resp.data.dollar_transactions;
          this.total_pending_request = resp.data.total_pending ;
          this.total_processing_request = resp.data.total_processing ;
          this.total_completed_request = resp.data.total_completed ;
          this.total_rejected_request = resp.data.total_rejected ;
          this.loading = false;
        });
    },

    actionBtn(id) {
      document
        .getElementById("boost_action_" + id)
        .classList.toggle("toggle_order_action");
    },


    changeStatus(id,status,index) {
      axios
        .get("/api/change/dollar/transaction/status/"+id+'/'+status)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status) {
            this.$toastr.s(resp.data.message);
            this.dollar_transactions.data[index].status = status ;
          } 
        })
        .catch((error) => {
          this.$toastr.e(error.response.data.message);
        });
    },


    filter(status){
      this.loading = true ;
      this.status = status ; 
      this.transactionDetails();
    },

    goBack() {
      window.history.back();
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

.filtering_row{

  margin-bottom: 15px;

}

.total_container {
    min-height: 60px;
    box-shadow: 1px 1px 2px 1px #ddd;
    margin-bottom: 15px;
    background: white;
    width: 23.6%;
    margin-left: 1%;
    cursor: pointer;
}

.total_container>h4{
  margin-top:17px;
}

.account_container {
  height: 150px;
  margin-bottom: 20px;
  overflow-y: auto;
}
.btn_account {
  padding: 3px 20px;
  border: none;
  min-width: 225px;
  background: #eee;
}

.btn_account:hover {
  transition: 3s;
  background: blueviolet;
  color: #fff;
}

.active_border {
  color: #000;
  border-bottom: 2px dashed #000;
}

.btn_conditional {
  box-shadow: 0 1pt 12pt rgb(150 165 237);
  font-size: 16px;
  font-weight: bold;
  font-family: serif;
  margin: 0px 10px;
}
.vm--modal {
  position: relative;
  overflow: hidden;
  box-sizing: border-box;
  background-color: white;
  border-radius: 3px;
  box-shadow: 0 20px 60px -2px rgba(27, 33, 58, 0.4);
  overflow-y: scroll !important;
}
</style>
