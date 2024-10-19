<template>
  <div>
    <Header></Header>
    <!-- ============= Auth Area Start =============== -->
    <div class="auth_area">
      <v-container>
        <div v-if="sendMode">
          <form
            @submit.prevent="sendOtp()"
            @keydown="form.onKeydown($event)"
            enctype="multipart/form-data"
          >
            <v-row>
              <v-col lg="6" md="6" sm="6" cols="12">
                <div class="auth_left_side">
                  <div class="auth_header">
                    <div class="auth_title">
                      <h3>Give the phone number</h3>
                    </div>
                  </div>

                  <div class="auth_body">
                    <div class="auth_form">
                      <v-row>
                        <v-col cols="2" lg="2" style="padding-right:0px">
                          <input class="custom_register_number" type="text" placeholder="+88" disabled>
                        </v-col>
                        <v-col cols="10" lg="10">
                          <input class="custom_register_input" type="number" placeholder="Enter the phone number" required minlength="11" maxlength="11" v-model="form.phone">
                          <has-error :form="form" field="phone"></has-error>
                        </v-col>
                      </v-row>

                      <div class="auth_form_item_btn">
                        <button type="submit" class="auth_form_item_btn_link">
                          <v-progress-circular
                            v-if="loading"
                            :size="25"
                            color="primary"
                            indeterminate
                          ></v-progress-circular>
                          Go Ahead
                        </button>
                      </div>
                    </div>
                  </div>
                  <div class="auth_footer">
                    <p>
                      Already a register?
                      <router-link
                        to="/inventory/login"
                        class="auth_footer_link"
                        >Login here</router-link
                      >
                    </p>
                  </div>
                </div>
              </v-col>

              <v-col lg="6" md="6" sm="6" cols="12">
                <div class="auth_right_side">
                  <iframe :src="tutorial.link" frameborder="0"></iframe>
                </div>
              </v-col>
            </v-row>
          </form>
        </div>

        <div class="auth_content" v-else>
          <form
            @submit.prevent="verifyOtp()"
            @keydown="form.onKeydown($event)"
            enctype="multipart/form-data"
          >
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="auth_left_side">
                  <div class="auth_header">
                    <div class="auth_title">
                      <h5>Verify 4 Digits OTP Code</h5>
                    </div>
                  </div>

                  <div class="auth_body">
                    <div class="auth_form">
                      <div class="auth_form_item">
                        <input
                          type="number"
                          class="auth_form_item_input"
                          name="code"
                          v-model="vForm.code"
                          maxlength="4"
                          placeholder="4 digit code "
                          required
                        />
                        <i class="fa-solid fa-phone"></i>
                        <has-error
                          style="color: red; font-size: 12px"
                          :form="vForm"
                          field="code"
                        ></has-error>
                      </div>

                      <h4
                        class="mb-1 mt-1"
                        style="text-align: center"
                        v-if="second_limit > 0"
                      >
                        {{ second_limit }}
                        <i class="fa fa-clock-o"></i>
                      </h4>

                      <div class="auth_form_item_btn" v-else>
                        <a @click="sendOtp()" class="auth_form_item_btn_link">
                          <v-progress-circular
                            v-if="loading"
                            :size="25"
                            color="primary"
                            indeterminate
                          ></v-progress-circular>
                          Resend OTP
                        </a>
                      </div>

                      <div class="auth_form_item_btn">
                        <button
                          type="submit"
                          class="auth_form_item_btn_link"
                          :disabled="vForm.code.length < 4"
                        >
                          <v-progress-circular
                            v-if="loading"
                            :size="25"
                            color="primary"
                            indeterminate
                          ></v-progress-circular>
                          Verify OTP
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                <div class="auth_right_side">
                  <iframe :src="tutorial.link" frameborder="0"></iframe>
                </div>
              </div>
            </div>
          </form>
        </div>
      </v-container>
    </div>
    <!-- ============= Auth Area End =============== -->
    <Footer></Footer>
  </div>
</template>

<script>
import Vue from "vue";
import Form from "vform";
import { HasError } from "vform/src/components/bootstrap5";
import Header from "../../components/AuthLayouts/Header.vue";
import Footer from "../../components/AuthLayouts/Footer.vue";
Vue.component(HasError.name, HasError);

export default {
  created() {
    this.getVideoTutorial();
  },

  components: {
    Header,
    Footer,
  },
  data() {
    return {
      form: new Form({
        phone: "",
      }),
      vForm: new Form({
        phone: "",
        code: "",
      }),
      second_limit: 60,
      sendMode: true,
      pre_route: null,
      isLoading: true,
      fullPage: true,
      base_url: this.$store.state.image_base_link,
      loading: false,
      slug: "register",
      tutorial: {},
    };
  },

  methods: {
    async getVideoTutorial() {
      await axios
        .get("/api/inventory/video-tutorial/login-register/" + this.slug)
        .then((resp) => {
          if (resp.data.status === true) {
            this.tutorial = resp.data.tutorial;
          }
        })
        .catch((error) => console.log(error));
    },

    async sendOtp() {
      this.loading = true;
      try {
        let response = await this.form.post("/api/inventory/send/otp");
        console.dir(response);
        if (response.data.status === true) {
          this.$toasted.show(
            response.data.message + " and verify the otp code",
            {
              type: "success",
              position: "top-right",
              duration: 2000,
            }
          );

          this.vForm.phone = this.form.phone;
          this.sendMode = false;
          this.second_limit = 120;
          this.timeDecremental();
          this.loading = false;
        } else {
          this.$toasted.show(response.data.message, {
            type: "success",
            position: "top-right",
            duration: 2000,
          });
          this.loading = false;
        }
      } catch (error) {
        console.log(error);
        this.loading = false;
        this.$toasted.show(error.response.data.message, {
          type: "success",
          position: "top-right",
          duration: 2000,
        });
      }
    },

    async verifyOtp() {
      this.loading = true;
      try {
        let response = await this.vForm.post("/api/inventory/verify/otp/code");
        console.dir(response);
        if (response.data.status === true) {
          this.$toasted.show(response.data.message, {
            type: "success",
            position: "top-right",
            duration: 2000,
          });
          this.loading = false;
          localStorage.setItem("VERIFIED_PHONE", response.data.phone);
          localStorage.setItem("VERIFIED", response.data.verified);
          this.$router.push({ name: "inventory_register_step_2" });
        } else {
          this.$toasted.show(response.data.message, {
            type: "success",
            position: "top-right",
            duration: 2000,
          });
          this.loading = false;
        }
      } catch (error) {
        this.loading = false;
        console.log(error);
        this.$toasted.show(response.data.message, {
          type: "error",
          position: "top-right",
          duration: 2000,
        });
      }
    },

    timeDecremental() {
      setInterval(() => {
        this.second_limit -= 1;
      }, 1000);
    },
  },
};
</script>

<style scoped>
@import "./css/style.css";
.invalid-feedback.d-block.plan_section_error {
  color: red;
  text-align: center;
  margin-top: 10px;
}
.auth_right_side {
  width: 100%;
  height: 100%;
}

.auth_right_side iframe {
  width: 100%;
  height: 100%;
}
</style>
