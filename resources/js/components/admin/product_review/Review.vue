<template>
    <div>
        <admin-main></admin-main>
        <div class="content-wrapper">
            <section class="content-header">
                <ol class="breadcrumb">
                    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                    <li class="active">Product Review</li>
                </ol>
            </section>
            <section class="content">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-10 col-lg-offset-1">
                            <div class="box box-primary review_container">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Review Table</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Product </th>
                                            <th scope="col">Customer Name</th>
                                            <th scope="col">Review</th>
                                            <th scope="col">Rating</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        <h1 v-if="loading"><i class="fa fa-spin fa-spinner"></i></h1>
                                        <tr v-else v-for="(review,index) in reviews.data" :key="index">  
                                           <td>  {{ index+1 }} </td>
                                           <td>  {{ review.review_product.name+"-"+review.review_product.product_code }} </td>
                                           <td>  {{ review.reviewer.name}} </td>
                                           <td>  {{ review.review }} </td>
                                           <td>
                                              <span class="user_rating_stars" > 
                                                <i v-for="(star,index) in review.rating_starts" :key="index"  class="fa fa-star __highlight_star "> </i>

                                                <i v-for="(star,index) in (parseInt(5)-parseInt(review.rating_starts))" :key="index"  class="fa fa-star "> </i>
                                            </span> 
                                          </td>
                                          <td>
                                                <span v-if="review.status==1" class="badge">active</span>
                                                <span v-else class="badge">De-active</span>
                                            </td>

                                            <td>
                    
                                                <a class="btn btn-warning btn-sm" title="De-active" @click="deActiveReview(review.id)"
                                                   v-if="review.status==1"><i class="fa fa-ban"></i></a>
                                                <a class="btn btn-primary btn-sm" title="active" @click="activeReview(review.id)"
                                                   v-else><i
                                                    class="fa fa-check "></i></a>
                                                <a @click="deleteReview(review.id)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="box-footer">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <pagination :data="reviews"
                                                        @pagination-change-page="getCustomerReview"></pagination>

                                        </div>
                                        <div class="col-lg-6 mt-1" style="margin-top: 25px;text-align:right;">
                                            <p>Showing <strong>{{reviews.from}}</strong> to
                                                <strong>{{reviews.to}}</strong> of total
                                                <strong>{{reviews.total}}</strong> entries
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
           this. getCustomerReview();
        },
        data() {
            return {
                reviews: {},
                loading: true,
                basePath:this.$store.state.image_base_link,
            }
        },
        methods: {

            getCustomerReview(page=1) {
                axios.get('/api/get/customer/review/on/products?='+page)
                    .then((resp) => {
                        console.log(resp)
                        if (resp.data.status == 'OK') {
                            this.reviews = resp.data.reviews;
                            this.loading = false
                        } 
                    })
            },

            activeReview(review_id) {
                axios.get('/api/active/review/of/customer/'+review_id)
                    .then((resp) => {
                     //   console.log(resp)
                        if (resp.data.status == 'OK') {
                            this.getCustomerReview();
                            this.$toasted.show(resp.data.message, {
                                type: "success",
                                position: 'top-center',
                                duration: 4000
                            });
                        } 
                    })
            
            },
            deActiveReview(review_id) {
                axios.get('/api/deActive/review/of/customer/'+review_id)
                    .then((resp) => {
                     //   console.log(resp)
                        if (resp.data.status == 'OK') {
                            this.getCustomerReview();
                            this.$toasted.show(resp.data.message, {
                                type: "success",
                                position: 'top-center',
                                duration: 4000
                            });

                        } 
                    })
             },

          deleteReview(review_id) {
                axios.get('/api/remove/review/of/customer/'+review_id)
                    .then((resp) => {
                     //   console.log(resp)
                        if (resp.data.status == 'OK') {
                            this.getCustomerReview();
                            this.$toasted.show(resp.data.message, {
                                type: "success",
                                position: 'top-center',
                                duration: 4000
                            });

                        } 
                    })
             },
       

        }
    }

</script>

<style scoped>

.review_container{
    overflow-x: scroll;
    margin-left: -100px;
    margin-top: 15px;
}

.__highlight_star {
    color: #ff4d03;
}




</style>
