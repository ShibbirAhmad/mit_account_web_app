<template>
  <div>
    <navbar></navbar>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link
            :to="{ name: 'merchant_product_menage' }"
            class="btn btn-primary"
          >
            <i class="fa fa-arrow-right"></i>
          </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i> merchant </a>
          </li>
          <li class="active">add product</li>
        </ol>
      </section>
      <section class="content">
        <h1 v-if="loading" style="text-align: center; font-size: 50px">
          <i class="fa fa-spinner fa-spin"></i>
        </h1>

        <form
          v-else
          @submit.prevent="addProduct"
          @keydown="form.onKeydown($event)"
          enctype="multipart/form-data"
        >
          <div
            class="alert alert-danger alert-dismissible"
            v-if="error"
            role="alert"
          >
            {{ error }}
            <span
              aria-hidden="true"
              class="close"
              data-dismiss="alert"
              style="color: #fff"
              aria-label="Close"
              >&times;</span
            >
          </div>
          <div class="row justify-content-center">
            <div class="col-lg-4">
              <div class="box box-primary height">
                <div class="box-header with-border">
                  <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label>
                      Name

                      <b class="text-danger">*</b>
                    </label>
                    <input
                      v-model="form.name"
                      type="text"
                      name="name"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('name') }"
                      autofocus
                      autocomplete="off"
                      placeholder="Ex:jean's pents"
                    />
                    <has-error :form="form" field="name"></has-error>
                  </div>

                  <div class="form-group">
                    <label>
                      Category
                      <b class="text-danger">*</b>
                    </label>
                    <select
                      class="form-control"
                      name="category"
                      v-model="form.category"
                      :class="{ 'is-invalid': form.errors.has('category') }"
                      @change="categoryWiseSubCategory()"
                    >
                      <option value>select category</option>
                      <option
                        v-for="category in categories"
                        :value="category.id"
                      >
                        {{ category.name }}
                      </option>
                    </select>
                    <has-error :form="form" field="category"></has-error>
                  </div>
                  <div class="form-group">
                    <label>
                      sub category

                      <b class="text-danger">*</b>
                    </label>
                    <select
                      class="form-control"
                      v-model="form.sub_category"
                      name="sub_category"
                      :class="{ 'is-invalid': form.errors.has('sub_category') }"
                      @change="subCategoryWiseSubSUbCategory()"
                    >
                      <option value>select sub category</option>
                      <option
                        v-for="subcategory in subcategories"
                        v-if="subcategories"
                        :value="subcategory.id"
                      >
                        {{ subcategory.name }}
                      </option>
                    </select>
                    <has-error :form="form" field="sub_category"></has-error>
                  </div>
                  <div class="form-group">
                    <label>sub sub category</label>
                    <select
                      class="form-control"
                      name="'sub_sub_category"
                      :class="{
                        'is-invalid': form.errors.has('sub_sub_category'),
                      }"
                      v-model="form.sub_sub_category"
                    >
                      <option value>select sub sub category</option>
                      <option
                        v-for="subSubCategory in subSubCategories"
                        v-if="subSubCategories"
                        :value="subSubCategory.id"
                      >
                        {{ subSubCategory.name }}
                      </option>
                    </select>
                    <has-error
                      :form="form"
                      field="sub_sub_category"
                    ></has-error>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="box box-success height">
                <div class="box-header with-border">
                  <h3 class="box-title">Price & Quantity</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label>
                      sale_price
                      <b class="text-danger">*</b>
                    </label>
                    <input
                      v-model="form.sale_price"
                      type="text"
                      name="sale_price"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('sale_price') }"
                      autocomplete="off"
                      placeholder="sale_price"
                      @keyup="salePrice()"
                    />
                    <has-error :form="form" field="sale_price"></has-error>
                  </div>
                  <div class="form-group">
                    <label>Discount (BDT)</label>
                    <input
                      v-model="form.discount"
                      type="text"
                      name="discount"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('discount') }"
                      autocomplete="off"
                      placeholder="discount"
                      @keyup="discount()"
                    />
                    <has-error :form="form" field="discount"></has-error>
                  </div>
                  <div class="form-group">
                    <label>price</label>
                    <input
                      v-model="form.price"
                      type="text"
                      name="price"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('price') }"
                      autocomplete="off"
                      placeholder="price"
                      readonly
                    />
                    <has-error :form="form" field="price"></has-error>
                  </div>

                   <div class="form-group">
                    <label>quantity</label>
                    <input
                      v-model="form.quantity"
                      type="text"
                      name="quantity"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('quantity') }"
                      autocomplete="off"
                      placeholder="quantity"
                      
                    />
                    <has-error :form="form" field="quantity"></has-error>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="box box-primary height">
                <div class="box-header with-border">
                  <h3 class="box-title">Attribute & variant</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label>
                      attribute
                      <b class="text-danger">*</b>
                    </label>
                    <select
                      class="form-control"
                      name="attribute"
                      v-model="form.attribute"
                      @change="attributeWiseVariants()"
                    >
                      <option value >select attribute</option>
                      <option
                        v-for="attribute in attributes"
                        :value="attribute.id"
                      >
                        {{ attribute.name }}
                      </option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>
                      variant
                      <b class="text-danger">*</b>
                    </label>
                    <select
                      class="form-control"
                      name="variant[]"
                      multiple
                      v-model="form.variant"
                    >
                      <option value disabled>select variant</option>
                      <option
                        v-for="variant in variants"
                        v-if="variants"
                        :value="variant.id"
                      >
                        {{ variant.name }}
                      </option>
                    </select>
                  </div>
                  <br />
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    Image Gallery
                    <small>
                      <b>
                        <i>(Every image can not be bigger than 1200*1200 px)</i>
                      </b>
                    </small>
                  </h3>
                </div>
                <div class="box-body">
                  <div
                    class="uploader"
                    @dragenter="onDragEnter"
                    @dragleave="onDragLeave"
                    @dragover.prevent
                    @drop="drop"
                    :class="{ draging: isDraging }"
                  >
                    <div class="upload-control" v-show="form.files.length">
                      <label for="file" class="label">Select a file</label>
                    </div>
                    <div v-show="!form.files.length">
                      <i class="fa fa-cloud-upload"></i>
                      <p>Drag your file here</p>
                      <div>or</div>
                      <div class="file-input">
                        <label for="file" class="label">select a file</label>
                        <input
                          type="file"
                          name="image"
                          id="file"
                          @change="uploadImage"
                          multiple
                        />
                      </div>
                    </div>
                    <div class="images-preview" v-show="form.files.length">
                      <div
                        class="img-wrapper"
                        v-for="(image, index) in form.files"
                        :key="index"
                      >
                        <img :src="image" :alt="`Image Uplaoder ${index}`" />
                        <div class="cancel" @click="cancel(index)">
                          <span>X</span>
                        </div>
                      </div>
                    </div>

                    <has-error :form="form" field="image"></has-error>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">
                    Product Details
                    <b class="text-danger">*</b>
                  </h3>
                </div>
                <div class="box-body">
                  <ckeditor
                    :editor="editor"
                    name="details"
                    :class="{ 'is-invalid': form.errors.has('details') }"
                    v-model="form.details"
                    :config="editorConfig"
                  ></ckeditor>
                  <has-error :form="form" field="details"></has-error>
                </div>
              </div>
            </div>
          </div>

          <button class="btn btn-primary">Submit</button>
        </form>
      </section>
    </div>
  </div>
