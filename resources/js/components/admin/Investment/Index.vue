<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'investment_add' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Investors</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-11">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading">Investment Table</h3>
                </div>
                <div class="box-body">
                  <div class="row total_row">
                     <div class="col-md-4">

                    <a href="/api/download/all/investment/pdf" target="_blank" class="btn btn-success"> <i class="fa fa-download"> </i> Export PDF </a>

                     <button @click="activeList" :class="{active_border: status == 1}" class="btn btn-success">
                                    Active
                      </button>

                      <button @click="deActiveList" :class="{active_border: status == 0}"  class="btn btn-warning">
                              DeActive
                      </button>
                     </div>
                    <div class="col-md-4"> <h4> Total Investment : <b class="total_style"  style="color:green"> {{ total_investment }} </b> </h4> </div>
                    <div class="col-md-4"> <h4> Total Profit Paid : <b  class="total_style" style="color:green"> {{ total_profit_paid  }}</b> </h4> </div>

                  </div>
                  <table class="table text-center table-bordered table-hover table-striped ">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Referance Name</th>
                        <th scope="col">Mobile Number</th>
                        <th scope="col">Address</th>
                        <th scope="col">Invested Amount</th>
                        <th scope="col">Profit Margin</th>
                        <th scope="col">Profit Paid Amount</th>
                        <th scope="col"> Action </th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-for="(items, index) in investment.data"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                          <router-link
                            :to="{
                              name: 'investor_details',
                              params: { id: items.id },
                            }"
                            >{{ items.name }}
                          </router-link>
                        </td>
                        <td>{{ items.referance_name }}</td>
                        <td>{{ items.mobile_no }}</td>

                        <td>{{ items.address }}</td>

                        <td>{{ items.invest_amount }}</td>
                        <td>{{ items.profit_margin }}</td>
                        <td>{{ items.profit_paid_amount  }}</td>
                        <td> <router-link class="btn btn-xs btn-success" :to="{name:'investment_edit',params:{id:items.id}}"> <i class="fa fa-edit"></i> </router-link> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-lg-6">
                      <pagination
                        :data="investment"
                        @pagination-change-page="getinvestments"
                      >
                      </pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing <strong>{{ investment.from }}</strong> to
                        <strong>{{ investment.to }}</strong> of total
                        <strong>{{ investment.total }}</strong> entries
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
    this.getinvestments();
  },
  data() {
    return {
      investment: {},
      loading: true,
      basePath: this.$store.getters.image_base_link,
      item: "",
      search: "",
      total_investment: "",
      total_profit_paid: "",
      total_due_amount: 0,
      status: 1,
    };
  },
  methods: {


    activeList(){
        this.status=1 ;
        this.getinvestments();
    },

    deActiveList(){
        this.status=0 ;
        this.getinvestments();
    },



    getinvestments(page = 1) {
      axios
        .get("/api/company/investor?page=" + page,{
            params : {
              status : this.status ,
            }
        } )
        .then((resp) => {
          if (resp.data.success == "OK") {
             this.investment = resp.data.investment;
             this.total_investment = resp.data.total_investment;
             this.total_profit_paid = resp.data.total_profit_paid;
            // this.total_due_amount=resp.data.due_amount;
            this.loading = false;
          }
        })

    },
    totalAmount(investment) {
      let total = 0;
      Object.keys(investment).forEach(function (key) {
        console.log(key);
      });
      return total;
    },
  },

};
</script>

<style scoped>


.total_style {
  border: 2px solid #ddd;
  padding: 4px 25px;
}
.total_row {
  padding-bottom: 20px;
}

.active_border{
  border-bottom: 2px solid #ff4d03;
}


</style>
