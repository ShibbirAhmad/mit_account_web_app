<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'order' }" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">Order</li>
        </ol>
      </section>
      <section class="content">
        <form @submit.prevent="searchProduct" id="ProductSearch"></form>
        <h1 v-if="loading">
          <i class="fa fa-spin fa-spinner"></i>
        </h1>

        <form
          v-else
          @submit.prevent="updateOrder"
          @keydown="form.onKeydown($event)"
        >
          <div class="row justify-content-center">
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
            <div class="col-lg-4">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title">Customer information</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label>Customer Mobile </label>
                    <input
                      type="text"
                      name="customer_mobile"
                      class="form-control"
                      maxlength="11"
                      placeholder="Enter 11 digit mobile number"
                      v-model="form.customer_mobile"
                      @keyup="searchCustomer"
                      :class="{
                        'is-invalid': form.errors.has('customer_mobile'),
                      }"
                    />
                    <has-error :form="form" field="customer_mobile"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Name</label>
                    <input
                      type="text"
                      name="name"
                      class="form-control"
                      autofocus
                      placeholder="Name"
                      v-model="form.customer_name"
                      :class="{
                        'is-invalid': form.errors.has('customer_name'),
                      }"
                    />
                    <has-error :form="form" field="customer_name"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Address</label>
                    <input
                      type="text"
                      name="address"
                      class="form-control"
                      autofocus
                      placeholder="address"
                      v-model="form.customer_address"
                      :class="{
                        'is-invalid': form.errors.has('customer_address'),
                      }"
                    />
                    <has-error
                      :form="form"
                      field="customer_address"
                    ></has-error>
                  </div>

                  <div class="form-group">
                    <label>City</label>
                    <select
                      name="city"
                      id
                      class="form-control"
                      v-model="form.city"
                      @change="selectCity"
                      :class="{ 'is-invalid': form.errors.has('city') }"
                    >
                      <option value>select city</option>
                      <option v-for="city in cities" :value="city.id">
                        {{ city.name }}
                      </option>
                    </select>
                    <has-error :form="form" field="city"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Sub City</label>
                    <select
                      class="form-control"
                      name="sub_city"
                      :class="{ 'is-invalid': form.errors.has('sub_city') }"
                      v-model="form.sub_city"
                    >
                      <option disabled value="">select sub city</option>
                      <option
                        v-if="sub_cities.length > 0"
                        v-for="sub_city in sub_cities"
                        :key="sub_city.id"
                        :value="sub_city.id"
                      >
                        {{ sub_city.name }}
                      </option>
                    </select>
                    <has-error :form="form" field="sub_city"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Shipping Cost</label>
                    <input
                      type="text"
                      name="shipping_cost"
                      class="form-control"
                      v-model="form.shipping_cost"
                      :class="{
                        'is-invalid': form.errors.has('shipping_cost'),
                      }"
                    />
                    <has-error :form="form" field="shipping_cost"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Courier</label>
                    <select
                      name="courier"
                      v-model="form.courier"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('courier') }"
                    >
                      <option value>select courier</option>
                      <option v-for="courier in couriers" :value="courier.id">
                        {{ courier.name }}
                      </option>
                    </select>
                    <has-error :form="form" field="courier"></has-error>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-8">
              <div class="box box-success">
                <div class="box-header with-border">
                  <h3 class="box-title">Product information</h3>
                </div>
                <div class="box-body">
                  <div class="form-group">
                    <label>Product</label>
                    <input
                      type="text"
                      name="name"
                      class="form-control"
                      autofocus
                      placeholder="Enter Product Code"
                      v-model="product_code"
                      form="ProductSearch"
                    />
                  </div>

                  <div class="row">
                    <div class="col-lg-12">
                      <table
                        class="table table-hover table-bordered table-striped"
                      >
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Product</th>
                            <!-- <th>Attribute</th> -->
                            <th>Variant</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>total</th>
                            <th>Remove</th>
                          </tr>
                        </thead>
                        <tbody v-if="products.length > 0">
                          <tr v-for="(product, index) in products" :key="index">
                            <td>{{ index + 1 }}</td>
                            <td>
                              {{ product.name + "-" + product.product_code }}
                              <input type="hidden" :value="product.id" />
                            </td>
                            <!-- <td>
                              <select
                                class="form-control"
                                v-model="form.products[index].attribute_id"
                              >
                                <option value>select attribute</option>
                                <option
                                  v-if="product.attributes"
                                  v-for="(
                                    product_attribute, index
                                  ) in product.attributes"
                                  :key="index"
                                  :value="product_attribute.attribute.id"
                                >
                                  {{ product_attribute.attribute.name }}
                                </option>
                              </select>
                            </td> -->
                            <td>
                              <select
                                class="form-control"
                                v-model="form.products[index].variant_id"
                              >
                                <option value>select attribute</option>
                                <option
                                  v-if="product.variants"
                                  v-for="(
                                    product_variant, index
                                  ) in product.variants"
                                  :key="index"
                                  :value="product_variant.variant.id"
                                >
                                  {{
                                    product_variant.variant
                                      ? product_variant.variant.name
                                      : ""
                                  }}
                                </option>
                              </select>
                            </td>
                            <td>
                              <input
                                type="number"
                                v-model="form.products[index].quantity"
                                @keyup="quantity(index)"
                                @change="quantity(index)"
                                style="width: 50px"
                              />
                              <span class="badge badge-danger">{{
                                product.stock
                              }}</span>
                            </td>
                            <td>
                              <input
                                v-model="form.products[index].price"
                                @keyup="totalCalculation && quantity(index)"
                              />
                            </td>
                            <td>{{ form.products[index].total }}</td>
                            <td>
                              <a
                                class="btn btn-danger btn-sm"
                                @click="remove(index)"
                                ><i class="fa fa-trash"></i
                              ></a>
                            </td>
                          </tr>
                          <tr v-if="products.length > 0">
                            <td colspan="4"></td>
                            <td>Total Amount</td>
                            <td colspan="2">{{ form.total }}</td>
                          </tr>
                          <tr>
                            <td colspan="4">
                              <select
                                @change="calculateDiscount"
                                name="discount_type"
                                class="form-control"
                                v-model="form.discount_type"
                              >
                                <option value="" disabled>
                                  select discount type
                                </option>
                                <option value="percentage">percentage</option>
                                <option value="flat">flat</option>
                              </select>
                            </td>
                            <td>Discount</td>
                            <td colspan="2">
                              <input
                                class="form-control"
                                @keyup="totalCalculation"
                                v-model="form.discount"
                                placeholder="0"
                              />
                            </td>
                          </tr>
                          <tr>
                            <td colspan="4">
                              <input
                                v-if="
                                  form.discount_type == 'percentage' ||
                                  form.discount_type == 'flat'
                                "
                                type="number"
                                class="form-control"
                                v-model="discount_value"
                                @keyup="calculateDiscount"
                              />
                            </td>
                            <td>Paid</td>
                            <td colspan="2">
                              <input
                                @keyup="calculateDiscount"
                                v-model="form.paid"
                                class="form-control"
                              />
                            </td>
                          </tr>
                          <tr>
                            <td colspan="4"></td>

                            <td>Credit In (Balance)</td>
                            <td colspan="2">
                              <div class="form-group">
                                <select
                                  class="form-control"
                                  v-model="form.paid_by"
                                >
                                  <option value="" disabled>
                                    Select Balance
                                  </option>
                                  <option
                                    v-for="(balance, index) in balance"
                                    :key="index"
                                    :value="balance.id"
                                  >
                                    {{ balance.name }}
                                  </option>
                                </select>
                                <has-error
                                  :form="form"
                                  field="paid_by"
                                ></has-error>
                              </div>
                            </td>
                          </tr>
                          <tr v-if="products.length > 0">
                            <td colspan="4"></td>
                            <td>Shipping Charge</td>
                            <td colspan="2">{{ form.shipping_cost }}</td>
                          </tr>
                          <tr v-if="products.length > 0">
                            <td colspan="4"></td>
                            <td>Amount due</td>
                            <td colspan="2">{{ form.due }}</td>
                          </tr>
                          <button
                            class="btn btn-success"
                            style="margin-top: 12px"
                            type="submit"
                          >
                            Save
                          </button>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </section>
    </div>
  </div>
