<template>
  <div >
        <loading :active.sync="isLoading" :can-cancel="true" :is-full-page="fullPage"></loading>

    <frontend-header></frontend-header>
      <div class="row text-center">
            <div :style="{
                height: '300px',
                marginTop: '-10px',
                backgroundImage: 'url(/public/images/background.jpg)',
                backgroundPosition: 'center center',
                backgroundSize: 'cover'
                }" class="background_image">
               
            </div>
      </div>

     <div class="row">
            <div class="col-sm-12 col-md-12"> 
              <div style="background-color:#eee" class="container-fluid ">
                <div style="margin-top:20px;" class="container">   
                  <div class="box bg-white shadow desc_info"> 
                  <h4 style="padding:15px" class="heading" > <i class="fa fa-eye"></i><b> How To Buy</b>  </h4>    
                  <p>    ১। আপনি যদি আমাদের নতুন ক্রেতা হয়ে থাকেন তাহলে যেকোন পণ্য কিনতে এখনই সাইন-আপ/রেজিস্ট্রেশন করুন। </p> 
                  <p>    ২। আপনার পছন্দের পন্যটি কেনার জন্য পণ্যের ছবির উপরে ক্লিক করুন এবং পরবর্তী পাতায় পণ্যের বিস্তারিত দেখে ছবির পাশে Order Now (এখনি অর্ডার করুন) এই বাটনে ক্লিক করুন।       </p>
                  <p>    ৩। আপনি যদি একাধিক পন্য কিনতে চান তাহলে ‘কার্ট এ যোগ করুন’ এই বাটনে ক্লিক করে আপনার নির্বাচিত সকল পন্য একবারে অর্ডার করুন।   </p>
                  <p>    ৪। যদি ইতোমধ্যেই mohasagor.com এ আপনার একাউন্ট থেকে থাকে,তাহলে আপনার ইউজার নেম ও পাসওয়ার্ড দিয়ে সাইন ইন / লগইন করুন অথবা নতুন ক্রেতা হিসেবে সাইন-আপ/রেজিস্ট্রেশন করে আপনার নতুন একাউন্টের জন্য ইউজার নেম ও পাসওয়ার্ড তৈরি করুন। </p>
                  <p>    ৫। ডেলিভারী ঠিকানায় আপনি যেখানে ডেলিভারী নিতে ইচ্ছুক সেখানকার বিস্তারিত ঠিকানাসহ শহরটি সেলেক্ট করুন। আপনার প্রদানকৃত সঠিক তথ্য আপনার পন্যের ডেলিভারী প্রক্রিয়াকে দ্রততর করবে। </p>
                  <p>    ৬। এরপরে আপনি পরবর্তী ধাপে যেতে ‘কার্ট তথ্য সংরক্ষণ’ বাটনে ক্লিক করুন। </p>
                  <p>    ৭। অর্ডার সফল হলে আপনার ই-মেইলে বুকিং কোড সহ একটি মেইল যাবে । </p>
                  <p>    ৮। অর্ডার সাবমিটের পর 12 ঘন্টার মধ্যে আমাদের প্রতিনিধি আপনার সাথে যোগাযোগ করে পণ্য ডেলিভারী প্রক্রিয়া শুরু করবে। </p>
                  <p>    ৯। সাধারণত ঢাকার মধ্যে 24 ঘন্টা (প্রায়) এবং ঢাকার বাহিরে যে কোন জেলায় 72 ঘন্টার (প্রায়) মধ্যে আপনার পণ্য হাতে পাবেন। </p>
                  <p style="padding-bottom:20px;">    ১০। অর্ডার সংক্রান্ত যে কোন তথ্য বা সরাসরি অর্ডার দিতে ফোন করুন 09-636-203040 বা 01627-444999 (সকাল ৯টা থেকে রাত ১১ টার মধ্যে)। </p>
              
                 </div>
                </div>
              </div>    
            </div>
          </div>
          
    <frontend-footer></frontend-footer>>
  </div>
</template>
<script>
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/vue-loading.css";
export default {
  created(){

    setTimeout(()=>{
      this.isLoading=false;
    },1000)
  },
  data(){
    return {
      isLoading: true,
      fullPage: true,
    }
  },
  components:{
Loading
  }
}
</script>

<style >
  .desc_info p {

      padding:5px;
      margin:10px;
  }
</style>