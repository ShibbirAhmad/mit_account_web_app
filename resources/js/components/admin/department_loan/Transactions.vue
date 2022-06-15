<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'department_loan' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          >back </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">transactins</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-6 col-md-6">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading"> {{ department.name }}  Loan Transactions  </h3>
                </div>
                <div class="box-body">

                  <table class="table table-hover table-bordered  table-striped ">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Taken Departments</th>
                        <th scope="col">  balance To </th>
                        <th scope="col">  balance From </th>
                        <th scope="col">  Amount</th>
                        <th scope="col">  Transaction By</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(transaction, index) in department.transactions"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>  {{ transaction.balance_to.department }} </td>
                        <td> {{ transaction.balance_to.name }} </td>
                        <td> {{ transaction.balance_from.name }} </td>
                        <td> {{ transaction.amount }} </td>
                        <td> {{ transaction.transferby.name }} </td>

                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-5 col-md-5">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading"> {{ department.name }} Loan  Due </h3>
                </div>
                <div class="box-body">

                  <table class="table table-hover table-bordered  table-striped ">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col"> Departments</th>
                        <th scope="col">  Given Amount </th>
                        <th scope="col">  Taken Amount</th>
                        <th scope="col">  Due Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(record, r_index) in records"
                        v-bind:key="r_index"
                      >
                        <td scope="row">{{ r_index + 1 }}</td>
                        <td>  {{ record.department }} </td>
                        <td>  {{ record.paid }} </td>
                        <td>  {{ record.receive }} </td>
                        <td>  {{ parseInt(record.paid) - parseInt(record.receive)  }} </td>

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
export default {
  created() {
    this.departmentLoanTransactions();
  },
  data() {
    return {
      department:"",
      records:"",
      loading: true,
    };
  },
  methods: {

   departmentLoanTransactions() {
      axios
        .get("/api/department/loan/transaction/history/"+this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
             this.department = resp.data.department;
             this.records = resp.data.records;
             this.loading = false;
          }
        })
    },

    back(){
      return window.history.back();
    }

  },

};
</script>

