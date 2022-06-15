<template>
  <div class="wrapper-wide">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
    ></loading>

    <frontend-header></frontend-header>

    <div id="container">
      <div class="container">
        <!-- Breadcrumb End-->
        <div class="row">
          <!--Middle Part Start-->
          <div id="content">
            <div class="row">
              <div class="col-lg-3"></div>
              <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                <div class="custom-box">
                  <h4
                    class="title"
                    style="margin-bottom: 2px; border-bottom: none"
                  >
                    {{ heading }}
                  </h4>
                  <span style="margin-left: 40px; margin-top: 2px"> </span>
                  <br />
                  <br />
                  <form v-if="sendMode" @submit.prevent="sendotp">
                    <div class="form-group">
                      <div class="row">
                        <div class="col-lg-8"></div>
                      </div>
                      <label for="">{{ span_message }}</label>
                      <input
                        autofocus
                        type="text"
                        name="mobile_no"
                        class="form-control"
                        autocomplete="off"
                        maxlength="11"
                        :class="{ 'is-invalid': form.errors.has('mobile_no') }"
                        v-model="form.mobile_no"
                        placeholder="01xxx-xxxxxx"
                      />
                      <has-error :form="form" field="mobile_no"></has-error>
                    </div>
                    <br />
                    <button
                      class="btn btn-block btn-primary"
                      type="submit"
                      :disabled="form.busy || form.mobile_no.length != 11"
                    >
                      <i class="fa fa-spinner fa-spin" v-if="form.busy"></i>SEND
                      CODE
                    </button>
<!-- 
                    <br />

                    <a
                      href="/sociallogin/google"
                      class="btn btn-block btn-primary google-login"
                      >Login With Google</a
                    >

                    <a
                      href="/login/facebook"
                      class="btn btn-block btn-primary facebook-login"
                      >Login With Facebook</a
                    > -->

                    <br />
                    <router-link
                      :to="{ name: 'UserLogin' }"
                      class="loginWithPass"
                      >Login With Password</router-link
                    >
                  </form>
                  <form @submit.prevent="verifyOtp" v-else>
                    <div class="form-group">
                      <input
                        type="text"
                        name="verify_code"
                        class="form-control"
                        autocomplete="off"
                        :class="{
                          'is-invalid': form.errors.has('verify_code'),
                        }"
                        v-model="verify_code"
                        placeholder="Enter Code"
                      />
                      <has-error :form="form" field="verify_code"></has-error>
                    </div>
                    <br />
                    <button
                      class="btn btn-block btn-primary"
                      type="submit"
                      :disabled="verify_code.length != 4"
                    >
                      VERIFY
                    </button>
                    <button
                      @click.prevent="sendotp"
                      :disabled="resend_disabable"
                      class="btn btn-block btn-primary"
                    >
                      Resend Code
                      <small v-if="time_count > 0"
                        >{{ "(" + time_count + ")" }}
                      </small>
                    </button>
                  </form>
                  <br />

                  <br />
                  <br />
                </div>
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
    setTimeout(() => {
      this.isLoading = false;
    }, 1000);
  },
  data() {
    return {
      form: new Form({
        mobile_no: "",
      }),
      isLoading: true,
      fullPage: true,

      disabled: true,
      sendMode: true,
      verify_code: "",
      span_message: "MOBILE NUMBER",
      heading: "LOGIN WITH MOBILE NUMBER",
      time_count: 20,
      resend_disabable: true,
      pre_route: "",
    };
  },
  methods: {
    sendotp() {
      this.isLoading = true;
      this.form
        .post("/send/otp")
        .then((resp) => {
          console.log(resp);
          if (resp.data) {
            this.isLoading = false;
            this.sendMode = false;

            this.$toasted.show(resp.data, {
              type: "success",
              position: "bottom-center",
              duration: 5000,
            });

            this.$refs.verify_code;
            this.span_message = "PLEASE ENTER 4 DIGITS VERIFICATION CODE";
            this.heading = "ENTER CODE";
            this.resend_disabable = true;
            this.timeCount();
          } else {
            this.$toasted.show("some thing want to wronggg", {
              type: "error",
              position: "bottom-center",
              duration: 5000,
            });
          }
        })
        .catch((error) => {
          console.log(error)
          this.isLoading = false;
          this.$toasted.show("some thing want to wroooong", {
            type: "error",
            position: "bottom-center",
            duration: 2000,
          });
        });
    },
    verifyOtp() {
      this.isLoading = true;
      axios
        .get("/verify/otp/code", {
          params: {
            mobile_no: this.form.mobile_no,
            verify_code: this.verify_code,
          },
        })
        .then((resp) => {
          this.isLoading = false;

          if (resp.data.status == "OK") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "bottom-center",
              duration: 4000,
            });
            localStorage.setItem("user_token", resp.data.user.mobile_no);

            if (this.$store.getters.cartContent.total > 0) {
              this.$router.push({ name: "Chekout" });
            } else {

            this.$router.push({ name: "UserDashboard" });

            }
          } else {
            this.$toasted.show(resp.data, {
              type: "error",
              position: "bottom-center",
              duration: 4000,
            });
          }
        })
        .catch((e) => {
          this.isLoading = false;
        });
    },
    timeCount() {
      if (parseInt(this.time_count) != 60) {
        this.time_count = 60;
      }
      setInterval(() => {
        this.time_count--;
      }, 1000);
    },
  },
  components: {
    Loading,
    HasError,
  },
  watch: {
    time_count: function (value) {
      if (parseInt(value) <= parseInt(0)) {
        this.resend_disabable = false;
      }
    },
  },
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      // console.log('to');
      // console.log(from.name);
      vm.pre_route = from.name;
    });
  },
};
</script>