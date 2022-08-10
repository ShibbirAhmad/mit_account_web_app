
<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'boost_agency' }" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Boost</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row  over_view_row text-center">
                <h4 class="heading"> {{ boost_agency.name }} </h4>
                <h4 class="heading"> {{ boost_agency.email }} </h4>
          </div>
          <div class="row data_table_row">
            <div class="col-md-12 col-sm-12">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title"> Payment History </h3>
                </div>

                <div class="box-body">
                  <table class="table table-striped text-center table-bordered">
                    <tr>
                      <th>#</th>
                      <th width="20%" >Date</th>
                      <th width="40%">Comment</th>
                      <th>Paid By</th>
                      <th>Amount</th>
                    </tr>
                    <tbody>
                      <tr v-for="(item, index) in payments.data" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ item.created_at }}</td>
                        <td>{{ item.comment }}</td>
                        <td>{{ item.paid_by }}</td>
                        <td>{{ item.amount }}</td>
                      </tr>
                      <tr>
                        <td colspan="4">In Total</td>
                        <td> <span class="badge badge-success">{{ payment_total }}</span> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                   <div class="box-footer">
                  <div class="row">
                    <div class="col-sm-12">
                      <pagination
                        :data="payments"
                        @pagination-change-page="paymentDetails"
                        :limit="3"
                      >
                      </pagination>
                    </div>
                    <div class="col-sm-12 ">
                      <p>
                        Showing <strong>{{ payments.from }}</strong> to
                        <strong>{{ payments.to }}</strong> of total
                        <strong>{{ payments.total }}</strong> entries
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
    this.paymentDetails();
  },
  data() {
    return {
      boost_agency: "",
      loading: true,
      payments:{},
      payment_total: "",
      date: "",
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
    };
  },
  methods: {
    paymentDetails(page=1) {
      axios
        .get("/api/boost/agency/payment/details/"+this.$route.params.id+'?page='+page)
        .then((resp) => {
          console.log(resp);
          this.boost_agency = resp.data.boost_agency;
          this.payments = resp.data.payments;
          this.payment_total = resp.data.payment_total;

        })
        .catch((error) => {
          console.log(error);
        });
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

.data_table_row{
  width: 90%;
  overflow-x: scroll;
}

.over_view_row{
    width: 92%;
    height: 70px;
    margin-left: 0%;
    box-shadow: 1px 1px 2px 1px #ddd;
    margin-bottom: 20px;

}

</style>
