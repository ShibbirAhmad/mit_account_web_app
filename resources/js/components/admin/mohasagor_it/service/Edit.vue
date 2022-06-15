<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'services'}" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">  Service </li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add  Service </h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="serviceUpdate"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>

                    <div class="form-group">
                        <label>Service Name</label>
                        <input
                          v-model="form.name"
                          type="text"
                          required
                          name="name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('name') }"
                          placeholder="Example"
                        />
                        <has-error :form="form" field="name"></has-error>
                     </div>

                  <div class="form-group">
                    <label for="details">Details</label>

                    <textarea placeholder="write title about this merchant"
                      v-model="form.details"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('details') }"
                      name="details"
                      rows="3"
                    ></textarea>

                    <has-error :form="form" field="details"> </has-error>
                  </div>


                  <div class="form-group">
                    <label>Image</label>
                    <input
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('image') }"
                      type="file"
                      @change="uploadImage"
                      name="image"
                    />
                    <has-error :form="form" field="image"></has-error>
                  </div>

                  <div class="form-group text-center">
                    <button
                      :disabled="form.busy"
                      type="submit"
                      class="btn btn-primary"
                    >
                      <i class="fa fa-spin fa-spinner" v-if="form.busy"></i
                      >Submit
                    </button>
                  </div>
                </form>
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
  created(){
      this.getService();
  },
  data() {
    return {
      form: new Form({
        name: "",
        details: "",
        image: "",
      }),
      error: "",
    };
  },

  methods: {

    getService() {
      this.form
        .post("/api/service/get/"+this.$route.params.id)
        .then((resp) => {
           this.form.name= resp.data.service.name;
           this.form.details= resp.data.service.details;
        })

    },

    serviceUpdate() {
      this.form
        .post("/api/service/update/"+this.$route.params.id)
        .then((resp) => {
          //console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({name:"services"});
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          } else {
            this.error = "something went to wrong";
          }
        })
        .catch((error) => {
          console.log(error);
          this.error = "something went to wrong";
        });
    },
    uploadImage(e) {
      const file = e.target.files[0];
       this.form.image = file;
    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
