<template>
  <div class="wrapper-wide">
    <frontend-header></frontend-header>

    <div class="container">
      <div class="slider slider-animation bv xz">
        <div class="row">
          <div class="col-lg-9">
            <carousel
              v-if="sliders"
              :items="1"
              :nav="false"
              :autoplay="true"
              :autoplayTimeout="2000"
            >
              <a
                v-for="slider in sliders"
                target="_blank"
                :href="slider.url"
                :key="slider.id"
              >
                <!-- <v-lazy-image
                  :src-placeholder="base_url + 'bigd.jpg'"
                  :src="base_url + slider.image"
                /> -->
                <img :src="base_url + slider.image" />
              </a>
            </carousel>
          </div>
          <div class="col-lg-3 d-sm-none" v-if="slider_banner">
            <a :href="slider_banner.url">
              <img :src="base_url + slider_banner.image" />
            </a>
          </div>
        </div>
      </div>

      <!-- <div class="row flash_deals" v-if="flash_deals_products">
        <div class="flash_deals_timer">
          <span class="clock_span">
            <i class="fa fa-clock-o fa-lg"> </i>Time Left</span
          >
          <Countdown :deadline="flash_deals.expired_date"></Countdown>
        </div>
        <div class="row flash_deals_row">
          <img
            :src="base_url + flash_deals.banner"
            class="flash_deals_banner"
          />
          <h2 class="discount_stand_out">
            {{
              flash_deals.discount_stand_out
                ? flash_deals.discount_stand_out
                : " "
            }}
          </h2>

          <div
            v-for="(f_d_p, index) in flash_deals_products"
            :key="index"
            class="col-md-2 col-xs-4 flash_p_container"
          >
            <div class="flash_p_card">
              <div class="flash_p_card_body">
                <router-link
                  class="flash_p_link"
                  :to="{
                    name: 'single',
                    params: { slug: f_d_p.slug },
                  }"
                >
                  <v-lazy-image
                    style="cursor: pointer"
                    v-if="f_d_p.product_image.length"
                    :src="base_url + f_d_p.product_image[0].product_image"
                    :src-placeholder="base_url + 'default_product.jpg'"
                  />
                </router-link>
                <div class="flash_p_detail">
                  <h4>
                    <router-link
                      class="flash_p_link"
                      :to="{
                        name: 'single',
                        params: { slug: f_d_p.slug },
                      }"
                      >{{ f_d_p.name }}</router-link
                    >
                  </h4>
                  <p class="price flash_price">
                    <span class="price-new">
                      <strong> &#2547; </strong>
                      {{ f_d_p.price }}</span
                    >
                    <span class="price-old" v-if="f_d_p.discount">
                      {{ f_d_p.sale_price }}</span
                    >
                  </p>
                </div>
              </div>
              <div class="flash_P_quick_view">
                <a
                  class="btn flash_q_btn btnQuick"
                  style="cursor: pointer"
                  @click="quick_v_product_id = f_d_p.id"
                >
                  <i class="fa fa-eye fa-lg quick_eye"> </i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-10 col-xs-8 flash_deal_slider_area">
            <carousel
              v-if="flash_slider_products"
              :items="6"
              :nav="false"
              :autoplay="true"
              :autoplayTimeout="2000"
            >
              <div
                style="text-align: center"
                v-for="flash_slider_product in flash_slider_products"
                :href="flash_slider_product.id"
                :key="flash_slider_product.id"
              >
                <router-link
                    :to="{
                      name: 'single',
                      params: { slug: flash_slider_product.slug },
                    }"
                    >
                <v-lazy-image
                  v-if="flash_slider_product.product_image.length"
                  :src="
                    base_url +
                    flash_slider_product.product_image[0].product_image
                  "
                  :src-placeholder="base_url + 'default_product.jpg'"
                />
                 </router-link>

                <p class="flash_slider_p_link">
                  <router-link
                    :to="{
                      name: 'single',
                      params: { slug: flash_slider_product.slug },
                    }"
                    >{{ flash_slider_product.name }}</router-link
                  >
                </p>
                <p class="price flash_slider_p_price">
                  <span class="flash_price_new">{{
                    flash_slider_product.price
                  }}</span>
                  <span
                    class="flash_price_old"
                    v-if="flash_slider_product.discount"
                    >{{ flash_slider_product.sale_price }}</span
                  >
                </p>
              </div>
            </carousel>

          </div>
        </div>
      </div> -->

      <div class="row flash_deals" v-if="flash_deals_products.length > 0">
        <div class="flash_deals_timer">
          <span class="clock_span">
            <i class="fa fa-clock-o fa-lg"> </i>Time Left</span
          >
          <Countdown :deadline="flash_deals.expired_time"></Countdown>
        </div>
        <div class="row flash_deals_row">
          <ul class="flash_deals_higlight">
            <li>
              <router-link :to="{ name: 'public_flash_deals' }"
                >Flash Deals
              </router-link>
            </li>
          </ul>

          <img
            :src="base_url + flash_deals.banner"
            class="flash_deals_banner"
          />
          <h2 class="discount_stand_out">
            {{
              flash_deals.discount_stand_out
                ? flash_deals.discount_stand_out
                : " "
            }}
          </h2>

          <div
            v-for="(f_d_p, index) in flash_deals_products"
            :key="index"
            class="col-md-2 col-xs-4 flash_p_container"
          >
            <div class="flash_p_card">
              <div class="flash_p_card_body">
                <router-link
                  class="flash_p_link"
                  :to="{
                    name: 'single',
                    params: { slug: f_d_p.slug },
                  }"
                >
                  <v-lazy-image
                    style="cursor: pointer"
                    v-if="f_d_p.product_image.length"
                    :src="base_url + f_d_p.product_image[0].product_image"
                    :src-placeholder="base_url + 'default_product.jpg'"
                  />
                </router-link>
                <div class="flash_p_detail">
                  <h4>
                    <router-link
                      class="flash_p_link"
                      :to="{
                        name: 'single',
                        params: { slug: f_d_p.slug },
                      }"
                      >{{ f_d_p.name }}</router-link
                    >
                  </h4>
                  <p class="price flash_price">
                    <span class="price-new"> {{ f_d_p.price }}</span>
                    <span v-if="f_d_p.discount > 0" class="price-old">
                      {{ f_d_p.sale_price }}</span
                    >
                    <span v-if="f_d_p.discount > 0" class="flas_p_discount">
                      <i class="fa fa-star discount_star1"> </i>
                      <i class="fa fa-star discount_star2"> </i>
                      {{
                        ((f_d_p.discount / f_d_p.sale_price) * 100).toFixed(0)
                      }}% <span class="d_off">off</span>
                    </span>
                  </p>
                </div>
              </div>
              <div class="flash_P_quick_view">
                <a
                  class="btn flash_q_btn btnQuick"
                  style="cursor: pointer"
                  @click="quick_v_product_id = f_d_p.id"
                >
                  <i class="fa fa-eye fa-lg quick_eye"> </i>
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-10 col-xs-8 flash_deal_slider_area">
            <carousel
              v-if="flash_slider_products"
              :items="6"
              :nav="false"
              :autoplay="true"
              :autoplayTimeout="2000"
            >
              <div
                style="text-align: center"
                v-for="flash_slider_product in flash_slider_products"
                :href="flash_slider_product.id"
                :key="flash_slider_product.id"
              >
                <router-link
                  :to="{
                    name: 'single',
                    params: { slug: flash_slider_product.slug },
                  }"
                >
                  <v-lazy-image
                    v-if="flash_slider_product.product_image.length"
                    :src="
                      base_url +
                      flash_slider_product.product_image[0].product_image
                    "
                    :src-placeholder="base_url + 'default_product.jpg'"
                  />
                </router-link>

                <p class="flash_slider_p_link">
                  <router-link
                    :to="{
                      name: 'single',
                      params: { slug: flash_slider_product.slug },
                    }"
                    >{{ flash_slider_product.name }}</router-link
                  >
                </p>
                <p class="price flash_slider_p_price">
                  <span class="flash_price_new">{{
                    flash_slider_product.price
                  }}</span>
                  <span
                    class="flash_price_old"
                    v-if="flash_slider_product.discount"
                    >{{ flash_slider_product.sale_price }}</span
                  >

                  <span
                    v-if="flash_slider_product.discount > 0"
                    class="flas_s_discount"
                  >
                    <i class="fa fa-star discount_star1"> </i>
                    <i class="fa fa-star discount_star2"> </i>
                    {{
                      (
                        (flash_slider_product.discount /
                          flash_slider_product.sale_price) *
                        100
                      ).toFixed(0)
                    }}% <span class="d_off">off</span>
                  </span>
                </p>
              </div>
            </carousel>
          </div>
        </div>
      </div>

      <div v-if="isScroll > 0">
        <div class="best-selling">
          <h4>Top Selling Products</h4>
          <div class="row" v-if="best_selling_produtcs.length > 0">
            <carousel
              :nav="false"
              :autoplay="true"
              :autoplayTimeout="4000"
              :responsive="{ 0: { items: 2 }, 600: { items: 6 } }"
            >
              <div
                class="col-lg-3 col-sm-6 col-md-6 col-xs-6"
                v-for="best_slllers_product in best_selling_produtcs"
                :key="best_slllers_product.id"
              >
                <div class="product-card top-product-card-small">
                  <div class="prodict-card-body">
                    <router-link
                      :to="{
                        name: 'single',
                        params: { slug: best_slllers_product.slug },
                      }"
                    >
                      <!-- <img
                      v-if="best_slllers_product.product_image.length"
                      :src="
                        base_url +
                        best_slllers_product.product_image[0].product_image
                      "
                    /> -->
                      <v-lazy-image
                        v-if="best_slllers_product.product_image.length"
                        :src="
                          base_url +
                          best_slllers_product.product_image[0].product_image
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
                            params: { slug: best_slllers_product.slug },
                          }"
                          >{{ best_slllers_product.name }}</router-link
                        >
                      </h4>
                      <p class="price">
                        <span class="price-new">{{
                          best_slllers_product.price
                        }}</span>
                        <span
                          class="price-old"
                          v-if="best_slllers_product.discount"
                          >{{ best_slllers_product.sale_price }}</span
                        >

                        <span
                          v-if="best_slllers_product.discount > 0"
                          class="top_s_discount"
                        >
                          <i class="fa fa-star discount_star1"> </i>
                          <i class="fa fa-star discount_star2"> </i>
                          {{
                            (
                              (best_slllers_product.discount /
                                best_slllers_product.sale_price) *
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

        <div>
          <div
            v-if="occasion_campaign && seasion_campaign"
            class="row offer_collection"
          >
            <div
              :style="{
                backgroundImage: `url(${
                  base_url + occasion_campaign.background_image
                })`,
              }"
              class="col-lg-6 col-sm-12 col-md-6 collection_left"
            >
              <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="container_offer_header text-center">
                    <p>{{ occasion_campaign.quote }}</p>
                    <h4>{{ occasion_campaign.heading }}</h4>
                  </div>
                </div>
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="collection_content">
                    <!-- <img
                      v-if="occasion_p_top.product_image"
                      :src="
                        base_url + occasion_p_top.product_image[0].product_image
                      "
                    /> -->

                    <v-lazy-image
                      v-if="occasion_p_top.product_image"
                      :src="
                        base_url + occasion_p_top.product_image[0].product_image
                      "
                      :src-placeholder="base_url + 'default_product.jpg'"
                    />
                    <div class="collect_sub_data text-center">
                      <p class="p1">{{ occasion_p_top.name }}</p>
                      <p class="p2">{{ occasion_p_top.price }}&#2547;</p>
                      <router-link
                        class="btn btn_more"
                        :to="{
                          name: 'single',
                          params: { slug: occasion_p_top.slug },
                        }"
                      >
                        More
                        <i class="fa fa-xs fa-arrow-right arrow_right_icon"></i>
                      </router-link>
                    </div>
                  </div>

                  <div class="collection_content">
                    <!-- <img
                    v-if="occasion_p_bottom.product_image"
                    :src="base_url +occasion_p_bottom.product_image[0].product_image"
                  /> -->

                    <v-lazy-image
                      v-if="occasion_p_bottom.product_image"
                      :src="
                        base_url +
                        occasion_p_bottom.product_image[0].product_image
                      "
                      :src-placeholder="base_url + 'default_product.jpg'"
                    />

                    <div class="collect_sub_data text-center">
                      <p class="p1">{{ occasion_p_bottom.name }}</p>
                      <p class="p2">{{ occasion_p_bottom.price }}&#2547;</p>
                      <router-link
                        class="btn btn_more"
                        :to="{
                          name: 'single',
                          params: { slug: occasion_p_bottom.slug },
                        }"
                      >
                        More
                        <i class="fa fa-xs fa-arrow-right arrow_right_icon"></i>
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
              :style="{
                backgroundImage: `url(${
                  base_url + seasion_campaign.background_image
                })`,
              }"
              class="col-lg-6 col-sm-12 col-md-6 collection_right"
            >
              <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="container_offer_header text-center">
                    <p>{{ seasion_campaign.quote }}</p>
                    <h4>{{ seasion_campaign.heading }}</h4>
                  </div>
                </div>
                <div class="col-md-6 col-xs-12 col-sm-12">
                  <div class="collection_content">
                    <!-- <img
                    v-if="seasion_p_top.product_image"
                    :src="
                      base_url + seasion_p_top.product_image[0].product_image
                    "
                  /> -->
                    <v-lazy-image
                      v-if="seasion_p_top.product_image"
                      :src="
                        base_url + seasion_p_top.product_image[0].product_image
                      "
                      :src-placeholder="base_url + 'default_product.jpg'"
                    />

                    <div class="collect_sub_data text-center">
                      <p class="p1">{{ seasion_p_top.name }}</p>
                      <p class="p2">{{ seasion_p_top.price }}&#2547;</p>
                      <router-link
                        class="btn btn_more"
                        :to="{
                          name: 'single',
                          params: { slug: seasion_p_top.slug },
                        }"
                      >
                        More
                        <i class="fa fa-xs fa-arrow-right arrow_right_icon"></i>
                      </router-link>
                    </div>
                  </div>

                  <div class="collection_content">
                    <!-- <img
                    v-if="seasion_p_bottom.product_image"
                    :src=" base_url + seasion_p_bottom.product_image[0].product_image"
                  /> -->

                    <v-lazy-image
                      v-if="seasion_p_bottom.product_image"
                      :src="
                        base_url +
                        seasion_p_bottom.product_image[0].product_image
                      "
                      :src-placeholder="base_url + 'default_product.jpg'"
                    />

                    <div class="collect_sub_data text-center">
                      <p class="p1">{{ seasion_p_bottom.name }}</p>
                      <p class="p2">{{ seasion_p_bottom.price }}&#2547;</p>
                      <router-link
                        class="btn btn_more"
                        :to="{
                          name: 'single',
                          params: { slug: seasion_p_bottom.slug },
                        }"
                      >
                        More
                        <i class="fa fa-xs fa-arrow-right arrow_right_icon"></i>
                      </router-link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="poster">
            <div class="row">
              <h4 class="heading banner_heading">categories</h4>
              <div class="banner_container">
                <div
                  class="col-lg-2 col-md-2 col-sm-6 col-xs-4"
                  v-for="offer in getOffers"
                  :key="offer.id"
                >
                  <a :href="offer.url" target="_blank" class="">
                    <div class="offer_box">
                      <v-lazy-image
                        title="offer"
                        alt="offer"
                        :src="base_url + offer.image"
                        :src-placeholder="base_url + 'default_product.jpg'"
                      />
                      <h4 class="offer_caption">
                        {{ offer.name ? offer.name : "" }}
                      </h4>
                    </div>

                    <div
                      class="overlaw-offer"
                      :class="{ 'overlaw-offer-height': offer.id % 2 == 0 }"
                    ></div>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div>
            <div
              class="c-product"
              v-for="(sub_category, idx) in home_page_products"
              :key="idx"
              v-if="sub_category.products.length"
            >
              <div class="c-product-header">
                <h4 class="category-heading">{{ sub_category.name }}</h4>
                <div
                  class="s-category"
                  v-if="sub_category.sub_sub_category.length > 0"
                >
                  <div
                    :id="'subCategoryNameView' + sub_category.id"
                    class="sub_category_view"
                  >
                    <router-link
                      :to="{
                        name: 'PublicSubSUbCategory',
                        params: { slug: sub_sub_category.slug },
                      }"
                      class="sub-category-name"
                      v-for="(
                        sub_sub_category, index
                      ) in sub_category.sub_sub_category"
                      :key="index"
                      v-if="index <= 7"
                      >{{ sub_sub_category.name }}</router-link
                    >

                    <router-link
                      :to="{
                        name: 'PublicSubCategory',
                        params: { slug: sub_category.slug },
                      }"
                      class="c-v-all"
                      >View All
                    </router-link>
                  </div>
                </div>
              </div>

              <div class="row">
                <div
                  class="col-lg-3 col-sm-6 col-md-4 col-xs-6 width-20"
                  v-for="(product, index) in sub_category.products"
                  :key="index"
                  v-if="index < 10"
                >
                  <div class="product-card">
                    <div class="prodict-card-body">
                      <router-link
                        :to="{ name: 'single', params: { slug: product.slug } }"
                      >
                        <!-- <img
                        v-if="product.product_image.length"
                        :src="base_url + product.product_image[0].product_image"
                      /> -->
                        <v-lazy-image
                          v-if="product.product_image.length"
                          :src="
                            base_url + product.product_image[0].product_image
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

                          <span v-if="product.discount > 0" class="discount">
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
              </div>
              <div class="row" v-if="sub_category.id == 3">
                <div class="parallax_background">
                  <div class="parallax_inside">
                    <div class="parallax_content">
                      <a
                        href="https://mohasagor.com/public/product/view/mens-full-sleeve-t-shirt-1177"
                        target="_blank"
                      >
                        <img
                          src="https://mohasagor.com/public/storage/images/static/wed-emplate.png"
                          style="max-width: 450px; margin-top: 10px"
                          alt=""
                        />
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <infinite-loading @infinite="home_page_product">
            <div slot="no-more"></div>
          </infinite-loading>
        </div>
      </div>
    </div>
    <frontend-footer v-if="isScroll > 0"></frontend-footer>
    <quick-view
      v-if="quick_v_product_id"
      v-on:clicked="closedModal($event)"
      :quick_v_p_id="quick_v_product_id"
    >
    </quick-view>
  </div>
