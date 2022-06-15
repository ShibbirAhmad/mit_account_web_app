<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'investment' }" class="btn btn-primary"
            ><i class="fa fa-arrow-left"></i
          ></router-link>
        </h1>
        <ol class="breadcrumb">
          <li>
            <a href="#"><i class="fa fa-dashboard"></i>Dashboard</a>
          </li>
          <li class="active">Investor</li>
        </ol>
      </section>
      <section class="content">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-lg-offset-2">
            <div class="box box-primary">
              <div class="box-header with-border text-center">
                <h3 class="box-title">Edit Investor Info. </h3>
              </div>
              <div class="box-body">
                <h1 v-if="loading"><i class="fa fa-spinner fa-spin"></i></h1>
                <form
                  v-else
                  @submit.prevent="updateInvestor"
                  @keydown="form.onKeydown($event)"
                  enctype="multipart/form-data"
                >
                  <div class="alert-danger alert" v-if="error">
                    {{ error }}
                  </div>


                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Name</label>

                        <input
                          v-model="form.name"
                          type="text"
                          name="name"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('name') }"
                          autofocus
                          autocomplete="off"
                          placeholder="Ex: MD Mohammad"
                        />
                        <has-error :form="form" field="name"></has-error>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Mobile Number</label>

                        <input
                          v-model="form.mobile_no"
                          type="text"
                          name="mobile_no"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('mobile_no'),
                          }"
                          autocomplete="off"
                          placeholder="01xxxxxxxxx"
                        />
                        <has-error :form="form" field="mobile_no"></has-error>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Address</label>
                        <input
                          v-model="form.address"
                          type="text"
                          name="email"
                          class="form-control"
                          :class="{ 'is-invalid': form.errors.has('address') }"
                          autocomplete="off"
                          placeholder="address"
                        />
                        <has-error :form="form" field="address"></has-error>
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Referance Name</label>

                        <input
                          v-model="form.referance_name"
                          type="text"
                          name="referance_name"
                          class="form-control"
                          :class="{
                            'is-invalid': form.errors.has('referance_name'),
                          }"
                          autofocus
                          autocomplete="off"
                          placeholder="refarance name"
                        />
                        <has-error
                          :form="form"
                          field="referance_name"
                        ></has-error>
                      </div>
                    </div>
                  </div>

                      <div class="form-group">
                        <label> Status </label>
                        <select name="status" class="form-control"  v-model="form.status">
                          <option value="1">Active</option>
                          <option value="0">DeActive</option>
                        </select>
                        <has-error :form="form.status" field="status"></has-error>
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
  created() {
    this.getInvestor();
  },
  data() {
    return {
      form: new Form({
        name: "",
        referance_name: "",
        mobile_no: "",
        address:"",
        purpose:"",
        amount:"",
        profit_margin:"",
        status:'',
      }),
      loading: true,
      error: "",
      options: {
        format: "YYYY-MM-DD",
        useCurrent: false,
      },
      loaners: "",
    };
  },

  methods: {

    getInvestor(){
      axios.get('/api/company/investor/get/'+this.$route.params.id)
      .then(resp=>{
         this.form.name = resp.data.investor.name ;
         this.form.mobile_no = resp.data.investor.mobile_no ;
         this.form.address = resp.data.investor.address ;
         this.form.referance_name = resp.data.investor.referance_name ;
         this.form.status = resp.data.investor.status ;
         this.loading=false;
      })

    },

    updateInvestor() {
      this.form
        .post("/api/company/investor/update/"+this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data.status == "OK") {
            this.$router.push({ name: "investment" });
            this.$toasted.show(resp.data.message, {
              type: "success",
              position: "top-right",
              duration: 4000,
            });
          } else {
            this.error = "something went to wrong";
          }
        })

    },

  },
};
</script>

<style scoped>
.mb-2 {
  margin-bottom: 5px !important;
}
</style>
