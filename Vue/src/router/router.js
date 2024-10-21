const routes = [
    {
      path: "/",
      component: () => import("../pages/Login.vue"),
      name: "home",
    },

    // authenticate layouts
    {
      path: "/",
      redirect: "/dashboard",
      component: () => import("../layouts/AuthMaster.vue"),
      children: [
        {
          path: "dashboard",
          component: () => import("../pages/Index.vue"),
          name: "dashboard",
          meta: {
            title: "Dashboard",
            auth: true, // You can use this meta property for auth checks
          },
        },
      ],
    },
  
  
    // {
    //   path: "/login",
    //   component: () => import("../pages/LoginPage.vue"),
    //   name: "login",
    //   meta: {
    //     guest: true,
    //   },
    // },
  
    // {
    //   path: "/register",
    //   component: () => import("../pages/RegisterPage.vue"),
    //   name: "register",
    //   meta: {
    //     guest: true,
    //   },
    // },
  
    
    {
      path: "/:notFound(.*)",
      name: "error.404",
      component: () => import("../pages/errors/NotFoundErrorPage.vue"),
    },
  ];
  

  export default routes;