</template>

<script>
import Vue from "vue";

import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import carousel from "vue-owl-carousel";
import Countdown from "vuejs-countdown";
import VueHorizontalList from "vue-horizontal-list";
Vue.use(Loading);

export default {
  name: "welcome",
  data() {
    return {
      loading: true,
      // sub_categories: [],
      page: 1,
      offers: [],
      product_id: null,
      background_parallax:
        this.$store.state.image_base_link + "images/parallax.jpg",
      base_url: this.$store.state.image_base_link,
      isScroll: 0,
      quick_v_product_id: "",
      o_modal: false,
      home_page_products: [],
      sub_category_icon: true,
      flash_slider_options: {
        responsive: [
          { start: 0, end: 500, size: 1 },
          { start: 501, end: 768, size: 2 },
          { start: 769, end: 992, size: 6 },
          { size: 6 },
        ],
        list: {
          windowed: 600,
          padding: 10,
        },
        position: {
          start: 2,
        },
        autoplay: {
          play: true,
          repeat: true,
          speed: 3000,
        },
      },
      occasion_campaign: "",
      occasion_p_top: "",
      occasion_p_bottom: "",

      seasion_campaign: "",
      seasion_p_top: "",
      seasion_p_bottom: "",
      buy_get: "",
      buy_get_p: "",
    };
  },
  methods: {
    productDetals(product_id) {
      this.prdoct_modal = true;
      this.product_id = product_id;
    },
    closedModal(close) {
      this.quick_v_product_id = "";
    },
    showCategoryName(id) {
      console.log(id);
    },

    getOccatoinCampaign() {
      axios.get("/_public/api/publish/occasional/campaign").then((resp) => {
        this.occasion_campaign = resp.data.occasion;
        this.occasion_p_top = resp.data.occasion_p_top;
        this.occasion_p_bottom = resp.data.occasion_p_bottom;
      });
    },
    getSeasionCampaign() {
      axios.get("/_public/api/publish/seasional/campaign").then((resp) => {
        this.seasion_campaign = resp.data.seasion;
        this.seasion_p_top = resp.data.seasion_p_top;
        this.seasion_p_bottom = resp.data.seasion_p_bottom;
      });
    },

    getBuyGetCampaign() {
      axios
        .get("/_public/api/publish/buy/one/get/one/campaign")
        .then((resp) => {
          this.buy_get = resp.data.buy_get;
          this.buy_get_p = resp.data.buy_get_p;
        });
    },

    home_page_product($state) {
      axios
        .get("/_public/products?page=" + this.page)
        .then((resp) => {
          if (resp.data.data.length) {
            this.page += 1;
            this.home_page_products.push(...resp.data.data);
            $state.loaded();
          } else {
            $state.complete();
          }
        })
        .catch((e) => {});
    },
    handleScrol() {
      this.isScroll = 1;
    },
  },
  components: {
    Loading,
    carousel,
    Countdown,
    VueHorizontalList,
  },
  mounted() {
    this.$store.dispatch("category");
    this.$store.dispatch("sliders");
    this.$store.dispatch("offer_banner");
    this.$store.dispatch("flash_deals");
    this.getOccatoinCampaign();
    this.getSeasionCampaign();
    this.getBuyGetCampaign();
    window.addEventListener("scroll", this.handleScrol);
  },

  computed: {
    category() {
      return this.$store.getters.categories;
    },
    slider_banner() {
      return this.$store.getters.slider_banner;
    },
    sliders() {
      return this.$store.getters.sliders;
    },
    getOffers() {
      return this.$store.getters.offer_banner;
    },
    best_selling_produtcs() {
      return this.$store.getters.best_selling_produtcs;
    },

    flash_sale_products() {
      return this.$store.getters.flash_sale_products;
    },
    sub_categories() {
      return this.$store.getters.home_page_products;
    },
    flash_deals() {
      return this.$store.getters.flash_deals;
    },
    flash_deals_products() {
      return this.$store.getters.flash_deals_products;
    },
    flash_slider_products() {
      return this.$store.getters.flash_slider_products;
    },
    sub_categories() {
      return this.$store.getters.home_page_products;
    },
  },
  watch: {
    isScroll: function (value) {
      if (value == 1) {
        this.$store.dispatch("home_page_products");
      }
    },
    // best_selling_produtcs: function (value) {
    //   let loader = this.$loading.show({
    //     // Optional parameters
    //     container: this.fullPage ? null : this.$refs.formContainer,
    //     canCancel: true,
    //     onCancel: this.onCancel,
    //     loader: "bars",
    //     color: "#FF4D03",
    //     backgroundColor: "#222",
    //     width: 100,
    //     height: 100,
    //   });

    //   if (Object.keys(value).length > 0) {
    //       loader.hide();
    //   }

    // },
  },
};
</script>

