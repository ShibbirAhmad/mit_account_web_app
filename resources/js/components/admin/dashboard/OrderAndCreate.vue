<template>
  <div>
    <admin-main></admin-main>

    <div class="content-wrapper">

      <h2 v-if="loading" style="text-align: center; font-size: 50px">
        <i class="fa fa-spinner fa-spin"></i>
      </h2>
      <section v-else class="content">

        <div >
          <div class="row" v-if="$can('Manage accounts')">
            <div class="col-lg-6">
              <div class="row">
                <h2 style="margin-left: 15px">Due Summary</h2>
                <div class="col-lg-6">
                  <div class="custom-box">
                    <div class="custom-box-body">
                      <h4>
                        Office Sale Due
                        <strong>{{ parseInt(due.office_sale_due) }}</strong>
                      </h4>
                      <h4>
                        Whole Sale Due
                        <strong>{{ parseInt(due.whole_sale_due) }}</strong>
                      </h4>
                      <h4>
                        Order Due <strong>{{ parseInt(due.order_due) }}</strong>
                      </h4>
                      <h4>
                        <strong>
                          =
                          {{
                            parseInt(due.office_sale_due) +
                            parseInt(due.whole_sale_due) +
                            parseInt(due.order_due)
                          }}
                        </strong>
                      </h4>
                    </div>

                    <div class="custom-box-footer" style="margin-top: 70px">
                      <h3 class="text-center text-uppercase">GET it</h3>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="custom-box">
                    <div class="custom-box-body">
                      <h4>
                        Product Supplier
                        <strong>{{ parseInt(due.product_supplier) }}</strong>
                      </h4>
                      <h4>
                        Fabrics Supplier
                        <strong>{{ parseInt(due.fabrics_supplier) }}</strong>
                      </h4>
                      <h4>
                        <strong
                          >=
                          {{
                            parseInt(due.product_supplier) +
                            parseInt(due.fabrics_supplier)
                          }}</strong
                        >
                      </h4>
                    </div>

                    <div class="custom-box-footer" style="margin-top: 100px">
                      <h3 class="text-center text-uppercase">To Pay</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="row">
                <div class="col-lg-12">
                  <h2 style="text-align: right; margin-top: 30px">
                    Order Analysis
                  </h2>

                  <div class="custom-box">
                    <div class="custom-box-body">
                      <ul class="analysis-item">
                        <li
                          :class="{ active: analysisshow == 1 }"
                          @click="analysisshow = 1"
                        >
                          Today
                        </li>
                        <li
                          :class="{ active: analysisshow == 4 }"
                          @click="analysisshow = 4"
                        >
                          yesterday
                        </li>
                        <li
                          :class="{ active: analysisshow == 2 }"
                          @click="analysisshow = 2"
                        >
                          This week
                        </li>
                        <li
                          :class="{ active: analysisshow == 3 }"
                          @click="analysisshow = 3"
                        >
                          This Month
                        </li>
                      </ul>
                      <table class="table">
                        <thead>
                          <tr>
                            <td></td>
                            <td>O.Qty</td>
                            <td>P.Qty</td>
                            <td>Amount</td>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="(item, index) in analysis" :key="index">
                            <td>{{ index }}</td>
                            <td>
                              <span v-if="analysisshow == 1">
                                {{ item.today.order_quantity }}
                              </span>

                              <span v-if="analysisshow == 4">
                                {{ item.yesterday.order_quantity }}
                              </span>

                              <span v-if="analysisshow == 2">
                                {{ item.this_week.order_quantity }}
                              </span>
                              <span v-if="analysisshow == 3">
                                {{ item.this_month.order_quantity }}
                              </span>
                            </td>
                            <td>
                              <span v-if="analysisshow == 1">
                                {{ item.today.product_quanity }}
                              </span>
                              <span v-if="analysisshow == 4">
                                {{ item.yesterday.product_quanity }}
                              </span>
                              <span v-if="analysisshow == 2">
                                {{ item.this_week.product_quanity }}
                              </span>
                              <span v-if="analysisshow == 3">
                                {{ item.this_month.product_quanity }}
                              </span>
                            </td>
                            <td>
                              <span v-if="analysisshow == 1">
                                {{ item.today.amount }}
                              </span>
                              <span v-if="analysisshow == 4">
                                {{ item.yesterday.amount }}
                              </span>
                              <span v-if="analysisshow == 2">
                                {{ item.this_week.amount }}
                              </span>
                              <span v-if="analysisshow == 3">
                                {{ item.this_month.amount }}
                              </span>
                            </td>
                          </tr>
                          <tr>
                            <td></td>
                            <td>
                              <span>
                                =
                                <b v-if="analysisshow == 1">
                                  {{
                                    parseInt(
                                      analysis.officeSale.today.order_quantity
                                        ? analysis.officeSale.today
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.today.order_quantity
                                        ? analysis.wholesale.today
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.today.order_quantity
                                        ? analysis.order.today.order_quantity
                                        : 0
                                    )
                                  }}
                                </b>

                                <b v-if="analysisshow == 4">
                                  {{
                                    parseInt(
                                      analysis.officeSale.yesterday
                                        .order_quantity
                                        ? analysis.officeSale.yesterday
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.yesterday
                                        .order_quantity
                                        ? analysis.wholesale.yesterday
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.yesterday.order_quantity
                                        ? analysis.order.yesterday
                                            .order_quantity
                                        : 0
                                    )
                                  }}
                                </b>

                                <b v-if="analysisshow == 2">
                                  {{
                                    parseInt(
                                      analysis.officeSale.this_week
                                        .order_quantity
                                        ? analysis.officeSale.this_week
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.this_week
                                        .order_quantity
                                        ? analysis.wholesale.this_week
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.this_week.order_quantity
                                        ? analysis.order.this_week
                                            .order_quantity
                                        : 0
                                    )
                                  }}
                                </b>
                                <b v-if="analysisshow == 3">
                                  {{
                                    parseInt(
                                      analysis.officeSale.this_month
                                        .order_quantity
                                        ? analysis.officeSale.this_month
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.this_month
                                        .order_quantity
                                        ? analysis.wholesale.this_month
                                            .order_quantity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.this_month.order_quantity
                                        ? analysis.order.this_month
                                            .order_quantity
                                        : 0
                                    )
                                  }}
                                </b>
                              </span>
                            </td>
                            <td>
                              <span>
                                =
                                <b v-if="analysisshow == 1">
                                  {{
                                    parseInt(
                                      analysis.officeSale.today.product_quanity
                                        ? analysis.officeSale.today
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.today.product_quanity
                                        ? analysis.wholesale.today
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.today.product_quanity
                                        ? analysis.order.today.product_quanity
                                        : 0
                                    )
                                  }}
                                </b>

                                <b v-if="analysisshow == 4">
                                  {{
                                    parseInt(
                                      analysis.officeSale.yesterday
                                        .product_quanity
                                        ? analysis.officeSale.yesterday
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.yesterday
                                        .product_quanity
                                        ? analysis.wholesale.yesterday
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.yesterday.product_quanity
                                        ? analysis.order.yesterday
                                            .product_quanity
                                        : 0
                                    )
                                  }}
                                </b>

                                <b v-if="analysisshow == 2">
                                  {{
                                    parseInt(
                                      analysis.officeSale.this_week
                                        .product_quanity
                                        ? analysis.officeSale.this_week
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.this_week
                                        .product_quanity
                                        ? analysis.wholesale.this_week
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.this_week.product_quanity
                                        ? analysis.order.this_week
                                            .product_quanity
                                        : 0
                                    )
                                  }}
                                </b>
                                <b v-if="analysisshow == 3">
                                  {{
                                    parseInt(
                                      analysis.officeSale.this_month
                                        .product_quanity
                                        ? analysis.officeSale.this_month
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.this_month
                                        .product_quanity
                                        ? analysis.wholesale.this_month
                                            .product_quanity
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.this_month.product_quanity
                                        ? analysis.order.this_month
                                            .product_quanity
                                        : 0
                                    )
                                  }}
                                </b>
                              </span>
                            </td>
                            <td>
                              <span>
                                =
                                <b v-if="analysisshow == 1">
                                  {{
                                    parseInt(
                                      analysis.officeSale.today.amount
                                        ? analysis.officeSale.today.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.today.amount
                                        ? analysis.wholesale.today.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.today.amount
                                        ? analysis.order.today.amount
                                        : 0
                                    )
                                  }}
                                </b>

                                <b v-if="analysisshow == 4">
                                  {{
                                    parseInt(
                                      analysis.officeSale.yesterday.amount
                                        ? analysis.officeSale.yesterday.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.yesterday.amount
                                        ? analysis.wholesale.yesterday.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.yesterday.amount
                                        ? analysis.order.yesterday.amount
                                        : 0
                                    )
                                  }}
                                </b>

                                <b v-if="analysisshow == 2">
                                  {{
                                    parseInt(
                                      analysis.officeSale.this_week.amount
                                        ? analysis.officeSale.this_week.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.this_week.amount
                                        ? analysis.wholesale.this_week.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.this_week.amount
                                        ? analysis.order.this_week.amount
                                        : 0
                                    )
                                  }}
                                </b>
                                <b v-if="analysisshow == 3">
                                  {{
                                    parseInt(
                                      analysis.officeSale.this_month.amount
                                        ? analysis.officeSale.this_month.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.wholesale.this_month.amount
                                        ? analysis.wholesale.this_month.amount
                                        : 0
                                    ) +
                                    parseInt(
                                      analysis.order.this_month.amount
                                        ? analysis.order.this_month.amount
                                        : 0
                                    )
                                  }}
                                </b>
                              </span>
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

          <div class="row" v-if="$can('manage product')">
            <h2 style="margin-left: 15px">Stock</h2>

            <div class="col-lg-4 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3 class="">{{ stock.total_quantity }}</h3>

                  <h4>Total Stock Quantity</h4>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-xs-6">
              <div class="small-box bg-green">
                <div class="inner">
                  <h3 class="">{{ parseInt(stock.total_price) }}</h3>
                  <h4>Total Stock Amount</h4>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <h3 class="text-center text-uppercase">Create Analysis</h3>
            <div class="col-lg-4 col-md-4">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <img
                    :src="base_url + admin.image"
                    class="img-circle small-image"
                    :alt="admin.name"
                    v-if="admin.image"
                  />
                  <img
                    :src="base_url + 'images/static/user2-160x160.jpg'"
                    class="img-circle small-image"
                    :alt="admin.name"
                    v-else
                  />
                  <h4 class="text-center">
                    {{ "Hi " + admin.name + " see your order create history" }}
                  </h4>
                </div>
                <div class="box-body">
                  <table
                    class="
                      table
                      text-center
                      table-bordered table-striped table-hover
                    "
                  >
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Total Create</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="(
                          session_admin_order, index
                        ) in admin_orders.session_admin"
                        :key="index"
                      >
                        <td>{{ index + 1 }}</td>
                        <td>{{ session_admin_order.created_at }}</td>
                        <td>{{ session_admin_order.total }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="col-lg-8 col-md-8">
              <div class="box box-success">
                <div class="box-header with-border text-center">
                  <ul class="admin_order_menu">
                    <li
                      @click="adminOrderToday"
                      :class="{ active_border: admin_order_today == true }"
                    >
                      Today
                    </li>
                    <li
                      @click="adminOrderYesterday"
                      :class="{ active_border: admin_order_yesterday == true }"
                    >
                      Yesterday
                    </li>
                    <li
                      @click="adminOrderThisWeek"
                      :class="{ active_border: admin_order_this_week == true }"
                    >
                      This Week
                    </li>
                    <li
                      @click="adminOrderThisMonth"
                      :class="{ active_border: admin_order_this_month == true }"
                    >
                      This Month
                    </li>
                  </ul>
                </div>
                <div class="box-body">
                  <table class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Admin</th>
                        <th>Total Create</th>
                        <th>Total Amount</th>
                      </tr>
                    </thead>

                    <tbody v-if="admin_order_today">
                      <tr
                        v-for="(
                          order_create_count, index
                        ) in admin_orders.today"
                        :key="index"
                      >
                        <td>{{ index + 1 }}</td>
                        <td>
                          <img
                            :src="
                              base_url + order_create_count.create_admin.image
                            "
                            class="img-circle small-image"
                            style="width: 60px; height: 60px"
                            v-if="order_create_count.create_admin.image"
                          />
                          {{ order_create_count.create_admin.name }}
                        </td>
                        <td>{{ order_create_count.total }}</td>
                        <td>&#2547;{{ order_create_count.total_amount }}</td>
                      </tr>
                    </tbody>

                    <tbody v-if="admin_order_yesterday">
                      <tr
                        v-for="(
                          order_create_count, index
                        ) in admin_orders.yesterday"
                        :key="index"
                      >
                        <td>{{ index + 1 }}</td>
                        <td>
                          <img
                            :src="
                              base_url + order_create_count.create_admin.image
                            "
                            class="img-circle small-image"
                            style="width: 60px; height: 60px"
                            v-if="order_create_count.create_admin.image"
                          />
                          {{ order_create_count.create_admin.name }}
                        </td>
                        <td>{{ order_create_count.total }}</td>
                        <td>&#2547;{{ order_create_count.total_amount }}</td>
                      </tr>
                    </tbody>

                    <tbody v-if="admin_order_this_week">
                      <tr
                        v-for="(
                          order_create_count, index
                        ) in admin_orders.this_week"
                        :key="index"
                      >
                        <td>{{ index + 1 }}</td>
                        <td>
                          <img
                            :src="
                              base_url + order_create_count.create_admin.image
                            "
                            class="img-circle small-image"
                            style="width: 60px; height: 60px"
                            v-if="order_create_count.create_admin.image"
                          />
                          {{ order_create_count.create_admin.name }}
                        </td>
                        <td>{{ order_create_count.total }}</td>
                        <td>&#2547;{{ order_create_count.total_amount }}</td>
                      </tr>
                    </tbody>

                    <tbody v-if="admin_order_this_month">
                      <tr
                        v-for="(
                          order_create_count, index
                        ) in admin_orders.this_month"
                        :key="index"
                      >
                        <td>{{ index + 1 }}</td>
                        <td>
                          <img
                            :src="
                              base_url + order_create_count.create_admin.image
                            "
                            class="img-circle small-image"
                            style="width: 60px; height: 60px"
                            v-if="order_create_count.create_admin.image"
                          />
                          {{ order_create_count.create_admin.name }}
                        </td>
                        <td>{{ order_create_count.total }}</td>
                        <td>&#2547;{{ order_create_count.total_amount }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div class="row" v-if="$can('view dashboard')">
            <h3 class="text-center text-uppercase">Top Sell Product Today</h3>

            <div class="col-lg-12">
              <div class="box box-primary">
                <div class="bx-header with-border"></div>
                <div class="box-body">
                  <carousel
                    v-if="top_selling_products_today.length"
                    :nav="false"
                    :autoplay="true"
                    :autoplayTimeout="4000"
                    :responsive="{ 0: { items: 3 }, 600: { items: 7 } }"
                  >
                    <div
                      class="product-thumb clearfix"
                      v-for="top_s_product in top_selling_products_today"
                      :key="top_s_product.id"
                    >
                      <div class="image">
                        <img v-if="top_s_product.product.thumbnail_img"
                          :src="thumbnail_url+ top_s_product.product.thumbnail_img  "
                          class="img-responsive"
                        />
                      </div>
                      <div class="caption">
                        <h6>
                          {{
                              top_s_product.product.name +
                              "" +
                              top_s_product.product.product_code
                            }}
                        </h6>
                        <h6>
                          order: <b> {{ top_s_product.total }} </b>
                        </h6>
                      </div>
                    </div>
                  </carousel>
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
import carousel from "vue-owl-carousel";
export default {
   mounted(){
    window.scrollTo(0,0);
  },
  data() {
    return {
      loading: true,
      due: "",
      stock: "",
      admin_orders: "",
      top_selling_products_today: "",
      base_url: this.$store.state.image_base_link,
      thumbnail_url: this.$store.state.thumbnail_img_base_link,
      analysis: "",
      analysisshow: 1,
      admin_order_today: true,
      admin_order_yesterday: false,
      admin_order_this_week: false,
      admin_order_this_month: false,
    };
  },

  created() {
    this.$store.dispatch("admin");
    this.getData();
  },
  methods: {

    adminOrderToday() {
      this.admin_order_today = true;
      this.admin_order_yesterday = false;
      this.admin_order_this_week = false;
      this.admin_order_this_month = false;
    },

    adminOrderYesterday() {
      this.admin_order_today = false;
      this.admin_order_yesterday = true;
      this.admin_order_this_week = false;
      this.admin_order_this_month = false;
    },

    adminOrderThisWeek() {
      this.admin_order_today = false;
      this.admin_order_yesterday = false;
      this.admin_order_this_week = true;
      this.admin_order_this_month = false;
    },

    adminOrderThisMonth() {
      this.admin_order_today = false;
      this.admin_order_yesterday = false;
      this.admin_order_this_week = false;
      this.admin_order_this_month = true;
    },

    getData() {
      this.$Progress.start();
      axios.get("/api/order/stock/sell/analysis/and/others")
      .then((resp) => {
        console.log(resp.data.top_selling_products_today);
        this.stock = resp.data.stock;
        this.admin_orders = resp.data.admin_order;
        this.top_selling_products_today = resp.data.top_selling_products_today;
        this.analysis = resp.data.analysis;
        this.due = resp.data.due;
        this.$Progress.finish();
        this.loading=false;
      });
    },

  },

  components: {
    carousel,
  },
  computed: {
    admin() {
      return this.$store.getters.admin;
    },
  },
};
</script>

