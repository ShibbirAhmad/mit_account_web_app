<template>
  <div class="wrapper-wide">
    <div>
      <!-- <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading> -->

      <frontend-header></frontend-header>

      <div id="container">
        <div class="container">
          <!-- Breadcrumb End-->
          <div class="row">
            <div class="col-lg-3"></div>
            <div id="content" class="col-sm-10 col-lg-5">
              <div class="custom-box" style="height: auto">
                <h2 class="title">Welcome back</h2>
                <form @submit.prevent="login">
                  <div class="form-group">
                    <label class="control-label" for="input-email"
                      >Mobile Number</label
                    >
                    <input
                      type="phone"
                      name="mobile_no"
                      placeholder="01xxx-xxxxx"
                      class="form-control"
                      autocomplete="off"
                      maxlength="11"
                      :class="{ 'is-invalid': form.errors.has('mobile_no') }"
                      v-model="form.mobile_no"
                      autofocus
                    />
                    <has-error :form="form" field="mobile_no"></has-error>
                  </div>

                  <div class="form-group">
                    <label class="control-label" for="input-password"
                      >Password</label
                    >
                    <input
                      type="password"
                      name="password"
                      placeholder="Password"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('password') }"
                      v-model="form.password"
                    />
                    <has-error :form="form" field="password"></has-error>

                    <br />

                    <!-- <router-link  :to="{name:'UserRegister'}">Register here</router-link> -->
                    <router-link :to="{ name: 'user_password_reset' }"
                      >Forget Password</router-link
                    >
                  </div>
                  <button
                    :disabled="form.busy"
                    class="btn btn-block btn-primary"
                    type="submit"
                  >
                    <i class="fa fa-spinner fa-spin" v-if="form.busy"></i>LOGIN
                  </button>
                </form>
                <br />
                <router-link
                  :to="{ name: 'otpLogin' }"
                  class="btn btn-block btn-info"
                  type="submit"
                >
                  LOGIN WITH OTP
                </router-link>
                <br />
                <!-- <div class="row">
                  <div class="col-lg-6">
                    <a
                      href="/sociallogin/google"
                      class="btn btn-block btn-primary google-login"
                      >Login With Google</a
                    >
                  </div>
                  <div class="col-lg-6">
                    <a
                      href="/login/facebook"
                      class="btn btn-block btn-primary facebook-login"
                      >Login With Facebook</a
                    >
                  </div>
                </div> -->
                <br />
                <br />
                Don't have an account?
                <router-link :to="{ name: 'UserRegister' }">
                  Register here</router-link
                >
                <br />
                <br />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <frontend-footer></frontend-footer>
  </div>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Form, HasError } from "vform";

export default {
  created() {
    // setTimeout(() => {
    //   this.isLoading = false;
    // }, 1500);
  },
  data() {
    return {
      form: new Form({
        mobile_no: "",
        password: "",
      }),
      pre_route: null,
      isLoading: true,
      fullPage: true,
    };
  },
  methods: {
    login() {
      this.form
        .post("/_public/userToLogin")
        .then((resp) => {
          if (resp.data.status == "SUCCESS") {
            console.log(resp);
            localStorage.setItem("user_token", resp.data.token);
            this.$store.dispatch("user");

            if (this.$store.getters.cartContent.total > 0) {
              this.$router.push({ name: "Chekout" });
            } else {
              this.$router.push({ name: "OrderHistory" });
            }
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
          } else {
            this.$toasted.show(
              "Your given credintial don not match our records",
              {
                type: "error",
                position: "top-center",
                duration: 2000,
              }
            );
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show("some thing want to worng", {
            type: "error",
            position: "top-center",
            duration: 2000,
          });
        });
    },
  },
  // beforeRouteEnter(to, from, next) {

  //    next((vm) => {
  //     vm.from = from;
  //   });
  //   if (from.name == "Chekout") {
  //     this.pre_route=from.name;
  //   } else {
  //     this.pre_route="welcome";
  //   }

  // },

  beforeRouteEnter(to, from, next) {
    next((vm) => {
      console.log(vm);
      vm.pre_route = from.name;
    });
  },
  components: {
    Loading,
    HasError,
  },
};
</script>