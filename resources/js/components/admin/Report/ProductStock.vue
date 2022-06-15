<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <button @click="goBack" class="btn btn-primary">
            <i class="fa fa-arrow-left"></i>
          </button>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">Product Stock</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-11">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row">
                    <div class="col-lg-2">
                      <select
                        class="form-control"
                        v-model="item"
                        @change="productList()"
                      >
                        <option value="20">20</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="80">80</option>
                        <option value="150">150</option>
                        <option value="200">200</option>
                        <option value="500">500</option>
                      </select>
                    </div>
                    <div class="col-lg-4">
                      <button @click="downloadAllCategoriesPDF" class="btn btn-sm btn-success">
                        <i class="fa fa-download"></i> All Categories PDF
                      </button>

                      <button @click="downloadCategoryWisePDF" class="btn btn-sm btn-success">
                        <i class="fa fa-download"></i> Selected Category Wise PDF
                      </button>

                    </div>

                    <div class="col-lg-3 orders-heading">
                      <h3 class="box-title">STOCK REPORT OF PRODUCTS</h3>
                    </div>
                    <div class="col-lg-3">
                      <input
                        class="form-control"
                        placeholder="search with product code || product name "
                        v-model="search"
                        @keyup="searchProducts()"
                      />
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <br />
                  <div class="row">
                    <div class="col-lg-4 col-md-4">
                      <select
                        class="form-control"
                        name="category"
                        v-model="category_id"
                        @change="categoryWiseReport"
                      >
                        <option value="">select category</option>
                        <option
                          v-for="category in categories"
                          :value="category.id"
                          :key="category.id"
                        >
                          {{ category.name }}
                        </option>
                      </select>
                    </div>

                    <div class="col-lg-4 col-md-4">
                      <select
                        @change="subCategoryWiseReport"
                        class="form-control"
                        name="category"
                        v-model="sub_category_id"
                      >
                        <option value="">select sub category</option>
                        <option
                          v-for="s_item in sub_categories"
                          :value="s_item.id"
                          :key="s_item.id"
                        >
                          {{ s_item.name }}
                        </option>
                      </select>
                    </div>

                    <div class="col-lg-4 col-md-4">
                      <select
                        @change="subSubCategoryWiseReport"
                        class="form-control"
                        name="category"
                        v-model="sub_sub_category_id"
                      >
                        <option value="">select sub sub category</option>
                        <option
                          v-for="s_s_item in sub_sub_categories"
                          :value="s_s_item.id"
                          :key="s_s_item.id"
                        >
                          {{ s_s_item.name }}
                        </option>
                      </select>
                    </div>
                  </div>
                  <br />
                  <br />

                  <div class="row">
                    <div v-if="category_id" class="col-lg-4 col-md-4 category_wise_stock">
                      <h4>Stock of Categories</h4>
                      <ul  v-if="categories">
                        <li
                          v-if="category_id == item.id"
                          v-for="item in categories"
                          :key="item.id"
                        >
                          {{ item.name }} --- Qty :
                          {{ numberFormatter(item.total_stock) }} Amount :
                          {{ numberFormatter(item.total_amount) }}
                        </li>
                      </ul>
                    </div>

                    <div  v-if="sub_categories" class="col-lg-4 col-md-4 category_wise_stock">
                      <h4>Stock of Sub Categories</h4>
                      <ul>
                        <li v-for="item in sub_categories" :key="item.id">
                          {{ item.name }} --- Qty :
                          {{ numberFormatter(item.total_stock) }} Amount :
                          {{ numberFormatter(item.total_amount) }}
                        </li>
                      </ul>
                    </div>

                    <div v-if="sub_sub_categories" class="col-lg-4 col-md-4 category_wise_stock">
                      <h4>Stock of Sub Sub Categories</h4>
                      <ul >
                        <li v-for="item in sub_sub_categories" :key="item.id">
                          {{ item.name }} --- Qty :
                          {{ numberFormatter(item.total_stock) }} Amount :
                          {{ numberFormatter(item.total_amount) }}
                        </li>
                      </ul>
                    </div>
                  </div>

                  <table class="table table-bordered table-hover table-striped">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Product</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Total Purchase quantity</th>
                        <th scope="col">Average purchase price</th>
                        <th scope="col">Total purchase Price</th>
                        <th scope="col">Current Stock Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-for="(product, index) in products.data"
                        :key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>
                          {{ product.name + "-" + product.product_code }}
                        </td>
                        <td>
                          {{ product.stock }}
                        </td>
                        <td>
                          {{ totalPurchaseQuantity(product.purchase_item) }}
                        </td>
                        <td>
                          {{ averagePrice(product.purchase_item) }}
                        </td>
                        <td>
                          {{ totalPrice(product.purchase_item) }}
                        </td>
                        <td>
                          {{
                            currentStockPrice(
                              product.stock,
                              product.purchase_item
                            )
                          }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-lg-6">
                      <pagination
                        :data="products"
                        @pagination-change-page="productList"
                        :limit="5"
                      ></pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing
                        <strong>{{ products.from }}</strong> to
                        <strong>{{ products.to }}</strong> of total
                        <strong>{{ products.total }}</strong> entries
                      </p>
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
  created() {
    this.productList();
  },
  data() {
    return {
      products: {},
      categories: "",
      sub_categories: "",
      sub_sub_categories: "",
      category_id: "",
      sub_category_id: "",
      sub_sub_category_id: "",
      category_type: "",
      loading: true,
      search: "",
      item: 50,
    };
  },
  methods: {


    productList(page = 1) {
      axios
        .get("/api/product/stock?page=" + page, {
          params: {
            item: this.item,
            category_id: this.category_id,
            sub_category_id: this.sub_category_id,
            sub_sub_category_id: this.sub_sub_category_id,
            category_type: this.category_type,
          },
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data) {
            this.categories = resp.data.categories;
            this.sub_categories = resp.data.sub_categories;
            this.sub_sub_categories = resp.data.sub_sub_categories;
            this.products = resp.data.products;
            this.loading = false;
          }
        });
    },

    numberFormatter(x) {
      return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
    },

    categoryWiseReport() {
      this.loading = true;
      this.sub_category_id = "";
      this.sub_sub_category_id = "";
      this.category_type = "category";
      this.productList();
    },

    subCategoryWiseReport() {
      this.loading = true;
      this.category_type = "sub_category";
      this.productList();
    },

    subSubCategoryWiseReport() {
      this.loading = true;
      this.category_type = "sub_sub_category";
      this.productList();
    },

    goBack() {
      return window.history.back();
    },

    searchProducts() {
      if (this.search.length > 1) {
        axios
          .get("/api/search/seggested/product/with/name/code/" + this.search)
          .then((resp) => {
            console.log(resp);
            if (resp.data) {
              console.log(resp.data);
              this.products = resp.data;
            } else {
              this.productList();
            }
          });
      } else {
        this.productList();
      }
    },

    downloadAllCategoriesPDF() {
      window.open("/api/stock/report/product/all/categories/pdf", "_blank");
    },

    downloadCategoryWisePDF() {
      window.open("/api/stock/report/product/definite/category/pdf/"+this.category_id, "_blank");
    },


    totalPurchaseQuantity(items) {
      let total = 0;
      items.forEach((element) => {
        total += parseInt(element.stock);
      });
      return total;
    },

    averagePrice(items) {
      let total = 0;
      let total_stock = 0;
      if (items.length > 0) {
        items.forEach((element) => {
          total += parseInt(element.price) * parseInt(element.stock);
          total_stock += parseInt(element.stock);
        });
      }

      let a_price = parseFloat(total) / parseFloat(total_stock);
      return a_price.toFixed(2);
    },

    totalPrice(items) {
      let total = 0;
      if (items.length > 0) {
        items.forEach((element) => {
          total += parseInt(element.price) * parseInt(element.stock);
        });
      }
      return parseFloat(total);
    },

    currentStockPrice(stock, items) {
      let total = 0;
      let total_stock = 0;
      if (items.length > 0) {
        items.forEach((element) => {
          total += parseInt(element.price) * parseInt(element.stock);
          total_stock += parseInt(element.stock);
        });
      }

      let a_price = total / total_stock;

      return `${stock}*${a_price.toFixed(2)}=${(
        parseFloat(stock) * parseFloat(a_price.toFixed(2))
      ).toFixed(0)}`;
    },
  },
};
</script>

<style scoped>
.category_wise_stock h4 {
  padding-left: 12%;
}

.category_wise_stock ul li {
  padding: 10px 0;
  width: 100%;
  display: flex;
  border-bottom: 1px solid rgba(0, 0, 0, 0.08);
  transition: all 0.2s ease;
}
</style>
