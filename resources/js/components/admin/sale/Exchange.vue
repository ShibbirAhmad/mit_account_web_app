<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'officeSale' }" class="btn btn-primary">
            <i class="fa fa-arrow-right"></i>
          </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">Sale</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-12">
            <div class="box box-primary">
              <div class="box-header with-border text-center ">
                <h3 class="box-title">Add Sale(Exchange)</h3>
              </div>
              <div class="box-body">
                <div class="alert-danger alert" v-if="error">{{ error }}</div>
                <div class="row">
                  <div v-if="form.type == 1">
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>Mobile Number </label>
                        <small style="float: right"
                          >{{ form.mobile_no.length }}/11</small
                        >

                        <input
                          class="form-control"
                          v-model="form.mobile_no"
                          placeholder="01xxx-xxxxxx"
                          @keyup="searchCustomer"
                          autocomplete="off"
                          type="text"
                          maxlength="11"
                          autofocus
                        />
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>Name</label>
                        <input
                          class="form-control"
                          v-model="form.name"
                          placeholder="Name"
                          @keyup="finalValidation"
                        />
                      </div>
                    </div>

                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>Address</label>
                        <input
                          class="form-control"
                          v-model="form.address"
                          placeholder="address"
                          @keyup="finalValidation"
                        />
                      </div>
                    </div>
                  </div>
                  <div v-else>
                    <div class="col-lg-5">
                      <label>Company</label>
                      <select
                        class="form-control"
                        v-model="form.company_id"
                        @change="finalValidation"
                      >
                        <option value="" selected disabled>
                          Select Company
                        </option>
                        <option
                          v-for="company in companies"
                          :value="company.id"
                          :key="company.id"
                        >
                          {{ company.name }}
                        </option>
                      </select>
                    </div>
                    <div class="col-lg-5">
                      <label>Invoice No.</label>
                      <input
                        type="text"
                        class="form-control"
                        @keydown="finalValidation"
                        v-model="form.invoice_no"
                      />
                    </div>
                  </div>
                </div>
                <div class="product_information">
                  <div class="row">
                    <div class="col-lg-3">
                      <div class="form-group">
                        <label>Product Code || Barcode </label>
                        <input
                          class="form-control"
                          autocomplete="off"
                          name="product"
                          v-model="search"
                          placeholder="type product code"
                          @keyup="autocompleteSearh"
                        />

                        <!-- <div class="autocomplete" v-show="automcomplete">
                          <ul class="list-group">
                            <li
                              class="list-group-item"
                              v-for="productItem in productItems"
                              @click="selectedProduct(productItem)"
                              :key="productItem.id"
                            >
                              {{ productItem.product_code +  "-" + productItem.name
                              }}
                            </li>
                          </ul>
                        </div> -->
                      </div>
                    </div>
                    <div class="col-lg-2">
                      <div class="form-group">
                        <label>Price</label>
                        <input
                          v-model="preview_products.price"
                          type="text"
                          ref="price"
                          name="price"
                          class="form-control"
                          autocomplete="off"
                          placeholder="price"
                          @keyup="total"
                        />
                      </div>
                    </div>
                    <div class="col-lg-2">
                      <div class="form-group">
                        <label>Quantity</label>
                        <input
                          v-model="preview_products.quantity"
                          type="text"
                          name="quantity"
                          class="form-control"
                          autocomplete="off"
                          :placeholder="
                            'max quantity:' + preview_products.stock + ' pcs'
                          "
                          @keyup="total"
                          id="product_quanitty"
                        />
                      </div>
                    </div>

                    <div class="col-lg-2">
                      <div class="form-group">
                        <label>Total</label>
                        <input
                          v-model="preview_products.total"
                          type="text"
                          name="total"
                          class="form-control"
                          autocomplete="off"
                          placeholder="total"
                          readonly
                        />
                      </div>
                    </div>
                    <div class="col-lg-2">
                      <div class="form-group">
                        <label for="">Add Type</label>
                        <select
                          class="form-control"
                          v-model="add_to_exchnage"
                          @change="validation"
                        >
                          <option value="1">To Sale Product</option>
                          <option value="2">To Exchange Product</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-1">
                      <button
                        class="btn btn-success btn-sm"
                        style="margin-top: 25px"
                        @click="productAdd"
                        :disabled="validationPreview"
                      >
                        Add
                      </button>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <div
                      class="product_preview"
                      v-if="form.products.length > 0"
                    >
                      <h4 class="text-uppercase text-center">Sale Product</h4>
                      <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>price</th>
                            <th>quantity</th>
                            <th>total</th>
                            <th>X</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(product, index) in form.products">
                            <td>{{ index + 1 }}</td>
                            <td>
                              {{
                                product.product_code +
                                "-" +
                                product.product_name
                              }}
                            </td>
                            <td>{{ product.price }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.total }}</td>
                            <td class="text-danger" @click="cancel(index)">
                              <i class="fa fa-trash"></i>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3"></td>
                            <td>Total Amount</td>
                            <td>{{ this.form.sale_total }}</td>
                          </tr>
                          <!-- <tr>
                        <td colspan="3"></td>
                        <td>Paid</td>
                        <td>
                          <input class="form-control"  placeholder="Paid" @keyup="amountDue" v-model="form.paid" />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>Discount</td>
                        <td>
                          <input class="form-control"  placeholder="Paid" @keyup="amountDue" v-model="form.discount" />
                        </td>
                      </tr>

                      <tr>
                        <td colspan="3"></td>
                        <td>Paid_by</td>
                        <td style="display:flex;">
                          <select class="form-control" v-model="form.paid_by">
                          <option  v-for="(paid_by_option,index) in paid_by_options" :value="paid_by_option" :key="index">{{paid_by_option}}</option>

                          </select>
                          <button  v-if="form.paid> 0 &&  form.partials_payment_amount <=0 " title="Partials paymnet" class="btn btn-sm btn-info" @click="partialsPayment">PP</button>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>Amount Due</td>
                        <td>{{ form.due }}</td>
                      </tr> -->
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-lg-6">
                    <div
                      class="product_preview"
                      v-if="form.exchnage_products.length > 0"
                    >
                      <h4 class="text-uppercase text-center">
                        Exchange Product
                      </h4>
                      <table class="table">
                        <thead>
                          <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>price</th>
                            <th>quantity</th>
                            <th>total</th>
                            <th>X</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="(product, index) in form.exchnage_products"
                            :key="index"
                          >
                            <td>{{ index + 1 }}</td>
                            <td>
                              {{
                                product.product_code +
                                "-" +
                                product.product_name
                              }}
                            </td>
                            <td>{{ product.price }}</td>
                            <td>{{ product.quantity }}</td>
                            <td>{{ product.total }}</td>
                            <td class="text-danger" @click="cancel(index)">
                              <i class="fa fa-trash"></i>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="3"></td>
                            <td>Total Amount</td>
                            <td>{{ this.form.exchange_total }}</td>
                          </tr>
                          <!-- <tr>
                        <td colspan="3"></td>
                        <td>Paid</td>
                        <td>
                          <input class="form-control"  placeholder="Paid" @keyup="amountDue" v-model="form.paid" />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>Discount</td>
                        <td>
                          <input class="form-control"  placeholder="Paid" @keyup="amountDue" v-model="form.discount" />
                        </td>
                      </tr>

                      <tr>
                        <td colspan="3"></td>
                        <td>Paid_by</td>
                        <td style="display:flex;">
                          <select class="form-control" v-model="form.paid_by">
                          <option  v-for="(paid_by_option,index) in paid_by_options" :value="paid_by_option" :key="index">{{paid_by_option}}</option>

                          </select>
                          <button  v-if="form.paid> 0 &&  form.partials_payment_amount <=0 " title="Partials paymnet" class="btn btn-sm btn-info" @click="partialsPayment">PP</button>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>Amount Due</td>
                        <td>{{ form.due }}</td>
                      </tr> -->
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div
                  class="row"
                  v-if="
                    form.products.length > 0 ||
                    form.exchnage_products.length > 0
                  "
                >
                  <div class="col-lg-12">
                    <table>
                      <tr>
                        <td colspan="3">Sale Amount</td>
                        <td colspan="3"></td>
                        <td>
                          <input
                            class="form-control"
                            readonly
                            placeholder="Paid"
                            v-model="form.sale_total"
                          />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">Exchange Amount</td>
                        <td colspan="3"></td>
                        <td>
                          <input
                            class="form - control"
                            readonly
                            placeholder="Paid"
                            v-model="form.exchange_total"
                          />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">Total</td>
                        <td colspan="3">Colspan</td>
                        <td>
                          <input
                            type="text"
                            class="form-control"
                            readonly
                            :value="form.AmountTotal"
                          />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">Discount</td>
                        <td colspan="3"></td>
                        <td>
                          <input
                            class="form-control"
                            placeholder="paid"
                            @keyup="amountDue"
                            v-model="form.discount"
                          />
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">Paid</td>
                        <td colspan="3"></td>
                        <td>
                          <input
                            class="form-control"
                            placeholder="Paid"
                            @keyup="amountDue"
                            v-model="form.paid"
                          />
                        </td>
                      </tr>

                      <tr>
                        <td colspan="3">Credit In </td>
                        <td colspan="3"></td>
                        <td style="display: flex">
                          <select class="form-control" v-model="form.paid_by">
                            <option value="" selected disabled>Select Balance</option>
                            <option
                              v-for="(balance, index) in balance"
                              :value="balance.id"
                              :key="index"
                            >
                              {{ balance.name }}
                            </option>
                          </select>
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3">Amount Due</td>
                        <td colspan="3"></td>
                        <td>
                          <input
                            type="text"
                            class="form-control"
                            v-model="form.due"
                            readonly
                          />
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>

                <br />
                <button
                  :disabled="submitValidation"
                  type="submit"
                  @click="add()"
                  class="btn btn-primary"
                >
                  Submit
                </button>

                <!--                                </form>-->
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
import datePicker from "vue-bootstrap-datetimepicker";

