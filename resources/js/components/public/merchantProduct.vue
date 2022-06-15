<template>
  <div class="wrapper-wide">
    <frontend-header></frontend-header>
    <div id="container">
      <div class="container">
        <div class="row">
          <div class="col-lg-12 text-center">
            <h4 class="text-uppercase">
              Product Of
              <strong>{{
                merchant.company_name ? merchant.company_name : ""
              }}</strong>
            </h4>
          </div>
        </div>

        <!-- <div class="product-filter">
          <div class="row">
            <div class="col-lg-4">
              <select class="form-control" v-model="category_id">
                <option value="" selected disabled> Filter By Category</option>
                <option v-for="(category,idx) in categories" :key="idx" :value="category.id">{{category.name}}</option>
              </select>
            </div>
          </div>
        </div> -->

        <div class="row">
          <div id="content" class="col-sm-12">
            <div class="row">
              <div
                class="col-lg-3 col-sm-6 col-md-6 col-xs-6 width-20"
                v-for="(product,index) in products"
                :key="index"
              >
                <div class="product-card small-card">
                  <div class="prodict-card-body">
                    <router-link
                      :to="{ name: 'single', params: { slug: product.slug } }"
                    >
                      <!-- <img   v-if="product.product_image.length" :src=" base_url +product.product_image[0].product_image"/> -->

                      <v-lazy-image
                        v-if="product.product_image.length"
                        :src="base_url + product.product_image[0].product_image"
                        :src-placeholder="base_url + 'default_product.jpg'"
                      />
                    </router-link>
                    <div class="product-detail small-detail">
                      <h4>
                        <router-link
                          class="product-link"
                          :to="{
                            name: 'single',
                            params: { slug: product.slug },
                          }"
                          >{{ product.name }}</router-link
                        >
                      </h4>
                      <p class="price">
                        <span class="price-new">{{ product.price }}</span>
                        <span class="price-old" v-if="product.discount">{{
                          product.sale_price
                        }}</span>
                        <!-- <span class="saving">-26%</span> -->
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
              <infinite-loading @infinite="merchantProduct">
                <div slot="no-more"></div>
              </infinite-loading>
            </div>
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
  </div>
</template>
<script>
import carousel from "vue-owl-carousel";

export default {
  created() {
    window.scrollTo(0, 0);
  },
  data() {
    return {
      products: [],
      category_name: "",
      page: 1,
      price_page: 1,

      price_filter: {
        min_price: "",
        max_price: "",
      },
      base_url: this.$store.state.image_base_link,
      sort_by_price: "select_by",
      quick_v_product_id: "",
      o_modal: false,
      merchant: "",
      category_id:""
    };
  },
  methods: {
    merchantProduct($state) {
      axios .get("/_public/merchant/wise/product/?page=" + this.page, {
          params: {
            id: this.$route.params.id,
            category_id:this.category_id

          },
        })
        .then((resp) => {
          // console.log(resp);
          if (resp.data.products.data.length) {
            if (this.page == 1) {
              this.merchant = resp.data.merchant;
              console.log(resp);
            }
            this.page += 1;
            this.products.push(...resp.data.products.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch((error) => {
          //  console.log(error);
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
        .get("/_public/api/sort/product/according/to/asc/desc", {
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

    closedModal(close) {
      this.quick_v_product_id = "";
    },
  },
  computed: {
    categories() {
      return this.$store.getters.categories;
    },
  },

  mounted() {
    this.$store.dispatch("category");
  },
  watch:{
    category_id:function(value){

      
    }
  },
  components: {
    carousel,
  },
};
</script>

<style >
.search-box {
  margin-top: 20px;
}

.btn_search {
  background: #ff4d03;
  color: #fff;
  border: 1px dashed;
}

.product-card-footer {
  padding: 0px;
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