const routes = [
  {
    path: "/",
    component: () => import("../pages/HomePage.vue"),
    name: "home",
  },
  {
    path: "/client",
    redirect: "/client/dashboard",
    component: () => import("../components/DefaultMaster.vue"),
    children: [
      {
        path: "dashboard",
        component: () => import("../views/Index.vue"),
        name: "client_dashboard",
        meta: {
          title: "Dashboard",
          auth: true, // You can use this meta property for auth checks
        },
      },
      {
        path: "tasks",
        component: () => import("../pages/TasksPage.vue"),
        name: "tasks",
        meta: {
          auth: true,
        },
      },
      {
        path: "summary",
        component: () => import("../pages/SummaryPage.vue"),
        name: "summary",
        meta: {
          auth: true,
        },
      },
    ],
  },


  {
    path: "/login",
    component: () => import("../pages/LoginPage.vue"),
    name: "login",
    meta: {
      guest: true,
    },
  },

  {
    path: "/register",
    component: () => import("../pages/RegisterPage.vue"),
    name: "register",
    meta: {
      guest: true,
    },
  },

  
  {
    path: "/:notFound(.*)",
    name: "error.404",
    component: () => import("../pages/errors/NotFoundErrorPage.vue"),
  },
];
