<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'department_loan_add' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          >Add Loan </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">departments loan </li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-lg-offset-1">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading">Departments Loans</h3>
                </div>
                <div class="box-body">

                  <table class="table table-hover table-bordered  table-striped ">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col"> Given  Amount</th>
                        <th scope="col">  Taken Amount</th>
                        <th scope="col"> Due Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(item, index) in departments"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>  <router-link style="color:blue" :to="{name:'department_loan_transactions',params:{id:item.id}}">{{ item.name }}</router-link>  </td>
                        <td> {{ item.given_amount }} </td>
                        <td> {{ item.taken_amount }} </td>
                        <td> {{  parseInt(item.given_amount) - parseInt(item.taken_amount)  }} </td>

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
    this.departmentLoan();
  },
  data() {
    return {
      departments:"",
      loading: true,
    };
  },
  methods: {

   departmentLoan() {
      axios
        .get("/api/department/loan")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
             this.departments = resp.data.departments;
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

<style scoped>


table th {
  font-size: 16px;
}


table td {
  font-size: 16px;
}
</style>