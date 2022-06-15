<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'supplier_note',params:{id:$route.params.id} }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Note</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Add Note</h3>
              </div>
              <div class="box-body">
                
                <form
         
                  @submit.prevent="add"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>

                  <div class="form-group">
                    <ckeditor
                    :editor="editor"
                    name="details"
                    :class="{ 'is-invalid': form.errors.has('details') }"
                    v-model="form.text"
                    :config="editorConfig"
                  ></ckeditor> 
                  
                    <has-error :form="form" field="text"></has-error>
                  </div>

                  <div class="form-group">
                    <label>Attachment(Image,doc,pdf)</label>
                    <input
                      class="form-control"
                      :class="{ 'is-invalid': form.errors.has('attachment') }"
                      type="file"
                      @change="uploadAttachment"
                      name="attachment"
                    />
                    <has-error :form="form" field="attachment"></has-error>
                  </div>

                  <div class="form-group text-center">
                    <button
                      :disabled="form.busy"
                      type="submit"
                      class="btn btn-sm btn-primary"
                    >
                      <i class="fa fa-spin fa-spinner" v-if="form.busy"></i
                      >Save
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
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";


Vue.component(HasError.name, HasError);
export default {
  name: "Add",
  created() {
  
  },
  data() {
    return {
      form: new Form({
        supplier_id:this.$route.params.id,
        text: "write details here..",
        attachment:"",
      }),
      error: "",
      editor: ClassicEditor,
      editorConfig: {},
    };
  },

  methods: {
    add() {
      this.form
        .post("/api/store/new/note/"+this.$route.params.id, {
          transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
        })
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
            this.$router.push({ name: "supplier_note",params:{id:this.$route.params.id} });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          } else {
            this.error = "some thing want to wrong";
          }
        })
        .catch((error) => {
          console.log(error);
          this.error = "some thing want to wrong";
        });
    },
     uploadAttachment(e){
      const file = e.target.files[0] ;
      this.form.attachment = file ;
    }
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
