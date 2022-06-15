<template>
  <div class="wrapper-wide">
    <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>

    <frontend-header ></frontend-header>

    <div id="container">
      <div class="container">
        <!-- Breadcrumb End-->
        <div class="row" v-if="cart.total>0">
          <!--Middle Part Start-->
          <div id="content">
            <div class="row">
              <div class="col-lg-5 col-md-5 col-sm-12 col-xs-12">
                <div class="custom-box">
                  <h2 class="title">Checkout</h2>
                  <form @submit.prevent="chekout">
                    <div class="form-grop">
                      <label class="control-label" for="input-email">Name</label>
                      <input
                        type="text"
                        name="name"
                        class="form-control"
                        placeholder="your name"
                        :class="{ 'is-invalid': form.errors.has('name') }"
                        v-model="form.name"
                        autofocus
                      />
                      <has-error :form="form" field="name"></has-error>
                    </div>
                    <br />
                    <div class="form-grop">
                      <label class="control-label" for="input-email">Mobile Number</label>
                      <input
                        type="text"
                        name="mobile_no"
                        placeholder="01xxx-xxxxx"
                        class="form-control"
                        maxlength="11"
                        :class="{ 'is-invalid': form.errors.has('mobile_no') }"
                        v-model="form.mobile_no"

                      />
                      <has-error :form="form" field="mobile_no"></has-error>
                    </div>
                    <br />
                    <div class="form-group">
                      <label class="control-label" for="input-password">Address</label>
                      <textarea
                        name="address"
                        placeholder="Address"
                        class="form-control"
                        :class="{ 'is-invalid': form.errors.has('address') }"
                        v-model="form.address"
                        @keyup="validation"
                      ></textarea>
                      <has-error :form="form" field="address"></has-error>

                      <br />
                      <div class="form-group">
                        <label class="control-label" for="input-email">City</label>
                        <select
                          name="city"
                         :class="{ 'is-invalid': form.errors.has('city') }"
                          class="form-control"
                          v-model="form.city"
                          @change="selectCity"
                        >
                          <option value="" selected disabled>Select City</option>
                          <option
                            value
                            v-for="(city,index) in cities"
                            :key="index"
                            :value="city.id"
                          >{{city.name}}</option>
                        </select>
                        <has-error :form="form" field="city"></has-error>
                      </div>

                       <div class="form-group">
                    <label>Sub City</label>
                    <select @change="validation" class="form-control" name="sub_city"  :class="{ 'is-invalid': form.errors.has('sub_city') }" v-model="form.sub_city">
                      <option disabled value="">select sub city</option>
                      <option v-if="sub_cities.length > 0 " v-for="sub_city in sub_cities" :key="sub_city.id" :value="sub_city.id">{{sub_city.name}}</option>
                    </select>
                    <has-error :form="form" field="sub_city"></has-error>

                  </div>
                      <br />
                    </div>
                    <button

                      class="btn btn-block btn-primary"
                      type="submit"
                      :disabled="form.busy || disabled"
                    >
                      <i class="fa fa-spinner fa-spin" v-if="form.busy"></i>
                      CONFIRM ORDER
                    </button>
                  </form>

                   <!-- <button
                          href="#"
                          class="btn btn-primary"
                          id="sslczPayBtn"
                          token="if you have any token validation"
                          postdata="your javascript arrays or objects which requires in backend"
                          order="If you already have the transaction generated for current order"
                          endpoint="/pay-via-ajax"
                          style="display:none"
                        >
                          Pay Now
                        </button> -->

                        <form action="/pay" id="payment_form" method="post">
                              <input type="hidden" name="order_id"  id="__o_id">
                        </form>
                </div>
              </div>
              <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12">
                <div class="custom-box">
                  <div class="cart-total">
                    <table>
                      <tbody>
                        <tr>
                          <td>Total</td>
                          <td colspan="4"></td>
                          <td>
                            :
                            <span v-if="form.shipping_cost">&#2547;{{form.total}}</span>
                            <span v-else>.....</span>
                          </td>
                        </tr>
                        <br/>
                          <tr v-if="form.coupon_disocunt > 0">
                          <td>Coupon Discount</td>
                          <td colspan="4"></td>
                          <td>
                            :
                            <span v-if="form.shipping_cost">&#2547;{{form.coupon_disocunt}}</span>
                          </td>
                        </tr>
                        <br />
                        <tr>
                          <td>Shipping cost</td>
                          <td colspan="4"></td>
                          <td>
                            :
                            <span v-if="form.shipping_cost">&#2547;{{ form.shipping_cost }}</span>
                            <span v-else>.....</span>
                          </td>
                        </tr>
                        <br />
                        <tr>
                          <td>Total</td>
                          <td colspan="4"></td>
                          <td>
                            :
                            <span
                              v-if="form.shipping_cost"
                            >&#2547;{{(parseInt(form.total.replace(',','')-parseInt(form.coupon_disocunt)))+parseInt(form.shipping_cost)}}</span>
                            <span v-else>.....</span>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12"  style="margin-top:10px;">
                <a href="#" class="coupon-apply" @click.prevent="coupon = !coupon">Have you any cupon?</a>
                <br/>
                <div class="custom-box" v-if="coupon">

                  <div class="coupon">
                    <label for="">
                      <strong>Apply Coupon Here</strong>
                    </label>
                   <div style="display:flex">
                      <input id="coupon_code" style="width:60%" v-model="coupon_code" type="text" class="form-control">
                    <button :disabled="coupon_code.length <=0 " class="btn btn-primary" @click.prevent="applyCoupon" style="border-radius:0px;">Apply</button>
                   </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-5 col-md-5 col-xs-12 col-sm-12" v-if="form.city" style="margin-top:10px;">
                <div class="custom-box">
                  <div class="payment-slect">
                    <h4>
                      <strong>Select Payment Method</strong>
                    </h4>
                      <div class="row">
                        <div class="col-lg-6">
                             <div
                               class="payment_option"
                               :class="{'payment_option_active':payment_option==1}"
                                @click="payment_option=1"
                        >
                        <h4>Cash On Delivery</h4>
                      </div>
                        </div>
                        <div class="col-lg-6">
                            <div
                             :class="{'payment_option_active':payment_option==2}"
                             class="payment_option"
                            @click="payment_option=2">
                         <h4>Online Payments</h4>
                      </div>
                        </div>
                      </div>

                  </div>
                </div>
              </div>
                </div>
          </div>
        </div>
        <div class="row" v-else>
          <div class="empty-cart">
            <h4 class="text-uppercase">your cart is empty</h4>
            <img style="    margin-bottom: 50px;
    margin-top: 50px;" src="https://mohasagor.com/public/storage//images/static/cartEmpty.jpg">
            <br/>
            <a href="/" style="    background: #ff4d03;
    color: #fff;
    padding: 12px 15px;
    border-radius: 5px;"> Home Page</a>
          </div>
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

    this.user();
    this.getCity();
    this.getCartContent();

    setTimeout(() => {
      this.isLoading = false;
      this.validation();
    }, 1500);
  },
  data() {
    return {
      form: new Form({
        mobile_no: "",
        name: "",
        address: "",
        city: "",
        total: "",
        cart: "",
        shipping_cost: "",
        sub_city: "",
        coupon_disocunt: 0,
        coupon_type: "",
        coupon_id: "",
      }),
      isLoading: true,
      fullPage: true,
      cities: "",
      cart: {
        total: 0,
      },
      disabled: true,
      sub_cities: "",
      payment_option: 1,
      coupon_code: "",
      coupon: false,
    };
  },
  methods: {

    chekout() {
      // if(this.payment_option !=1 ||  this.payment_option !=2){
      //    alert('Please Select A Payment Method');
      //   return;
      // }
      this.form
        .post("/_public/checkout")
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            if (this.payment_option == 2) {
              this.makePaymentSSl(resp.data.order);
              this.$toasted.show(
                "Your Order Was Place,Please Ensure Your Payment.",
                {
                  type: "success",
                  position: "top-center",
                  duration: 4000,
                }
              );
            } else {
            window.open('/user/Checkout/success','_self')
          //  this.$router.push({ name: "checkoutSuccess" });
              this.$toasted.show(resp.data.message, {
                type: "success",
                position: "top-center",
                duration: 2000,
              });
            }
          } else {
            this.$toasted.show( resp.data.message || "something went to wrong", {
              type: "error",
              position: "top-center",
              duration: 2000,
            });
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show("something went to wrong", {
            type: "error",
            position: "top-center",
            duration: 2000,
          });
        });
    },

    getCity() {
      axios
        .get("/api/get/city/for/order/checkout")
        .then((resp) => {
          this.cities = resp.data.cities;
        })
        .catch((error) => {
          console.log(error);
        });
    },

    selectCity() {
      for (let i = 0; i < this.cities.length; i++) {
        if (this.cities[i].id == this.form.city) {
          this.form.shipping_cost = this.cities[i].delivery_charge;
        }
      }
      this.subCity();
      this.validation();
    },
    user() {
      axios.get("/_public/userToCheck").then((resp) => {
        if (resp.data.status == "AUTHORIZED") {
          this.form.mobile_no = resp.data.user.mobile_no;
          this.form.name = resp.data.user.name;
          this.form.address = resp.data.user.address;
        }
      });
    },

    getCartContent() {
      axios.get("/_public/cartToContent").then((resp) => {
        // console.log(resp.data)
        this.cart.total = resp.data.cart_total;
        this.form.total = resp.data.cart_total;
      });
    },
    validation() {
      if (this.form.address.length < 3) {
        this.disabled = true;
        return;
      }
      if (this.form.city.length <= 0) {
        this.disabled = true;
        return;
      }
      if (this.form.total.length <= 0) {
        this.disabled = true;
        return;
      }
      if (this.form.city <= 0) {
        this.disabled = true;
        return;
      }

      if (!this.form.sub_city) {
        this.disabled = true;
        return;
      }

      this.disabled = false;
    },

    subCity() {
      if (this.form.city) {
        this.isLoading = true;
        axios
          .get("/city/wise/sub/city/" + this.form.city)
          .then((resp) => {
            if (resp.data.length) {
              if (this.sub_cities.length > 0) {
                this.sub_cities = "";
              }
              if (this.form.sub_city) {
                this.form.sub_city = "";
              }
              this.sub_cities = resp.data;
            } else {
              this.form.sub_city = "";
              this.sub_cities = "";
              alert("No Sub City Under Selected Under City");
            }
            this.isLoading = false;
            this.validation();

            console.log(resp);
          })
          .catch((e) => {
            console.log(e);
            this.isLoading = false;
          });
      }
    },

    makePaymentSSl(order) {
      // let button = document.getElementById("sslczPayBtn");
      // let customer = order.customer;
      // var obj = {};
      // obj.cus_name = customer.name;
      // obj.cus_phone = order.cutomer_phone;
      // obj.cus_email = customer.email ? customer.email : "abc@email.com";
      // obj.cus_addr1 = customer.address;
      // obj.amount =
      //   parseInt(order.total) -
      //   (parseInt(order.discount) + parseInt(order.shipping_cost));
      // obj.order_id = order.id;
      // button.setAttribute("postdata", obj);
      // button.click();
      document.getElementById("__o_id").value = order.id;
      document.getElementById("payment_form").submit();
    },
    applyCoupon() {
      if (this.coupon_code.length <= 0) {
        alert("Coupon Code Is Empty");
        document.getElementById("coupon_code").focus();
        return;
      }

      if (this.form.city.length <= 0) {
        alert("Please Select City First");
        return;
      }
      axios
        .get("/_public/apply/coupon/code", {
          params: {
            coupoon_code: this.coupon_code,
          },
        })
        .then((resp) => {
          if (resp.data.success == "OK") {
            console.log(resp);

            let discount = 0;
            let coupon = resp.data.coupon;
            let total = this.form.total;
            if (coupon.discount_type == "percentage") {
              discount =
                (parseInt(total) / parseInt(100)) *
                parseInt(coupon.discount_amount);
            } else {
              discount = parseInt(coupon.discount_amount);
            }

            this.form.coupon_disocunt = discount.toFixed(2);
            this.form.coupon_id = coupon.id;

            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            this.coupon_code = "";
          } else {
            this.$toasted.show(resp.data, {
              type: "error",
              position: "top-center",
              duration: 2000,
            });
          }
        })
        .catch((e) => {
          this.$toasted.show("something went to Wrong ", {
            type: "error",
            position: "top-center",
            duration: 2000,
          });
        });
    },
  },
  components: {
    Loading,
    HasError,
  },
  mounted() {
    setTimeout(() => {
      this.selectCity();
    }, 1000);
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
  padding: 15px 15px;
  text-align: center;
  font-size: 13px;
  border-radius: 5px;
  cursor: pointer;
  height: 70px;
  margin-top: 8px;
}
.payment_option h4 {
  font-size: 16px;
  font-weight: 600;
}
.payment_option_active {
  border: 3px solid #ff4d03;
}
</style>