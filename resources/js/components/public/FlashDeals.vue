<template>
  <div class="wrapper-wide">
    <frontend-header></frontend-header>
    <div id="container">
      <div class="row fls_row">
        <div class="flash_deals_section">
          <img
            :src="base_url + 'images/flash_sale_banner.jpg'"
            class="flash_banner"
          />
          <div class="row count_down_row">
              <div class="col-md-2 text-center">
                  <p class="on_sale"> On Sale Now </p>
              </div>
              <div class="col-md-3 flex"> 
                  <span class="end_in">Ends In </span>
                   <div class="count_container">
                        <Countdown v-if="expired_time" :deadline="expired_time" > </Countdown>
                   </div>
              </div>
              <div class="col-md-6">
                     <ul class="higlight_on_flash_page">
                       <li> <a>Flash Deals Updated Daily </a>   </li>
                     </ul>
              </div>
        
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div
            class="col-lg-3 col-sm-6 col-md-6 col-xs-6 width-20"
            v-for="product in products"
            v-if="products"
            :key="product.id"
          >
            <div class="product-card small-card">
              <div class="prodict-card-body">
                <router-link
                  :to="{ name: 'single', params: { slug: product.slug } }"
                >
                  <img
                    v-if="product.product_image.length"
                    :src="base_url + product.product_image[0].product_image"
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

          <div class="col-lg-12 text-center" v-if="products.length < 1">
            <h4 class="alert alert-warning"> :) at now no products exits in flash deals</h4>
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
</template>
<script>
import Vue from "vue" ;
import Countdown from 'vuejs-countdown' ;

export default {
  mounted() {
    this.flashDeals();
    window.scrollTo(0, 0);
  },
  data() {
    return {
      products: "",
      expired_time:"",
      base_url: this.$store.state.image_base_link,
      quick_v_product_id: "",
      o_modal: false,
    };
  },

  methods: {
    flashDeals() {
      this.$Progress.start();
      axios
        .get("/_public/api/get/all/falsh/deals")
        .then((resp) => {
          console.log(resp);
          this.products = resp.data.products;
          this.expired_time = resp.data.expired_time;
          this.$Progress.finish();
        })
        .catch((error) => {
          console.log(error);
        });
    },

    closedModal(close) {
      this.quick_v_product_id = "";
    },
  },
  components:{
      Countdown ,
  }
};
</script>

<style  scoped>
.btnQuick:hover {
  background: #ff4d03;
}

.flash_deals_section{

   margin-bottom: 10px;  
}

.flash_banner {
  height: 200px;
  width: 100%;
}

.count_down_row{
    height: 45px;
    width: 100%;
    box-shadow: 1px 1px 2px 1px #ddd;

}

.count_container{
     margin-left:10%;
}
.end_in{
    margin-top: 13px;
    font-size: 15px;
}
.on_sale{
    margin-top: 13px;
    font-size: 15px;
    color:#ff4d03;
}

.fls_row{
  margin-top: -20px;
}

</style>