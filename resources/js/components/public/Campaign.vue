<template>
  <div class="wrapper-wide">
    <loading
      :active.sync="loading"
      :can-cancel="true"
      :is-full-page="fullPage"
    ></loading>

    <frontend-header></frontend-header>
    <div id="container">
      <div class="container">
        <div class="slider slider-animation bv xz">
          <div class="row">
            <div class="col-lg-12">
              <carousel
                v-if="campaign.sliders"
                :items="1"
                :nav="false"
                :autoplay="true"
                :autoplayTimeout="2000"
              >
                <a
                  v-for="slider in campaign.sliders"
                  :href="slider.url"
                  :key="slider.id"
                >
                  <img :src="base_url + slider.image" />
                </a>
              </carousel>
            </div>
          </div>

          <div class="all-campaign" v-if="campaign.campaign">
            <div class="single-campaign">
              <div
                class="campaign-heading"
                :style="'background-color:' + campaign.campaign.bg_color"
              >
                <div class="heading-content">
                  <h4
                    :style="{
                      color: campaign.campaign.text_color,
                      'margin-top': '50px',
                    }"
                  >
                    {{ campaign.campaign.campaign_name }}
                  </h4>
                  <div class="coundow">
                    <Countdown
                      v-if="campaign.campaign.end_date"
                      :deadline="campaign.campaign.end_date"
                    ></Countdown>
                  </div>
                </div>
              </div>
              <div
                class="campaignn-products"
                :style="'background-color:' + campaign.campaign.bg_color"
              >
                <div
                  class="row"
                  v-if="campaign.campaign.campaign_products.length > 0"
                >
                  <div
                    class="col-lg-3 col-sm-6 col-md-4 col-xs-6 width-20"
                    v-for="(product, index) in campaign.campaign
                      .campaign_products"
                    :key="index"
                  >
                    <div class="product-card">
                      <div class="prodict-card-body">
                        <router-link
                          :to="{
                            name: 'single',
                            params: { slug: product.product.slug },
                          }"
                        >
                          <!-- <img
                        v-if="product.product_image.length"
                        :src="base_url + product.product_image[0].product_image"
                      /> -->
                          <v-lazy-image
                            v-if="product.product.product_image.length"
                            :src="
                             base_url+product.product.product_image[0].product_image
                            "
                            :src-placeholder="base_url + 'default_product.jpg'"
                          />
                        </router-link>
                        <div class="product-detail">
                          <h4>
                            <router-link
                              class="product-link"
                              :to="{
                                name: 'single',
                                params: { slug: product.product.slug },
                              }"
                              >{{ product.product.name }}</router-link
                            >
                          </h4>
                          <p class="price">
                            <span class="price-new">{{
                              product.product.price
                            }}</span>
                            <span
                              class="price-old"
                              v-if="product.product.discount"
                              >{{ product.product.sale_price }}</span
                            >

                            <span
                              v-if="product.product.discount > 0"
                              class="discount"
                            >
                              <i class="fa fa-star discount_star1"> </i>
                              <i class="fa fa-star discount_star2"> </i>
                              {{
                                (
                                  (product.product.discount /
                                    product.product.sale_price) *
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
                          @click="quick_v_product_id = product.product.id"
                        >
                          view
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-else
                  style="
                    text-align: center;
                    width: 50%;
                    margin: auto;
                    background: #fff;
                    border-radius: 10px;
                    box-shadow: 3px 3px 3px #ddd;
                    /* height: 150px; */
                    /* padding: 104px; */
                    font-weight: 600;
                    font-style: italic;
                    color: #4a4242fa;
                    text-transform: uppercase;
                    padding: 30px;
                  "
                >
                  <h4>No Product Found Against {{ campaign.campaign_name }}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <frontend-footer></frontend-footer>
    </div>
  </div>
</template>
<script>
import carousel from "vue-owl-carousel";
import Countdown from "vuejs-countdown";
import Loading from "vue-loading-overlay";

export default {
  created() {
    window.scrollTo(0, 0);
  },

  data() {
    return {
      base_url: this.$store.state.image_base_link,
      loading: true,
         fullPage: true,
    };
  },
  computed: {
    campaign() {
      return this.$store.getters.campaign;
    },
  },
  watch: {
    campaign: function (value) {
      this.loading = false;
    },
  },
  mounted() {
    //for get category from vuex
    this.$store.dispatch("campaign", this.$route.params.id);
    //  this.$store.dispatch("category_sliders");
  },
  components: {
    carousel,
    Countdown,
    Loading,
  },
};
</script>

<style  scoped>
.single-campaign {
  width: 100%;

  margin-bottom: 10px;

  position: relative;
}
.campaign-heading {
  bottom: 0;
  height: 100px;
  padding: 10px;
  position: relative;
}
.heading-content {
  bottom: 5%;
  display: flex;
  align-items: center;
  width: 100%;
  justify-content: space-between;
  position: absolute;
}

a.c-view-all {
  background: #fff;
  padding: 10px 28px;
  margin-right: 25px;
  border-radius: 7px;
  color: #000;
}
.campaignn-products {
  margin-top: 10px;
  padding: 10px;
  border-radius: 5px;
}
.flash_deals_timer {
  color: #fff !important;
}
.vuejs-countdown {
  background: #fff;
  padding: 20px;
  border-radius: 15px 50px 30px;
  margin-right: 15px;
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