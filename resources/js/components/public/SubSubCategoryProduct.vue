<template>
  <div class="wrapper-wide">
    <frontend-header></frontend-header>
    <div id="container">
      <div class="container">
        <div class="slider slider-animation bv xz">
          <div class="row">
            <div class="col-lg-12">
              <carousel
                v-if="sub_sub_category_sliders"
                :items="1"
                :nav="false"
                :autoplay="true"
                :autoplayTimeout="2000"
              >
                <a
                  v-for="slider in sub_sub_category_sliders"
                  :href="slider.url"
                  :key="slider.id"
                >
                  <img :src="base_url + slider.image" />
                </a>
              </carousel>
            </div>
          </div>
        </div>

        <!-- Breadcrumb Start-->
        <ul class="breadcrumb">
          <li
            v-for="sub_category in sub_sub_categories"
            v-if="sub_category.slug == $route.params.slug"
          >
            <router-link
              :to="{ name: 'PublicSubCategory', params: { slug: sub_category.slug } }"
            >
              <i class="fa fa-home"></i>
              /
              {{ sub_category.name }}
            </router-link>
          </li>
        </ul>

        <!-- Breadcrumb End-->
        <div class="row">
          <!--Left Part Start -->
          <aside id="column-left" class="col-sm-3 hidden-xs">
            <div class="categories hidden-xs bg-white shadow c-box">
              <h3 class="subtitle">Other's Categories</h3>
              <div class="box-category">
                <ul id="cat_accordion">
                  <li
                    v-for="sub_category in sub_sub_categories"
                    v-if="sub_category.slug != $route.params.slug"
                  >
                    <router-link
                      :to="{
                        name: 'PublicSubCategory',
                        params: { slug: sub_category.slug },
                      }"
                      >{{ sub_category.name }}</router-link
                    >
                  </li>
                </ul>
              </div>
            </div>

            <div class="search-box hidden-xs bg-white shadow c-box">
              <h3 style="margin-left: 20px" class="subtitle">filter by price</h3>
              <form @submit.prevent="priceFilter">
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                    <label for="min-price">min-price</label>
                    <input
                      class="form-control"
                      v-model="price_filter.min_price"
                      placeholder="00.00"
                      type="number"
                    />
                  </div>
                  <div class="col-md-6 col-sm-6">
                    <label for="max-price">max-price</label>
                    <input
                      class="form-control"
                      v-model="price_filter.max_price"
                      placeholder="max-price"
                      type="number"
                    />
                  </div>
                </div>
                <br />
                <button type="submit" class="btn btn-block btn_search">
                  Filter <i class="fa fa-lg fa-filter"></i>
                </button>
              </form>
            </div>
            <div class="search-box hidden-xs bg-white shadow c-box">
              <select
                v-model="sort_by_price"
                @change="price_sorting_asec_desc"
                class="form-control"
              >
                <option value="select_by" disabled>Select Best Match</option>
                <option value="1">price less to high</option>
                <option value="2">price high to less</option>
              </select>
            </div>
          </aside>

          <div id="content" class="col-sm-9">
            <div class="row">
              <div
                class="col-lg-3 col-sm-6 col-md-6 col-xs-6"
                v-for="product in products"
                :key="product.id"
              >
                <div class="product-card small-card">
                  <div class="prodict-card-body">
                    <router-link :to="{ name: 'single', params: { slug: product.slug } }">
                      <!-- <img
                        v-if="product.product_image.length"
                        :src="base_url + product.product_image[0].product_image"
                      /> -->
                      <v-lazy-image
                         v-if="product.product_image.length"
                        :src="base_url + product.product_image[0].product_image"
                        :src-placeholder="base_url + 'default_product.jpg'"
                      />
                    </router-link>
                    <div class="product-detail">
                      <h4>
                        <router-link
                          class="product-link"
                          :to="{ name: 'single', params: { slug: product.slug } }"
                          >{{ product.name }}</router-link
                        >
                      </h4>
                      <p class="price">
                        <span class="price-new">{{ product.price }}</span>
                        <span class="price-old" v-if="product.discount">{{
                          product.sale_price
                        }}</span>
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
                    <button
                      class="btn btn-primary btnQuick"
                      style="cursor: pointer"
                      @click="quick_v_product_id = product.id"
                    >
                      view
                    </button>
                  </div>
                </div>
              </div>
              <infinite-loading @infinite="subSubCategoryWiseProduct">
                <div slot="no-more"></div>
              </infinite-loading>
            </div>
          </div>
          <!--Middle Part End -->
        </div>
      </div>
    </div>
    <frontend-footer></frontend-footer>
    <quick-view
      v-if="quick_v_product_id"
      v-on:clicked="closedModal($event)"
      :quick_v_p_id="quick_v_product_id"
    >
    </quick-view>
  </div>
