<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'team_member' }" class="btn btn-primary"
            ><i class="fa fa-arrow-right"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Team Members</a>
          </li>
          <li class="active">Add</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Employee</h3>
              </div>
              <div class="box-body">
                <h1 v-if="loading"><i class="fa fa-spinner fa-spin"></i></h1>
                <form
                  v-else
                  @submit.prevent="addMember"
                  enctype="multipart/form-data"
                >
                  <ul class="list-group" v-if="errors">
                    <li
                      class="list-group-item"
                      v-for="(error, index) in errors"
                      :key="index"
                    >
                      {{ error.name }}
                    </li>
                  </ul>

                  <div class="form-group">
                    <label>Joining Date</label>

                    <input
                      class="form-control"
                      type="date"
                      v-model="form.joining_date"
                    />
                  </div>

                  <div class="form-group">
                    <label>Name</label>

                    <input
                      v-model="form.name"
                      type="text"
                      name="name"
                      class="form-control"
                      required
                      placeholder="name"
                    />
                  </div>

                  <div class="form-group">
                    <label>Email</label>
                    <input
                      v-model="form.email"
                      type="email"
                      name="email"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('email') }"
                      placeholder="email"
                    />
                    <has-error :form="form" field="email"></has-error>
                  </div>

                  <div class="form-group">
                    <label for="phone">Phone</label>
                    <input
                      type="text"
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('phone') }"
                      autocomplete="off"
                      autofocus
                      v-model="form.phone"
                      name="phone"
                    />
                    <has-error :form="form" field="phone"> </has-error>
                  </div>

                  <div class="form-group">
                    <label for="designation">Designation</label>

                    <input
                      type="text"
                      class="form-control"
                      v-model="form.designation"
                    />
                  </div>
                  <div v-show="preview_image" class="form-group  text-center">
                    <img
                      :src="preview_image"
                      style="width:200px;height:210px"
                      class="image-responsive"
                    />
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
                  <br />
                  <div class="form-group">
                    <label>CV/Resume (doc,pdf or zip)</label>
                    <input
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('resume') }"
                      type="file"
                      @change="uploadResume"
                      name="resume"
                    />
                    <has-error :form="form" field="resume"></has-error>
                  </div>
                  <br />
                  <button
                    :disabled="form.busy"
                    type="submit"
                    class="btn btn-primary"
                  >
                    <i class="fa fa-spin fa-spinner" v-if="form.busy"></i>Submit
                  </button>
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
  created() {
    setTimeout(() => {
      this.loading = false;
    }, 500);
  },
  data() {
    return {
      form: new Form({
        name: "",
        designation: "",
        email: "",
        phone: "",
        image: "",
        resume: "",
        joining_date: "",
      }),

      loading: true,
      errors: [],
      preview_image: "",
    };
  },

  methods: {
    addMember() {
      this.form
        .post("/team/members/add", {
          transformRequest: [
            function(data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
            this.$router.push({ name: "team_member" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-center",
              duration: 4000,
            });
          }
        })
        .catch((e) => {
          this.errors = [];
          this.errors.push(e.response.data.errors);
        });
    },

    uploadImage(e) {
      const file = e.target.files[0];

      ///let image file size check
      let reader = new FileReader();

      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();

        img.onload = () => {
          console.log(img.width + "-" + img.height);
        };

        img.src = evt.target.result;
        this.preview_image = evt.target.result;
        this.form.image = file;
      };
    },

    uploadResume(e) {
      const file = e.target.files[0];
      this.form.resume = file;
    },
  },
  computed: {},
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
