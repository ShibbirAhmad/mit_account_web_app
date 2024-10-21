import Vue from "vue";
import Vuex from "vuex";
import router from "../router"; // Vue router instance
Vue.use(Vuex);

/* ========================== State ========================== */
const state = {
  auth: {
    user: null,
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
const getters = {};

/* ========================== Actions ========================== */
const actions = {
  /* ========================= start object ========================= */
  async login({ commit }, credentials) {
    await axios.get("/sanctum/csrf-cookie");
    const { data } = await axios.post("/login", credentials);
    commit("setUser", data);
  },
  async fetchUser({ commit }) {
    try {
      const { data } = await axios.get("/user");
      commit("setUser", data);
    } catch {
      commit("setUser", null);
    }
  },
  logout({ commit }) {
    axios.post("/logout").then(() => commit("setUser", null));
  },

  /* ========================= end object ========================= */
};

/* ========================== Mutations ========================== */
const mutations = {
  setUser(state, user) {
    state.auth.user = user;
  },
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
