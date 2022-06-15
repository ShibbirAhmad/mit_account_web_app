<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">
        <h1>
          <router-link :to="{ name: 'supplier_note_add',params:{id:$route.params.id} }" class="btn btn-primary"
            ><i class="fa fa-plus"></i
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
        <div class="container">
                
          <div class="row justify-content-center">
            <div class="col-lg-8 col-md-8 col-lg-offset-1">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                  <h3 class="box-title heading">Note List</h3>
                </div>
                <div class="box-body">
         
                  <table class="table table-striped text-center">
                    <thead>
                      <tr>
                        <th width="5%"  scope="col">Serial</th>
                        <th width="80%" scope="col">Note</th>
                        <th width="10%" scope="col">Created </th>
                        <th width="10%" scope="col">Attachment </th>
                        <th width="5%"  scope="col">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <h1 class="text-center" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>
                      <tr v-for="(item, index) in notes.data" v-bind:key="index">
                        <td scope="row">{{ index + 1 }}</td>
                
                        <td> <p v-html="item.text"> </p> </td>
                         <td>{{ item.created_at }}</td>
                        <td> <a  :href="base_url+item.attachment" download> <i class="fa fa-download download_sign"></i> </a> </td>
                        <td> <button @click="deleteNote(item.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button> </td> 
                       
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="box-footer">
                  <div class="row">
                    <div class="col-lg-6">
                      <pagination
                        :data="notes"
                        @pagination-change-page="getNotes"
                      >
                      </pagination>
                    </div>
                    <div
                      class="col-lg-6 mt-1"
                      style="margin-top: 25px; text-align: right"
                    >
                      <p>
                        Showing <strong>{{ notes.from }}</strong> to
                        <strong>{{ notes.to }}</strong> of total
                        <strong>{{ notes.total }}</strong> entries
                      </p>
                    </div>
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
export default {
  created() {
    this.getNotes();
  },
  data() {
    return {
      notes: {},
      loading: true,
      base_url:this.$store.state.image_base_link ,
    };
  },
  methods: {
    getNotes(page=1) {
      axios
        .get("/api/get/note/list/"+this.$route.params.id+"?page="+page)
        .then((resp) => {
          console.log(resp);
          if (resp.data.success == "OK") {
            this.notes = resp.data.notes;
            this.loading = false;
          }
        })
    },

    deleteNote($id){
       axios.get('/api/delete/note/'+$id)
       .then((resp)=>{
           if (resp.data.status=="OK") {
               this.getNotes();
           }
       })
    }
 
  },
  computed: {},
};
</script>

<style scoped>

.download_sign{
  font-size:30px;
  color:green;
}


</style>