</template>
<script>
import carousel from "vue-owl-carousel";
export default {
  created() {
    window.scrollTo(0, 0);
    this.getSubSubCatgory();
    this.sub_sub_category_slider();

    this.$Progress.start();
    setTimeout(() => {
      this.$Progress.finish();
    }, 500);
  },
  data() {
    return {
      products: [],
      page: 1,
      price_page: 1,
      sub_sub_categories: "",

      price_filter: {
        min_price: "",
        max_price: "",
      },
      base_url: this.$store.state.image_base_link,
      sort_by_price: "select_by",
      quick_v_product_id: "",
      o_modal: false,
      sub_sub_category_sliders:""
    };
  },
  methods: {
    subSubCategoryWiseProduct($state) {
      axios
        .get("/_public/sub/sub/category/wise/product/?page=" + this.page, {
          params: {
            slug: this.$route.params.slug,
          },
        })
        .then((resp) => {
          // console.log(resp);
          if (resp.data.data.length) {
            this.page += 1;
            this.products.push(...resp.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch((error) => {
          //console.log(error);
        });
    },

    priceFilter($state) {
      this.$Progress.start();
      let max_price = this.price_filter.max_price;
      let min_price = this.price_filter.min_price;
      let products = [];

      this.products.forEach((product) => {
        if (product.price >= min_price && product.price <= max_price) {
          products.push(product);
        }
      });
      if (products.length > 0) {
        this.products = products;
      } else {
        Swal.fire({
          type: "warning",
          text: " ): no produtc found......",
          duration: 3000,
        });
      }
      this.$Progress.finish();
    },
    price_sorting_asec_desc() {
      axios
        .get("/_public/api/sort/product/sub/sub/category/according/to/asc/desc", {
          params: {
            sort_value: this.sort_by_price,
            slug: this.$route.params.slug,
          },
        })
        .then((resp) => {
          this.products = [];
          this.products.push(...resp.data.products);
        })
        .catch();
    },
    getSubSubCatgory() {
      axios
        .get("/_public/sub/sub/category/" + this.$route.params.slug)
        .then((resp) => {
          //console.log(resp);
          this.sub_sub_categories = resp.data.sub_sub_categories;
        })
        .catch((error) => {
          //  console.log(error);
        });
    },
    closedModal(close) {
      this.quick_v_product_id = "";
    },
    sub_sub_category_slider() {
      axios.get("/_public/api/display/sub/sub/category/slider", {
          params: {
            slug: this.$route.params.slug,
          },
        })
        .then((resp) => {
          console.log(resp)
           this.sub_sub_category_sliders=resp.data.sub_sub_category_sliders;
        });
    },
  },

  components: {
    carousel,
  },
};
</script>

<style>
.search-box {
  margin-top: 20px;
}

.btn_search {
  background: #ff4d03;
  color: #fff;
  border: 1px dashed;
}

.btnQuick:hover {
  background: #ff4d03;
}

@media screen and (max-width: 350px) {
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
    margin-top: -5px;
  }

  .prodict-card-body img {
    width: 120px !important;
    height: 130px !important;
  }
}
</style>
