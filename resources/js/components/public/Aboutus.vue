<template>
  <div class="wrapper-wide">
    <loading
      :active.sync="isLoading"
      :can-cancel="true"
      :is-full-page="fullPage"
    ></loading>

    <frontend-header></frontend-header>
    <div id="container">
      <div class="container">
        <div class="row">
          <!--Middle Part Start-->
          <div id="content" class="col-sm-12 bg-white shadow">
            <h4
              class="title"
              style="
                text-transform: uppercase;
                text-align: center;
                font-size: 30px;
                font-weight: 600;
              "
            >
              About Us
            </h4>
            <div class="row">
              <div class="col-sm-12">
                <p style="text-transform: capitalize">
                  <strong style="font-size:18px;">Mohasagor.com</strong> is a differentiated last-mile e-commerce
                  solution and last-mile fulfillment to the customers. It is the
                  ultimate online shopping destination for Bangladesh offering a
                  completely hassle-free shopping experience through secure and
                  trusted gateways. We offer you trendy and reliable shopping
                  with all your favorite brands and more. Now shopping is
                  easier, faster and always joyous. We help you make the right
                  choice here. Mohasagor is the most customer-centric online
                  retailer enabling small and medium businesses to sell a wide
                  selection of authentic products to the customers without
                  having to invest in working capital stock. Mohasagor is
                  aggregating small businesses into a network, enabling B2B and
                  B2B2C e-commerce to connect small business and big brands and
                  suppliers through its App and website. Mohasagor.com has been
                  launched in June 2015 & It is constructed by Mohasagor IT
                  solution. Mohasagor showcases products from all categories
                  such as clothing, footwear, jewelry, accessories, electronics,
                  appliance, books, restaurants, health & beauty, and still
                  counting! Our collection combines the latest in fashion trends
                  as well as the all-time favorites. Our products are
                  exclusively selected for you. In tune with the vision of
                  Digital Bangladesh, mohasagor.com opens the doorway for
                  everybody to shop over the Internet. We constantly update with
                  a lot of new products, services, and special offers. We
                  provide on-time delivery of products and quick resolution of
                  any concerns. We provide our customers with a memorable online
                  shopping experience. Our dedicated Mohasagor quality assurance
                  team works round the clock to personally make sure the right
                  packages reach on time. You can choose whatever you like. We
                  deliver it right at your address across Bangladesh. Our
                  services are at your doorsteps all the time. Get the best
                  products with the best online shopping experience every time.
                  You will enjoy online shopping here!
                </p>
              </div>
            </div>
          </div>
          <!--Middle Part End -->
        </div>
      </div>
    </div>

    <frontend-footer></frontend-footer>>
  </div>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
export default {
  created() {
    setTimeout(() => {
      this.isLoading = false;
    }, 1000);
  },
  data() {
    return {
      isLoading: true,
      fullPage: true,
    };
  },
  components: {
    Loading,
  },
};
</script>

<style scoped>
@media screen and (max-width: 350px) {
  h4 {
    margin-left: 10px;
  }
  p {
    font-size: 13px;
    padding: 10px;
    line-height: 24px;
  }
}
#content {
  min-height: 400px;
  padding: 50px 50px;
}
</style>