</template>


<script>
import Vue from "vue";
import navbar from "../Navbar";
import { Form, HasError, AlertError } from "vform";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
Vue.component(HasError.name, HasError);
export default {
  name: "Add",
  created() {
    this.getAttributes();
    this.getCategories();
    setTimeout(() => {
      this.loading = false;
    }, 500);
  },
  data() {
    return {
      form: new Form({
        name: "",
        merchant: "",
        category: "",
        sub_category: "",
        sub_sub_category: "",
        quantity: "",
        alert_quantity: 1,
        purchase_price: "",
        sale_price: "",
        discount: "",
        price: "",
        details: "",
        attribute: "",
        variant: [],
        image: [],
        files: [],
        quantity:""
      }),
      loading: true,
      error: "",
      categories: "",
      subcategories: "",
      subSubCategories: "",
      merchants: "",
      attributes: "",
      variants: null,
      editor: ClassicEditor,
      editorConfig: {},
      isDraging: false,
      dragCount: 0,
      files: [],
      image: [],
    };
  },

  methods: {
    addProduct() {
      window.scrollTo(0, 0);
      this.$Progress.start();

      this.form .post("/api/merchant/product/store", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
          onUploadProgress: (e) => {
            // Do whatever you want with the progress event
            console.log(e);
          },
        })
        .then((resp) => {
          console.log(resp);
          this.$Progress.finish();

          if (resp.data.status == "SUCCESS") {
            this.$router.push({ name: "merchant_product_menage" });
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
          console.log(error);
          console.log(error);
          this.$Progress.finish();

          this.error = "some thing want to wrong";
        });
    },

    getAttributes() {
      axios
        .get("/api/common/data/fetch", {
          params: {
            type: 1,
            table: "attributes",
          },
        })
        .then((resp) => {
          if (resp.data.success == true) {
            this.attributes = resp.data.data;
          }
        });
    },

    getCategories() {
      axios
        .get("/api/common/data/fetch", {
          params: {
            type: 1,
            table: "categories",
          },
        })
        .then((resp) => {
          if (resp.data.success == true) {
            this.categories = resp.data.data;
          }
        });
    },
    categoryWiseSubCategory() {
      axios
        .get("/api/category/wise/subCategory/" + this.form.category)
        .then((resp) => {
          // console.log(resp)
          if (resp.data.status == "SUCCESS") {
            if (resp.data.subCategories.length > 0) {
              this.subcategories = resp.data.subCategories;
            } else {
              this.subcategories = "";
            }
          } else {
            console.log("error");
            this.subcategories = "";
          }
        })
        .catch((error) => {
          this.error = "some thing want wrong";
        });
    },
    subCategoryWiseSubSUbCategory() {
      axios
        .get("/api/subCategory/wise/subSubCategory/" + this.form.sub_category)
        .then((resp) => {
          // console.log(resp.data.subsubcategories);
          if (resp.data.status == "SUCCESS") {
            if (resp.data.subsubcategories.length > 0) {
              this.subSubCategories = resp.data.subsubcategories;
            } else {
              this.subSubCategories = "";
            }
          } else {
            console.log("error");
            this.subSubCategories = "";
          }
        })
        .catch((error) => {
          this.error = "some thing want wrong";
        });
    },
    attributeWiseVariants() {
      axios
        .get("/api/attribute/wise/variant/" + this.form.attribute)
        .then((resp) => {
          // console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.variants = resp.data.variants;
            // let length = resp.data.variants.length;
            // let i = 0;
            // for (i; i < length; i++) {
            //   this.variants.push(resp.data.variants[i]);
            // }
          } else {
            console.log("error");
          }
        })
        .catch((error) => {
          // console.log(error);
          this.error = "some thing want wrong";
        });
    },
    salePrice() {
      this.form.price = this.form.sale_price;
    },
    discount() {
      let price = this.form.sale_price - this.form.discount;
      if (price < 0) {
        alert("discount can not be greater then sale_price");
        this.form.price = this.form.sale_price;
        this.form.discount = "";
      } else {
        this.form.price = price;
      }
    },
    uploadImage(e) {
      const file = e.target.files;

      Array.from(file).forEach((file) => this.addImage(file));
    },
    onDragEnter(e) {
      e.preventDefault();
      this.dragCount++;
      this.isDraging = true;
    },
    onDragLeave(e) {
      e.preventDefault();
      this.dragCount--;
      if (this.dragCount <= 0) {
        this.isDraging = false;
      }
    },
    drop(e) {
      e.preventDefault();
      e.stopPropagation();
      this.isDraging = false;
      const files = e.dataTransfer;
      Array.from(files).forEach((file) => this.addImage(file));
    },
    addImage(file) {
      //  console.log(file);
      if (!file.type.match("image.*")) {
        alert("this is not any kind of image");
        return;
      }

      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();
        img.onload = () => {
          if (img.width <= 1200 && img.height <= 1200) {
            this.form.image.push(file);
            this.form.files.push(evt.target.result);
            return;
          } else {
            this.disabled = true;
            alert(
              "Image maximu size 1200*1200x.But Upload image size" +
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
    cancel(index) {
      this.form.files.splice(index, 1);
      this.form.image.splice(index, 1);
    },
  },

  components: {
    navbar,
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
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
