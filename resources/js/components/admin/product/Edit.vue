<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a  @click="back" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </a>
          <button
            class="btn btn-primary"
            :class="{ activeBtn: editContent == 1 }"
            @click="editContent = 1"
          >
            Basic Information
          </button>
          <button
            class="btn btn-primary"
            :class="{ activeBtn: editContent == 2 }"
            @click="editContent = 2"
          >
            Product Property
          </button>
          <button
            class="btn btn-primary"
            :class="{ activeBtn: editContent == 3 }"
            @click="editContent = 3"
          >
            Product Image
          </button>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">Edit product</li>
        </ol>
      </section>
      <section class="content">
        <h1 v-if="loading" style="text-align: center; font-size: 50px">
          <i class="fa fa-spinner fa-spin"></i>
        </h1>

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
        <div class="row">
          <div class="col-lg-8 col-lg-offset-2" v-show="editContent == 1">
            <form @submit.prevent="UpdateBasicInformation">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Basic Information</h3>
                </div>
                <div class="box-body">
                  <div class="row">
                    <div class="col-lg-8">
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
                          placeholder="Ex:jean's pants"
                        />
                        <has-error :form="form" field="name"></has-error>
                      </div>
                    </div>
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>
                          price
                          <b class="text-danger">*</b>
                        </label>
                        <input
                          v-model="form.sale_price"
                          type="text"
                          name=" price"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('sale_price'),
                          }"
                          autocomplete="off"
                          placeholder="price"
                          @keyup="salePrice()"
                        />
                        <has-error :form="form" field="sale_price"></has-error>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
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
                    </div>
                    <div class="col-lg-3">
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
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>sale price</label>
                        <input
                          v-model="form.price"
                          type="text"
                          name="price"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('price') }"
                          autocomplete="off"
                          placeholder="sale price"
                          readonly
                        />
                        <has-error :form="form" field="price"></has-error>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label>
                          sub category
                          <b class="text-danger">*</b>
                        </label>
                        <select
                          class="form-control"
                          v-model="form.sub_category"
                          name="sub_category"
                          :class="{
                            'is-invalid': form.errors.has('sub_category'),
                          }"
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
                        <has-error
                          :form="form"
                          field="sub_category"
                        ></has-error>
                      </div>
                    </div>
                    <div class="col-lg-6">
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
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>Product Placement</label>
                        <select
                          class="form-control"
                          name="product_placement"
                          v-model="form.product_placement"
                          @change="flashDealSelection"
                        >
                          <option value="0">Normal product</option>
                          <option value="1">Flash Sale</option>
                        </select>
                      </div>
                      <div
                        v-show="form.product_placement == 1"
                        class="col-lg-10"
                      >
                        <div class="form-group">
                          <label> Expired Date</label>

                          <date-picker
                            autocomplete="off"
                            :class="{
                              'is-invaid': form.errors.has('expired_date'),
                            }"
                            v-model="form.expired_date"
                            :config="options"
                          >
                          </date-picker>
                          <has-error
                            :form="form"
                            field="expired_date"
                          ></has-error>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>Product Position</label>
                        <input
                          type="number"
                          class="form-control"
                          name="product_position"
                          v-model="form.product_position"
                        />
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>Select Home Page</label>
                        <select
                          class="form-control"
                          v-model="form.select_home_page"
                        >
                          <option value="1">yes</option>
                          <option value="0">no</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label for="">Single Checkout</label>
                        <select
                          v-model="form.single_checkout"
                          class="form-control"
                        >
                          <option value="1">Yes</option>
                          <option value="0">No</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <h4 class="box-title">
                        Product Details
                        <b class="text-danger">*</b>
                      </h4>

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
                  <br />
                  <button type="submit" class="btn btn-primary btn-block">
                    Save
                  </button>
                </div>
              </div>
            </form>
          </div>

          <div
            class="
              col-lg-7 col-lg-offset-2
              animate__animated animate__backInLeft
            "
            v-show="editContent == 2"
          >
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Attribute & variant</h3>
              </div>
              <div class="box-body">
                <form @submit.prevent="UpdateProductProperties">
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
                      <option disabled>select attribute</option>
                      <option
                        v-for="attribute in attributes"
                        :class="{ 'is-invalid': form.errors.has('attribute') }"
                        :value="attribute.id"
                      >
                        {{ attribute.name }}
                      </option>
                    </select>
                    <has-error :form="form" field="attribute"></has-error>
                  </div>
                  <div class="form-group">
                    <br />

                    <div
                      class="v_check"
                      v-for="variant in variants"
                      :key="variant.id"
                    >
                      <label class="v_check_label">
                        <input
                          class="v_check_input"
                          type="checkbox"
                          v-model="form.variant"
                          :value="variant.id"
                        />
                        <span class="v_check_name"> {{ variant.name }} </span>
                      </label>
                    </div>

                    <has-error :form="form" field="variant"></has-error>
                  </div>
                  <br />
                  <button type="submit" class="btn btn-primary btn-block">
                    Save
                  </button>
                </form>
              </div>
            </div>
          </div>
          <div
            class="
              col-lg-7 col-lg-offset-2
              animate__animated animate__backInRight
            "
            v-show="editContent == 3"
          >
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Image Gallery</h3>
              </div>
              <form @submit.prevent="UpdateProductImage">
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
                        <div class="cancel">
                          <span @click="cancel(index)">X</span>
                          <span>
                            <i
                              class="fa fa-trash"
                              @click="DeleteProductImage(index)"
                            ></i>
                          </span>
                        </div>
                      </div>
                    </div>

                    <has-error :form="form" field="image"></has-error>
                  </div>
                  <br />

                  <button type="submit" class="btn btn-primary btn-block mt-2">
                    Save
                  </button>
                </div>
              </form>
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
import datePicker from "vue-bootstrap-datetimepicker";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

