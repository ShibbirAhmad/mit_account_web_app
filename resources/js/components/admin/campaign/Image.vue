 <template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'campaign' }" class="btn btn-primary">
            <i class="fa fa-arrow-right"></i>
          </router-link>
          <a
            href="#"
            @click.prevent="$modal.show('example')"
            class="btn btn-primary"
          >
            <i class="fa fa-plus"></i>
          </a>
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
                      <h3 class="box-title">Campaign Image</h3>
                    </div>
                  </div>
                </div>
                <div class="box-body">
                  <div v-for="image in images" :key="image.id">
                    <img style="  max-width: 90%;
    max-height: 300px;
    margin-bottom: 20px;
    border: 8px solid #222d32;
    border-radius: 10px;" :src="basePath+image.image" alt="">
   <a @click.prevent="remove(image.id)" href="#" class="btn btn-sm btn-danger">
      <i class="fa fa-trash"></i>
   </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>

      <modal name="example" :width="700" :height="450">
        <div class="card" style="padding: 20px">
          <div class="card-body">
            <form @submit.prevent="save">
              <div class="form-group">
                <label>Url</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.url"
                  :class="{
                    'is-invalid': form.errors.has('url'),
                  }"
                  name="url"
                />
                <has-error :form="form" field="url"> </has-error>
              </div>
              <div class="form-group">
                <label
                  >Image <small style="font-size:10px;">Image Size Must Be 1100*300px</small>
                </label>
                <input
                  type="file"
                  class="form-control"
                  :class="{
                    'is-invalid': form.errors.has('image'),
                  }"
                  name="image"
                  @change="uploadImage"
                />
                <has-error :form="form" field="image"> </has-error>
              </div>

              <div class="image">
                <img
                  v-if="file"
                  :src="file"
                  style="
                    max-width: 100%;
                    padding: 10px;
                    border: 9px solid #000;
                    border-radius: 10px;
                    max-height: 200px;
                    margin-bottom: 10px;
                  "
                />
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
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
    this.getCampaignSlider();
  },
  data() {
    return {
      images:[],
      loading: true,

      basePath: this.$store.state.image_base_link,

      form: new Form({
        url: "#",
        image: "",
        campaign_id:this.$route.params.id
      }),
      file: "",
    };
  },
  methods: {
    getCampaignSlider() {
      axios
        .get("/api/campaign/image/" + this.$route.params.id)
        .then((resp) => {
          console.log(resp);
          if (resp.data) {
            this.images = resp.data.images;
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

    uploadImage(e) {
      const file = e.target.files[0];
      if (!file.type.match("image.*")) {
        Swal.fire({
          type: "warning",
          text: "this is not any kind of image",
        });
        return;
      }

      let reader = new FileReader();
      reader.readAsDataURL(file);
      reader.onload = (evt) => {
        let img = new Image();
        img.onload = () => {
          if (img.width > 100 && img.height > 100) {
            this.form.image = file;
            this.file = evt.target.result;

            return;
          } else {
            this.disabled = true;
            alert(
              "Image size need 1100*300px.But Upload imaze size" +
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
    save(){
      this.form.post('/api/campaign/image',{
           transformRequest: [
            function (data, headers) {
              return objectToFormData(data);
            },
          ],
      })
      .then(resp=>{
        if(resp.data.success){
          this.$modal.hide('example');
          this.file=""
          this.getCampaignSlider();
        }
        console.log(resp)
      })
    },
    remove(id){
      if(confirm('are  you sure? rou want to remove this')){
        axios.get('/api/campagin/image/remove/'+id)
        .then(resp=>{
          if(resp.data.success){
              this.getCampaignSlider();
          }
        })
      }
    }
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
