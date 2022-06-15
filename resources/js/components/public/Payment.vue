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
        <div class="row" v-if="!payment_complete">
          <div id="content">
            <div class="row">
              <div class="col-lg-2"></div>
              <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="custom-box">
                  <h4 class="title">select payment method</h4>

                  <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-4">
                      <div
                        class="payment_option"
                        :class="{
                          select_payement_type: payment_method_type == 1,
                        }"
                        @click="payment_method_type = 1"
                      >
                        <h4>Cash On Delivery </h4>
                      </div>
                    </div>

                    <div class="col-lg-4" @click="payment_method_type = 2">
                      <div
                        class="payment_option"
                        :class="{
                          select_payement_type: payment_method_type == 2,
                        }"
                      >
                        <h4>Online Payment</h4>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-2"></div>
                    <div class="col-lg-10">
                      <div
                        class="payment_description"
                        v-show="payment_method_type == 1"
                      >
                        <p>
                          You can pay in cash to our courier when you receive
                          the products.
                        </p>
                        <button id="paymentBtn" @click.prevent="CashOnDelivery">
                          Complete Order
                        </button>
                      </div>

                      <div
                        class="payment_description"
                        v-show="payment_method_type == 2"
                      >
                        <p>
                         You can pay amount using SSlcommerz  Payment GetWay.
                        </p>
                        <button
                          href="#"
                          class="btn btn-primary"
                          id="sslczPayBtn"
                          token="if you have any token validation"
                          postdata="your javascript arrays or objects which requires in backend"
                          order="If you already have the transaction generated for current order"
                          endpoint="/pay-via-ajax"
                        >
                          Pay Now
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="custom-box" v-else>
          <h4>Thank your for your purchase.Enjoy your online shopping !!!</h4>
          <a href="#" class="btn btn-primary">Shopping More</a>
        </div>
      </div>
    </div>
    <frontend-footer></frontend-footer>
  </div>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
import { Form, HasError } from "vform";

export default {
  created() {
    window.scrollTo(0, 0);

    this.getOrder();

  },
  data() {
    return {
      payment_method_type: 0,
      isLoading: true,
      fullPage: true,
      payment_complete: false,
      order: "",
    };
  },
  methods: {
    CashOnDelivery() {
      this.isLoading = true;
      
      setTimeout(()=>{
     this.isLoading=false;
     this.$router.push({ name: "welcome"});
       this.$toasted.show("Thank You For Purchase Product. Enjoy Your Online Shopping !!", {
            position: "top-center",
            type: "success",
            duration: 3000,
     });

      },1500)
     
    },

    getOrder() {
      
      axios
        .get("/api/get/order/" + this.$route.params.id)
        .then((resp) => {
          this.order = resp.data.order;
          let order = this.order;
          let customer = order.customer;
          var obj = {};
          obj.cus_name = customer.name;
          obj.cus_phone = order.cutomer_phone;
          obj.cus_email = customer.email ? customer.email : "abc@email.com";
          obj.cus_addr1 = customer.address;
          obj.amount = parseInt(order.total) - (parseInt(order.discount) + parseInt(order.shipping_cost));
          obj.order_id = order.id;
          document.getElementById("sslczPayBtn").setAttribute("postdata", obj);
          this.isLoading=false;
        })
        .catch((e) => {
          this.isLoading=false;
        });
    },
  },
  components: {
    Loading,
  },
};
</script>

<style>
.empty-cart {
  width: 50%;
  background: #fff;
  text-align: center;
  margin-left: 25%;
  padding: 50px 50px;
  box-shadow: 3px 3px 3px #ddd;
}
.payment_option {
  background: #e6eaf2;
  padding: 30px 15px;
  text-align: center;
  font-size: 20px;
  border-radius: 5px;
  cursor: pointer;
}

.payment_option a {
  font-size: 22px;
}
.payment_description {
  padding: 55px 0px;
}
.select_payement_type {
  border: 1px solid #ff4d03;
}
</style>