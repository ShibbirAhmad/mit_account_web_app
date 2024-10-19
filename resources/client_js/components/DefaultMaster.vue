<template lang="">
    <div>
        <!-- main part -->
        <v-app id="inspire">
            <Sidebar />

            <v-main class="home-page">
                <router-view></router-view>
            </v-main>

            <Footer />
        </v-app>
        <!-- main part -->
    </div>
</template>
<script>
import Sidebar from "./Sidebar.vue";
import Footer from "./Footer.vue";
export default {
    name: "App",
    components: {
        Sidebar,
        Footer,
    },

    // created() {
    //     this.$store.dispatch("checkInventory");
    // },
};
</script>
<style lang=""></style>