</template>


<script>
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
import datePicker from "vue-bootstrap-datetimepicker";

Vue.component(HasError.name, HasError);

export default {
  created() {
    this.others();
    this.edit();
    this.balanceList();
  },
  data() {
    return {
      form: new Form({
        customer_mobile: "",
        customer_name: "",
        customer_address: "",
        city: "",
        courier: "",
        products: [],
        shipping_cost: 0,
        courier: "",
        total: 0,
        discount: 0,
        discount_type: "percentage",
        paid: 0,
        paid_by: "",
        due: 0,
        order_type: "",
        sub_city: "",
      }),
      discount_value: 0,
      balance: "",
      attribute_id: "",
      variant_id: "",
      product_code: "",
      city_id: "",
      courier_id: "",
      shipping_cost: "",
      products: [],
      product_attributes: null,
      product_variants: null,
      cities: null,
      couriers: null,
      product_quantity: 1,
      error: "",
      loading: true,
      sub_cities: "",
    };
  },

  methods: {
    balanceList() {
      axios.get("/api/balance/list").then((resp) => {
        this.balance = resp.data.balance;
      });
    },
    //method initial for get data
    edit() {
      axios.get("/order/view/" + this.$route.params.id).then((resp) => {
        console.log(resp);
        if (resp.data.status == "SUCCESS") {
          let order = resp.data.order;
          this.form.customer_mobile = order.cutomer_phone;
          this.form.customer_name = order.customer.name;
          this.form.customer_address = order.customer.address;
          this.form.city = order.city_id;
          this.form.sub_city = order.sub_city_id;
          this.form.shipping_cost = order.shipping_cost;
          this.form.courier = order.courier_id;
          this.form.paid = order.paid;
          this.form.discount = order.discount;
          this.form.total = order.total;
          this.form.paid_by = order.paid_by;
          this.form.order_type = order.order_type;
          this.form.due =
            parseInt(order.total) -
            (parseInt(order.paid) + parseInt(order.discount)) +
            parseInt(order.shipping_cost);

          //problem is there product attribute
          let products = [];
          for (let i = 0; i < resp.data.items.length; i++) {
            products[i] = {
              product_id: resp.data.items[i].product.id,
              name: resp.data.items[i].product.name,
              product_code: resp.data.items[i].product_code,
              quantity: resp.data.items[i].quantity,
              price: resp.data.items[i].price,
              variants: resp.data.items[i].product.product_variant,
            };
            this.products.push(products[i]);
            this.form.products.push(products[i]);
            this.form.products[i].total = resp.data.items[i].total;
            this.form.products[i].variant_id = resp.data.items[i].variant_id;
            this.form.products[i].attribute_id =
              resp.data.items[i].attribute_id;
          }
          this.cityWiseSubCity();
          this.form.sub_city = order.sub_city_id;
          //calculate discount value
          let discount_value = (order.discount / order.total) * 100;
          this.discount_value = Math.ceil(discount_value);
          this.loading = false;
        }
      });
    },

    //method initial for submit data
    updateOrder() {
      if (this.form.paid > 0 && this.form.paid_by == null) {
        alert("please,select credit balance");
        return;
      }
      this.form
        .post("/update/order/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            this.$router.push({ name: "order" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 2000,
            });
            //other wise success, show a error
          } else {
            this.$toasted.show(resp.data, {
              type: "error",
              position: "top-center",
              duration: 4000,
            });
          }
        })
        .catch((error) => {
          this.error =
            "Missing submit information. please check all field below and try again";
        });
    },

    //method initial for get other data to create order, like cit, courier and other.......
    others() {
      axios
        .get("/others")
        //success resp only
        .then((resp) => {
          if (resp.data.status == "SUCCESS") {
            this.cities = resp.data.cities;
            this.couriers = resp.data.couriers;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },

    //method initial for search product

    searchProduct() {
      let length = this.product_code.length;
      //  alert(length)
      if (length == 4) {
        axios
          .get("/search/product/with/code/" + this.product_code)

          .then((resp) => {
            //when resp success
            if (resp.data.status == "SUCCESS") {
              let product = {
                product_id: "",
                price: "",
                quantity: 1,
                attribute_id: "",
                variant_id: "",
                total: "",
              };
              for (let i = 0; i < resp.data.product.length; i++) {
                //check the product stock
                this.products.push(resp.data.product[i]);
                product.product_id = resp.data.product[i].id;
                product.price = resp.data.product[i].sale_price;
                product.total = resp.data.product[i].sale_price;
              }
              this.form.products.push(product);
              this.totalCalculation();
            }
            //when not resp success
            else {
              this.$toasted.show(
                "Product not found with " + this.product_code,
                {
                  position: "top-center",
                  type: "danger",
                  duration: 2000,
                }
              );
            }
          });
      }
    },

    //method initial search customer
    searchCustomer() {
      if (this.form.customer_mobile.length == 11) {
        axios
          .get(
            "/search/customer/with/phone/number/" + this.form.customer_mobile
          )
          .then((resp) => {
            //when com data from t resp
            if (resp.data) {
              if (resp.data.customer) {
                (this.form.customer_name = resp.data.customer.name),
                  (this.form.customer_address = resp.data.customer.address);
                this.form.city = resp.data.customer.city_id;
              }
              this.$toasted.show(resp.data.message, {
                type: "error",
                position: "top-center",
                duration: 4000,
              });
            }
          });
      }
    },

    //set product attribute
    attribute(index) {
      this.form.products[index].attribute_id = this.attribute_id;
    },
    //set product variant
    variant(index) {
      this.form.products[index].variant_id = this.variant_id;
    },
    //when change
    quantity(index) {
      this.form.products[index].total =
        this.form.products[index].price * this.form.products[index].quantity;
      this.totalCalculation();
    },

    calculateDiscount() {
      if (this.discount_value > 0) {
        if (this.form.discount_type == "flat") {
          this.form.discount = parseInt(this.discount_value);
        } else if (this.form.discount_type == "percentage") {
          this.form.discount = (
            (parseInt(this.form.total) * parseInt(this.discount_value)) /
            100
          ).toFixed(0);
        }
        this.totalCalculation();
      }
    },

    //total amount calculation
    totalCalculation() {
      let products = this.form.products;
      let total = 0;
      for (let i = 0; i < products.length; i++) {
        total += parseInt(products[i].price) * parseInt(products[i].quantity);
      }

      this.form.total = parseInt(total);
      if (this.discount_value > 0) {
        if (this.form.discount_type == "flat") {
          this.form.discount = parseInt(this.discount_value);
        } else if (this.form.discount_type == "percentage") {
          this.form.discount = (
            (parseInt(this.form.total) * parseInt(this.discount_value)) /
            100
          ).toFixed(0);
        }
      }
      let discount =
        this.form.discount.length <= 0 && this.form.discount <= 0
          ? 0
          : parseInt(this.form.discount);
      let paid =
        this.form.paid.length <= 0 && this.form.paid <= 0
          ? 0
          : parseInt(this.form.paid);
      let shipping_cost =
        this.form.shipping_cost.length <= 0 && this.form.shipping_cost <= 0
          ? 0
          : parseInt(this.form.shipping_cost);
      this.form.due = this.form.total + shipping_cost - (discount + paid);
    },

    //select city
    selectCity() {
      if (this.cities.length > 0) {
        this.cities.forEach((city) => {
          if (city.id == this.form.city) {
            this.form.shipping_cost = parseInt(city.delivery_charge);
          }
        });
      }
      this.sub_cities = "";
      this.cityWiseSubCity();
      this.totalCalculation();
    },

    remove(index) {
      // console.log(index);
      console.log(this.form.products[index]);
      this.form.products.splice(index, 1);
      this.products.splice(index, 1);
      this.totalCalculation();
    },

    cityWiseSubCity() {
      axios.get("/city/wise/sub/city/" + this.form.city).then((resp) => {
        console.log(resp);
        this.sub_cities = resp.data;
      });
    },
  },
  components: {
    datePicker,
  },
};
//Date picker
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}

.autocomplete {
  max-height: 120px;
  overflow-y: scroll;
  position: absolute;
  width: 100%;
  z-index: 454;
}

.autocomplete li:hover {
  background: #222d32;
  color: #ffffff;
}
</style>
