import Vue from "vue";
import VueRouter from "vue-router";
import store from "../store";
Vue.use(VueRouter);

const routes = [
  /* ====================== Invalid route ====================== */
  {
    path: "*",
    component: () =>
      import(
        /* webpackChunkName: "inventory/notFound" */ "../views/NotFound.vue"
      ),
    name: "notFound",
    meta: {
      title: "404 - Not Found!",
    },
  },

  {
    path: "/client/login",
    component: () =>
      import(
        /* webpackChunkName: "client/client_login" */ "../views/Auth/Login.vue"
      ),
    name: "client_login",
    meta: {
      title: "Login",
    },
  },

  {
    path: "/client/register",
    component: () =>
      import(
        /* webpackChunkName: "client/client_register" */ "../views/Auth/Register.vue"
      ),
    name: "client_register",
    meta: {
      title: "Register",
    },
  },



  /* ====================== Protected Router ====================== */
  {
    path: "/client",
    redirect: "/client/dashboard",
    component: () => import("../components/DefaultMaster.vue"),
    //meta: { requiresAuth: true },
    children: [
      /* ====================== Start inventory Dashboard Routing ====================== */

      {
        path: "/client/dashboard",
        component: () =>
          import(
            /* webpackChunkName: "client/client_dashboard" */ "../views/Index.vue"
          ),
        name: "client_dashboard",
        meta: {
          title: "Dashboard",
        },
      },

      /* _________________ End ________________ */
    ],
  },

  /* ====================== Protected Router ====================== */
];

const router = new VueRouter({
  mode: "history",
  base: __dirname,
  routes,
  scrollBehavior(to, from, savedPosition) {
    return new Promise((resolve, reject) => {
      setTimeout(() => {
        resolve({ x: 0, y: 0 });
      }, 500);
    });
  },
});

router.beforeEach((to, from, next) => {
  const loggedIn = store.state.auth.user;

  if (to.name !== "client_login" && to.name !== "client_register" && !loggedIn) {
    return next({ name: "client_login" });
  }

  next();
});

/* ================= Admin Middleware router =================== */
// router.beforeEach((to, from, next) => {
//     store.dispatch("checkAuth");
//     const check_merchant = store.getters.check_merchant;
//     if (
//         to.matched.some((record) => record.meta.requiresAuth) &&
//         check_merchant == "UNAUTHORIZED"
//     ) {
//         next({
//             href: "/login",
//         });
//     } else if (
//         to.matched.some((record) => record.meta.isGuest) &&
//         check_merchant == "AUTHORIZED"
//     ) {
//         next({
//             name: "inventory_dashboard",
//         });
//     } else {
//         next();
//     }
// });
/* ================= Protected router requiresAuthAdmin isAdminGuest =================== */

export default router;
