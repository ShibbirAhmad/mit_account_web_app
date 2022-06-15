<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">campaign</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-9 col-lg-offset-2">
            <div class="box box_container box-primary">
              <div class="box-header text-center with-border">
                <h3 class="box-title">set parallax banner</h3>
              </div>
              <div class="box-body">
                <div class="alert-danger alert" v-if="error">{{ error }}</div>
                <br/>
                <form
                  @submit.prevent="updateCampaign"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="form-group">
                    <div class="row">
                      <div class="col-md-1 text-center">
                       <h4> <label for="url"> url </label> </h4>
                      </div>
                      <div class="col-md-8 code_input">
                        <input
                          type="text"
                          v-model="form.url"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('url'),
                          }"
                          name="url"
                        />
                        <has-error :form="form" field="url">
                        </has-error>
                      </div>
                     <div class="col-md-2">
                       <select v-model="form.status" class="form-control" >
                         <option value="1">Active</option>
                         <option value="2">De-Active</option>
                       </select>
                     </div>
                    </div>
                  </div>
                  <div class="form-group text-center">
                    <label for="banner">Offer Banner <span> width*height(450px*268px) </span> </label>
                    <img
                      @click="clickImage"
                      id="campaignOfferBanner"
                      :src="preview_image?preview_image:base_url+form.offer_banner"
                    />
                    <input
                      id="uploadLogo"
                      class="form-control"
                      type="file"
                      @change="uploadImage"
                      name="banner"
                    />
                  </div>

                   <div class="form-group text-center">
                    <label for="banner">Background Banner <span>width*height(1200px*300px)</span> </label>
                    <img
                      @click="clickBackgroundImage"
                      id="backgroundBanner"
                      :src="preview_image?preview_background:base_url+form.background_banner"
                    />
                    <input
                      id="uploadBackground"
                      class="form-control"
                      type="file"
                      @change="uploadBackgroundImage"
                      name="background_banner"
                    />
                  </div>
                  <div class="form-group text-center">
                    <button
                      :disabled="form.busy || disabled"
                      type="submit"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-spin fa-spinner" v-if="form.busy"></i
                      >Submit
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>


<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
Vue.component(HasError.name, HasError);
export default {
  name: "subCategory",
  created() {
    this.getBuyOne();
  },
  data() {
    return {
      form: new Form({
        id: "",
        offer_banner: "",
        background_banner: "",
        url: "",
        status:"",
      }),
      disabled: true,
      image_width:450,
      image_height:268,
      imagae_size:500,
      background_width:1200,
      background_height:300,
      background_size:1024,
      error: "",
      loading: true,
      base_url: this.$store.state.image_base_link,
      preview_image: "",
      preview_background: "",
    };
  },

  methods: {
    getBuyOne() {
      axios.get("/api/get/parallax/banner")
      .then((resp) => {
        console.log(resp);
        if (resp.data.status == "OK") {
          this.form.id = resp.data.campaign.id;
          this.form.url = resp.data.campaign.url;
          this.form.offer_banner = resp.data.campaign.offer_banner;
          this.form.background_banner = resp.data.campaign.background_banner;
          this.form.status = resp.data.campaign.status;
          this.loading = false;

        }
      });
    },

    updateCampaign() {
      this.form
        .post("/api/edit/parallax/banner/"+this.form.id, {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.getBuyOne();
          } else {
            this.error = "some thing want to wrong";
          }
        });
    },

   uploadImage(e) {
      const file = e.target.files[0];
     if (!file.type.match("image.*")) {
         Swal.fire({
          type:'warning',
          text:'this is not any kind of image',
        });
        return;
      }
       if(file.size/1024>this.imagae_size){
        Swal.fire({
          type:'warning',
          text:`File size can not be bigger then ${this.imagae_size}KB.Reference file size is'+file.size/1024 +'KB`,
        });
        return;
      }
 ///let image file size check
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();
        img.onload = () => {
          console.log(img.width, img.height);
          if (
            img.width != this.image_width &&
            img.height != this.image_height
          ) {
            this.disabled = true;
            Swal.fire({
              type:'error',
              text:  "Image size need  " + this.image_width +"*" + this.image_height +  "px. But Upload imaze size " + img.width+ "*" +
                img.height +
                "px"
            })
          } else {
            this.setImage(file, evt);
            return;
          }
        };
        img.src = evt.target.result;
      };
    },

   setImage(file, evt) {
      console.log(file);
      this.disabled = false;
      this.preview_image = evt.target.result;
      this.form.offer_banner = file;
    },

    clickImage() {
      let  offer_banner = document.getElementById("uploadLogo");
       offer_banner.click();
    },


   uploadBackgroundImage(e) {
      const file = e.target.files[0];
     if (!file.type.match("image.*")) {
         Swal.fire({
          type:'warning',
          text:"this isn't any kind of image",
        });
        return;
      }
       if(file.size/1024>this.background_size){
        Swal.fire({
          type:'warning',
          text:`File size can not be bigger then ${this.background_size}KB.Reference file size is'+file.size/1024 +'KB`,
        });
        return;
      }
 ///let image file size check
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();
        img.onload = () => {
          if (
            img.width != this.background_width &&
            img.height != this.background_height
          ) {
            this.disabled = true;
            Swal.fire({
              type:'error',
              text:  "Image size need  " + this.background_width +"*" + this.background_height +  "px. But Upload imaze size " + img.width+ "*" +
                img.height +
                "px"
            })
          } else {
            this.setBackground(file, evt);
            return;
          }
        };
        img.src = evt.target.result;
      };
    },

   setBackground(file, evt) {
      this.disabled = false;
      this.preview_background = evt.target.result;
      this.form.background_banner = file;
    },

   clickBackgroundImage() {
      let background_banner = document.getElementById("uploadBackground");
      background_banner.click();
    },


  },
};
</script>

<style scoped>
.box_container {
  margin-left: -14%;
  margin-top: 2%;
}
.code_input {
  width:70%;
}

#uploadLogo {
  display: none;
}
#uploadBackground {
  display: none;
}

#campaignOfferBanner {
  border: 1px solid #ddd;
  padding: 5px;
  cursor: pointer;
  margin-left: 25px;
  width: 450px;
  height: 268px;
}

#backgroundBanner {
  border: 1px solid #ddd;
  padding: 5px;
  cursor: pointer;
  margin-left: 25px;
  width: 95%;
  height: 300px;
}



</style>