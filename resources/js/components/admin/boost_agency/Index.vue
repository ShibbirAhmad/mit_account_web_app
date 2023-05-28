<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'boost_agency_add' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          >Add Agency</router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">boost </li>
        </ol>
      </section>
      <section class="content">
        <div class="container-fluid">
          <div class="row ">
            <div class="col-lg-11 col-md-11">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading">Boost Agency</h3>
                </div>
                <div class="box-body">

                  <table class="table text-center table-centered table-striped  table-bordered table-hover">
                    <thead>
                      <tr>
                        <th >#</th>
                        <th > Supplier Company </th>
                        <th > Phone </th>
                        <th > Dollar Rate </th>
                        <th > Ads Account Users </th>
                       
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(item, index) in boost_agency"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                       
                        <td>
                          <router-link style="color:blue;font-size:16px"
                            :to="{
                              name: 'boost_agency_details',
                              params: { id: item.id },
                            }"
                            >{{ item.name }}
                          </router-link>

                          </td>
                          <td>
                             {{  item.phone }}
                          </td>
                          <td>
                            BDT {{  item.rate }}
                          </td>
              
                          <td>
                            <router-link class="btn btn-success btn-xs" 
                            :to="{
                              name: 'boost_agency_reselllers',
                              params: { id: item.id },
                            }"
                            >
                            <i class="fa fa-users"></i>   </router-link>  </td>
                  

                  

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
       <!-- start payment store modal  -->
         <modal name="paid" :width="400" :height="350">
           <form @submit.prevent="BoostAgencyPayment">
              <div class="card" style="padding: 20px">
                 <div class="card-header text-center">
                    <h4 style="font-weight:bold" class="card-title"> pay payment of  {{ agency_name }}</h4>
                </div>
              <div class="card-body">
                <div class="form-group">
                  <label>Amount</label>
                  <input
                    type="number"
                    name="amount"
                    v-model="payment_paid_form.amount"
                    :class="{ 'is-invalid': payment_paid_form.errors.has('amount') }"
                    class="form-control"
                    placeholder="0"
                  />
                  <has-error :form="payment_paid_form" field="amount" > </has-error>
                </div>

                <div class="form-group">
                  <label for="paid_by">Paid By</label>
                    <select required class="form-control" v-model="payment_paid_form.paid_by">
                      <option value="" disabled selected>Select Balance</option>
                      <option  v-for="(balance,index) in balance" :value="balance.id" :key="index">{{balance.name}}</option>
                    </select>
                </div>

                <div class="form-group">
                  <label for="comment">comment</label>
                    <input
                    type="text"
                    name="comment"
                    v-model="payment_paid_form.comment"
                    class="form-control"
                    placeholder="boost reseller payment"
                  />
                </div>

                <br>
                <div class="form-group text-center">
                  <button  type="submit"
                    class="btn btn-success ">
                    Submit
                  </button>
                </div>
                <br>
              </div>
            </div>
           </form>
          </modal>
   <!-- end payment store modal  -->
  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
export default {
  created() {
    this.getBoostAgencys();
    this.balanceList();
  },
  data() {
    return {
      boost_agency:"",
      agency_name:"",
      total_ad_accounts:"",
      loading: true,
      basePath: this.$store.getters.image_base_link,
      total_investment: "",
      total_profit_paid: "",
      total_due_amount: 0,
      payment_paid_form: new Form({
      boost_agency_id: "",
      paid_by: "",
      amount: "",
      comment: "",
      }),
      balance:"",
    };
  },
  methods: {
      balanceList() {
      axios
        .get("/api/balance/list/boost")
        .then((resp) => {
            this.balance = resp.data.balance;
        })
    },

    getBoostAgencys() {
      axios
        .get("/api/boost/agency/list")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.boost_agency = resp.data.agency;
             this.total_ad_accounts = resp.data.total_ad_accounts;
             this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },


  formatToCurrency(amount){
    return amount.toFixed(2);
  }

  },
  computed: {},
  components:{
    HasError,
  }
};
</script>

<style scoped>

.badge {
  font-size:14px !important ;
  padding: 6px 15px !important;
}
.total_style {
  border: 2px solid #ddd;
  padding: 4px 25px;
}
.total_row {
  padding-bottom: 20px;
}
</style>
