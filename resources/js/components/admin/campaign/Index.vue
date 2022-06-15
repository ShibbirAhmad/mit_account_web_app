 <template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'campaignAdd' }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Campaign</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-10 col-lg-offset-1">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row">
                    <div class="col-lg-6">
                      <h3 class="box-title">Campaign Table</h3>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Status</th>
                        <th scope="col">Link</th>
                        <th scope="col">Stard Date</th>
                        <th scope="col">End Date</th>

                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-for="(campaign, index) in campaigns.data"
                        :key="index"
                        v-else
                        :style="{'background-color':campaign.bg_color,color:campaign.text_color}"
                      >
                        <td scope="row">{{ index + 1 }}</td>
                        <td>{{ campaign.campaign_name }}</td>
                        <td>
                          <span
                            class="badge badge-success"
                            v-if="campaign.status == 1"
                          >
                            Active
                          </span>
                          <span class="badge badge-success" v-else>
                            De-Active
                          </span>
                        </td>

                        <td>
                          <a
                            :href="
                              'https://mohasagor.com/campaign/' +
                              campaign.id
                            "
                            target="__blank"
                            :style="{color:campaign.text_color}"
                          >
                            {{
                              "https://mohasagor.com/campaign/" +
                              campaign.id
                            }}
                          </a>
                        </td>

                        <td>
                          {{ campaign.start_date }}
                        </td>
                        <td>
                          {{ campaign.end_date }}
                        </td>

                        <td>
                          <router-link
                            :to="{
                              name: 'campaign_edit',
                              params: { id: campaign.id },
                            }"
                            class="btn btn-success btn-sm"
                            ><i class="fa fa-edit"></i
                          ></router-link>
                          <a
                            href="#"
                            @click.prevent="campaignProduct(campaign.id)"
                            class="btn btn-info"
                          >
                            <i class="fa fa-product-hunt"></i>
                          </a>

                          <a
                            href="#"
                            @click.prevent="trash(campaign.id, index)"
                            class="btn btn-danger"
                          >
                            <i class="fa fa-trash"></i>
                          </a>

                          <!-- <a class="btn btn-danger" @click="destroy(slider)"
                            ><i class="fa fa-trash"></i
                          ></a> -->

                          <a
                            class="btn btn-warning"
                            title="De-active"
                            @click.prevent="deActive(campaign)"
                            v-if="campaign.status == 1"
                            ><i class="fa fa-ban"></i
                          ></a>
                          <a
                            class="btn btn-primary"
                            title="active"
                            @click.prevent="active(campaign)"
                            v-else
                            ><i class="fa fa-check"></i
                          ></a>

                          <router-link :to="{name:'campaign_image',params:{id:campaign.id}}" href="" class="btn btn-sm btn-success">Image</router-link>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-lg-6">
                      <pagination
                        :data="campaigns"
                        @pagination-change-page="getCampaigns"
                      >
                      </pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing <strong>{{ campaigns.from }}</strong> to
                        <strong>{{ campaigns.to }}</strong> of total
                        <strong>{{ campaigns.total }}</strong> entries
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <modal name="example" :width="700" :height="400">
        <div class="card" style="padding: 20px">
          <div class="card-body">
            <div class="text-center">
              <h4 v-if="campaign.name">Campaign:{{ campaign.name }}</h4>
              <button class="btn btn-primary" @click="addProductToCampaign">
                <i class="fa fa-plus"></i>
              </button>
            </div>
            <hr />
            <div class="product-list">
              <li
                v-for="(campaign_product, cpidx) in campaign_products"
                :key="cpidx"
              >
                <strong> {{ campaign_product.product_code }}</strong>
                <span @click.prevent="removeProduct(campaign_product.id, cpidx)"
                  >x</span
                >
              </li>
            </div>
          </div>
        </div>
      </modal>
    </div>
  </div>
</template>

<script>

