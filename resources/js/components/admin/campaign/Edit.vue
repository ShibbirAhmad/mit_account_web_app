<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'backendCampaign' }" class="btn btn-primary">
            <i class="fa fa-arrow-right"></i>
          </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">Category slider</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Edit Campaign</h3>
              </div>
              <div class="box-body">
                <div class="alert-danger alert" v-if="error">{{ error }}</div>

                <h1 v-if="loading">
                  <i class="fa fa-spin fa-spinner"></i>
                </h1>

                <form
                  @submit.prevent="update"
                  v-else
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <label>Campaign Name</label>
                        <input
                          type="text"
                          class="form-control"
                          v-model="form.name"
                          :class="{ 'is-invalid': form.errors.has('name') }"
                          name="name"
                        />
                        <has-error :form="form" field="url"> </has-error>
                      </div>

                      <div class="form-group">
                        <label>Campaign Start Date</label>
                        <date-picker
                          autocomplete="off"
                          v-model="form.start_date"
                          :class="{
                            'is-invalid': form.errors.has('start_date'),
                          }"
                          name="start_date"
                          :config="options"
                        ></date-picker>

                        <has-error :form="form" field="start_date"> </has-error>
                      </div>

                      <div class="form-group">
                        <label>Campaign End Date</label>
                        <date-picker
                          autocomplete="off"
                          v-model="form.end_date"
                          :class="{ 'is-invalid': form.errors.has('end_date') }"
                          name="end_date"
                          :config="options"
                        ></date-picker>

                        <has-error :form="form" field="end_date"> </has-error>
                      </div>

                     <div class="row">
                        <div class="col-lg-7">
                          <div class="form-group">
                            <label>Backgroud Color</label>
                            <input
                              v-model="form.bg_color"
                              type="color"
                              class="form-control"
                              name="bg-color"
                            />
                          </div>
                        </div>
                        <div class="col-lg-5">
                          <div class="form-group">
                            <label> Color Code </label>
                            <input
                              v-model="form.bg_color"
                              type="text"
                              class="form-control"
                              name="bg-color"
                              placeholder="Enter 6 Digits Color Code With #"
                            />
                          </div>
                        </div>
                      </div>
                         <div class="row">
                        <div class="col-lg-7">
                          <div class="form-group">
                            <label>Text Color</label>
                            <input
                              v-model="form.text_color"
                              type="color"
                              class="form-control"
                              name="bg-color"
                            />
                          </div>
                        </div>
                        <div class="col-lg-5">
                          <div class="form-group">
                            <label> Color Code </label>
                            <input
                              v-model="form.text_color"
                              type="text"
                              class="form-control"
                              name="bg-color"
                              placeholder="Enter 6 Digits Color Code With #"
                            />
                          </div>
                        </div>
                      </div>

                      <div class="form-group">
                        <label> Terms & Condition</label>
                        <ckeditor
                          :editor="editor"
                          name="details"
                          :config="editorConfig"
                          v-model="form.terms_and_condition"
                        ></ckeditor>
                      </div>
                    </div>
                  </div>
                  <br />
                  <button
                    :disabled="form.busy || disabled"
                    type="submit"
                    class="btn btn-primary btn-block"
                  >
                    <i class="fa fa-spin fa-spinner" v-if="form.busy"></i>Update
                  </button>
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
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

Vue.component(HasError.name, HasError);

