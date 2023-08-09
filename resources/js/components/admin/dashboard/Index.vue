<template>
  <div>
    <admin-main></admin-main>

    <div class="content-wrapper">
      <section class="content-header">

        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Home</a>
          </li>
          <li class="active">Dashboard</li>
        </ol>
      </section>
      <h1 v-if="loading" style="text-align: center; font-size: 50px">
        <i class="fa fa-spinner fa-spin"></i>
      </h1>
      <section v-else class="content">

         <mitAccounts  />

         <boostAccounts  />

      </section>
    </div>

  </div>
</template>

<script>
import boostAccounts from "../account/BoostAccounts.vue" ;
import mitAccounts from "../account/MitAccounts.vue" ;
export default {
  mounted(){
    window.scrollTo(0,0);
  },
  data() {
    return {
      orders: {},
      loading: true,
      more_accounts:false,
    };
  },
  components:{
    mitAccounts,
    boostAccounts
  },
  created() {
    this.$store.dispatch("admin");
    this.dashboardHome();
  },
  methods: {
    dashboardHome() {
      axios.get("/dashboard/welcome")
      .then((resp) => {
        console.log(resp);
        this.orders = resp.data.orders;
        this.loading = false;
      });
    },
    displayMoreAccount(){
       this.more_accounts=true ;
    }

  },

  computed: {
    admin() {
      return this.$store.getters.admin;
    },
  },
};
</script>

<style scoped >

.small-box .icon {
  color: #fff !important;
  opacity: 0.6;
}

.__o_amount {
  color: #fff !important;
  font-size: 20px !important;
  float: right;
}

.load_more_btn {
    box-shadow: 0 1pt 12pt rgb(150 165 237);
    width: 260px;
    height: 40px;
    font-size: 16px;
    font-family: sans-serif;
    color: #000;
    padding: 8px;
}

</style>
