<template>
  <div>
    <Header></Header>
    <!-- ============= Auth Area Start =============== -->
    <div class="auth_area">
      <v-container>
        <v-row>
          <v-col cols="12" md="12" lg="12">
            <div class="auth_content">
              <v-row>
                <v-col lg="6" md="6" sm="6" cols="12">
                  <div class="auth_left_side">
                    <div class="auth_header">
                      <div class="auth_title">
                        <h3>Login Account</h3>
                      </div>
                    </div>
                    <div class="auth_body">
                      <div class="auth_form">
                        <form
                          @submit.prevent="login()"
                          @keydown="form.onKeydown($event)"
                        >
                          <div class="auth_form_item">
                            <input
                              type="number"
                              class="auth_form_item_input"
                              placeholder="Phone Number"
                              v-model="form.phone"
                              required
                            />
                            <i class="fa-solid fa-phone"></i>
                            <has-error :form="form" field="phone"></has-error>
                          </div>
                          <div class="auth_form_item">
                            <input
                              type="password"
                              class="auth_form_item_input"
                              placeholder="Password"
                              v-model="form.password"
                              required
                            />
                            <i class="fa-solid fa-lock"></i>
                            <has-error
                              :form="form"
                              field="password"
                            ></has-error>
                          </div>
                          <div class="auth_form_item_btn">
                            <button
                              type="submit"
                              class="auth_form_item_btn_link"
                            >
                              <v-progress-circular
                                v-if="loading"
                                :size="25"
                                color="primary"
                                indeterminate
                              ></v-progress-circular>
                              Login
                            </button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="auth_footer">
                      <p>
                        You have a no account?
                        <router-link
                          to="/inventory/register"
                          class="auth_footer_link"
                          >Register here</router-link
                        >
                      </p>
                    </div>
                  </div>
                </v-col>
                <v-col lg="6" md="6" sm="6" cols="12">
                  <div class="auth_right_side" >
                    <iframe :src="tutorial.link" frameborder="0"></iframe>
                  </div>
                </v-col>
              </v-row>
            </div>
          </v-col>
        </v-row>
      </v-container>
    </div>
    <!-- ============= Auth Area End =============== -->
    <Footer></Footer>
  </div>
</template>

<script>
import Header from "../../components/AuthLayouts/Header.vue";
import Footer from "../../components/AuthLayouts/Footer.vue";
import Form from "vform";
import { HasError } from "vform/src/components/bootstrap5";
import axios from "axios";
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
      base_url: this.$store.state.image_base_link,
      valid: true,
      form: new Form({
        email: "",
        password: "",
      }),
      loading: false,
      tutorial: {},
      slug: "login",
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

    login() {
      this.loading = true;
      this.form
        .post("/api/inventory/login")
        .then((resp) => {
          if (resp.data.status == true) {
            this.$store.dispatch("inventory");
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 2000,
            });
            this.loading = false;
          } else {
            this.$toasted.show(
              "Your given credential don not match our records",
              {
                type: "error",
                position: "top-right",
                duration: 2000,
              }
            );
            this.loading = false;
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show(error, {
            type: "error",
            position: "top-right",
            duration: 2000,
          });
          this.loading = false;
        });
    },
  },
};
</script>
<style scoped>
@import "./css/style.css";
.auth_right_side {
    width: 100%;
    height: 100%;
}

.auth_right_side iframe {
    width: 100%;
    height: 100%;
}
</style>