Vue.component(HasError.name, HasError);
export default {
  name: "subCategory",
  created() {
    this.getCampaign();
  },
  data() {
    return {
      form: new Form({
        image: null,
        name: "",
        start_date: "",
        end_date: "",
        files: null,
        product_show: false,
        slider_img: false,
        terms_and_condition: "",
        bg_color:"",
        text_color:""
      }),
      error: "",
      loading: true,
      disabled: false,
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      isDraging: false,
      dragCount: 0,
      basePath: this.$store.getters.image_base_link,
      editorConfig: {},
      editor: ClassicEditor,
      error: "",
    };
  },

  methods: {
    getCampaign() {
      axios
        .get("/api/campaign/edit/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.campaign) {
            let campaign = resp.data.campaign;
            this.form.name = campaign.campaign_name;
            this.form.start_date = campaign.start_date;
            this.form.end_date = campaign.end_date;
            this.form.terms_and_condition = campaign.terms_and_condition;
            this.form.bg_color = campaign.bg_color;
            this.form.text_color = campaign.text_color;
            if (campaign.image) {
              this.form.file = this.basePath + campaign.image;
            }
            this.loading = false;
          } else {
            this.$toasted.show("some thing went wronggg", {
              type: "error",
              position: "top-center",
              duration: 5000,
            });
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show("some thing want to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },
    update() {
      this.form
        .post("/api/campaign/update/" + this.$route.params.id, {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "backendCampaign" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          } else {
            this.error = "some thing want to wrong";
          }
        })
        .catch((error) => {
          this.error = error.response.data.errors;
          this.$toasted.show("some thing want to wrong", {
            type: "error",
            position: "top-center",
            duration: 5000,
          });
        });
    },

    uploadImage(e) {
      const file = e.target.files[0];
      if (!file.type.match("image.*")) {
        Swal.fire({
          type: "warning",
          text: "this is not any kind of image",
        });
        return;
      }

      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();
        img.onload = () => {
          if (img.width > 1100 && img.height > 300) {
            this.form.image = file;
            this.form.file = evt.target.result;

            return;
          } else {
            this.disabled = true;
            alert(
              "Image minimum size 1100*300px.But Upload imaze size" +
                img.width +
                "*" +
                img.height +
                "px"
            );
            return;
          }
        };
        img.src = evt.target.result;
      };
    },
  },
  computed: {},
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
label.selectFile {
  width: 150px;
  background: #fff;
  color: #222d32;
  padding: 10px 10px;
  text-align: center;
  font-size: 20px;
  border-radius: 5px;
  margin-top: 15px;
  cursor: pointer;
  border: 1px solid #222d32;
}
.height {
  height: 360px !important;
}

.uploader {
  width: 100%;
  background: #222d32;
  color: #fff;
  padding: 40px 15px;
  text-align: center;
  border-radius: 10px;
  border: 3px dashed;
  font-size: 20px;
  position: relative;
}

.draging {
  background: #fff;
  color: #222d32;
  border: 3px dashed #222d32;
}

.file-input label {
  background: #222d32;
  color: #fff;
}

i.fa.fa-cloud-upload {
  font-size: 85px;
}

#file {
  opacity: 0;
  z-index: -1;
}

.file-input {
  width: 280px;
  margin: auto;
  position: relative;
  height: 86px;
}

.images-preview {
  display: flex;
  flex-wrap: wrap;
  margin-top: 20px;
}

.img-wrapper {
  width: 110px;
  display: flex;
  flex-direction: column;
  margin: 10px;
  height: 105px;
  justify-content: space-between;
  background: #fff;
  box-shadow: 5px 5px 20px #3e3737;
  margin-bottom: 32px;
}

img {
  max-height: 105px;
}

.files {
  font-size: 12px;
  background: #fff;
  color: red;
  display: flex;
  flex-direction: column;
  align-items: self-start;
  padding: 3px 6px;
}

.name {
  overflow: hidden;
  height: 18px;
}

.upload-control {
  position: absolute;
  width: 100%;
  background: #fff;
  top: 0;
  left: 0;
  border-top-left-radius: 7px;
  border-top-right-radius: 7px;
  padding: 10px;
  padding-bottom: 4px;
  text-align: right;
}

.label {
  padding: 2px 5px;
  margin-right: 10px;
  border: 2px solid #222d32;
  border-radius: 3px;

  font-size: 15px;
  cursor: pointer;
  color: #222d32;
}

.file-input label {
  background: #fff;
  color: #222d32;
  padding: 10px 40px;
}
.cancel {
  background: #fff;

  cursor: pointer;
  color: red;
  width: 110px;
}
</style>
