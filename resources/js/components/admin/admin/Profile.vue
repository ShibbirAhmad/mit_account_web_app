<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'admin' }" class="btn btn-primary">
            <i class="fa fa-arrow-right"></i>
          </router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"> <i class="fa fa-dashboard"></i>Dashboard </a>
          </li>
          <li class="active">Category</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div  :class="{'col-lg-8' : editMode,'col-lg-offset-3' : !editMode, 'col-lg-4' : !editMode,'col-lg-offset-2': editMode}">
            <!-- Profile Image -->
            <div class="box box-primary">
              <div class="box-body box-profile">
                <img
                  class="profile-user-img img-responsive img-circle"
                  :src="file"
                  alt="User profile picture"
                />

                <h3 class="profile-username text-center" v-if="form.name">
                  {{ form.name }}
                </h3>
                <h3 class="profile-username text-center" v-else>.......</h3>

                <p class="text-muted text-center">Admin</p>

                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <b>Name</b>
                    <div v-if="!editMode">
                      <a
                        class="pull-right text-bold text-black"
                        v-if="form.name"
                        style="margin-top: -18px"
                        >{{ form.name }}</a
                      >
                      <a class="pull-right" v-else>....</a>
                    </div>
                    <div v-else>
                      <input
                        type="text"
                        class="form-control"
                        v-model="form.name"
                        name="name"
                        :class="{ 'is-invalid': form.errors.has('name') }"
                      />
                      <has-error :form="form" field="name"></has-error>
                    </div>
                  </li>
                  <li class="list-group-item">
                    <b>Email</b>
                    <div v-if="!editMode">
                      <a
                        class="pull-right text-bold text-black"
                        v-if="form.email"
                        style="margin-top: -18px"
                        >{{ form.email }}</a
                      >
                      <a class="pull-right" v-else>....</a>
                    </div>
                    <div v-else>
                      <input
                        type="email"
                        class="form-control"
                        v-model="form.email"
                        name="email"
                        :class="{ 'is-invalid': form.errors.has('email') }"
                      />
                      <has-error :form="form" field="email"></has-error>

                      <br />
                      <b>Upload Image</b>
                      <div class="form-group">
                        <input
                          type="file"
                          name="image"
                          class="form-control"
                          @change="uploadImage"
                        />
                      </div>
                    </div>
                  </li>
                  <li style="list-style:none;" v-if="editMode">
                    <div
                    class="form-group"
                    id="signatur-pad"
                  >
                  <div v-if="old_signature_preview" style="text-align:center;">

                      <img  v-if="old_signature.length>0" :src="base_link+old_signature" alt="">
                      <button class="btn" @click="old_signature_preview=false">Change</button>
                  </div>
                   <div  style="height:300px;" v-else>
                      <label for="">Signature</label>

                    <sig
                      ref="signature"
                      style="margin-bottom: 5px"
                      :class="{ 'is-invalid': form.errors.has('signature') }"
                      id="sig"
                      class="bg-warning"
                      :sigOption="option"
                      :disabled="disabled"
                     
                    >
                    </sig>

                    <button type="button" @click.prevent="clear" style="position: absolute;
                    right: 10px" class="m-1">
                      clear
                    </button>
                      <button @click="save" type="button" style="position: absolute;
                    right: 60px" class="m-1">
                      Perfect
                    </button>
                          <button @click="checkOldSignature" type="button" style="position: absolute;" class="m-1">
                      Old
                    </button>
                   </div>
                  </div>
                  </li>
                </ul>

                <div class="row">
                  <div :class="editMode ? 'col-lg-8' : 'col-lg-12'">
                    <a
                      href="#"
                      class="btn btn-primary btn-block"
                      @click.prevent="edit"
                    >
                      <b>{{ actionText }}</b>
                    </a>
                  </div>
                  <div class="col-lg-4" v-if="editMode">
                    <a
                      href="#"
                      class="btn btn-danger btn-block"
                      @click.prevent="(editMode = false), (actionText = 'Edit')"
                    >
                      <b>cancel</b>
                    </a>
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
import Vue from "vue";
import { Form, HasError, AlertError } from "vform";
import sig from "vue-signature";

Vue.component(HasError.name, HasError);
export default {
  name: "Profile",
  created() {
    this.getAdmin();
    setTimeout(() => {
      this.loading = false;
    }, 500);
  },
  data() {
    return {
      form: new Form({
        name: "",
        email: "",
        image: "",
        id: "",
        signature_write: false,
        signature:"",
      }),

      old_signature:"",
      old_signature_preview:true,
      
      loading: true,
      error: "",

      actionText: "Edit",
      editMode: false,
      file: this.$store.state.image_base_link+"images/static/user2-160x160.jpg",
      //signature pad.......
      option: {
        penColor: "rgb(0, 0, 0)",
        backgroundColor: "yellow",
      },
      disabled: false,
      base_link:this.$store.state.image_base_link
    };
  },

  methods: {

    updateProfile() {
      console.log("add");
      this.form
        .post("/update/admin/" + this.form.id, {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp)
          if (resp.data.status == "SUCCESS") {
            this.$toasted.show("Your profile was updated.", {
              type: "success",
              position: "top-right",
              duration: 1000,
            });
            this.editMode = false;
          this.$router.push({ name: "adminLogin" });
         
          }
        })
        .catch((error) => {
          console.log(error);
          this.error = "some thing want to wrong";
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
          if (img.width < 360 && img.height < 360) {
            this.form.image = file;
            this.file = evt.target.result;
            return;
          } else {
            this.disabled = true;
            alert(
              "Image maximu size 360*360px.But Upload imaze size" +
                img.width +
                "*" +
                img.height +
                "px"
            );
            return;
          }
        };
        img.src = evt.target.result;
      };
    },

    getAdmin() {
      axios.get("/single/admin").then((resp) => {
        this.form.name = resp.data.admin.name;
        this.form.email = resp.data.admin.email;
        this.form.id = resp.data.admin.id;
        if (resp.data.admin.image != null) {
          this.file =this.$store.state.image_base_link+resp.data.admin.image;
        }

        if(resp.data.admin.signature){
          this.old_signature=resp.data.admin.signature;
          this.old_signature_preview=true;
        }else{
          this.old_signature_preview=false;
        }
      });
    },

    edit() {
      if (this.editMode == true) {
        this.updateProfile();
      } else {
        this.editMode = true;
        this.actionText = "Update";
      }
    },
    clear() {
      var _this = this;
      _this.$refs.signature.clear();
      this.form.signature_write=false;
      this.form.signature="";
     
    },
     save() {
      var _this = this;
      var png = _this.$refs.signature.save();
      this.form.signature = png;
    },
    checkOldSignature(){
      if(this.old_signature.length<=0){
        alert("You Did Not Write Any Signaure Of Our Records");
        return;
      }else{
        this.old_signature_preview=true;
      }
    }
    
  },
  components:{
      sig
    }
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}

#signatur-pad{
    height: 300px;
    margin-bottom: 80px;
    margin-top: 15px;
}
</style>
