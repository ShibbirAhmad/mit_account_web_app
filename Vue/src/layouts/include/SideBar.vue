<template>
    <div>
        <!-- Menu Bar -->
        <v-navigation-drawer v-model="drawer" class="navigation__drawer">
            <div class="sidebar__header">
                <img src="/images/logo.png" alt="" />
            </div>
            <!-- sidebar body -->
            <v-list dense>
                <!-- menu start -->

                <v-list-item class="">
                    <a href="#" class="custom_router_link">
                    <span class="sidebar-menu-icon">
                        <i class="fa-solid fa-house"></i>
                    </span>
                    Dashboard
                    </a>
                </v-list-item>

                <!-- account module -->
                <v-list-group class="">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props">
                            <div class="custom_dropdown_router_link custom_mb_10">
                                <span class="sidebar-menu-icon">
                                    <i class="fa-solid fa-money-bill-transfer"></i>
                                </span>
                                Account
                            </div>
                        </v-list-item>
                    </template>
                    <div>
                        <a href="#" class="custom_router_sub_link">
                            <span class="ml-3">
                                <span class="dot_list"><i class="fa-solid fa-circle"></i></span>
                                Account Purpose
                            </span>
                        </a>
                    </div>
                </v-list-group>
                <!-- account module -->

                <v-list-item class="">
                    <button type="button" @click="logoutAccount" class="custom_router_link">
                        <span class="sidebar-menu-icon">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </span>
                        Logout
                    </button>
                </v-list-item>


                <!-- menu end -->
            </v-list>
            <!-- sidebar body -->
        </v-navigation-drawer>

        <!-- =========================================== header part =========================================== -->
        <v-app-bar>
            <div class="header_toggle_icon">
                <i class="fa-solid fa-bars" @click="drawer = !drawer"></i>
            </div>

            <div class="header_right_side_wrapper">



                <div class="custom_header_top_company_content">

                    <v-menu>
                        <template v-slot:activator="{ props }">
                            <div class="app-bar-menu-icon">
                                <span v-bind="props">
                                    <div class="d-flex justify-center">

                                        <p class="mt-2 mr-2">
                                            Saiful islam
                                        </p>
                                        <img  src="" alt="image-not" />

                                        <!-- <img v-if="$page.props.auth.user.image !== null"
                                            :src="$page.props.image_link + $page.props.auth.user.image">
                                        <img v-else :src="$page.props.static_image + '/profile_thumbnail.png'" alt="" /> -->

                                    </div>
                                </span>
                            </div>
                        </template>

                        <div class="menu-list">
                            <ul>
                                <li>
                                    <a href="#">Profile</a>
                                </li>
                                <li>
                                    <button type="button" @click="logoutAccount">Logout</button>
                                </li>
                            </ul>
                        </div>
                    </v-menu>
                </div>
            </div>
        </v-app-bar>
        <!-- =========================================== header part =========================================== -->
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data: () => ({
        drawer: null,
    }),


    methods: {

        logoutAccount() {
            axios.post('/api/admin/logout')
                .then((resp) => {
                    if (resp.data.status == true) {
                        location.href = "/admin/login"
                    }
                }).catch((err) => {
                    console.log(err);
                });
        }

    }
};
</script>


<style scoped>
.header_right_side_wrapper {
    display: flex;
    align-items: center;
}

.custom_header_top_language_content {
    padding-right: 15px;
}

.__custom__header__top__language {
    background-color: #ecedfd;
    border-radius: 30px;
    display: flex;
    align-items: center;
}

.__custom__header__top__language__english {
    font-size: 12px;
    padding: 1px 5px;
}

.__custom__header__top__language__bangla {
    font-size: 12px;
    padding: 1px 5px;
}

.language__select__active {
    background-color: #464deb;
    color: #ffffff;
    border-radius: 30px;
}

.custom_header_top_company_content {
    border-left: 1px solid #dddddd;
    text-align: center;
    padding-left: 15px;
}
</style>
