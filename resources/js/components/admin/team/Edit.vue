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
            <a href="#"><i class="fa fa-dashboard"></i>member</a>
          </li>
          <li class="active">Edit</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Edit Information</h3>
              </div>
              <div class="box-body">
                <h1 v-if="loading"><i class="fa fa-spinner fa-spin"></i></h1>
                <form
                  v-else
                  @submit.prevent="updateMember"
     
                  enctype="multipart/form-data"
                >
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
                      autofocus
                      autocomplete="off"
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

                  <div class="form-group text-center">
                    <img
                      :src="
                        form.image
                          ? base_url + form.image
                          : base_url + 'images/no_image.jpg'
                      "
                      style="width: 200px; height: 200px"
                      id="preview_image"
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

                 <div class="form-group text-center">
                  <button :disabled="form.busy" type="submit" class="btn btn-primary">
                    <i class="fa fa-spin fa-spinner" v-if="form.busy"></i>Update
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
  created() {
    setTimeout(() => {
      this.loading = false;
    }, 500);

    this.getMember();
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
      options: {
        format: "DD-MM-YYYY",
        useCurrent: false,
      },
      loading: true,
      base_url: this.$store.state.image_base_link,
    };
  },

  methods: {
    updateMember() {
      this.form
        .post("/team/members/update/" + this.$route.params.id, {
          transformRequest: [
            function (data, headers) {
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

    getMember() {
      axios.get("/team/members/edit/" + this.$route.params.id).then((resp) => {
        if (resp.data.success == "OK") {
          
          console.log(resp);
          this.form.name = resp.data.member.name;
          this.form.email = resp.data.member.email;
          this.form.phone = resp.data.member.phone;
          this.form.joining_date = resp.data.member.joining_date;
          this.form.designation = resp.data.member.designation;
          this.form.image = resp.data.member.avator;
          console.log(resp.data.member.basic_salary)
      
        }
      });
    },

    uploadImage(e) {
      const file = e.target.files[0];
      this.form.image = file;
      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();
        img.onload = () => {
          console.log(img.width + "-" + img.height);
        };
        document.getElementById("preview_image").src = evt.target.result;
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
