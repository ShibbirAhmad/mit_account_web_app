require('./bootstrap');
window.Vue = require('vue');


import router from "./router";
import store from "./store";
import VueProgressBar from 'vue-progressbar'
const objectToFormData = window.objectToFormData;
import Toasted from 'vue-toasted';
import CKEditor from '@ckeditor/ckeditor5-vue';
import VModal from 'vue-js-modal';
import datePicker from 'vue-bootstrap-datetimepicker';
import Permissions from "./components/admin/Permissions/Permissions.vue";

Vue.use(datePicker);
Vue.mixin(Permissions);

import VueToastr from 'vue-toastr';
// use plugin
Vue.use(VueToastr, {
    defaultTimeout: 4000,
    defaultProgressBar: false,
    defaultProgressBarValue: 0,
    defaultType: 'error',
    defaultCloseOnHover: false,
    defaultClassNames: ['animated', 'zoomInUp'],
    defaultPosition: 'toast-top-right',
});



import InfiniteLoading from 'vue-infinite-loading';
Vue.use(InfiniteLoading, { /* options */ });
const options = {
  color: '#FF4D03',
    failedColor: '#fff',
    thickness: '6px',
    transition: {
        speed: '0.2s',
        opacity: '1',
        termination: 300
    },
    autoRevert: true,
    location: 'top',
    inverse: false
}

Vue.use(Toasted);
Vue.use(CKEditor);
Vue.use(VueProgressBar, options);
Vue.use(VModal, { dynamicDefault: { draggable: true, resizable: true } })


import Vue from 'vue';
import VueSweetalert2 from 'vue-sweetalert2';
// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);

import { VLazyImagePlugin } from "v-lazy-image";
Vue.use(VLazyImagePlugin);


Vue.component('frontend-header', require('./components/public/Header.vue').default);
Vue.component('frontend-footer', require('./components/public/Footer.vue').default);


Vue.component('admin-main', require('./components/admin/Main.vue').default);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('access', require('./components/admin/AccessDenied.vue'));
Vue.component('quick-view', require('./components/public/QuickView.vue').default)



Vue.config.devtools = true;
const app = new Vue({
    el: '#app',
    router,
    store,
    basePath: 'storage/',
     watch: {
        '$route'(to, from) {
           document.title = to.meta.title;
        }
    },
   created () {
       this.$router.beforeEach((to, from, next) => {
            this.$Progress.start()
            next()
        })
        this.$router.afterEach((to, from) => {
            this.$Progress.finish()
        })
    },


});


