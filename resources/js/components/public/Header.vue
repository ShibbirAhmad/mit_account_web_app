<template>
  <div>
    <div class="header" id="__header_main">
      <div class="header-top">
        <div class="container flex">
          <div class="header-top-left">
            <!-- <li>
            <i class="fa fa-envelope"></i>
            <span>some@gmail.com</span>
          </li> -->
            <li>
              <i class="fa fa-phone"></i>
              <a href="tel:09636-203040">09636-203040 (9am-11pm)</a>
            </li>
          </div>
          <div class="header-top-right">
            <!-- <li>

            <i class="fa fa-envelope"></i>
            <span>Reseller</span>
          </li> -->
            <!-- <li>
            <i class="fa fa-phone"></i>
            <span>01795297424</span>
          </li> -->
            <li>
              <i class="fa fa-users"></i>
              <span>
                <router-link target="_blank" :to="{ name: 'merchant_login' }"
                  >Merchant</router-link
                >
              </span>
            </li>
            <!-- <li>
            <i class="fa fa-user"></i>
            <span> <a :href="'/_reseller/login'">Reseller</a> </span>
          </li> -->
          </div>
        </div>
      </div>
      <div class="main-header">
        <div class="container flex">
          <div class="main-header-left">
            <li id="toggle-menu" @click="menuShow">
              <i class="fa fa-bars" id="__icon_fa_menu"></i>
            </li>
            <li>
              <a href="/">
                <img
                  :src="base_url + 'images/unnamed.gif'"
                  class="site-logo"
                  alt=""
                />
              </a>
            </li>
            <li>
              <form @submit.prevent="subMitAutoComppleteForm">
                <input
                  type="text"
                  placeholder="search products "
                  class="search-input"
                  @keyup="autocomplteSearch"
                  v-model="search"
                />
                <button class="search-btn"><i class="fa fa-search"></i></button>
                <div class="search-content" v-if="search_products.length >= 1">
                  <ul class="list-group">
                    <li
                      class="list-group-item"
                      v-for="(product, index) in search_products"
                      :key="index"
                    >
                      <router-link
                        :to="{ name: 'single', params: { slug: product.slug } }"
                        class="search-router-link"
                      >
                        <div class="__search_poruduct-details">
                          <div class="__search_img">
                            <img
                              v-if="product.product_image.length"
                              :src="
                                base_url +
                                product.product_image[0].product_image
                              "
                              alt=""
                            />
                          </div>
                          <div class="__search_porducts_details">
                            {{ product.name + "-" + product.product_code }}

                            <p>
                              &#2547; {{ product.price }}
                              <small v-if="product.discount">
                                <del>&#2547; {{ product.sale_price }}</del>
                              </small>
                            </p>
                          </div>
                        </div>
                      </router-link>
                    </li>
                  </ul>
                </div>
              </form>
            </li>
          </div>
          <div class="main-header-right">

            <li class="our_service_list">
              <div class="service_btn_container">
                 <div class="service_outer">
                    <div class="service_inner">
                        <a class="btn" >
                          Our Services</a>
                    </div>
                 </div>
              </div>

                <ul class="our_service_item">
                  <li v-for="(item,index) in services" :key="index" >
                    <a target="_blank" :href="item.url">{{ item.name }}</a>
                   </li>
                </ul>
            </li>

            <li v-if="Object.keys(user).length">
              <div class="dropdown">
                <a
                  class="btn btn-secondary dropdown-toggle"
                  type="button"
                  id="dropdownMenuButton"
                  data-toggle="dropdown"
                  aria-haspopup="true"
                  aria-expanded="false"
                >
                  {{ user.mobile_no }}
                </a>
                <div
                  class="dropdown-menu"
                  aria-labelledby="dropdownMenuButton"
                  style="padding: 15px 15px"
                >
                  <router-link
                    :to="{ name: 'UserDashboard' }"
                    class="dropdown-item"
                    href="#"
                    >Dashboard</router-link
                  ><br /><br />
                  <router-link
                    :to="{ name: 'PasswordEdit' }"
                    class="dropdown-item"
                    href="#"
                    >Change Password</router-link
                  ><br /><br />
                  <router-link
                    :to="{ name: 'user_new_password_set' }"
                    class="dropdown-item"
                    href="#"
                    >Set New Password</router-link
                  ><br /><br />

                  <a class="dropdown-item" href="#" @click="Logout">Logout</a>
                </div>
              </div>
            </li>
            <li v-else>
              <router-link
                style="color: #000; font-size: 16px"
                :to="{ name: 'otpLogin' }"
              >
                Login/Signup
              </router-link>
            </li>
          </div>
        </div>
      </div>
      <div class="menu" id="navbar">
        <ul class="menu-list" id="menu-list">
          <li
            v-for="category in categories"
            :key="category.id"
            class="menu-item"
          >
            <router-link
              :to="{ name: 'PublcaCategory', params: { slug: category.slug } }"
              class="menu-item-link"
            >
              {{ category.name }}
            </router-link>
            <i
              class="fa fa-angle-down menu-icon"
              @click="shownextElement"
              v-if="category.sub_category.length > 0"
            ></i>
            <ul class="sub-item-list" v-if="category.sub_category.length > 0">
              <li
                class="sub-item"
                v-for="sub_category in category.sub_category"
                :key="sub_category.id"
              >
                <router-link
                  :to="{
                    name: 'PublicSubCategory',
                    params: { slug: sub_category.slug },
                  }"
                  class="sub-item-link"
                  >{{ sub_category.name }}</router-link
                >
                <i
                  class="fa fa-angle-down sub-menu-icon"
                  v-if="sub_category.sub_sub_category.length"
                  @click="shownextElement"
                ></i>
                <ul
                  class="sub-sub-item-list"
                  v-if="sub_category.sub_sub_category.length"
                >
                  <li
                    class="sub-sub-item"
                    v-for="sub_sub_category in sub_category.sub_sub_category"
                    :key="sub_sub_category.id"
                  >
                    <router-link
                      :to="{
                        name: 'PublicSubSUbCategory',
                        params: { slug: sub_sub_category.slug },
                      }"
                      class="sub-sub-item-link"
                      >{{ sub_sub_category.name }}</router-link
                    >
                  </li>
                </ul>
              </li>
            </ul>
          </li>
          <li class="menu-item">
            <router-link
              :to="{ name: 'publicCampaign' }"
              class="menu-item-link"
            >
              Campaigns
            </router-link>
          </li>
        </ul>
        <!-- <ul class="__higlight_menu">
        <li>
          <a href="https://mohasagor.com/public/sub/caetgory/products/1049-Campaign" target="_blank">Campaign

            <small>
              <strong>(70% OFF)</strong>
            </small>
          </a>
        </li>
      </ul> -->
      </div>
    </div>
    <div class="cart" id="s-cart">
      <div class="cart-header" @click="cartClosed">
        <h4 id="exitcart" class="exitC">CLOSED</h4>
      </div>
      <div class="cart-body">
        <div
          class="row cart_row"
          v-for="(cart_content, index) in cart.content"
          :key="index"
          style="border-bottom: 1px solid #ddd"
        >
          <div class="col-lg-5 col-sm-5 flex" style="align-items: center">
            <ul
              class="p-image-name"
              style="display: flex; margin: 0; padding: 0px 0px"
            >
              <li>
                <img
                  :src="base_url + cart_content.options.image[0].product_image"
                  style="max-width: 35px; max-height: 35px"
                  alt=""
                />
              </li>
              <li>{{ cart_content.name }}</li>
            </ul>
          </div>
          <div class="col-lg-2 col-sm-2 cart_responsive_item flex">
            <u style="text-decoration: none">
              <li class="q-i-d" @click="increamentQuantity(cart_content)">
                <i class="fa fa-angle-up"></i>
              </li>
              <li>{{ cart_content.qty }}</li>
              <li class="q-i-d" @click="decreamentQuantity(cart_content)">
                <i class="fa fa-angle-down"></i>
              </li>
            </u>
          </div>
          <div class="col-lg-2 col-sm-2 cart_responsive_price">
            <h6>{{ cart_content.price }}</h6>
          </div>
          <div class="col-lg-2 col-sm-2 cart_responsive_total">
            <h6>{{ cart_content.price * cart_content.qty }}</h6>
          </div>
          <div class="col-lg-1 col-sm-1 cart_responsive_remove">
            <h6
              class="text-danger"
              style="cursor: pointer"
              @click="cartRemove(cart_content)"
            >
              X
            </h6>
          </div>
        </div>
      </div>
      <div class="cart-empy" v-if="cart.itemCount <= 0">
        <img :src="base_url + 'images/static/cartEmpty.jpg'" />
        <p>Your cart is empty</p>
      </div>
      <div class="cart-footer">
          <router-link  :to="{name:'Chekout'}" class="btn btn-block placebtn"
          >Place Order | <span> {{ cart.total }}</span></router-link>
          
        <!-- <a class="btn btn-block placebtn" @click.prevent="goToCheckOut"
          >Place Order | <span> {{ cart.total }}</span></a
        > -->

      </div>
    </div>

    <div class="cart-open" @click="cartOpen">
      <div class="cart-total">
        <i class="fa fa-shopping-bag"></i>
        <h5>{{ cart.total }}</h5>
      </div>
      <div class="cart-item-total">{{ cart.itemCount }} items</div>
    </div>
  </div>
