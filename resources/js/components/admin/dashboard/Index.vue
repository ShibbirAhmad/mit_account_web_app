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
  
      <section  class="content">

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

  components:{
    mitAccounts,
    boostAccounts
  },
  created() {
    this.$store.dispatch("admin");
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
