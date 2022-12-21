<!-- Tell the browser to be responsive to screen width -->
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<!-- Bootstrap 3.3.7 -->
<link rel="stylesheet" href="{{asset('admin/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="{{asset('admin/bower_components/Ionicons/css/ionicons.min.css')}}">
<!-- Theme style -->
<link rel="stylesheet" href="{{asset('admin/dist/css/AdminLTE.min.css')}}">
<!-- AdminLTE Skins. Choose a skin from the css/skins
     folder instead of downloading all of them to reduce the load. -->
<link rel="stylesheet" href="{{asset('admin/dist/css/skins/_all-skins.min.css')}}">
<!-- Morris chart -->
<link rel="stylesheet" href="{{asset('admin/bower_components/morris.js/morris.css')}}">
<!-- jvectormap -->
<link rel="stylesheet" href="{{asset('admin/bower_components/jvectormap/jquery-jvectormap.css')}}">
<!-- Date Picker -->
<link rel="stylesheet" href="{{asset('admin/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
<!-- Daterange picker -->
<link rel="stylesheet" href="{{asset('admin/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('admin/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
<link rel="stylesheet" href="{{ asset('admin/css/sweetalert2.css') }}">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
<link rel="stylesheet" type="text/css" href="{{asset('frontend/css/scrol.css')}}"/>
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"
/>

<script src="{{asset('admin/bower_components/jquery/dist/jquery.min.js')}}"></script>

<script src="{{asset('admin/bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{asset('admin/bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('admin/js/objectToFormData.js')}}"></script>


<!-- Google Font -->
<script src="{{ asset('admin/js/sweetalert2.all.js') }}"></script>

<style>

    ::-webkit-scrollbar{
        width: 10px;
        height: 10px;
    }
    
    .combo_p_list{
       list-style-type: disc;
       display: flex;
       flex-wrap: wrap;
       margin-top: 28px;
    }
    
    .combo_p_list>li {
        margin: 0px 25px;
        background: black;
        padding: 2px 24px;
        color: #fff;
        margin-bottom: 10px;
    }
    
    .combo_p_list>li>span {
        cursor: pointer;
        background: red;
        position: absolute;
        width: 39px;
        height: 24px;
        margin-top: -2px;
        margin-left: 20px;
    }
    
    .combo_p_list>li>span>i{
        margin: 5px 14px;
    }
    
    .container {
            width: 100% !important;
        }
    
        @media only screen and (max-width:1200px) {
            .container {
                width: 100% !important;
            }
    
            .col-lg-11 {
                width: 100% !important;
                overflow-x: auto;
            }
        }
    
        @media only screen and (min-width:1200px) {
            .col-lg-11 {
                width: 100% !important;
            }
        }
    
    .box{
        overflow-x: auto ;
    }
    
    
    .vm--modal {
        position: relative;
        overflow: hidden;
        box-sizing: border-box;
        background-color: white;
        border-radius: 3px;
        box-shadow: 0 20px 60px -2px rgba(27, 33, 58, 0.4);
        overflow-y: scroll !important;
    }
    
    
        .is-invalid {
            border: 1px solid red;
        }
    
        .invalid-feedback {
            color: red;
        }
        .swal2-popup{
            width: 24em!important;
            font-size: 1.2em !important;
        }
        .swal2-styled.swal2-confirm {
    
            font-size: 1.0625em;
        }
        .small-image{
            width: 50px;
            border: 1px solid #000;
        }
        .skin-blue .sidebar-menu .treeview-menu>li>router-link {
            color: #8aa4af;
        }
    
        .text-danger{
            color: red !important;
        }
        span.badge-danger {
            background: red;
        }
        span.badge-success {
            background: green;
        }
        span.badge-warning {
            background: #f39c12;
            color: #000 !important;
        }
        span.badge-primary {
            background: #3c8dbc;
        }
        .table td {
            border: 1px solid #ddd;
    
        }
        .table th {
            background-color: #ddd;
    
        }
        .login{
            display: none !important;
        }
        .table-image{
            width: 100px;height: 95px
        }
        .barcode{
            margin-bottom: 0px;
        }
        .barcode-number{
            margin-left: 40px;
        }
        .two-percent{
            width: 2% !important;
        }
        .three-percent{
            width: 3% !important;
        }
        .action-btn {
        width: 70px !important;
        margin-bottom: 2px !important;
    }
    li.dropdown a {
        color: #fff !important;
    }
    li.dropdown a:hover {
        color: #000 !important;
    }
    .invoice-header{
        text-align: left;
        display: none;
    }
    .invoice-header .address {
        margin-top: 6px;
        text-align: right;
        position: absolute;
        right: 50px;
    
    }
    .invoice-header .address p {
        margin: 0 0px;
        font-weight: bolder;
    
    }
    .invoice-body{
        margin-bottom: 30px;
    }
    .invoice-body p{
        margin: 0 0px;
    }
    .toasted.toasted-primary.success {
        width: 400px;
        height: 55px;
        font-size: 18px;
    }
    
    
    .toasted.toasted-primary.error {
        width: 400px;
        height: 55px;
        font-size: 18px;
        font-weight: bolder;
    }
    .ck.ck-content.ck-editor__editable.ck-rounded-corners.ck-editor__editable_inline.ck-blurred{
        height: 200px !important;
    }
    .permisson-denied {
            text-align: center;
    
    }
    
    .permisson-denied img {
        border: 5px solid red;
        border-radius: 9px;
    }
    .content-wrapper{
        min-height: 100vh !important;
    }
    html {
      scroll-behavior: smooth;
    }
    .content{
        background: none !important;
    }
    
    
        .order_statistic{
            margin-left: 10px;
        }
    
        .statistic_item {
            float: left;
            background: #fff;
            box-shadow: 0 1pt 6pt rgb(150 165 237);
            border: none;
            padding: 1px 40px;
            margin: 5px;
            width: 15%;
            height: 90px;
            cursor: pointer;
        }
        .statistic_item h2 {
            font-size: 34px;
            font-weight: bold;
            font-family: serif;
            line-height: 23px;
            color: #000;
        }
    
        .statistic_item p {
            font-size: 17px;
            font-family: serif;
            position: absolute;
            line-height: 16px;
            color: #000;
        }
    
        .action_container {
            display: none;
        }
    
    
        .toggle_order_action{
            display: block;
        }
    
    
        @media only screen and (max-width:1200px) {
    
                .col-lg-11 {
                    width: 105.666667% !important;
                    margin-left: -5% !important;
                    overflow-x: auto;
                }
    
    
            }
    
    
    
    @media screen and (max-width:768px) {
    
        div#app {
        background: #fff !important;
        }
    
         .col-lg-11, .col-md-11, .col-lg-10,.col-md-10,.col-lg-6,.col-md-8,.col-lg-6,.col-md-6 {
                  overflow-x: auto ;
         }
    
        .order_statistic a {
            width: 45%;
            height: 100px;
            padding: 10px 20px;
        }
    
    }
    
    </style>
