<template>
  <div class="login-box" v-if="!isLoading">
    <div class="login-logo">
      <a href="#"> <b>Mohasagor</b>.com </a>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form @submit.prevent="login">
        <div class="alert alert-danger" v-if="error">{{ error }}</div>

        <div class="form-group has-feedback">
          <input
            type="email"
            class="form-control"
            :class="{ 'is-invalid': form.errors.has('email') }"
            name="email"
            v-model="form.email"
            placeholder="Email"
            autocomplete="off"
            autofocus
          />
          <has-error :form="form" field="email"></has-error>
        </div>
        <div class="form-group has-feedback">
          <input
            type="password"
            class="form-control"
            :class="{ 'is-invalid': form.errors.has('password') }"
            name="password"
            v-model="form.password"
            placeholder="Password"
            autocomplete="off"
          />
          <has-error :form="form" field="password"></has-error>
        </div>
        <button
          href="#"
          :disabled="form.busy"
          class="btn btn-block btn-primary"
          type="submit"
        >
          <i class="fa fa-spinner fa-spin" v-if="form.busy"></i>LOGIN
        </button>
        <router-link class="span password_reset_button" :to="{name : 'merchant_password_reset'}">Forgotten Password</router-link>
        <a href="/merchant/register" class="btn btn-info btn-block" :to="{ name : 'merchant_register'}">Register</a>
      </form>
      
    </div>
    <!-- /.login-box-body -->
  </div>
  <div class="loading" v-else>
    <h2>Loading............</h2>
  </div>
</template>

<script>
import Vue from "vue";
import { Form, HasError } from "vform";

Vue.component(HasError.name, HasError);
export default {
  created() {
    this.removeClass();

    setTimeout(() => {
      this.isLoading = false;
    }, 1000);
  },

  data() {
    return {
      form: new Form({
        email: "",
        password: "",
      }),
      error: "",
      isLoading: true,
    };
  },

  methods: {
    login() {
      this.form
        .post("/api/merchant/login")
        .then((resp) => { 
          console.log(resp) ;

          if (resp.data.status == "SUCCESS") {
            localStorage.setItem("merchant_token", resp.data.merchant_token);
            this.$store.commit("merchant", resp.data.merchant);
            setTimeout(()=>{
              location.reload();
           },2000)

            this.addClass();
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          }
          if (resp.data.status == "Fail") {
            this.error = resp.data.message;
          }
        })
        .catch((error) => {
          this.error = "Some thing want to wrong. please try again";
        });
    },

    removeClass() {
      let myBody = document.getElementsByTagName("body")[0];
      let sidebar = document.getElementsByClassName("main-sidebar");
      let footer = document.getElementsByClassName("main-footer");

      myBody.classList.remove("skin-blue");
      myBody.classList.add("login-page");

      if (sidebar.length > 0) {
        sidebar[0].classList.add("none");
      }
      if (footer.length > 0) {
        footer[0].classList.add("none");
      }
    },

    addClass() {
      let myBody = document.getElementsByTagName("body")[0];
      let sidebar = document.getElementsByClassName("main-sidebar")[0];
      let footer = document.getElementsByClassName("main-footer")[0];
      myBody.classList.remove("login-page");
      myBody.classList.add("skin-blue");

      sidebar.classList.remove("none");
      footer.classList.remove("none");
    },
  },
};

document.addEventListener("DOMContentLoaded", () => {
  let sidebar = document.getElementsByClassName("main-sidebar")[0];
  let footer = document.getElementsByClassName("main-footer")[0];
  if (window.location.href == "http://127.0.0.1:8000/merchant/backend/login") {
    sidebar.classList.add("none");
    footer.classList.add("none");
  }
});
</script>
<style >
.none {
  display: none !important;
}

.password_reset_button{

    color: #ee6a18;
    padding-top: 6px;
    padding-bottom: 6px;
    display: flex;
}
</style>
