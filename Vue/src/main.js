import { createApp } from "vue";
import App from "./App.vue";


// Vuetify
import "@mdi/font/css/materialdesignicons.css";
import "vuetify/styles";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
    components,
    directives,
});

import "./assets/css/all.min.css";
import "./assets/css/custom.css";
import "./assets/css/style.css";

import router from "./router";


const app = createApp(App);
app.use(vuetify)
app.use(router)
app.mount("#app");