</template>

<script>
export default {
  name: "main-header",
  //props: ["categories"],

  data() {
    return {
      renderComponent: false,
      cartContents: null,
      cartTotal: "",
      display: "none",
      base_url: this.$store.state.image_base_link,
      search_products: [],
      search: "",
    };
  },
  methods: {

    increamentQuantity(cartContent) {
      let quantity = parseInt(cartContent.qty) + parseInt(1);
      axios
        .get("/_public/cartToUpdate", {
          params: {
            qty: quantity,
            rowId: cartContent.rowId,
          },
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.$store.dispatch("getCartContent");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    decreamentQuantity(cartContent) {
      if (cartContent.qty == 1) {
        alert("Qauntity shoud be at least 1");
        return;
      }

      let quantity = parseInt(cartContent.qty) - parseInt(1);
      axios
        .get("/_public/cartToUpdate", {
          params: {
            qty: quantity,
            rowId: cartContent.rowId,
          },
        })
        .then((resp) => {
          if (resp.data.status == "SUCCESS") {
            this.$store.dispatch("getCartContent");
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    cartRemove(cartContent) {
      if (confirm("are you sure remove this item?")) {
        axios
          .get("/_public/cartToDestroy", {
            params: {
              rowId: cartContent.rowId,
            },
          })
          .then((resp) => {
            console.log(resp);
            if (resp.data.status == "SUCCESS") {
              this.$store.dispatch("getCartContent");
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    changeDisplay() {
      if (this.display == "block") {
        this.display = "none";
      } else {
        this.display = "block";
      }
    },

    Logout() {
      axios
        .get("/_public/user/logout")
        .then((resp) => {
          console.log(resp);
          this.user = null;
          localStorage.removeItem("user_token");
          location.reload();
        })
        .catch();
    },

    autocomplteSearch() {
      if (this.search.length > 1) {
        axios
          .get("/_public/search/products/" + this.search)
          .then((resp) => {
            if (resp.data.length) {
              this.search_products = [];
              this.search_products.push(...resp.data);
            } else {
              this.search_products = [];
            }
          })
          .catch((error) => {
            console.log(error);
          });
      } else {
        this.search_products = [];
      }
    },
    subMitAutoComppleteForm() {
      this.$router.push({
        name: "PublicProductSearch",
        params: { search: this.search },
      });
    },
    menuShow() {
      let clickMenu = document.getElementById("toggle-menu");
      let main_menu = document.getElementById("menu-list");
      main_menu.classList.toggle("collapse-manu");
      let menu_icon = document.getElementById("__icon_fa_menu");

      if (menu_icon.classList.contains("fa-bars")) {
        menu_icon.classList.remove("fa-bars");
        menu_icon.classList.add("fa-close");
      } else {
        menu_icon.classList.add("fa-bars");
        menu_icon.classList.remove("fa-close");
      }
    },
    cartOpen() {
      document.getElementById("s-cart").classList.add("colapse-cart");
    },
    cartClosed() {
      document.getElementById("s-cart").classList.remove("colapse-cart");
    },
    goToCheckOut() {
      if (this.cart.total <= 0) {
        this.$toasted.show("Your cart is empty", {
          type: "error",
          position: "top-center",
          duration: 2000,
        });

        return;
      }

      axios.get("/_public/cart/check").then((resp) => {
        if (resp.data.success) {
          window.open("/user/Checkout", "_self");
        } else {
          this.$toasted.show(resp.data.message || "Something Wrong", {
            type: "error",
            position: "top-center",
            duration: 40000000,
          });
        }
      });

      //
    },
    shownextElement(e) {
      let target_element = e.target;
      let sub_menu_ul = target_element.nextSibling.nextElementSibling;
      sub_menu_ul.classList.toggle("show");
      target_element.classList.toggle("rotate");

      console.log(target_element);
    },
    handleScrol() {
      let header = document.getElementById("__header_main");

      // if (window.pageYOffset > 1500 ) {
      //   header.classList.add("__sticky");
      // } else {
      //   header.classList.remove("__sticky");
      // }
    },
  },

  mounted() {
    this.$store.dispatch("getCartContent");
    this.$store.dispatch("user");
    this.$store.dispatch("category");
    window.addEventListener("scroll", this.handleScrol);
  },
  computed: {
    user() {
      return this.$store.getters.user;
    },
    categories() {
      return this.$store.getters.categories;
    },
    services(){
         return this.$store.getters.services;
    },
    cart() {
      return this.$store.getters.cartContent;
    },
  },
};
</script>


<style>
.nav-active {
  display: block !important;
}
.sticky {
  position: fixed;
  top: 0;
  width: 100%;
  z-index: 9999999999999;
}
.q-i-d {
  cursor: pointer;
}
ul.p-image-name li {
  padding: 0px 2px;
  font-size: 12px;
}
</style>
