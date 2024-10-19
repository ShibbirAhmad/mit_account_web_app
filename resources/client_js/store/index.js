import Vue from "vue";
import Vuex from "vuex";
import router from "../router"; // Vue router instance
Vue.use(Vuex);

/* ========================== State ========================== */
const state = {
    user: {
        data: {},
        token: localStorage.getItem("INVENTORY_TOKEN"),
        purchase_status: localStorage.getItem("MANUAL_STOCK"),
    },
   
    // language: "english",

    /* ========================== global ========================== */
    // image_base_link: '/../public/storage/',
    // static_image_link: "/../public/storage/static/",

    /* ========================== local ========================== */
    image_base_link: "/../storage/",
    static_image_link: "/../storage/static/",
};

/* ========================== Getters ========================== */
const getters = {
   
};

/* ========================== Actions ========================== */
const actions = {
    
    async checkAuth(context) {
        // await axios
        //     .get("/api/inventory/authenticate-user")
        //     .then((resp) => {
        //         if (resp.data.status == true) {
        //             context.commit("merchant", resp.data.merchant);
        //         }
        //     })
        //     .catch(() => {
        //         this.$toasted.show("some thing want to wrong", {
        //             type: "error",
        //             position: "top-center",
        //             duration: 4000,
        //         });
        //     });
    },

};

/* ========================== Mutations ========================== */
const mutations = {
    
};

/* ========================== Store ========================== */
const store = new Vuex.Store({
    state: state,
    mutations: mutations,
    getters: getters,
    actions: actions,
});

global.store = store;
export default store;
