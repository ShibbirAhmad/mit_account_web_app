import Vue from 'vue'
import Vuex from 'vuex'

import router from './router.js'; // Vue router instance

Vue.use(Vuex);

const state = {

    //store admin
    admin: {},
    //int merchant
    merchant: {},
    //for check admin page view permission
    view_permission: false,
    image_base_link: '/../public/storage/',
    thumbnail_img_base_link: '/../public/storage/images/product_thumbnail_img/',

}
const getters = {
    admin(state) {
        return state.admin;
    },
    merchant(state) {
        return state.merchant;
    },
    admin(state) {
        return state.admin;
    },

    view_permission(state) {
        return state.view_permission;
    },

    image_base_link(state) {
        return state.image_base_link;
    },



}
const actions = {
    // merchant action in server
    merchant(context) {
        axios.get('/api/merchant/login/session/check')
            .then(resp => {
                //when session running
                if (resp.data.session == "running") {
                    context.commit('merchant', resp.data.merchant);
                }
                //when session expired
                if (resp.data.session == 'expired') {
                    localStorage.removeItem('merchant_token');
                    router.push({ name: 'merchant_login' });
                }

            })
    },


    //every time this session check, for admin session running or expired
    admin(context) {
        axios.get("/check/session/admin")
            //if session status running
            .then((resp) => {
                if (resp.data.status == "RUNNING") {
                    context.commit('admin', resp.data.admin)

                }
                //if session is expired, admin redirect ot login again
                if (resp.data.status == "EXPIRED") {
                    localStorage.removeItem("admin_token");
                    router.push({ name: "adminLogin" });
                    return;
                }
            })
            //error handling
            .catch(() => {
                router.push({ name: "adminLogin" });
            });
    },


}
const mutations = {
    admin(state, payload) {
        return state.admin = payload
    },

    merchant(state, payload) {
        return state.merchant = payload;
    },


}

const store = new Vuex.Store({

    state: state,
    mutations: mutations,
    getters: getters,
    actions: actions

})

global.store = store;

export default store;