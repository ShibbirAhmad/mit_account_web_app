<template>
  <nav>
    <v-app-bar color="#fff" app>
      
      
    </v-app-bar>

    <!-- ====================================== Sidebar Part Start ====================================== -->
    <v-navigation-drawer v-model="drawer" color="#2a3042" app class="darken-4 custom__sidebar">


      <!-- side bar header box -->
      <v-layout>
        <div class="">
          
          
        </div>
      </v-layout>

      
      <!-- sidebar menu start -->


      <!-- sidebar menu end -->
    </v-navigation-drawer>
    <!-- ====================================== Sidebar Part End ====================================== -->
  </nav>
</template>
<script>


export default {
  data() {
    return {
      drawer: false,
      static_image_url: this.$store.state.static_image_link,
      // image_link: this.$store.state.image_base_link,
    };
  },

  computed: {
    // video_tutorial() {
    //   return this.$store.getters.video_tutorial;
    // },
  },

  /* ========================== Methods ========================== */
  methods: {



    /* ======================= Logout ======================== */
    logout() {
      axios
        .get("/api/inventory/logout")
        .then((resp) => {
          if (resp.data.status == true) {
            this.$store.dispatch("checkInventory");
            this.$toasted.show("Logout successfully ! you are back soon .", {
              type: "error",
              position: "top-center",
              duration: 10000,
            });
          }
        })
        .catch(() => {
          this.$toasted.show("some thing want to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },
    
    
    /* ================== Check Breakpoint ================== */
    checkScreenWidth() {
      // Use Vuetify's breakpoint object to check the current screen width
      if (this.$vuetify.breakpoint.width < 960) {
        // Set the drawerOpen value to false for mobile devices
        this.drawer = false;
      } else {
        // Set the drawerOpen value to true for larger devices
        this.drawer = true;
      }
    },
  },

  computed: {
    // auth_merchant() {
    //   return this.$store.getters.auth_merchant;
    // },

    
  },

  mounted() {
    // this.$store.dispatch("authenticateMerchant");
    // Check the screen width on component mount
    this.checkScreenWidth();

    // Add a listener for window resize events
    // window.addEventListener("resize", this.checkScreenWidth);
  },
  beforeDestroy() {
    // Clean up the listener when the component is destroyed
    // window.removeEventListener("resize", this.checkScreenWidth);
  },
};
</script>
<style scoped>

</style>
