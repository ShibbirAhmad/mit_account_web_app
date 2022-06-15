<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content">
        <div v-if="loading">
          <h1 class="text-center" v-if="loading">
            <i class="fa fa-spin fa-spinner"></i>
          </h1>
        </div>
        <div v-else class="container">
          <div class="row justify-content-center">
            <div class="col-lg-11">
              <div class="row">
                <div
                  class="col-lg-12"
                  style="
                    background: #fff;
                    margin-bottom: 13px;
                    font-weight: bolder;
                    text-align: center;
                    box-shadow: 3px 3px 3px #ddd;
                  "
                >
                  <h4 class="" style="font-weight: bold">
                    Supplier:{{ supplier.company_name }}
                  </h4>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <div class="supplier-box">
                    <h4 class="c-name ">Total Purchase Amount</h4>
                    <hr />
                    <h4 class="c-name ">
                      {{ amount.total_purchase_amount }}
                    </h4>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="supplier-box">
                    <h4 class="c-name ">Total Paid Amount</h4>
                    <hr />
                    <h4 class="c-name ">
                      {{ amount.total_paid_amount }}
                    </h4>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="supplier-box">
                    <h4 class="c-name ">Total Reverse Paid Amount</h4>
                    <hr />
                    <h4 class="c-name ">
                      {{ amount.total_reverse_paid_amount }}
                    </h4>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="supplier-box">
                    <h4 class="c-name ">Total Due Amount</h4>
                    <hr />
                    <h4 class="c-name ">
                      {{
                        (parseInt(amount.total_purchase_amount) + parseInt(amount.total_reverse_paid_amount)) -
                        parseInt(amount.total_paid_amount)
                      }}
                    </h4>
                  </div>
                </div>
              </div>
              <br />
              <div class="row">
                <div class="col-lg-12">
                  <div class="tab-menu-list">
                    <li
                      @click="paymentShow"
                      :class="[{ active: payment_history_show == true }]"
                    >
                      Payment history
                      <a
                        :href="'/api/pdf/suplier/amount/' + supplier.id"
                        style="background: #000; color: #fff; cursor: pointer"
                      >
                        PDF
                      </a>
                    </li>
                    <li
                      @click="reversePaymentShow"
                      :class="[{ active: reverse_payment_history_show == true }]"
                    >
                      Reverse Payment
                    </li>
                    <li
                      @click="purchaseShow"
                      :class="[{ active: purchase_history_show == true }]"
                    >
                      Purchase history
                      <a
                        :href="'/api/pdf/suplier/purchase/' + supplier.id"
                        style="background: #000; color: #fff; cursor: pointer"
                      >
                        PDF
                      </a>
                    </li>

                    <li>
                      <router-link
                        class="btn btn_add_sale"
                        :to="{
                          name: 'supplier_purchase_from_us',
                          params: { id: supplier.id },
                        }"
                      >
                        <i class="fa fa-plus"></i> Add Sale
                      </router-link>
                    </li>
                    <li>
                      <router-link
                        class="btn btn_add_sale"
                        :to="{
                          name: 'supplier_purchase_add',
                          params: { id: supplier.id },
                        }"
                      >
                        <i class="fa fa-plus"></i> Add Purchase
                      </router-link>
                    </li>

                    <li
                      @click="salesShow"
                      :class="[{ active: reverse_sales_history_show == true }]"
                    >
                      Reverse Sales History
                    </li>

                    <li>
                      <router-link
                        :to="{
                          name: 'supplier_note',
                          params: { id: supplier.id },
                        }"
                        >Write Note</router-link
                      >
                    </li>
                  </div>
                </div>
              </div>
              <div class="row">
                <div
                  class="col-lg-12"
                  v-if="payment_history_show == true"
                  style="background: #fff; padding: 15px 16px"
                >
                  <h4>Payment History</h4>
                  <table
                    class="table table-hover table-bordered table-striped"
                    v-if="paid_items.length > 0"
                  >
                    <thead>
                      <tr>
                        <td>#</td>
                        <td>Date</td>
                        <td>Paid By</td>
                        <td>Amount</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(paid_item, index) in paid_items" :key="index">
                        <td>
                          {{ index + 1 }}
                        </td>
                        <td>
                          {{ paid_item.date }}
                        </td>
                        <td>
                          {{ paid_item.paid_by ? paid_item.paid_by : "" }}
                        </td>
                        <td>
                          {{ paid_item.amount }}
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>
                          <strong> = {{ PaymentDue() }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <h4 class="text-center  text-bold" v-else>
                    Payment History Is Empty
                  </h4>
                </div>

                 <div
                  class="col-lg-12"
                  v-if="reverse_payment_history_show == true"
                  style="background: #fff; padding: 15px 16px"
                >
                 <a   @click=" showMoneyPaidModal
                              " class=" btn btn-sm btn-success"> <i class="fa fa-plus"></i> Receive Payment </a>
                  <h4 style="text-align:center">Reverse Payment History</h4>
                  <table
                    class="table table-hover table-bordered table-striped"
                >
                    <thead>
                      <tr>
                        <td>#</td>
                        <td>Date</td>
                        <td>Paid By</td>
                        <td>Amount</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(item, index) in reverse_payments" :key="index">
                        <td>
                          {{ index + 1 }}
                        </td>
                        <td>
                          {{ item.date }}
                        </td>
                        <td>
                          {{ item.paid_by ? item.paid_by : "" }}
                        </td>
                        <td>
                          {{ item.amount }}
                        </td>
                      </tr>

                    </tbody>
                  </table>

                </div>

                <div
                  class="col-lg-12"
                  v-if="purchase_history_show == true"
                  style="background: #fff; padding: 15px 16px"
                >
                  <h4>Purchase History</h4>
                  <table
                    class="table table-hover table-bordered table-striped"
                    v-if="purchase_items.length > 0"
                  >
                    <thead>
                      <tr>
                        <td>#</td>
                        <td>Date</td>
                        <td>Invoice No</td>
                        <td>Comment</td>
                        <td>Product Qty</td>
                        <td>Amount</td>
                        <td>Paid</td>
                        <td>Due</td>
                        <td>Action</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(purchase_item, index) in purchase_items"
                        :key="index"
                      >
                        <td>
                          {{ index + 1 }}
                        </td>
                        <td>
                          {{ purchase_item.purchase_date }}
                        </td>
                        <td>
                          {{ purchase_item.invoice_no }}
                        </td>
                        <td>
                          {{
                            purchase_item.comment ? purchase_item.comment : ""
                          }}
                        </td>
                        <td>
                          {{ productQty(purchase_item.purchase_items) }}
                        </td>

                        <td>
                          {{ purchase_item.total }}
                        </td>
                        <td>
                          {{ purchase_item.paid }}
                        </td>
                        <td>
                          {{
                            parseInt(purchase_item.total) -
                            parseInt(purchase_item.paid)
                          }}
                        </td>
                        <td>
                          <router-link
                            :to="{
                              name: 'purchaseView',
                              params: { id: purchase_item.id },
                            }"
                            class="btn btn-primary btn-sm"
                            target="_blank"
                            >Details</router-link
                          >
                        </td>
                      </tr>
                      <tr>
                        <td colspan="3"></td>
                        <td>
                          <strong>= {{ productQtyTotal() }}</strong>
                        </td>
                        <td>
                          <strong>= {{ totalAmount() }}</strong>
                        </td>
                        <td>
                          <strong>= {{ totalPaid() }}</strong>
                        </td>
                        <td>
                          <strong>= {{ dueAmount() }}</strong>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <h4 class="text-center  text-bold" v-else>
                    Purchase History Is Empty
                  </h4>
                </div>

                <div
                  class="col-lg-12"
                  v-if="reverse_sales_history_show == true"
                  style="background: #fff; padding: 15px 16px"
                >
                  <h4>Sales History</h4>
                  <table
                    class="table table-hover table-bordered table-striped"
                    v-if="reverse_sales.length > 0"
                  >
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Invoice</th>
                        <th scope="col">Date</th>
                        <th scope="col">Paid</th>
                        <th scope="col">Total</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(sale, index) in reverse_sales" :key="index">
                        <td>
                          {{ index + 1 }}
                        </td>
                        <td>
                          {{ sale.invoice_no }}
                        </td>

                        <td>
                          {{ sale.created_at }}
                        </td>
                        <td>
                          {{ sale.paid }}
                        </td>
                        <td>
                          {{ sale.total }}
                        </td>
                        <td>
                          <router-link
                            :to="{
                              name: 'ViewSale',
                              params: { id: sale.id },
                            }"
                            class="btn btn-primary btn-sm"
                            target="_blank"
                            >Details</router-link
                          >
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <h4 class="text-center  text-bold" v-else>
                    Sales History Is Empty
                  </h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

          <!-- start payment store modal  -->
          <modal name="paid" :width="400" :height="350">
            <form @submit.prevent="getPayment">
              <div class="card" style="padding: 20px">
                <div class="card-header text-center">
                   Receive Payment
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Amount</label>
                    <input
                      type="number"
                      name="amount"
                      required
                      v-model="payment_form.amount"
                      :class="{
                        'is-invalid': payment_form.errors.has('amount'),
                      }"
                      class="form-control"
                      placeholder="0"
                    />
                    <has-error :form="payment_form" field="amount">
                    </has-error>
                  </div>

                  <div class="form-group">
                    <label for="balance_id">Credit In</label>
                    <select
                      required
                      class="form-control"
                      v-model="payment_form.balance_id"
                    >
                      <option value="" disabled selected>Select Balance</option>
                      <option
                        v-for="(balance, index) in balance"
                        :value="balance.id"
                        :key="index"
                      >
                        {{ balance.name }}
                      </option>
                    </select>
                  </div>

                  <div class="form-group">
                    <label for="comment">comment</label>
                    <input
                      type="text"
                      name="comment"
                      v-model="payment_form.comment"
                      class="form-control"
                      placeholder=" payment"
                    />
                  </div>

                  <br />
                  <div class="form-group text-center">
                    <button type="submit" class="btn btn-success">
                      Submit
                    </button>
                  </div>
                  <br />
                </div>
              </div>
            </form>
          </modal>
          <!-- end payment store modal  -->

  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