Vue.component(HasError.name, HasError);

export default {
  created(){
   this.balanceList();
 },
  data() {
    return {
      //form submit data
      form: new Form({
        type: 1,
        name: "",
        mobile_no: "",
        address: "",
        //multiple product data
        products: [],
        exchnage_products: [],
        // culation data
        AmountTotal: 0,
        paid: 0,
        due: 0,
        discount: 0,
        company_id: "",
        paid_by: "",
        invoice_no: "",
        partials_paid_by: "",
        partials_payment_amount: 0,
        sale_total: 0,
        exchange_total: 0,
      }),
      balance: '',
      companies: "",
      error: "",
      //store product item from to get db when user type product code or product name
      productItems: [],
      //auto complete
      automcomplete: false,
      search: "",
      //for check product reltaed property validation
      validationPreview: true,
      submitValidation: true,
      //first store product as obejct, when user select a sepecific product from auto complete serach box
      //and this object pust form.product arraw
      preview_products: {
        product_name: "",
        product_id: "",
        product_code: "",
        price: "",
        quantity: "",
        total: "",
        alert_quantity: "",
        stock: "",
      },

      add_to_exchnage: 1,
    };
  },

  methods: {
    balanceList() {
      axios
        .get("/api/balance/list")
        .then((resp) => {
            this.balance = resp.data.balance;

        })
    },
    //method initial for add sale
    add() {
      if (this.form.paid > 0 && this.form.paid_by=='') {
         alert('please, select balance') ;
         return ;
      }
      this.$Progress.start();
      this.form
        .post("/sale/exchange/store")
        .then((resp) => {
          console.log(resp);
          this.$Progress.finish();
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            console.log(resp);
            if (this.form.type == 1) {
              this.$router.push({ name: "officeSale" });
            } else {
              this.$router.push({ name: "compnaySale" });
            }
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          } else {
            this.error = "do not saved data. please try again";
          }
        })
        .catch((error) => {
          this.$Progress.finish();
          console.log(error);
          this.error = "some thing want to wrong";
        });
    },

    //method initial for get product data when admin type product code or name
    autocompleteSearh() {
      let length = this.search.length;
      this.validation();

      if (length >= 4) {
        axios
          .get("/search/single/product/" + this.search)

          .then((resp) => {
            console.log(resp);

            if (resp.data.product) {
              let product = resp.data.product;

              if (product.stock <= 0 && this.add_to_exchnage == 1) {
                this.$toasted.show(
                  `${product.name}-${product.product_code}-Stock Out`,
                  {
                    type: "error",
                    position: "top-center",
                    duration: 4000,
                  }
                );
                return;
              }
              this.preview_products.product_name = product.name;
              this.preview_products.product_id = product.id;
              this.preview_products.product_code = product.product_code;
              this.preview_products.stock = product.stock;
              this.preview_products.price = product.price;
              this.preview_products.quantity = 1;
              this.preview_products.total =
                parseInt(product.price) * parseInt(1);
              document.getElementById("product_quanitty").focus();
              this.search = product.product_code + "-" + product.name;
              this.validationPreview = false;
            } else {
              this.$toasted.show(`${this.search}-Not Found`, {
                type: "error",
                position: "top-center",
                duration: 4000,
              });
            }
            // if (resp.data.status == "SUCCESS") {
            //   let x = resp.data.products.data.length;
            //   let i = 0;

            //   if (x >= 1) {
            //     this.productItems = [];
            //     for (i; i < x; i++) {
            //       this.productItems.push(resp.data.products.data[i]);
            //     }
            //   } else {
            //     let no_found = {
            //       name: "No product found",
            //       product_code: "404",
            //     };
            //     this.productItems = [];
            //     this.productItems.push(no_found);
            //   }
            // }
          })
          .catch((error) => {
            console.log(error);
          });
        this.automcomplete = true;
      } else {
        this.automcomplete = false;
      }
    },

    //method initial for set product item when admin select specific product item
    // selectedProduct(productItem) {

    //   if (productItem.name == "No product found") {
    //     alert("please enter product valid code or name");
    //     this.validation();
    //     return;
    //   }else if(productItem.stock<=0){
    //     alert('This Product All ready stock out')
    //   }
    //   else {
    //     this.automcomplete = false;
    //     this.preview_products.product_name = productItem.name;
    //     this.preview_products.product_id = productItem.id;
    //     this.preview_products.product_code = productItem.product_code;
    //     this.preview_products.stock=productItem.stock;
    //     this.search = productItem.product_code + "-" + productItem.name;
    //     this.validation();
    //   }
    // },

    //method initial for calulate sub total  amount, prodict price*quantity
    total() {
      // console.log( this.preview_products.quantity)
      if (
        parseInt(this.preview_products.quantity) >
          parseInt(this.preview_products.stock) &&
        this.add_to_exchnage == 1
      ) {
        this.$toasted.show(
          `product max quantity ${this.preview_products.stock}`,
          {
            type: "error",
            position: "top-center",
            duration: 3000,
          }
        );
        this.preview_products.quantity = this.preview_products.stock;
        // this.$refs.quantity.focus();
      }
      if (this.preview_products.price.length <= 0) {
        this.$toasted.show("sorry! price filed fille up first", {
          type: "error",
          position: "top-center",
          duration: 3000,
        });
        this.$refs.price.focus();
        this.validation();
        return;
      } else {
        let price = this.preview_products.price;
        let quantity = this.preview_products.quantity;

        if (quantity <= 1) {
          quantity = 1;
        }
        let total = parseInt(price) * parseInt(quantity);
        this.preview_products.total = parseInt(total);
        this.validation();
      }
    },

    //method initial for product add on form.prodcut arraw
    productAdd() {
      this.totalAmount();
      if (this.form.products.length <= 0 && this.add_to_exchnage == 2) {
        this.$toasted.show(`First add sale amount`, {
          type: "error",
          position: "top-center",
          duration: 4000,
        });
        return;
      }
      if (this.add_to_exchnage == 1) {
        this.form.products.push(this.preview_products);
      } else {
        this.form.exchnage_products.push(this.preview_products);
      }

      this.preview_products = {
        product_id: "",
        product_code: "",
        product_name: "",
        price: "",
        total: "",
        quantity: "",
        alert_quantity: "",
        stock: "",
      };
      this.search = "";
      this.totalAmount();
      this.amountDue();
      this.validation();
      this.finalValidation();
    },

    //method initial for validation product data
    validation() {
      //console.log(this.preview_products.price)
      if (
        this.preview_products.price.length > 0 &&
        this.preview_products.quantity.length > 0 &&
        this.preview_products.product_id &&
        this.search.length > 0
      ) {
        this.validationPreview = false;
        return;
      } else if (
        parseInt(this.preview_products.quantity) <=
          parseInt(this.preview_products.stock) &&
        this.add_to_exchnage == 1
      ) {
        this.validationPreview = false;
        return;
      } else {
        this.validationPreview = true;
        return;
        // this.submitValidation=true;
      }
    },

    finalValidation() {
      if (this.form.products.length <= 0) {
        this.submitValidation = true;
        return;
      }

      if (this.form.exchnage_products.length <= 0) {
        this.submitValidation = true;
        return;
      }

      if (this.form.sale_total < this.form.exchange_total) {
        this.submitValidation = true;
        return;
      }
      if (this.form.name.length <= 0) {
        this.submitValidation = true;
        return;
      }
      if (this.form.mobile_no.length != 11) {
        this.submitValidation = true;
        return;
      }
      if (this.form.address.length <= 0) {
        this.submitValidation = true;
        return;
      }
      this.submitValidation = false;
    },

    totalAmount() {
      let sale_amount = 0;
      let products = this.form.products;

      if (this.form.products.length > 0) {
        this.form.products.forEach((ele) => {
          sale_amount += parseInt(ele.price) * parseInt(ele.quantity);
        });
      }

      let exchange_amount = 0;
      if (this.form.exchnage_products.length > 0) {
        this.form.exchnage_products.forEach((el) => {
          exchange_amount += parseInt(el.price) * parseInt(el.quantity);
        });
      }
      // console.log(exchange_amount);
      this.form.sale_total = parseInt(sale_amount);
      this.form.exchange_total = parseInt(exchange_amount);
      this.form.AmountTotal = parseInt(sale_amount) - parseInt(exchange_amount);
      this.form.due = parseInt(sale_amount) - parseInt(exchange_amount);

      if (exchange_amount > sale_amount) {
        this.$toasted.show(`Sale amount low, exchange amount many`, {
          type: "error",
          position: "top-center",
          duration: 4000,
        });
      }
    },
    amountDue() {
      let paid = this.form.paid;
      let total = this.form.AmountTotal;
      let due = parseInt(total) - parseInt(paid) - parseInt(this.form.discount);

      this.form.due = due;
    },
    cancel(index) {
      if (this.add_to_exchnage == 1) {
        this.form.products.splice(index, 1);
      } else {
        this.form.exchnage_products.splice(index, 1);
      }
      this.totalAmount();
      this.amountDue();
      this.validation();
      this.finalValidation();
    },
    pDate() {
      let d = new Date();

      let month = d.getMonth() + 1;
      let day = d.getDate();
      let output =
        d.getFullYear() +
        "-" +
        (("" + month).length < 2 ? "0" : "") +
        month +
        "-" +
        (("" + day).length < 2 ? "0" : "") +
        day;
      this.purchase_date = output;
    },

    partialsPayment() {
      let inputOptions = {};
      this.paid_by_options.forEach((ele) => {
        if (ele != this.form.paid_by) {
          inputOptions[ele] = ele;
        }
      });
      Swal.fire({
        title: "Partials Payment",
        input: "select",
        inputOptions: inputOptions,
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          Swal.fire({
            title: "Amount",
            input: "number",
            showCancelButton: false,
          }).then((amount) => {
            if (amount.value) {
              this.$toasted.show(
                `partials payment.${result.value} : ${amount.value}`,
                {
                  type: "info",
                  position: "top-center",
                  duration: 4000,
                }
              );
              this.form.partials_paid_by = result.value;
              this.form.partials_payment_amount = amount.value;
              console.log(typeof amount.value);
              this.form.paid =
                parseInt(amount.value) + parseInt(this.form.paid);
              this.form.due =
                parseInt(this.form.AmountTotal) - parseInt(this.form.paid);
            }
          });
        }
      });
    },
    //method initial search customer
    searchCustomer() {
      // console.log('serach')
      if (this.form.mobile_no.length == 11) {
        axios
          .get("/search/customer/with/phone/number/" + this.form.mobile_no)
          .then((resp) => {
            console.log(resp);
            //when com data from t resp
            if (resp.data.customer) {
              (this.form.name = resp.data.customer.name),
                (this.form.address = resp.data.customer.address);
              this.$toasted.show("Registered customer", {
                type: "info",
                position: "top-center",
                duration: 3000,
              });
            } else {
              this.$toasted.show("New Customer", {
                type: "info",
                position: "top-center",
                duration: 3000,
              });
            }
            this.finalValidation();
          })
          .catch((error) => {
            //console.log(error);
          });
      }
    },
  },
  computed: {},
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
