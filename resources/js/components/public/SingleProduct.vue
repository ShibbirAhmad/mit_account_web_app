<template>
  <div class="wrapper-wide">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>
    <frontend-header></frontend-header>
    <div id="container" v-if="!isLoading">
      <div class="container">
        <div class="single-product-box" >
          <div class="row">
            <div class="col-lg-5">
                <div>
              <div class="row product-info">
                <div class="col-lg-6 col-md-6 product-image-viewer" style="margin-bottom:20px;"  >
                  <ProductImage :images="product_images"></ProductImage>
                  <!-- <ProductZoomer :base-images="product_images" :base-zoomer-options="zoomerOptions" v-if="Object.keys(product_images).length" /> -->
                  </div>

              </div>
             </div>
            </div>
            <div class="col-lg-6">
               <ul class="list-unstyled description">
                  <li>
                 <h2 class="title" itemprop="name">{{ product.name }}</h2>

                  </li>
                    <li>
                      <h4>
                        <b>Product Code:</b>
                        <span itemprop="mpn">{{ product.product_code }}</span>
                      </h4>
                    </li>
                    <!-- <li v-if="product.merchant_id==282">
                      <h4>
                        <b>Availability:</b>
                        <span class="instock" v-if="product.stock > 0">Stock In <small>({{product.stock }})</small></span>
                        <span class="outstcok" v-else>Stock Out</span>
                      </h4>
                    </li> -->
                     <!-- <li v-if="product.merchant_id">
                      <b>Brand:</b>
                     <router-link :to="{ name: 'merchantProduct', params: { id: product.merchant_id } }">

                        <strong>
                          {{ product.merchant.company_name ?  product.merchant.company_name : "" }}
                        </strong>

                     </router-link>

                    </li> -->
                  </ul>
                  <ul class="price-box">
                    <li class="price">
                     <h3>

                        <span class="price-old" v-if="product.discount">&#2547 {{product.sale_price}}</span>
                      <span class="price-new">&#2547 {{product.price}}</span>
                     </h3>
                    </li>
                    <li></li>
                  </ul>
                  <div id="product">
                    <div class="row">
                      <div class="col-lg-4" v-if="product.product_variant  && product.product_attribute">
                          <div>
                      <!-- <h3 class="subtitle">Available Options</h3> -->
                      <div class="form-group required">
                        <h4 class="control-label">
                          <b>{{ product.product_attribute.attribute.name }}</b>
                        </h4>
                        <select
                          class="form-control"
                          v-model="variant_index"
                          @change="SelectVaraint"
                          name="option[200]"
                        >
                          <option value disabled>--- Please Select ---</option>
                          <option
                            v-for="(variant,v) in product.product_variant"
                            :key="v"
                            :value="v"
                          >{{variant.variant.name}}</option>
                        </select>
                      </div>
                    </div>
                      </div>
                       <div class="col-lg-4">
                        <div class>
                          <h4 class="control-label" for="input-quantity">
                            <b>Quantity</b>
                          </h4>
                          <input
                            type="number"
                            name="quantity"
                            v-model="cart.quantity"
                            size="2"
                            value="1"
                            class="form-control"
                            @change="validation"
                            @keyup="validation"
                          />

                          <div class="clear"></div>
                        </div>
                      </div>
                    </div>

                    <div class="row">



                         <div class="col-lg-4"  v-if="product.single_checkout=='1'">
                        <button

                          @click.prevent="buyNow"
                          type="button"
                          class="btn btn-primary btn-lg btn-block"
                           style="margin-top:38px;"
                          >Buy Now</button>
                      </div>

                      <div class="col-lg-4">
                        <button
                          @click.prevent="CartToAdd"
                          type="button"
                          id="button-cart"
                          class="btn btn-primary btn-lg btn-block"
                          style="margin-top:38px;"
                          >
                         <span>Add To Cart</span>
                        </button>
                      </div>
                    </div>

                  </div>
            </div>
         </div>
        </div>

          <div class="product-details-tabe">
               <ul class="details-tab-menu-list">
                  <li class="details-tab-menu-item"  @click="tab_content=1" :class="{'tab-menu-item-active':tab_content==1}">Description</li>
                    <li class="details-tab-menu-item" @click="tab_content=2" :class="{'tab-menu-item-active':tab_content==2}" >How To Buy</li>
                      <li class="details-tab-menu-item"  @click="tab_content=3" :class="{'tab-menu-item-active':tab_content==3}">Return Policy</li>
                       <li class="details-tab-menu-item"  @click="tab_content=4" :class="{'tab-menu-item-active':tab_content==4}">Reviews({{ product.product_reviews.length ? product.product_reviews.length : '0' }})</li>
                 </ul>
              <div class="product-tab-content">
                <div v-html="product.details" class="product-details" :class="{block:tab_content==1}"></div>
                <div class="how-to-buy" :class="{block:tab_content==2}">
                  <ul>
                    <li class="h-b-li">Select the number of products you want to buy</li>
                    <li class="h-b-li">Click <strong>Add To Cart</strong> Button</li>
                    <li class="h-b-li">Then go to the checkout page</li>
                    <li class="h-b-li">If you are a new user, please click on Sign Up.provide us your valid information.</li>
                    <li class="h-b-li">Complete your checkout, we received your order, and for order confirmation or customer service contact with you</li>
                  </ul>
                </div>
                 <div class="how-to-buy"  :class="{block:tab_content==3}">
                  <ul>
                    <li class="h-b-li">
                      If your product is damaged, defective, incorrect, or incomplete at the time of delivery, please file a return request on call to the customer care support number within 3 days of the delivery date                      </li>
                    <li class="h-b-li">Change of mind is not applicable as a Return Reason for this product</li>

                  </ul>
                </div>


                  <div class="how-to-buy"  :class="{block:tab_content==4}">

                 <div v-if="product.product_reviews" class="user_star_rating_container">
                      <p>average based on {{ product.product_reviews.length }} reviews.</p>
                       <div class="user_rating_row">
                        <div class="side">
                          <div>5 star</div>
                        </div>
                        <div class="middle">
                          <div class="bar-container">
                            <div :style="{width:(rating_stars.five_star/product.product_reviews.length)*100+'%'}" class="bar-5"></div>
                          </div>
                        </div>
                        <div class="side right">
                          <div>{{ rating_stars.five_star }}</div>
                        </div>
                        <div class="side">
                          <div>4 star</div>
                        </div>
                        <div class="middle">
                          <div class="bar-container">
                            <div :style="{width:(rating_stars.four_star/product.product_reviews.length)*100+'%'}" class="bar-4"></div>
                          </div>
                        </div>
                        <div class="side right">
                          <div>{{ rating_stars.four_star }}</div>
                        </div>
                        <div class="side">
                          <div>3 star</div>
                        </div>
                        <div class="middle">
                          <div class="bar-container">
                            <div  :style="{width:(rating_stars.three_star/product.product_reviews.length)*100+'%'}" class="bar-3"></div>
                          </div>
                        </div>
                        <div class="side right">
                          <div>{{ rating_stars.three_star }} </div>
                        </div>
                        <div class="side">
                          <div>2 star</div>
                        </div>
                        <div class="middle">
                          <div class="bar-container">
                            <div :style="{width:(rating_stars.two_star/product.product_reviews.length)*100+'%'}" class="bar-2"></div>
                          </div>
                        </div>
                        <div class="side right">
                          <div>{{ rating_stars.two_star }} </div>
                        </div>
                        <div class="side">
                          <div>1 star</div>
                        </div>
                        <div class="middle">
                          <div class="bar-container">
                            <div :style="{width:(rating_stars.one_star/product.product_reviews.length)*100+'%'}" class="bar-1"></div>
                          </div>
                        </div>
                        <div class="side right">
                          <div>{{ rating_stars.one_star }}</div>
                        </div>
                      </div>


                      <div v-for="(review,index) in product.product_reviews" :key="index" class="row user_review_display">
                           <div class="col-md-2 col-sm-2 col-xs-6">
                              <img class="reviewer_image" :src="review.reviewer.image?base_url+review.reviewer.image:base_url+'images/user_profile.png'" >
                               <p> {{ review.reviewer.name }} </p>
                           </div>
                           <div class="col-md-2 col-sm-2 col-xs-6">
                              <span class="user_rating_stars" >
                                <i v-for="(star,index) in review.rating_starts" :key="index"  class="fa fa-star __highlight_star "> </i>

                                <i v-for="(star,index) in (parseInt(5)-parseInt(review.rating_starts))" :key="index"  class="fa fa-star "> </i>
                                </span>
                           </div>
                           <div class="col-md-8 col-sm-8 col-xs-12">
                             <div class="row">
                               <div class="col-md-6 col-xs-6">   <p class="review_text" > {{ review.review }} </p> </div>
                               <div class="col-md-6 col-xs-6">  <img v-if="review.image" class="review_image" :src="base_url+review.image" > </div>
                             </div>


                           </div>
                    </div>
                 </div>

                    <div v-if="Object.keys(user).length" class="review_section">
                       <form @submit.prevent="addUserReview"   enctype="multipart/form-data">
                        <h4>write your review </h4>
                        <div class="star_section">
                           <span>
                            <i class="fa fa-star r_star"  v-for="(star,index) in 5" :key="index" :star="index" @click="higlightStar(index)" :id="'__start_'+index" > </i>

                         </span>
                        </div>

                         <div class="form-group">
                           <div class="row">
                               <div class="col-md-6 col-xs-12">
                             <textarea v-model="form.review_text" class="form-control" required  rows="3" ></textarea>

                          </div>
                          <div class="col-md-6 col-xs-12">
                            <input @change="reviewImage" type="file" name="review_img" id="PreviewImage">
                          </div>
                           </div>
                         </div>
                        <div class="form-group text-center">
                           <button class="btn btn-primary" type="submit"> submit </button>

                        </div>
                        </form>
                    </div>

                     <div v-else class="login_section ">
                           <router-link class="btn btn-primary" :to="{name: 'UserLogin'}">login to review</router-link>
                     </div>

                </div>
              </div>

            </div>



        <div class="row realted-producs">
            <h3 class="title" >Related Products</h3>
             <div class="col-lg-3 col-sm-6 col-md-6 col-xs-6 width-20" v-for="(product,index) in related_products" :key="index">
            <div class="product-card small-card">
              <div class="prodict-card-body">
                <a :href="'/p/'+product.slug">
                  <img :src=" base_url +product.product_image[0].product_image"/>
                </a>
                <div class="product-detail small-detail">
                  <h4>

                      <a class="product-link" :href="'/p/'+product.slug">{{ product.name }}</a >

                      </h4>
                  <p class="price">
                    <span class="price-new">{{
                      product.price
                    }}</span>
                    <span
                      class="price-old"
                      v-if="product.discount"
                      >{{ product.sale_price }}</span
                    >
                       <span
                          v-if="product.discount > 0"
                          class="discount"
                        >
                          <i class="fa fa-star discount_star1"> </i>
                          <i class="fa fa-star discount_star2"> </i>
                          {{
                            (
                              (product.discount / product.sale_price) *
                              100
                            ).toFixed(0)
                          }}% <span class="d_off">off</span>
                        </span>
                  </p>
                </div>
              </div>
               <div class="product-card-footer">
                    <button class="btn btn-primary btnQuick" style="cursor:pointer" @click="quick_v_product_id=product.id"  >view</button>
              </div>
            </div>
          </div>

           <!-- <infinite-loading @infinite="getRelatedProducts">
            <div slot="no-more"></div>
          </infinite-loading> -->

      </div>
      <!-- <div class="row">
        <div class="col-lg-12 text-center">
                    <router-link v-if="product.sub_sub_category_id"
                      :to="{
                      name: 'PublicSubSUbCategory',
                      params: { slug: product.sub_sub_category.slug }}"
                      class="btn btn-primary">See More </router-link>

                      <router-link  v-else
                      :to="{
                      name: 'PublicSubCategory',
                      params: { slug: product.sub_category.slug }}"
                     class="btn btn-primary">See More </router-link>

        </div>
      </div> -->

      <div class="best-selling">
        <h4>Top Selling Products</h4>
        <div class="row" v-if="best_selling_produtcs.length > 0">
          <carousel
            :nav="false"
            :autoplay="true"
            :autoplayTimeout="4000"
            :responsive="{ 0: { items: 2 }, 600: { items: 5 } }"
          >
            <div
              class="col-lg-3 col-sm-6 col-md-6 col-xs-6"
              v-for="best_slllers_product in best_selling_produtcs"
              :key="best_slllers_product.id"
            >
              <div class="product-card top-product-card-small">
                <div class="prodict-card-body">
                  <a
                    :href="'/p/'+best_slllers_product.slug"
                  >
                    <img
                      v-if="best_slllers_product.product_image.length"
                      :src="
                        base_url +
                        best_slllers_product.product_image[0].product_image
                      "
                    />
                  </a>
                  <div class="product-detail">
                    <h4>
                      <a
                        class="product-link"
                          :href="'/p/'+best_slllers_product.slug"
                        >{{ best_slllers_product.name }}</a
                      >
                    </h4>
                    <p class="price">
                      <span class="price-new">{{
                        best_slllers_product.price
                      }}</span>
                      <span
                        class="price-old"
                        v-if="best_slllers_product.discount"
                        >{{ best_slllers_product.sale_price }}</span>
                        <span
                          v-if="best_slllers_product.discount > 0"
                          class="discount"
                        >
                          <i class="fa fa-star discount_star1"> </i>
                          <i class="fa fa-star discount_star2"> </i>
                          {{
                            (
                              (best_slllers_product.discount / best_slllers_product.sale_price) *
                              100
                            ).toFixed(0)
                          }}% <span class="d_off">off</span>
                        </span>
                    </p>
                  </div>
                </div>
                <div class="product-card-footer">
                  <button
                    class="btn btn-primary btnQuick"
                    style="cursor: pointer"
                    @click="quick_v_product_id = best_slllers_product.id"
                  >
                    view
                  </button>
                </div>
              </div>
            </div>
          </carousel>
        </div>
      </div>


      </div>
    </div>
    <frontend-footer></frontend-footer>
      <quick-view v-if="quick_v_product_id" v-on:clicked="closedModal($event)" :quick_v_p_id="quick_v_product_id">  </quick-view>

  </div>

