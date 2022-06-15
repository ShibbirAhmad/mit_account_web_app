<template>
  <div>
    <div class="__image_box">
      <image-zoom
        img-class="__image_box_img"
        :regular="base_url + images[0].product_image"
      >
      </image-zoom>
    </div>
    <div class="__preview_image_box">
      <img
        v-for="(image, index) in images"
        class="__preview_img"
        :key="index"
        :class="{ __p_active: index == 0 }"
        :src="base_url + image.product_image"
        @click="displaye_image_from_box"
      />
    </div>
    <br />
    <p>
      More Inforamtion About This Product, Just Call Now
      <span>
        <strong>
          <a href="tel:09636-203040">09636-203040</a>
        </strong>
      </span>
    </p>

    <p style="font-weght:600;color:#000" > Note: you have to pay in advance 120 Taka, if you are from outside Dhaka </p>

    <div class="__lightbox" v-if="popup">
      <div class="__light_box_content">
        <img
          class="__image_ligitbox_img"
          :src="base_url + images[0].product_image"
        />
      </div>
    </div>
  </div>
</template>
<script>
import imageZoom from "vue-image-zoomer";

export default {
  props: {
    images: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      base_url: this.$store.state.image_base_link,
      popup: false,
    };
  },
  methods: {
    displaye_image_from_box(e) {
      let target_element = e.target;
      let active_images = document.getElementsByClassName("__p_active");

      if (active_images.length > 0) {
        for (let i = 0; i < active_images.length; i++) {
          active_images[i].classList.remove("__p_active");
        }
      }

      target_element.classList.add("__p_active");
      let big_image = document.getElementsByClassName("__image_box_img");
      big_image[0].setAttribute("src", target_element.src);
    },
    show_pop_up_img() {
      this.popup = false;
    },
  },
  components: {
    imageZoom,
  },
};
</script>
