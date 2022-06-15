<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'product_transfer_add' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Credit</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-11">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row">
                    <div class="col-lg-2">
                      <h3 class="box-title">Product Transfer Table</h3>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th scope="col">#</th>

                        <th scope="col">From Product</th>
                        <th scope="col">To Product</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Comment</th>
                        <th scope="col">Creator</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-for="(item, index) in stockTransfer.data"
                        :key="index"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td >{{ item.from_product ? item.from_product.name+'-'+item.from_product.product_code : ""   }}</td>
                        <td >{{ item.to_product ? item.to_product.name +'-'+item.to_product.product_code: ""   }}</td>
                        <td >{{ item.qauntity }}</td>
                        <td >{{ item.comment || "" }}</td>
                        <td>
                          {{ item.creator ? item.creator.name : ""  }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-lg-6">
                      <pagination
                        :data="stockTransfer"
                        @pagination-change-page="stockTransferList"
                        :limit="3"
                      >
                      </pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing <strong>{{ stockTransfer.from }}</strong> to
                        <strong>{{ stockTransfer.to }}</strong> of total
                        <strong>{{ stockTransfer.total }}</strong> entries
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
    this.stockTransferList();

    // this.getResults();
  },
  data() {
    return {
      stockTransfer: {},
      loading: true,
      current_date: "",
      item: 20,
      search: "",
      status: "all",

      credit_in: "",
      //for date picker
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },

      start_date: "",
      end_date: "",
    };
  },
  methods: {
    stockTransferList(page = 1) {
      this.loading = true;
      axios
        .get("/api/product/stock/transfer?page=" + page, {
          params: {
            item: this.item,
          },
        })
        .then((resp) => {
          this.stockTransfer = resp.data;
          this.loading = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    trash(creditId, index) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't delete this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/credit/destroy/" + creditId)
            .then((resp) => {
              console.log(resp);
              if (resp.data.status == "SUCCESS") {
                this.credits.data.splice(index, 1);
                this.$toasted.show(resp.data.message, {
                  position: "top-center",
                  type: "success",
                  duration: 200,
                });
              } else {
                this.$toasted.show("some thing want to wrong", {
                  position: "top-center",
                  type: "error",
                  duration: 4000,
                });
              }
            })
            .catch((error) => {
              // console.log(error)
              this.$toasted.show("some thing want to wrong", {
                position: "top-center",
                type: "error",
                duration: 4000,
              });
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
  },
};
</script>

<style scoped>
</style>