<style scoped >
.box-gradiant {
  background: -webkit-linear-gradient(to right, #c33764, #1d2671);
  background: linear-gradient(to right, #c33764, #1d2671);
}
.small-box .icon {
  color: #fff !important;
  opacity: 0.6;
}
.product-thumb.clearfix {
  padding: 12px 13px;
  margin-right: 10px;
  margin-right: 10px;
  border: 1px solid #eee;
  box-shadow: 2px 2px 2px #eee;
}
.custom-box {
  background: #fff;
  padding: 13px;
  min-height: 280px;
  box-shadow: 3px 3px 3px #ddd;
  border-radius: 6px;
  margin-bottom: 10px;
}
.custom-box-body strong {
  position: absolute;
  right: 10%;
  color: blue;
}
.custom-box-footer {
  background: #00a65a;
  color: #fff;
}

.analysis-item {
  display: flex;
  list-style-type: none;
  float: right;
}

.analysis-item li {
  padding: 10px 10px;
  text-transform: uppercase;
  font-weight: bold;
  cursor: pointer;
}

.analysis-item .active {
  border-bottom: 2px solid #000;
}
.__o_amount {
  color: #fff !important;
  font-size: 20px !important;
  float: right;
}

.admin_order_menu {
  display: flex;
}

.admin_order_menu li {
  list-style-type: none;
  margin: 5px 14px;
  cursor: pointer;
  padding: 5px 10px;
  box-shadow: 0 1pt 12pt rgb(150 165 237);
}

.active_border {
  border: 2px dashed #000;
}

.custom-box-body > h4 {
  cursor: pointer;
}
</style>