</template>


<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import ProductImage from "./ProductImageView.vue";
import carousel from "vue-owl-carousel";

export default {
  created() {
    this.validation();
    // this.getRelatedProducts()
    window.scrollTo(0, 0);

    setTimeout(() => {
      this.validation();
    }, 200);
  },
  data() {
    return {
      isLoading: true,
      fullPage: true,
      disabled: true,
      variant_index: "",
      base_url: this.$store.state.image_base_link,
      cart: {
        product_id: "",
        variant_id: "",
        attrribute_id: "",
        quantity: 1,
      },
      related_products: [],
      page: 1,
      tab_content: 1,
      quick_v_product_id: "",
      o_modal: false,
      zoomerOptions: {
        zoomFactor: 4,
        pane: "pane",
        hoverDelay: 300,
        namespace: "zoomer-left",
        move_by_click: true,
        scroll_items: 4,
        choosed_thumb_border_color: "#ff3d00",
        scroller_position: "bottom",

        // zoomer_pane_position: "right",
      },
      product_reviews: "",
      form: new Form({
        star_number: "",
        review_text: "",
        review_img: "",
      }),
    };
  },
  methods: {

    CartToAdd() {
      axios
        .get("/_public/addToCart", {
          params: {
            slug: this.$route.params.slug,
            attribute_id: this.cart.attrribute_id,
            variant_id: this.cart.variant_id,
            quantity: this.cart.quantity,
          },
        })
        .then((resp) => {
          // console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show(resp.data.message, {
              position: "bottom-left",
              type: "success",
              duration: 2000,
            });
            this.$store.dispatch("getCartContent");
          } else if (resp.data.status == "error") {
            this.$toasted.show(resp.data.message, {
              position: "bottom-center",
              type: "error",
              duration: 4000,
            });
          }
        })
        .then((error) => {
          // console.log(error);
        });
    },

    buyNow() {
      axios
        .get("/_public/addToCart", {
          params: {
            slug: this.$route.params.slug,
            attribute_id: this.cart.attrribute_id,
            variant_id: this.cart.variant_id,
            quantity: this.cart.quantity,
          },
        })
        .then((resp) => {
          // console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.$store.dispatch("getCartContent");
            this.$toasted.show(resp.data.message, {
              position: "bottom-left",
              type: "success",
              duration: 2000,
            });
            this.$router.push({ name: "Chekout" });
            // let user = this.user;
            // if (user.mobile_no) {
            //   window.open("/user/Checkout", "_self");

            //   // this.$router.push({ name: "Chekout" });
            // } else {
            //   window.open("/otp/login", "_self");
            // }
          } else if (resp.data.status == "error") {
            this.$toasted.show(resp.data.message, {
              position: "bottom-center",
              type: "error",
              duration: 4000,
            });
          }
        })
        .then((error) => {
          // console.log(error);
        });
    },

    validation() {
      if (this.cart.quantity < 1) {
        this.cart.quantity = 1;
        alert("Quantity at least 1");
        return;
      }
      if (this.product.product_attribute) {
        if (this.cart.attrribute_id < 1) {
          this.disabled=true;
        } else {
          this.disabled = false;
        }
      } else {
        this.disabled = false;
      }
    },
    SelectVaraint() {
      // this.product.product_variant.length=0;
      let index = this.variant_index;
      let variant_id = this.product.product_variant[index].variant_id;
      let attribute_id = this.product.product_variant[index].variant
        .attribute_id;
      this.cart.attrribute_id = attribute_id;
      this.cart.variant_id = variant_id;
      this.validation();
    },

    getRelatedProducts($state) {
      axios
        .get("/_public/related/products/?page=" + this.page, {
          params: {
            product_slug: this.$route.params.slug,
          },
        })
        .then((resp) => {
          if (resp.data.data) {
            this.page += 1;
            this.related_products.push(...resp.data.data);

            // if (parseInt(resp.data.last_page) == parseInt(this.page)) {
            //   document.getElementById("loadMore").style.display = "none";
            // } else {
            //   document.getElementById("loadMore").style.display = "block";
            // }

            console.log(resp.data.last_page);
            $state.loaded();
          } else {
            $state.complete();
          }

          console.log(resp);
        })
        .catch((error) => {
          // console.log(error);
        });
    },
    closedModal(close) {
      this.quick_v_product_id = "";
    },

    higlightStar(index) {
      this.form.star_number = index;
      for (let i = 0; i <= index; i++) {
        let element = document.getElementById("__start_" + i);
        if (element.classList.contains("__highlight_star")) {
          element.classList.remove("__highlight_star");
        } else {
          element.classList.add("__highlight_star");
        }
      }
    },

    addUserReview() {
      this.form
        .post(
          "/_public/api/add/customer/review/to/product/" + this.product.id,
          {
            transformRequest: [
              function (data, headers) {
                return objectToFormData(data);
              },
            ],
          }
        )
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.form.review_text = "";
            this.higlightStar(this.form.star_number);
            this.form.review_img = "";
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          } else {
            this.$toasted.show("sorry! something went wrong", {
              type: "error",
              position: "top-center",
              duration: 4000,
            });
          }
        });
    },

    reviewImage(e) {
      const file = e.target.files[0];
      ///let image file size check
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();
        img.onload = () => {
          console.log(img.width + "-" + img.height);
        };
        img.src = evt.target.result;
        // this.preview_image = evt.target.result;
        this.form.review_img = file;
      };
    },

    loadMore() {
      this.$Progress.start();
      this.isLoading = true;
      this.getRelatedProducts();
      this.isLoading = false;
      this.$Progress.finish();
    },
  },

  mounted() {
    this.getRelatedProducts();
    this.$store.dispatch("product_images", this.$route.params.slug);
    this.$store.dispatch("single_product", this.$route.params.slug);
    this.$store.dispatch("user");
    this.$store.dispatch("sliders");
  },

  computed: {
    product() {
      return this.$store.getters.single_product;
    },
    rating_stars() {
      return this.$store.getters.rating_stars;
    },
    product_images() {
      return this.$store.getters.product_images;
    },

    user() {
      return this.$store.getters.user;
    },
    best_selling_produtcs() {
      return this.$store.getters.best_selling_produtcs;
    },
  },

  components: {
    Loading,
    ProductImage,
    carousel,
    HasError,
  },

  watch: {
    product_images: function (value) {
      if (Object.keys(value).length > 0) {
        this.isLoading = false;
      }
    },
  },
};
</script>

