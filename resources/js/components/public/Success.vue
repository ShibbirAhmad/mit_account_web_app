<template>
  <div class="wrapper-wide">
    <frontend-header></frontend-header>

    <div id="container">
      <div class="container">
        <!-- Breadcrumb End-->

        <div class="row">
           <div class="empty-cart">
              <h4 class="text-uppercase">Thank you for your order</h4>
              <img
                style="margin-bottom: 50px; margin-top: 50px; max-width: 80%"
                src="https://static-cms.hotjar.com/images/blog-17-Featured-1.width-750.png"
              />
              <br />
              <a
                href="/"
                style="
                  background: #ff4d03;
                  color: #fff;
                  padding: 12px 15px;
                  border-radius: 5px;
                "
                >Home</a
              >
              <a
                href="/user/dashboard"
                style="
                  background: #ff4d03;
                  color: #fff;
                  padding: 12px 15px;
                  border-radius: 5px;
                "
                >Dashboard</a
              >
            </div>

        </div>
        <hr/>
        <div class="row">
          <div class="col-lg-1"></div>
           <div class="col-lg-10 bg-white p-5 more">
            <h4 v-if="loading">Loaidng......</h4>
            <div  v-else>
              <dl class="row">
              <dt class="col-sm-3">Invoice Number:</dt>
              <dd class="col-sm-9">{{ order.invoice_no }}</dd>

              <dt class="col-sm-3" v-if="order.customer.name">Name:</dt>
              <dd class="col-sm-9" v-if="order.customer.name">{{ order.customer.name }}</dd>


              <dt class="col-sm-3" v-if="order.customer.email">Email:</dt>
              <dd class="col-sm-9" v-if="order.customer.email">{{ order.customer.email }}</dd>

              <dt class="col-sm-3">Mobile Number:</dt>
              <dd class="col-sm-9">{{ order.cutomer_phone }}</dd>

              <dt class="col-sm-3" v-if="order.customer.address">Address:</dt>
              <dd class="col-sm-9" v-if="order.customer.address">{{ order.customer.address }}</dd>


              <dt class="col-sm-3">Order Date:</dt>
              <dd class="col-sm-9">{{ order.created_at }}</dd>

              <dt class="col-sm-3">Order Status:</dt>
              <dd class="col-sm-9">

                 <span class="badge" v-if="order.status == 1">New</span>
                    <span class="badge" v-if="order.status == 2">Pending</span>

                    <span class="badge badge-success" v-if="order.status == 3"
                      >Approved</span
                    >
                    <span class="badge badge-success" v-if="order.status == 4"
                      >Shipment</span
                    >
                    <span class="badge badge-warning" v-if="order.status == 5"
                      >Delivered</span
                    >
                    <span class="badge badge-danger" v-if="order.status == 6"
                      >Cancel</span
                    >
                    <span class="badge badge-danger" v-if="order.status == 7"
                      >Return</span>
              </dd>
            </dl>

            <hr/>
            <table class="table table-bordered content">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Product</th>
                      <th>P_code</th>
                      <th>quntity</th>
                      <th>price</th>
                      <th>total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in items" :key="index">
                      <td>{{ index + 1 }}</td>
                      <td>
                        {{ item.product.name }}
                        <strong v-if="item.attribute"
                          >-{{ item.attribute.name }}[{{
                            item.variant.name
                          }}]</strong
                        >
                      </td>
                      <td>{{ item.product.product_code }}</td>
                      <td>{{ item.quantity }}</td>
                      <td>{{ item.price }}</td>
                      <td>{{ item.quantity * item.price }}</td>
                    </tr>

                    <tr>
                      <td colspan="4"></td>
                      <td>
                        <b>Sub Total</b>
                      </td>
                      <td>
                        <b>{{ order.total }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4"></td>
                      <td>
                        <b>Discount</b>
                      </td>
                      <td>
                        <b>{{ order.discount }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4"></td>
                      <td>
                        <b>Paid</b>
                      </td>
                      <td>
                        <b>{{ order.paid }}</b>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4"></td>
                      <td>
                        <b>Shiiping_cost</b>
                      </td>
                      <td>
                        <b>{{ order.shipping_cost }}</b>
                      </td>
                    </tr>

                    <tr>
                      <td colspan="4"></td>
                      <td>
                        <b>Amount Due</b>
                      </td>
                      <td>
                        <b>{{
                          parseInt(order.total) -
                          (parseInt(order.discount) + parseInt(order.paid)) +
                          parseInt(order.shipping_cost)
                        }}</b>
                      </td>
                    </tr>
                  </tbody>
                </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <frontend-footer></frontend-footer>
  </div>
</template>
<script>
export default {
  created() {
    this.getOrder();
  },
  data() {
    return {
      order: {},
      loading: true,
      items:""
    };
  },
  methods: {
    getOrder() {
      axios.get("/_public/customer/order/last").then((resp) => {
        console.log(resp);
        if (resp.data.status == "SUCCESS") {
          this.order = resp.data.order;
          this.items = resp.data.items;
          this.loading = false;
        }
      });
    },

  },

};
</script>




<style>
.empty-cart {
  width: 50%;
  background: #fff;
  text-align: center;
  margin-left: 25%;
  padding: 50px 50px;
  box-shadow: 3px 3px 3px #ddd;
}
.payment_option {
  background: #e6eaf2;
  padding: 15px 15px;
  text-align: center;
  font-size: 13px;
  border-radius: 5px;
  cursor: pointer;
  height: 70px;
  margin-top: 8px;
}
.payment_option h4 {
  font-size: 16px;
  font-weight: 600;
}
.payment_option_active {
  border: 1px solid #ff4d03;
}
.more{
      padding: 20px 50px;
    border-radius: 10px;
    overflow: auto;
}
@media only screen and (max-width: 500px) {
 .empty-cart {
    width: 70%;

    margin-left: 15%;
    padding: 35px 30px;

}
}
</style>