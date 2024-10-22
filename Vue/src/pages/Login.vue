<template>
    <div class="">
        <!-- <v-img class="mx-auto my-6" max-width="228"
            src="https://cdn.vuetifyjs.com/docs/images/logos/vuetify-logo-v3-slim-text-light.svg"></v-img>

        <v-card class="mx-auto pa-12 pb-8" elevation="8" max-width="448" rounded="lg">
            <div class="text-subtitle-1 text-medium-emphasis">Account</div>

            <v-text-field density="compact" placeholder="Email address" prepend-inner-icon="mdi-email-outline"
                variant="outlined"></v-text-field>

            <div class="text-subtitle-1 text-medium-emphasis d-flex align-center justify-space-between">
                Password

                <a class="text-caption text-decoration-none text-blue" href="#" rel="noopener noreferrer"
                    target="_blank">
                    Forgot login password?</a>
            </div>

            <v-text-field :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'"
                density="compact" placeholder="Enter your password" prepend-inner-icon="mdi-lock-outline"
                variant="outlined" @click:append-inner="visible = !visible"></v-text-field>

            <v-btn class="mb-2" color="blue" size="large" variant="tonal" block>
                Log In
            </v-btn>

            <v-card-text class="text-center">
                <a class="text-blue text-decoration-none" href="#" rel="noopener noreferrer" target="_blank">
                    Sign up now <v-icon icon="mdi-chevron-right"></v-icon>
                </a>
            </v-card-text>
        </v-card> -->


        <div class="login_form_area" style="background-image: url('https://img.freepik.com/premium-photo/abstract-background-design-images-wallpaper-ai-generated_643360-78986.jpg');">

            <div class="container">
                <div class="login_form">
                    <div class="login_form_title">
                        <h1>ToeDoe List</h1>
                        <h2 class="h3 mb-4 fw-normal">Please sign in</h2>
                    </div>
                    <div class="logo_icon">
                        <img src="https://i.ibb.co.com/j5M6qzp/Screenshot-3.png" alt="Logo icon">
                    </div>
                    <form class="auth-form" @submit.prevent="handleSubmit">


                        <div class="form-floating mb-3 mt-5">
                            <input type="email" class="form-control"
                                :class="{ 'is-invalid': errors.email && errors.email[0] }" id="email"
                                v-model="form.email" placeholder="name@example.com" />
                            <label for="email">Email</label>
                            <div class="invalid-feedback" v-if="errors.email && errors.email[0]">
                                {{ errors.email && errors.email[0] }}
                            </div>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="password" class="form-control"
                                :class="{ 'is-invalid': errors.password && errors.password[0] }" id="password"
                                v-model="form.password" placeholder="Password" />
                            <label for="password">Password</label>
                            <div class="invalid-feedback" v-if="errors.password && errors.password[0]">
                                {{ errors.password && errors.password[0] }}
                            </div>
                        </div>
                        <div class="login_from_btn">
                            <button class="submit_btn" type="submit">Sign in</button>
                        </div>


                        <!-- <div class="">
                            <p>or</p>
                            <h5>sign up</h5>
                        </div> -->
                    </form>
                </div>
            </div>
        </div>



    </div>
</template>

<script setup>
import { reactive } from "vue";
import { storeToRefs } from "pinia";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";

const router = useRouter();
const store = useAuthStore()
const { isLoggedIn, errors } = storeToRefs(store)
const { handleLogin } = store

const form = reactive({
    email: '',
    password: ''
})

const handleSubmit = async () => {
    await handleLogin(form)
    if (isLoggedIn.value) {
        console.log("ok");
        // router.push({ name: 'tasks' })
    }
};
</script>
