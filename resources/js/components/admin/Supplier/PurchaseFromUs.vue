<template>
  <div>

    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click="back" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">Sale</li>
        </ol>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-lg-12">
            <div class="box box-primary">
              <div class="box-body">
                <div class="alert-danger alert" v-if="error">{{ error }}</div>

                <div class="product_information">
                  <div class="row">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <label>Product Code</label>
                        <input
                          class="form-control"
                          autocomplete="off"
                          name="product"
                          v-model="search"
                          placeholder="type product code"
                          @keyup="autocompleteSearh"
                        />

                        <div class="autocomplete" v-show="automcomplete">
                          <ul class="list-group">
                            <li
                              class="list-group-item"
                              v-for="productItem in productItems"
                              @click="selectedProduct(productItem)"
                            >
                              {{
                                productItem.product_code +
                                "-" +
                                productItem.name
                              }}
                            </li>
                          </ul>
                        </div>
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
                          placeholder="quantity"
                          @keyup="total"
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
                    <div class="col-lg-1">
                      <button
                        class="btn btn-success btn-sm"
                        style="margin-top: 25px"
                        @click="productAdd"
                      >
                        Add
                      </button>
                    </div>

                  </div>
                </div>
                <div class="product_preview" v-if="form.products.length > 0">
                  <table class="table table-hover table-striped table-bordered">
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
                        <td>{{ index }}</td>
                        <td>
                          {{
                            product.product_code + "-" + product.product_name
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
                        <td>{{ this.form.AmountTotal }}</td>
                      </tr>

                      <tr>
                        <td colspan="3"></td>
                        <td>Paid</td>
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
                        <td colspan="3"></td>
                        <td>Debit From</td>
                        <td>
                          <select required class="form-control" v-model="form.paid_by">
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
                        <td colspan="3"></td>
                        <td> Comment </td>
                        <td>   <textarea class="form-control" v-model="form.comment" rows="3">
                      </textarea> </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

                <div class="form-group text-center">
                  <button
                    :disabled="submitValidation"
                    type="submit"
                    @click="add()"
                    class="btn btn-primary"
                  >
                    Submit
                  </button>
                </div>

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

Vue.component(HasError.name, HasError);

export default {
  name: "Add",
  created() {
    this.balanceList();
  },
  data() {
    return {
      //form submit data
      form: new Form({
        type: 3,
        name: "",
        mobile_no: "",
        address: "",
        //multiple product data
        products: [],
        // culation data
        AmountTotal: 0,
        paid: 0,
        discount: 0,
        due: 0,
        supplier_id: this.$route.params.id,
        paid_by: "",
        comment: "",
      }),
      balance: "",
      error: "",
      //store product item from to get db when user type product code or product name
      productItems: [],
      //auto complete
      automcomplete: false,
      search: "",
      submitValidation: true,
      preview_products: {
        product_name: "",
        product_id: "",
        product_code: "",
        price: "",
        quantity: "",
        total: "",
        alert_quantity: "",
      },
    };
  },

  methods: {
    balanceList() {
      axios.get("/api/balance/list").then((resp) => {
        this.balance = resp.data.balance;
      });
    },

    //method initial for add sale
    add() {
      this.$Progress.start();
      this.form
        .post("/sale/store")
        .then((resp) => {
          this.$Progress.finish();
          console.log(resp);
          if (resp.data.status == "SUCCESS") {
            console.log(resp);
            this.$router.push({
              name: "ViewSale",
              params: { id: resp.data.sale_id },
            });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          } else {
            this.error = "dont saved data. please try again";
          }
        })
        .catch((error) => {
          this.$Progress.finish();
          console.log(error);
          this.error = "something went to wrong";
        });
    },

    //method initial for get product data when admin type product code or name
    autocompleteSearh() {
      let length = this.search.length;
      if (length >= 3) {
        axios
          .get("/search/product/" + this.search)

          .then((resp) => {
            if (resp.data.status == "SUCCESS") {
              let x = resp.data.products.data.length;
              let i = 0;

              if (x >= 1) {
                this.productItems = [];
                for (i; i < x; i++) {
                  this.productItems.push(resp.data.products.data[i]);
                }
              } else {
                let no_found = {
                  name: "No product found",
                  product_code: "404",
                };
                this.productItems = [];
                this.productItems.push(no_found);
              }
            }
          });
        this.automcomplete = true;
      } else {
        this.automcomplete = false;
      }
    },

    //method initial for set product item when admin select specific product item
    selectedProduct(productItem) {
      if (productItem.name == "No product found") {
        alert("please enter product valid code or name");
        return;
      } else {
        this.automcomplete = false;
        this.preview_products.product_name = productItem.name;
        this.preview_products.price = productItem.sale_price;
        this.preview_products.quantity = 1;
        this.preview_products.product_id = productItem.id;
        this.preview_products.product_code = productItem.product_code;
        this.search = productItem.product_code + "-" + productItem.name;
        this.total();
      }
    },

    //method initial for  sub total  amount,  price*quantity
    total() {
      if (this.preview_products.price.length <= 0) {
        this.$toasted.show("sorry! price filed fille up first", {
          type: "error",
          position: "top-center",
          duration: 3000,
        });
        this.$refs.price.focus();
        return;
      } else {
        let price = this.preview_products.price;
        let quantity = this.preview_products.quantity;

        if (quantity <= 1) {
          quantity = 1;
        }
        let total = price * quantity;
        this.preview_products.total = total;
      }
    },

    //method initial for product add on form
    productAdd() {
      if (this.preview_products.price.length <= 0) {
        alert("price is empty");
        return;
      }
      if (this.preview_products.product_id.length <= 0) {
        alert("product id is empty");
        return;
      }
      if (this.preview_products.product_code.length <= 0) {
        alert("product code is empty");
        return;
      }

      this.form.products.push(this.preview_products);
      this.preview_products = {
        product_id: "",
        product_code: "",
        product_name: "",
        price: "",
        total: "",
        quantity: "",
        alert_quantity: "",
      };
      this.search = "";
      this.totalAmount();
      this.amountDue();
      this.finalValidation();
    },

    //method initial for validation product data
    finalValidation() {
      if (this.form.products.length <= 0) {
        this.submitValidation = true;
        return;
      }

      if (this.form.supplier_id.length <= 0) {
        this.submitValidation = true;
        return;
      }

      this.submitValidation = false;
    },

    totalAmount() {
      let i = 0;
      let total = 0;
      let products = this.form.products;
      for (i; i < products.length; i++) {
        total += products[i].price * products[i].quantity;
      }
      let discount = this.form.discount;
      let after_discount_total = total - discount;
      this.form.AmountTotal = after_discount_total;
      this.form.paid = after_discount_total;
      this.form.due = after_discount_total;
    },
    amountDue() {
      let paid = this.form.paid;
      let total = this.form.AmountTotal;
      let due = total - paid;

      this.form.due = due;
    },
    cancel(index) {
      this.form.products.splice(index, 1);
      this.totalAmount();
      this.amountDue();
      this.finalValidation();
    },

    back() {
      return window.history.back();
    },
  },
};
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