export default {
  created() {
    this.supplierAmount();
    this.balanceList();
    this.reverseSalesHistory();
  },
  components: {
    HasError,
  },
  data() {
    return {
      amount: "",
      loading: true,
      supplier: "",
      reverse_payments: "",
      paid_items: "",
      purchase_items: "",
      payment_history_show: false,
      purchase_history_show: true,
      reverse_payment_history_show: false,
      reverse_sales_history_show: false,
      reverse_sales: "",
      balance:"",
      payment_form: new Form({
        supplier_id: this.$route.params.id,
        balance_id: "",
        amount: "",
        comment: "",
      }),
    };
  },
  methods: {

    getPayment() {
      this.payment_form
        .post("/api/supplier/reverse/payment/store", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true) {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.payment_form.amount = "";
            this.payment_form.comment = "";
            this.payment_form.balance_id = "";
            this.supplierAmount();
            this.$modal.hide("paid");
          } else {
            this.$toasted.show(resp.data, {
              type: "error",
              position: "top-center",
              duration: 4000,
            });
          }
        })

    },


    balanceList() {
      axios.get("/api/balance/list")
      .then((resp) => {
        this.balance = resp.data.balance;
      });
    },

    showMoneyPaidModal() {
      this.$modal.show("paid");
    },

   supplierAmount() {
      axios
        .get("/api/suplier/amount/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          this.supplier = resp.data.supplier;
          this.amount = resp.data.amount;
          this.paid_items = resp.data.paid_items;
          this.reverse_payments = resp.data.reverse_payments;
          this.purchase_items = resp.data.purchase_items;
          this.loading = false;
        })

    },

    paymentShow() {
      this.payment_history_show = true;
      this.purchase_history_show = false;
      this.reverse_payment_history_show = false;
      this.reverse_sales_history_show = false;
    },

    reversePaymentShow() {
      this.payment_history_show = false;
      this.purchase_history_show = false;
      this.reverse_payment_history_show = true;
      this.reverse_sales_history_show = false;
    },

    purchaseShow() {
      this.payment_history_show = false;
      this.reverse_payment_history_show = false;
      this.purchase_history_show = true;
      this.reverse_sales_history_show = false;
    },

    salesShow() {
      this.payment_history_show = false;
      this.purchase_history_show = false;
      this.reverse_payment_history_show = false;
      this.reverse_sales_history_show = true;
    },

    reverseSalesHistory() {
      axios
        .get("/api/supplier/reverse/sales/records/" + this.$route.params.id)
        .then((resp) => {
          if (resp.data.status == "OK") {
            this.reverse_sales = resp.data.sales;
          }
        });
    },


    totalAmount() {
      let total = 0;
      this.purchase_items.forEach((element) => {
        total += parseInt(element.total);
      });
      return total;
    },
    dueAmount() {
      let total = 0;
      this.purchase_items.forEach((element) => {
        total += parseInt(element.total) - parseInt(element.paid);
      });
      return total;
    },
    PaymentDue() {
      let total = 0;
      this.paid_items.forEach((element) => {
        total += parseInt(element.amount);
      });
      return total;
    },
    productQty(items) {
      let total = 0;
      items.forEach((ele) => {
        total += parseInt(ele.stock);
      });

      return total;
    },
    productQtyTotal() {
      let total = 0;
      this.purchase_items.forEach((ele) => {
        ele.purchase_items.forEach((element) => {
          total += parseInt(element.stock);
        });
      });

      return total;
    },
    totalPaid() {
      let total = 0;
      this.purchase_items.forEach((ele) => {
        total += parseInt(ele.paid);
      });

      return total;
    },
  },
};
</script>

<style scoped>

.supplier-box {
  background: #fff;
  padding:3px 0px;
  box-shadow: 3px 3px 3px #ddd;
  border-radius: 5px;
  height:120px;
}

.c-name {
  text-align: center;
  height:25px;
}

.tab-menu-list {
  display: flex;
  background: #ecf0f5;
}

.tab-menu-list li{
    list-style: none;
    padding: 5px 6px;
    text-transform: uppercase;
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
    margin-bottom: 10px;
    background: rgb(225, 231, 236);
    margin-right: 10px;
}

.btn_add_sale {
  font-size: 13px;
  font-weight: bold;
}

li.active {
  border-bottom: 2px solid #000;
  background: #fff;
}
</style>
