<template>
  <div>
    <footer id="footer">
      <div class="fpart-first">
        <div class="container">
          <div class="row">
            <div class="contact col-lg-3 col-md-3 col-sm-12 col-xs-12">
              <ul>
                <li class="address">
                  <p class="short_desc">
                    <span
                      style="font-size: 16px; color: #ff4d03; font-weight: 700"
                      >Mohasagor.com</span
                    >
                    is a complete e-commerce website in Bengali and English.
                    Here, consumers of all ages can buy every essential product
                    of the day, from gadgets, electronics, home appliances,
                    leather goods, jewelry, baby accessories, cosmetics, fashion
                    and lifestyle products to affordable prices at home
                  </p>
                </li>
              </ul>
            </div>

            <div class="column col-lg-2 col-md-2 col-sm-6 col-xs-6">
              <div class="quick_link">
                <h5>Quick Links</h5>
                <div class="line"></div>
                <ul class="link_line">
                  <li>
                    <router-link :to="{ name: 'carrier_public' }"
                      >Carrier</router-link
                    >
                  </li>
                  <li>
                    <router-link :to="{ name: 'welcome' }">Home</router-link>
                  </li>
                  <li>
                    <router-link :to="{ name: 'blog' }">Blog</router-link>
                  </li>
                  <li>
                    <router-link :to="{ name: 'AboutUs' }"
                      >About Us</router-link
                    >
                  </li>
                  <li>
                    <router-link :to="{ name: 'ContactUs' }"
                      >Contact Us</router-link
                    >
                  </li>
                  <li>
                    <a href="https://mohadesh.com/" target="_blank">News</a>
                  </li>
                </ul>
              </div>
            </div>

            <div class="column col-lg-2 col-md-2 col-sm-6 col-xs-6">
              <h5>Information</h5>
              <div class="line"></div>
              <ul class="link_line">
                <li>
                  <router-link :to="{ name: 'how_to_buy' }"
                    >How to order</router-link
                  >
                </li>
                <li>
                  <router-link :to="{ name: 'return_policy' }"
                    >Return Policy</router-link
                  >
                </li>
                <li>
                  <router-link :to="{ name: 'shipment' }"
                    >Shipment
                  </router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'team' }">Team</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'about_seller' }"
                    >About Seller
                  </router-link>
                </li>
              </ul>
            </div>

            <div class="column col-lg-4 col-md-4 col-sm-12 col-xs-12">
              <div class="news_letter">
                <h5>Follow Us</h5>
                <div class="line"></div>
                <form @submit.prevent="subscribe()">
                  <div class="row">
                    <div class="form-group subscribe_input">
                      <label
                        style="margin-bottom: 20px; margin-top: 5px"
                        class="control-label"
                        for="subscribe"
                        >submit email to recieve latest offer .</label
                      >
                      <input
                        id="signup"
                        type="email"
                        required
                        placeholder="Email address"
                        v-model="email"
                        class="form-control"
                      />
                    </div>
                    <button type="submit" class="btn email_icon_container">
                      <i class="fa fa-lg fa-envelope"> </i>
                    </button>

                    <div style="margin-left: 1px; display: flex">
                      <iframe
                        src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2Fmymohasagor%2F&tabs=timeline&width=340&height=150&small_header=true&adapt_container_width=false&hide_cover=false&show_facepile=false&appId=112664817367714"
                        width="340"
                        height="150"
                        style="border: none; overflow: hidden"
                        scrolling="no"
                        frameborder="0"
                        allowfullscreen="true"
                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                      ></iframe>
                      <div class="social-icon" style="margin-top: 7px">
                        <!-- <a href="https://www.facebook.com/mymohasagor/" target="_blank" class="social-wrape">
                  <i class="fa fa-lg fa-facebook f-icon"></i>
                </a> -->

                        <a
                          href="https://www.youtube.com/channel/UCUaEOS7b8kcz47YAxJIUL2w"
                          class="social-wrape"
                          target="_blank"
                        >
                          <i class="fa fa-lg fa-youtube f-icon"></i>
                        </a>

                        <a
                          href="#"
                          class="social-wrape"
                          style="margin-top: 10px"
                          target="_blank"
                        >
                          <i class="fa fa-lg fa-twitter f-icon"></i>
                        </a>
                        <a
                          href="https://www.instagram.com/mohasagorltd/"
                          class="social-wrape"
                          style="margin-top: 10px"
                          target="_blank"
                        >
                          <i class="fa fa-lg fa-instagram f-icon"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="fpart-second">
        <div class="container">
          <div id="powered" class="clearfix"></div>
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
              <div class="payment-card">
                <img
                  :src="base_url + 'images/ssl.png'"
                  style="width: 100%; height: auto; margin-top: -30px"
                  alt=""
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="container-fluid">
        <div class="row">
          <div class="end_footer">
            <div class="col-md-4 col-xs-12 col-sm-12"></div>
            <div class="col-md-4 col-xs-12 col-sm-12">
              <p class="f_info_left">
                Copyright © mohasagor.com 2020. All rights reserved.
              </p>
            </div>
            <div class="col-md-4 col-xs-12 col-sm-12">
              <p class="f_info_right">
                Design & Developed by: Mohasagor it Solution
              </p>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>



<script>
export default {
  mounted() {
    //  console.log("footer mounetd");
  },
  data() {
    return {
      email: "",
      base_url: this.$store.state.image_base_link,
    };
  },
  methods: {
    subscribe() {
      axios
        .post("/_public/add/subscriber", {
          email: this.email,
        })
        .then((resp) => {
          if (resp.data.success == "OK") {
            this.email = "";
            Swal.fire({
              type: "success",
              text: resp.data.message,
              duration: 2000,
            });
          } else {
            this.email = "";
            Swal.fire({
              type: "warning",
              text: resp.data.message,
              duration: 2000,
            });
          }
        })
        .catch();
    },
  },
};
</script>


<style>
#footer .column a {
  text-decoration: none;
  display: block;
  padding: 3px 0;
  line-height: 24px;
  font-size: 12px;
}
.line {
  width: 72px;
  height: 2px;
  background: #ff4d03;
  margin-top: -10px;
  margin-bottom: 12px;
}

.social-icon {
  margin: 10px;
}

.social-wrape {
  float: left;
  width: 40px;
  height: 40px;
  border: 2px dashed;
  border-radius: 50%;
  margin-left: 10px;
}

.social-wrape:hover {
  background: #ff4d03;
}

.f_info_left {
  position: absolute;
  margin-top: 7px;
  margin-left: 5px;
  font-size: 14px;
}

.f_info_right {
  float: right;
  margin-top: 7px;
  margin-left: 120px;
  font-size: 14px;
}

.f-icon {
  margin: 11px;
  cursor: pointer;
  color: white;
}

#footer #powered {
  border-bottom-color: #ff4d03;
  margin-top: -20px;
}

#footer .powered_text p {
  line-height: 18px;
  margin: -19px;
}

.end_footer {
  background: #ff4d03;
  height: 40px;
  width: 100%;
}

.end_footer p {
  color: white;
}

.email_icon_container {
  background: #ff4d03;
  margin-top: -51px;
  float: right;
  padding: 9px 17px;
}

.email_icon_container i {
  color: #fff;
}

.short_desc {
  font-size: 14px;
  line-height: 24px;
}
</style>
