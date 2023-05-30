<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'boost_agency' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active"> boost </li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Edit  Agency </h3>
              </div>
              <div class="box-body">
                <form
                  @submit.prevent="updateData"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>
                    <div class="form-group">
                        <label>Company Name</label>
                        <input
                          v-model="form.name"
                          type="text"
                          required
                          class="form-control"

                        />
                      </div>

                  <div class="form-group">
                    <label>Mobile Number</label>

                    <input
                      v-model="form.phone"
                      type="text"
                      name="phone"
                      class="form-control"
                      :class="{
                        'is-invalid': form.errors.has('phone'),
                      }"
                      autocomplete="off"
                      placeholder="01xxxxxxxxx"
                    />
                    <has-error :form="form" field="phone"></has-error>
                  </div>

                  <div class="form-group">
                    <label>BDT (rate) </label>
                    <input
                      v-model="form.rate"
                      type="text"
                      class="form-control"
                      required
                    />
                  </div>

                  <div class="form-group text-center">
                    <button
                      :disabled="form.busy"
                      type="submit"
                      class="btn btn-primary"
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

Vue.component(HasError.name, HasError);
export default {
  created(){
    this.getData();
  },
  data() {
    return {
      form: new Form({
        name: "",
        phone: "",
        rate: "",
      }),
      error: "",
    };
  },

  methods: {

    getData(){

      axios.get("/api/boost/agency/edit/"+this.$route.params.id)
      .then(resp=>{
        console.log(resp);
         this.form.name = resp.data.agency.name ;
         this.form.phone = resp.data.agency.phone ;
         this.form.rate = resp.data.agency.rate ;
      })

     
    },

   async updateData() {
    await  this.form
        .post("/api/boost/agency/update/"+this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "boost_agency" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          }
        })
        .catch((error) => {
            this.$toasted.show(error.response.data.message, {
              type: "error",
              position: "top-right",
              duration: 4000,
            });
        });
    },
  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
