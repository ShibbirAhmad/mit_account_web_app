<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>report </a>
          </li>
          <li class="active">stock</li>
        </ol>
      </section>
      <br />
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-11 col-md-11">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title">Product Stock Tracking</h3>
                  <div class="row" style="margin: 20px 0px">
                    <div class="col-lg-4">
                      <div class="form-group">
                        <input
                          required
                          type="text"
                          placeholder="type product code "
                          v-model="product_code"
                          class="form-control"
                        />
                      </div>
                    </div>

                    <div class="col-lg-6">
                      <div class="row">
                        <div class="col-lg-5">
                          <date-picker
                            v-model="start_date"
                            placeholder="start-date"
                            :config="options"
                          ></date-picker>
                        </div>
                        <div class="col-lg-5" style="margin-left: -20px">
                          <date-picker
                            v-model="end_date"
                            placeholder="end-date"
                            :config="options"
                          ></date-picker>
                        </div>
                        <div class="col-lg-2">
                          <button
                            type="submit "
                            class="btn btn-primary"
                            @click.prevent="filter"
                          >
                            <i class="fa fa-filter"></i> Filter Stock
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <div style="margin-bottom: 20px" class="row">
                    <div class="col-md-12 text-center">
                      <h4 class="table_heading">Purchase Records</h4>
                      <table
                        class="
                          table table-bordered table-striped table-centered
                          text-center
                          table-hover
                        "
                      >
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Price</th>
                            <th>Stock Qty</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="item in reports.purchase_records"
                            :key="item.id"
                          >
                            <td>{{ item.purchase_date }}</td>
                            <td>
                              <router-link
                                :to="{
                                  name: 'purchaseView',
                                  params: { id: item.id },
                                }"
                              >
                                {{ item.invoice_no }}</router-link
                              >
                            </td>
                            <td>{{ item.price }}</td>
                            <td>{{ item.stock }}</td>
                            <td>
                              {{ parseInt(item.price) * parseInt(item.stock) }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div style="margin-bottom: 20px" class="row">
                    <div class="col-md-6 text-center">
                      <h4 class="table_heading">Order Records</h4>
                      <table
                        class="
                          table table-bordered table-striped table-centered
                          text-center
                          table-hover
                        "
                      >
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Price</th>
                            <th>Order Qty</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="item in reports.order_records"
                            :key="item.id"
                          >
                            <td>{{ item.created_at }}</td>
                            <td>
                              <router-link
                                :to="{
                                  name: 'viewOrder',
                                  params: { id: item.id },
                                }"
                                >{{ item.invoice_no }}</router-link
                              >
                            </td>
                            <td>{{ item.price }}</td>
                            <td>{{ item.quantity }}</td>
                            <td>
                              {{
                                parseInt(item.price) * parseInt(item.quantity)
                              }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="col-md-6 text-center">
                      <h4 class="table_heading">Sale Records</h4>
                      <table
                        class="
                          table table-bordered table-striped table-centered
                          text-center
                          table-hover
                        "
                      >
                        <thead>
                          <tr>
                            <th>Date</th>
                            <th>Invoice No</th>
                            <th>Price</th>
                            <th>Sale Qty</th>
                            <th>Amount</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr
                            v-for="item in reports.sale_records"
                            :key="item.id"
                          >
                            <td>{{ item.created_at }}</td>
                            <td>
                              <router-link
                                :to="{
                                  name: 'ViewSale',
                                  params: { id: item.id },
                                }"
                                >{{ item.invoice_no }}
                              </router-link>
                            </td>
                            <td>{{ item.price }}</td>
                            <td>{{ item.qty }}</td>
                            <td>
                              {{ parseInt(item.price) * parseInt(item.qty) }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>

                  <div v-if="reports" style="margin-bottom: 20px" class="row">
                    <div class="col-md-12 text-center">
                      <h4 class="table_heading">Summary</h4>
                      <table
                        class="
                          table
                          table-bordered
                          table-striped
                          table-centered
                          table-hover
                          text-center
                        "
                      >
                        <thead>
                          <tr>
                            <th>Product</th>
                            <th>Purchase</th>
                            <th>Order</th>
                            <th>Sale</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>
                              <div class="text-left">
                                <img
                                  width="120px"
                                  height="120px"
                                  :src="base_url + product.thumbnail_img"
                                />
                                <p>Name: {{ product.name }}</p>
                                <p>Code: {{ product.product_code }}</p>
                                <p>Current Stock : {{ product.stock }}</p>
                              </div>
                            </td>
                            <td>
                              <div class="text-left">
                                <p>Total Stocked : {{ total_stocked }}</p>
                                <p>Total Amount : {{ total_stocked_amount }}</p>
                              </div>
                            </td>

                            <td>
                              <div class="text-left">
                                <p>Total Ordered : {{ total_ordered }}</p>
                                <p>Total Amount : {{ total_ordered_amount }}</p>
                              </div>
                            </td>

                            <td>
                              <div class="text-left">
                                <p>Total Sold : {{ total_sold }}</p>
                                <p>Total Amount : {{ total_sold_amount }}</p>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2"></td>
                            <td>
                              <div class="text-left">
                                <p>
                                  In Total Sold (Order + Sale) :
                                  {{
                                    parseInt(total_ordered) +
                                    parseInt(total_sold)
                                  }}
                                </p>
                                <p>
                                  In Total Amount (Order + Sale) :
                                  {{
                                    parseInt(total_ordered_amount) +
                                    parseInt(total_sold_amount)
                                  }}
                                </p>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      loading: true,
      start_date: "",
      end_date: "",
      product_code: "",
      reports: "",
      product: "",
      total_stocked: 0,
      total_stocked_amount: 0,
      total_ordered: 0,
      total_ordered_amount: 0,
      total_sold: 0,
      total_sold_amount: 0,
      base_url: this.$store.state.thumbnail_img_base_link,
      //date picker options ..........  
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
    };
  },
  methods: {
    filter() {
      if (this.product_code.length < 3) {
        alert("product code required ");
        return;
      }
      this.$Progress.start();
      axios
        .get("/api/product/stock/tracking/report", {
          params: {
            product_code: this.product_code,
            start_date: this.start_date,
            end_date: this.end_date,
          },
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == true) {
            this.reports = resp.data.reports;
            this.product = resp.data.product;
            //calculate purchase total
            let p_item = 0;
            let p_total = 0;
            if (this.reports.purchase_records.length > 0) {
              this.reports.purchase_records.forEach((item) => {
                p_item += parseInt(item.stock);
                p_total += parseInt(item.stock) * parseInt(item.price);
              });
            }
            this.total_stocked = p_item;
            this.total_stocked_amount = p_total;

            //calculate order total
            let o_item = 0;
            let o_total = 0;
            if (this.reports.order_records.length > 0) {
              this.reports.order_records.forEach((item) => {
                o_item += parseInt(item.quantity);
                o_total += parseInt(item.quantity) * parseInt(item.price);
              });
            }
            this.total_ordered = o_item;
            this.total_ordered_amount = o_total;

            //calculate sale total
            let s_item = 0;
            let s_total = 0;
            if (this.reports.sale_records.length > 0) {
              this.reports.sale_records.forEach((item) => {
                s_item += parseInt(item.qty);
                s_total += parseInt(item.qty) * parseInt(item.price);
              });
            }
            this.total_sold = s_item;
            this.total_sold_amount = s_total;
          }
          this.$Progress.finish();
        })
        .catch((error) => {
          this.$toasted.show(error.response.data.message, {
            type: "error",
            position: "top-center",
            duration: 5000,
          });
        });
    },
  },
};
</script>

<style>
.orders-heading {
  text-align: center;
  text-transform: uppercase;
  border-bottom: 2px solid #000;
  margin-bottom: 10px;
}
.box-primary {
  overflow-x: scroll;
}
</style>