export default {
  created() {
    this.getCampaigns();
  },
  data() {
    return {
      campaigns: {},
      loading: true,
      search: "",
      basePath: this.$store.state.image_base_link,
      campaign_products: [],
      campaign: {},
    };
  },
  methods: {
    getCampaigns(page = 1) {
      axios
        .get("/api/campaign?page=" + page)
        .then((resp) => {
          console.log(resp);
          if (resp.data) {
            this.campaigns = resp.data;
            this.loading = false;
          } else {
            this.$toasted.show("some thing went wronggg", {
              type: "error",
              position: "top-center",
              duration: 5000,
            });
          }
        })
        .catch((error) => {
          console.log(error);
          this.$toasted.show("some thing want to wrong", {
            type: "error",
            position: "top-center",
            duration: 4000,
          });
        });
    },

    campaignProduct(campaign_id) {
      axios.get("/api/campaign/product/" + campaign_id).then((resp) => {
        console.log(resp);
        this.campaign_products = resp.data.products;
        this.campaign = resp.data.campaign;
      });
      this.$modal.show("example");
    },
    removeProduct(product_id, index) {
      if (confirm("are you sure remove this ?")) {
        axios
          .get("/api/campaign/product/remove/" + product_id, {
            params: {
              campaign_id: this.campaign.id,
            },
          })
          .then((resp) => {
            console.log(resp);
            if (resp.data.status == "OK") {
              this.$toasted.show(resp.data.message, {
                type: "success",
                position: "top-center",
                duration: 3000,
              });
              this.campaign_products.splice(index, 1);
            }
          });
      }
    },
    addProductToCampaign() {
      let product_code = prompt("Enter Product Code Or sku");
      if (product_code) {
        axios
          .get("/api/product/assign/to/campaign/" + product_code, {
            params: {
              campaign_id: this.campaign.id,
            },
          })
          .then((resp) => {
            if (resp.data.success == "OK") {
              this.$toasted.show(resp.data.message, {
                type: "success",
                position: "top-center",
                duration: 3000,
              });
              this.campaignProduct(this.campaign.id);
            } else {
              this.$toasted.show(resp.data.message, {
                type: "error",
                position: "top-center",
                duration: 3000,
              });
            }
          });
      }
    },
    trash(campaign_id, index) {
      if (confirm("are you sure remove this?")) {
        axios.get("/api/campaign/remove/" + campaign_id).then((resp) => {
          if (resp.data.success == "OK") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 3000,
            });
            this.campaigns.data.splice(index, 1);
          }
        });
      }
    },
    deActive(campaign) {
      Swal.fire({
        title: "Are you sure?",
        text: "You want de-active this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/api/deActive/campaign/" + campaign.id)
            .then((resp) => {
              console.log(resp);
              if (resp.data.status == "SUCCESS") {
                this.getCampaigns();
                this.$toasted.show(resp.data.message, {
                  position: "top-center",
                  type: "success",
                  duration: 4000,
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
              this.$toasted.show("some thing want to wrong", {
                position: "top-center",
                type: "error",
                duration: 4000,
              });
            });
        } else {
          this.$toasted.show("Ok ! no action here", {
            position: "top-center",
            type: "info",
            duration: 3000,
          });
        }
      });
    },
    active(slider) {
      Swal.fire({
        title: "Are you sure?",
        text: "You want to active this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/api/active/campaign/" + slider.id)
            .then((resp) => {
              console.log("active");
              console.log(resp);
              if (resp.data.status == "SUCCESS") {
                this.getCampaigns();

                this.$toasted.show(resp.data.message, {
                  position: "top-center",
                  type: "success",
                  duration: 4000,
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
              this.$toasted.show("some thing want to wrong", {
                position: "top-center",
                type: "error",
                duration: 4000,
              });
            });
        } else {
          this.$toasted.show("Ok ! no action here", {
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

<style>
.product-list {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  max-height: 350px;
  overflow-x: scroll;
}

.product-list li {
  list-style: none;
  padding: 2px 15px;
  background: #000;
  color: #fff;
  margin-right: 11px;
  border-radius: 5px;
  height: 25px;
  font-size: 14px;
  position: relative;

  width: 200px;
  margin-bottom: 20px;

  /* width: 127px; */
}

.product-list li span {
  background: red;
  position: absolute;
  right: 0;
  width: 20%;
  text-align: center;
  height: 100%;
  top: 0;
  border-radius: 5px;
  cursor: pointer;
}
.product-list li strong {
  margin-left: -9px;
}
</style>
