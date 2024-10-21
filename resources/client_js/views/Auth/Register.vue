<template>
  <div>
    <h2>Login</h2>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="">Email </label>
        <input v-model="form.email" placeholder="Email" class="form-control" />
        <has-error :form="form" field="email"></has-error>
      </div>

      <div class="form-group">
        <label for="">Password </label>
        <input v-model="form.password" type="password" placeholder="Password" class="form-control" />
        <has-error :form="form" field="password"></has-error>
      </div>
      
      <button type="submit">Login</button>
    </form>
  </div>
</template>

<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
Vue.component(HasError.name, HasError);

import { mapActions } from "vuex";

export default {
  data() {
    return {
      form: new Form({
        email: "",
        password: "",
      }),
    };
  },
  methods: {
    ...mapActions(["login"]),
    async login() {
      try {
        await this.login(this.form);
        // this.$router.push("/");
      } catch {
        alert("Login failed");
      }
    },
  },
};
</script>