<style scoped>
img.responsive-image.preview-box {
  width: 350px !important;
  height: 350px !important;
}

.product-details-tabe {
  margin-top: 20px;
}

.btnQuick:hover {
  background: #ff4d03;
}

@media screen and (max-width: 350px) {
  img.responsive-image.preview-box {
    width: 85% !important;
    margin-left: -80px;
  }

  .product-info {
    margin-bottom: -160px;
  }

  .product-details {
    margin-left: -16px;
  }
  ul {
    list-style: none;
    margin-left: -16px;
    margin-right: 3px;
  }

  .r_title {
    margin-left: 20px;
  }

  h4 {
    font-size: 16px;
  }
  h2 {
    font-size: 20px;
  }

  .small-card {
    width: 100%;
    height: auto;
    background: #fff;
    border: 1px solid #ddd;
    text-align: center;
    margin-bottom: 25px;
  }

  .small-card p {
    font-size: 13px;
    line-height: 0px;
    margin-top: -5px;
  }

  .small-detail h4 {
    font-size: 13px;
    padding-bottom: 3px;
    margin-top: -5px !important;
  }

  .small-card img {
    padding-bottom: 0px;
    width: 120px !important;
    height: 130px !important;
    transition: 0.3s;
  }

  .details-tab-menu-list li {
    font-size: 11px !important;
  }
}

.__highlight_star {
  color: #ff4d03;
}
</style>