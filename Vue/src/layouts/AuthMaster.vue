<template>
    <v-app id="inspire">
        <!-- for admin sidebar -->
        <SideBar></SideBar>
        <!-- Page Content -->
        <v-main>
            <slot />
        </v-main>

        <!-- Footer -->
        <v-footer padless app inset style="padding: 0!important;">
            <div class="px-4 py-2 bg-gray text-center w-100">
                {{ new Date().getFullYear() }} — <strong> Develop By Mohasagor It Solutions</strong>
            </div>
        </v-footer>

    </v-app>
</template>

<script>
import SideBar from './include/SideBar.vue';
export default {
    components: {
        SideBar,
    },

    data() {
        return {
            current_path: window.location.pathname
        }
    },

}
</script>
