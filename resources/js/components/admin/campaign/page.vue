 <template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <a @click.prevent="openModal" class="btn btn-primary"
            ><i class="fa fa-plus"></i
          ></a>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Campaign Page</li>
        </ol>
      </section>
      <section class="content">
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-lg-8 col-lg-offset-1">
              <div class="box box-primary">
                <div class="box-header with-border">
                  <div class="row">
                    <div class="col-lg-10">
                      <h3 class="box-title">Campaign Table</h3>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th scope="col">#</th>
                        <th scope="col">Link</th>
                        <th scope="col">Image</th>
                        <th scope="col">Position</th>
                        <th scope="col">Order</th>
                        <th scope="col">Status</th>
                        <th scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr
                        v-for="(campaign, idx) in campaigns"
                        :key="idx"
                        v-else
                      >
                        <td scope="row">{{ idx + 1 }}</td>
                        <td>{{ campaign.link }}</td>
                        <td>
                          <img
                            style="max-height: 100px"
                            :src="$image_base_link_1(campaign.image)"
                          />
                        </td>
                        <td>
                          <span v-if="campaign.position == 1" class="badge"
                            >as a slider</span
                          >
                          <span v-else class="badge">as a image</span>
                        </td>
                        <td>
                          {{ campaign.order }}
                        </td>

                        <td>
                          <span v-if="campaign.status == 1" class="badge"
                            >Active</span
                          >
                          <span v-else class="badge">De-active</span>
                        </td>
                        <td>
                          <a
                            href="#"
                            class="btn btn-success btn-sm"
                            @click.prevent="edit(idx)"
                          >
                            <i class="fa fa-edit"></i>
                          </a>

                          <a
                            href="#"
                            class="btn btn-danger btn-sm"
                            @click.prevent="trash(campaign.id)"
                          >
                            <i class="fa fa-trash"></i>
                          </a>

                          <a
                            class="btn btn-warning"
                            title="De-active"
                            @click="deActive(campaign)"
                            v-if="campaign.status == 1"
                            ><i class="fa fa-ban"></i
                          ></a>

                          <a
                            class="btn btn-primary"
                            title="active"
                            @click="active(campaign)"
                            v-else
                            ><i class="fa fa-check"></i
                          ></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <modal name="example" :width="700" :height="480">
        <div class="card" style="padding: 20px">
          <div class="card-body">
            <form @submit.prevent="handleAction">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="">Uplaod Image</label>
                    <input
                      type="file"
                      class="form-control"
                      @change="uploadImage"
                    />
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="">Link</label>
                    <input
                      v-model="form.link"
                      type="text"
                      class="form-control"
                    />
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="">Position On Campaign Page</label>
                    <select class="form-control" v-model="form.position">
                      <option value="1">As a slider</option>
                      <option value="2">As a campaign image</option>
                    </select>
                  </div>
                </div>

                <div class="col-lg-12" v-if="form.position == 2">
                  <div class="form-group">
                    <label for="">Image Width</label>
                    <select class="form-control" v-model="form.image_width">
                      <option value="50%">50%</option>
                      <option value="100%">100%</option>
                    </select>
                  </div>
                </div>

                <div class="col-lg-12">
                  <div class="form-group">
                    <label for="">Order</label>
                    <input
                      v-model="form.order"
                      type="number"
                      class="form-control"
                    />
                  </div>
                  <button class="btn btn-primary">Submit</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </modal>
    </div>
  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";

export default {
  created() {
    this.getCampaigns();
  },
  data() {
    return {
      campaigns: [],
      loading: true,
      form: new Form({
        image: "",
        link: "",
        order: "",
        position: 1,
        image_width: "50%",
      }),
      editModa: false,
      campaign_id: "",
    };
  },
  methods: {
    getCampaigns() {
      axios
        .get("/api/campaignpage")
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

    openModal() {
      this.$modal.show("example");
    },

    uploadImage(e) {
      this.form.image = e.target.files[0];
    },
    storeData() {
      this.form
        .post("/api/campaignpage", {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.getCampaigns();
            this.$modal.hide("example");
          }
          console.log(resp);
        });
    },
    edit(index) {
      let campaign = this.campaigns[index];
      this.form.link = campaign.link;
      this.form.order = campaign.order;
      this.form.position = campaign.position;
      this.editModa = true;
      this.campaign_id = campaign.id;
      this.$modal.show("example");

      console.log(campaign);
    },
    trash(campaign_id, index) {
      if (confirm("are you sure remove this one?")) {
        axios.delete("/api/campaignpage/" + campaign_id).then((resp) => {
          if (resp.data.success == "OK") {
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
            this.campaigns.splice(index, 1);
          }
        });
      }
    },
    update() {
      this.form.put("/api/campaignpage/" + this.campaign_id).then((resp) => {
        if (resp.data.success == "OK") {
          this.$toasted.show(resp.data.message, {
            type: "success",
            position: "top-center",
            duration: 4000,
          });
          this.getCampaigns();
          this.$modal.hide("example");
        }
      });
    },
    handleAction() {
      if (this.editModa) {
        this.update();
      } else {
        this.storeData();
      }
    },

    active(campaign) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't active this!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/api/campaignpage/active/" + campaign.id)
            .then((resp) => {
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
    deActive(campaign) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't de-active this!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/api/campaignpage/deActive/" + campaign.id)
            .then((resp) => {
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
    destroy(slider) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't delete this slider!",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes!",
      }).then((result) => {
        if (result.value) {
          axios
            .get("/api/category/delete/slider/" + slider.id)
            .then((resp) => {
              //console.log(resp)
              if (resp.data.status == "SUCCESS") {
                this.getSlider();
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
              console.log(error);
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

<style >
</style>