Vue.component(HasError.name, HasError);
export default {
  name: "Edit",
  created() {
    this.others();
    this.edit();
  },
  data() {
    return {
      form: new Form({
        name: "",
        merchant: "",
        category: "",
        sub_category: "",
        sub_sub_category: "",
        product_placement: "0",
        product_position: "",
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
        select_home_page: 1,
        expired_date: "",
        single_checkout: "1",
      }),
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      loading: true,
      error: "",
      categories: "",
      subcategories: "",
      subSubCategories: "",
      merchants: "",
      attributes: "",
      variants: "",
      editor: ClassicEditor,
      editorConfig: {},
      isDraging: false,
      dragCount: 0,
      basePath: this.$store.state.image_base_link,
      editContent: "1",
    };
  },

  methods: {

    back(){
      return window.history.back();
    },
    //method initial for get specific product from db........
    edit() {
      axios
        .get("/edit/product/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.loading = false;
            this.form.name = resp.data.product.name;
            this.form.category = resp.data.product.category_id;
            this.form.sub_category = resp.data.product.sub_category_id;
            this.form.sale_price = resp.data.product.sale_price;
            this.form.discount = resp.data.product.discount;
            this.form.price = resp.data.product.price;
            this.form.product_placement = resp.data.product.product_placement;
            this.form.expired_date = resp.data.product.expired_date
              ? resp.data.product.expired_date
              : "";
            console.log(typeof this.form.expired_date);
            this.form.product_position = resp.data.product.product_position;
            this.form.select_home_page = resp.data.product.select_home_page;
            this.form.details = resp.data.product.details;
            this.form.single_checkout = resp.data.product.single_checkout;

            if (resp.data.product.sub_category_id !== null) {
              this.form.sub_category = resp.data.product.sub_category_id;
              this.categoryWiseSubCategory();
            }
            if (resp.data.product.sub_sub_category_id !== null) {
              this.subCategoryWiseSubSUbCategory();
              this.form.sub_sub_category =
                resp.data.product.sub_sub_category_id;
            }
            let image = resp.data.productImage;
            for (let i = 0; i < image.length; i++) {
              this.form.files.push(this.basePath + image[i].product_image);
              this.form.image.push(this.basePath + image[i].product_image);
            }

          if (resp.data.productAttribute.length > 0) {
              //  let attribute = resp.data.productAttribute;
              this.form.attribute = resp.data.productAttribute[0].attribute_id;
              this.attributeWiseVariants();
              for(let i=0;i<=resp.data.productVariant.length;i++){
                this.form.variant.push(resp.data.productVariant[i].variant_id);
              }
            }
          } else {
            this.error = "something went to wrong";
          }
        })
        .catch((error) => {
          console.log(error);
          // this.error = "something went to wrong";
        });
    },
    DeleteProductImage(index) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't permanent delete this image??",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/delete/product/image/" + this.$route.params.id, {
              params: {
                index: index,
              },
            })
            .then((resp) => {
              if (resp.data.status == "SUCCESS") {
                this.$toasted.show(resp.data.message, {
                  type: "success",
                  position: "top-bottom",
                  duration: 4000,
                });
                this.form.files.splice(index, 1);
                this.form.image.splice(index, 1);
              }
            })
            .catch((error) => {
              console.log(error);
              this.error = "something went to wrong";
            });
        } else {
          this.$toasted.show("OK ! no action here", {
            position: "top-center",
            type: "info",
            duration: 3000,
          });
        }
      });
    },
    UpdateBasicInformation() {
      window.scrollTo(0, 0);
      this.$Progress.start();
      this.form
        .post("/update/product/basicInformation/" + this.$route.params.id, {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
          onUploadProgress: (e) => {
            // Do whatever you went with the progress event
            console.log(e);
          },
        })
        .then((resp) => {
          console.log(resp);
          this.$Progress.finish();

          if (resp.data.status == "SUCCESS") {
            window.history.back();
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          } else {
            this.error = "something went to wrong";
          }
        })
        .catch((error) => {
          console.log(error);
          console.log(error);
          this.$Progress.finish();

          this.error = "something went to wrong";
        });
    },
    UpdateProductProperties() {
      window.scrollTo(0, 0);
      this.$Progress.start();
      this.form
        .post("/update/product/properties/" + this.$route.params.id, {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
          onUploadProgress: (e) => {
            // Do whatever you went with the progress event
            console.log(e);
          },
        })
        .then((resp) => {
          console.log(resp);
          this.$Progress.finish();

          if (resp.data.status == "SUCCESS") {
            this.$router.push({ name: "product" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          } else {
            this.error = "something went to wrong";
          }
        })
        .catch((error) => {
          console.log(error);
          console.log(error);
          this.$Progress.finish();

          this.error = "something went to wrong";
        });
    },
    UpdateProductImage() {
      window.scrollTo(0, 0);
      this.$Progress.start();
      this.form
        .post("/update/product/image/" + this.$route.params.id, {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
          onUploadProgress: (e) => {
            // Do whatever you went with the progress event
            console.log(e);
          },
        })
        .then((resp) => {
          console.log(resp);
          this.$Progress.finish();

          if (resp.data.status == "SUCCESS") {
            this.$router.push({ name: "product" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          } else {
            this.error = "something went to wrong";
          }
        })
        .catch((error) => {
          this.$Progress.finish();
          this.error = "something went to wrong";
        });
    },

    others() {
      axios
        .get("/others")
        .then((resp) => {
          //console.log(resp)
          if (resp.data.status == "SUCCESS") {
            this.categories = resp.data.categories;
            this.merchants = resp.data.merchants;
            this.attributes = resp.data.attributes;
          } else {
            this.error = "something went to wrong";
          }
        })
        .catch((error) => {
          //  console.log(error)
          this.error = "something went to wrong";
        });
    },
    categoryWiseSubCategory() {
      axios
        .get("/category/wise/subCategory/" + this.form.category)
        .then((resp) => {
          // console.log(resp)
          //  console.log(resp.data.admins.data)
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
          this.error = "something went wrong";
        });
    },
    subCategoryWiseSubSUbCategory() {
      axios
        .get("/subCategory/wise/subSubCategory/" + this.form.sub_category)
        .then((resp) => {
          // console.log(resp.data.subsubcategories)
          //  console.log(resp.data.admins.data)
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
          this.error = "something went wrong";
        });
    },
    attributeWiseVariants() {
      axios
        .get("/attribute/wise/variant/" + this.form.attribute)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.variants = resp.data.variants;
          } else {
            console.log("error");
          }
        })
        .catch((error) => {
          console.log(error);
          this.error = "something went wrong";
        });
    },
    salePrice() {
      this.form.price =
        parseFloat(this.form.sale_price) - parseFloat(this.form.discount);
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
      const files = e.dataTransfer.files;
      console.log(files);
      Array.from(files).forEach((file) => this.addImage(file));
    },
    addImage(file) {
      console.log(file);
      if (!file.type.match("image.*")) {
        alert("this is not any kind of image");
        return;
      }

      if (file.size / 1024 > 5120) {
        Swal.fire({
          type: "warning",
          text:
            "File size can not be bigger then 5120kb.Reference file size is " +
            file.size / 1024 +
            "KB",
        });
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
              "Image maximum size 1200*1200px.But Upload image size" +
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
      //console.log(evt.target.result);
    },
    cancel(index) {
      this.form.files.splice(index, 1);
      this.form.image.splice(index, 1);
    },
    flashDealSelection() {
      if (this.form.product_placement == 0) {
        this.form.expired_date = "";
      }
    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}

.height {
  height: 420px !important;
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

.activeBtn {
  background: #d58512 !important;
  color: #000 !important;
  border: none;
}

.v_check {
  min-width: 15%;
  float: left;
  margin: 5px 12px;
}

input.v_check_input {
  transform: scale(1.5);
}

.v_check_name {
  font-size: 16px;
  margin: 7px;
}
</style>


