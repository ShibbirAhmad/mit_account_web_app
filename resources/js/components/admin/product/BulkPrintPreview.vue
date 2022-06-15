<template>
  <div>
    <admin-main></admin-main>
    <div class="content-wrapper">
      <section class="content-header">

      </section>
      <section class="content">
        <div class="container">
          <div class="row">
            <div class="col-lg-11 col-md-11">
              <div class="box box-primary">
                <div class="box-header with-border text-center">
                      <button @click="printBarcode" style="width:300px;height:44px;" class="btn btn-success "> Print Confirm </button>
                </div>
                <div class="box-body">
                  <table  class=" table table-centered table-striped table-hover table-bordered ">
                    <tbody>

                     <tr>
                       <th width="10%">
                         <input checked type="checkbox" @click="selectAll" /> </th>
                        <th width="30%">product</th>
                        <th width="20%"></th>
                        <th width="10%">Code</th>
                        <th width="30%">Print Item </th>
                      </tr>

                      <h1 style="text-align:center;" v-if="loading">
                        <i class="fa fa-spin fa-spinner"></i>
                      </h1>


                      <tr v-for="(content, index) in $route.params.contents" :key="index" v-else>
                        <td style="width: 1%">
                          <input
                            type="checkbox"
                            class="select-all"
                            v-model="products_id"
                            :value="content.product.id"
                          />
                        </td>
                        <td>{{ content.product.name }}</td>

                        <td>
                          <img
                            :src="thumbnail_img_url + content.product.thumbnail_img"
                            class="table-image product_img"
                            alt="product image"
                          />
                        </td>

                        <td>{{ content.product.product_code }} </td>

                        <td>

                          <div style="display:flex;">
                             <input class="form-control" type="number" min="1"  :id="'print_qty_update_item_'+content.product_id" :value="content.total_stock" >
                            <button @click="changeQuantity(content.product_id)" class="btn btn-success btn-xs change_btn">change</button>
                          </div>

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
    </div>

  </div>
</template>

<script>
import { Form, HasError, AlertError } from "vform";
export default {
  created() {
    this.selectAll();
    setTimeout(() => {
      this.loading = false;
    }, 1000);
  },
  data() {
    return {
      products: "",
      products_id: [],
      loading: true,
      thumbnail_img_url: this.$store.state.thumbnail_img_base_link,
      selected: false,
    };
  },
  methods: {


  async  printBarcode(){

        await axios.post('/api/product/bulk/barcode/print/set/in/session',{
           params:{
             products: this.products
           }
        })
        .then((resp)=>{
           console.log(resp);
           if (resp.data.status==1) {
            return  window.open('/api/product/bulk/barcode/print') ;
           }
        })

    },




   changeQuantity(product_id){

       this.products.forEach(element => {
           if (element.product_id == product_id) {
               let print_item = document.getElementById('print_qty_update_item_'+product_id).value ;
               element.total_stock= print_item ;
               return ;
           }
       });

   },


    //method initial for select all
    selectAll() {
      this.products=this.$route.params.contents;
      //first identify select true or false
      //we need a toggle all select box
      //if select true we make selected false, or select true
      if (this.selected == true) {
        this.selected = false;
      } else {
        this.selected = true;
      }
      //element find by the class name
      let checkBoxClass = document.getElementsByClassName("select-all");
      for (let i = 0; i < checkBoxClass.length; i++) {
        //if select active then element set attribute check==true
        //element set attribute check==false
        if (this.selected == true) {
          checkBoxClass[i].checked = true;
        } else {
          checkBoxClass[i].checked = false;
        }
      }
      //at last push order id in selected_order_id arrow....
      //and agin check selected true or false.....
      if (this.selected == true) {
        for (let i = 0; i < this.$route.params.contents.length; i++) {
          this.products_id.push(this.$route.params.contents[i].product.id);
        }
      } else {
        this.products_id = [];
      }

    },


    print(productId) {
      let how_many_times = prompt(
        "How many time you went to print this product barcode?"
      );
      let url = "/product/print/barcode/" + productId + "/" + how_many_times;
      window.open(url, "_blank");
    },

  },
};
</script>

<style scoped>
.box {
  overflow-x: scroll;
}

.change_btn {
    border: none !important;
    height: 34px !important;
    border-radius: 0 !important;
}

.product_img:hover{
    transform: scale(1.5);
}

</style>
