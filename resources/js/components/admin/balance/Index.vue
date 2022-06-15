<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'balance_add' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">balance </li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-lg-offset-1">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading">Balance Table</h3>
                </div>
                <div class="box-body">

                  <table class="table table-hover table-bordered  table-striped ">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col"> Department</th>
                        <th scope="col"> Name</th>
                        <th scope="col"> Status</th>
                        <th scope="col"> Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-else
                        v-for="(item, index) in balance"
                        v-bind:key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>  {{ item.department }} </td>
                        <td>  {{ item.name }} </td>
                        <td>  <span v-if="item.status==1" class="badge badge-success">Active</span>
                              <span v-else class="badge badge-warning">DeActive</span>
                        </td>
                        <td>
                           <a  v-if="item.status==1"  @click="changeStatus(item.id)" class="btn btn-xs btn-warning"><i class="fa fa-ban"></i></a>
                           <a v-else @click="changeStatus(item.id)" class="btn btn-xs btn-success"><i class="fa fa-check"></i></a>
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
export default {
  created() {
    this.getBalanceList();
  },
  data() {
    return {
      balance:"",
      loading: true,
      basePath: this.$store.getters.image_base_link,
      total_investment: "",
      total_profit_paid: "",
      total_due_amount: 0,
    };
  },
  methods: {

   getBalanceList() {
      axios
        .get("/api/balance/list/all/department")
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.balance = resp.data.balance;
             this.loading = false;
          }
        })
    },

    changeStatus(id) {
      axios.get("/api/balance/status/change/"+id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
             this.$toasted.show(resp.data.message, {
                type: "success",
                position: "top-center",
                duration: 3000,
              });
               this.getBalanceList();
          }
        })
    },

  },

};
</script>

