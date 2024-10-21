<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="">Email </label>
        <input v-model="form.email" placeholder="Email" class="form-control" />

        <div class="invalid-feedback" v-if="errors.email">
          {{ errors.email }}
        </div>

      </div>

      <div class="form-group">
        <label for="">Password </label>
        <input v-model="form.password" type="password" placeholder="Password" class="form-control" />
        <!-- <has-error :form="form" field="password"></has-error> -->

        <!-- <has-error :form="form" field="password"></has-error> -->
      </div>

      <div>
        {{  errors }}
      </div>

      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";

Vue.component(HasError.name, HasError);
Vue.component(AlertError.name, AlertError);

import { mapActions } from "vuex";

export default {
  data() {
    return {
      form: new Form({
        email: "",
        password: "",
      }),

      errors: {},

    };
  },
  methods: {
    ...mapActions(["login"]),
    async login() {
      try {
        await this.form.post(`${this.$base_url}/login`);
        // await this.$store.dispatch("fetchUser");  // Fetch user after login
        // this.$router.push("/client/dashboard");  // Redirect on success
      } catch (error) {
        if (error.response && error.response.status === 422) {
          this.errors = error.response.data.errors
        } else {
          console.error("Login failed:", error);
          alert("Login failed. Please try again.");
        }


      }
    },
  },
};
</script>