<style scoped>
.nav-active {
  display: block !important;
}

.product-card-footer {
  padding: 0px;
}

.btnQuick:hover {
  background: #ff4d03;
}

.parallax_background {
  width: 98%;
  height: 300px;
  margin-left: 14px;
  background-image: url(https://mohasagor.com/public/storage/images/static/wed-template.jpg);
  background-attachment: fixed;
  background-position: center;
  background-repeat: no-repeat;
  background-size: cover;
}

.parallax_inside {
  position: absolute;
  width: 40%;
  height: 200px;
}

.parallax_content {
  margin: 10px;
  position: absolute;

  width: 96%;
  height: 175px;
  text-align: center;
}

.parallax_content h4 {
  margin-top: 30px;
}

.parallax_offer {
  margin-top: 15px !important;
  margin-bottom: 15px !important;
}

.service_row {
  width: 103%;
  height: 135px;
  background: #2e2e2e;
  margin-left: 0px;
  margin-top: -50px;
}

.service_container {
  float: left;
}

.service_outer {
  width: 75px;
  height: 75px;
  border-radius: 50%;
  border: 1px solid #fff;
  background: #222;
  margin: -22px 94px;
}

.service_inner {
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #ff4d03;
  margin: 6.5px 6px;
}

.service_icon {
  position: absolute;
  margin: 11px;
  color: #fff;
  font-size: 35px;
}

.service_outer:hover > .service_inner > .service_icon {
  -webkit-transform: scaleX(-1);
  transform: scaleX(-1);
  cursor: pointer;
  transition: 0.5s;
}

.service_description1 {
  color: #fff;
  padding: 10px;
  margin-top: 13px;
  font-size: 13px;
}

.service_description2 {
  color: #fff;
  padding: 10px;
  margin-top: 13px;
  font-size: 13px;
  margin-left: 25px;
}

.service_description3 {
  color: #fff;
  padding: 10px;
  margin-left: 45px;
  margin-top: 17px;
  font-size: 13px;
}

.service_description4 {
  color: #fff;
  padding: 10px;
  margin-left: 50px;
  margin-top: 13px;
  font-size: 13px;
}

@media screen and (max-width: 750px) {
  .parallax_inside {
    width: 60%;
  }

  .parallax_content {
    width: 91%;
  }

  .parallax_offer {
    margin-top: 15px !important;
    margin-bottom: 15px !important;
  }

  .service_row {
    width: 100%;
    height: 160px;
    background: #2e2e2e;
    margin-left: 0px;
    margin-top: -50px;
  }

  .service_optional {
    display: none;
  }

  .service_container {
    display: flex;
  }
  .service_outer {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    border: dashed 1px #fff;
    background: #222;
    margin-left: 23px;
    margin-top: 26px;
  }

  .service_inner {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: #fff;
    margin: 7px 6px;
  }

  .service_icon {
    position: absolute;
    margin: 7px;
    font-size: 30px;
  }

  .service_outer:hover > .service_inner > .service_icon {
    -webkit-transform: scaleX(-1);
    transform: scaleX(-1);
    cursor: pointer;
    transition: 0.5s;
  }

  .service_description1 {
    position: absolute;
    font-size: 15px !important;
    line-height: 22px !important;
    margin-left: 95px;
    padding-top: 15px;
  }

  .service_description2 {
    position: absolute;
    font-size: 15px !important;
    line-height: 22px !important;
    line-height: 22px !important;
    margin-left: 95px;
    padding-top: 27px;
  }

  .parallax_background {
    display: none;
  }

  .arrow_icon {
    display: inline-block;
  }

  .sub_category_view {
    display: block;
    margin-top: 15px;
  }

  .sub-category-name {
    border-bottom: 1px solid #ff4d03;
    background: #fff;
    padding: 0px 7px;
    font-size: 13px;
    font-weight: bold;
    margin-left: 5px;
    float: left;
    margin-bottom: 10px;
    border-block-end-style: none;
  }

  .c-v-all {
    background: #000;
    color: #fff;
    padding: 5px 13px;
    width: 22px;
    height: 38px;
    display: inline;
    border-radius: 4px;
    transition: 0.5s;
  }
}

.displayeBlok {
  display: none;
}
</style>
