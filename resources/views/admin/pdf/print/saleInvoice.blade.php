

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <title>Print || Invoice</title>


    <style>

        .pull-right.moha_add_inv p {
            line-height: .5;
        ;
        }
        .pull-right.moha_add_inv {
            text-align: left;
            margin-right: 20px ;
            margin-left: 20px ;
            margin-top: 10px;
        }

        body {
            background: #ddd;
        }

        .print {
            background: #fff;
            padding:28px;
            display:block;
        }
        .page-break {
            page-break-after: always;
            background-color: #fff;
            padding-bottom: 50px;
            padding-top: 15px;
            margin-bottom: 10px;
            width: 70%;
        }
        .invoice_header_left_section{
            text-align: left;
            width: 55% !important;
        }
        .invoice_header_right_section{
            text-align: center;
            width: 35% !important;
            display: flex;
            flex-direction: column;
        }
        @media print {
            #print {
                display: none;
            }
        }
        .btn-pr{
            text-align: right;
            display: block;
            position: fixed;
            right:0;
            top: 280px;
        }
        .btn-pr button{
            height: 50px;
        }


        .rotate-logo {
            position: fixed;
            left: 30%;
            top:20%;
            right: 0;
            bottom: 50%;
            width: 502px;
            font-size: 24px;
            opacity: 0.2;

        }

        .rotate-logo img{ width: 350px; }


        .customer_info_list {
          border:3px dashed #000 ;
          margin-top: 30px;
          margin-right: 100px;
          margin-left: 10px;
        }

        .customer_info_list li {
           list-style-type: square;
           padding: 5px 0px;
           text-align: left;
        }

        .current_date {
            margin-right: 170px;
        }

        .company_logo {
            width: 200px;
            margin-left: 20px;
        }

    </style>

 @php

   function  countOrderTotayQty($saleItems){
       $total_qty = 0 ;
        foreach ($saleItems as  $item) {
            $total_qty += $item->qty ;
        }
        return $total_qty ;
   }

 @endphp

</head>
<body>
<div class="btn-pr">
    <button class="btn btn-success text-center print-button" onclick="allPrint()" id="print"><i class="fa fa-print"></i></button>
</div>

 <div class="container page-break">
        <div class="row justify-content-center break">
            <div class="invoice_header_left_section">
                    <ul class="customer_info_list">
                        <li> <strong> Name:  {{ $order->name ?? "" }} </strong>  </li>
                        <li> <strong> Mobile: {{ $order->mobile_no ?? "" }} </strong>  </li>
                        <li> <strong> Address: </strong>  {{ $order->address }}</li>
                        <li> <strong> Invoice No: {{'S-'.$order->id }} </strong> </li>
                    </ul>
                    {{-- <img class="inv_logo" src="{{ asset('frontend/image/logo.png') }}" alt="logo" width="200">
                    <p class="moha_title_inv" >Trusted Online Shopping In Bangladesh</p> --}}
            </div>
            <div class="invoice_header_right_section">
              <img class="company_logo" src="{{ asset('frontend/image/logo.png') }}" alt="logo">
                <div class="pull-right moha_add_inv" >
                    <p>Office: House: 02, Lane: 11, Block: A,</p>
                    <p>Benarashi Polly, Section-10, Mirpur, Dhaka.</p>
                    <p>E-mail: support@mohasagor.com</p>
                    <p>Hot Line: 09636-203040/01635-212121</p>
                    <p class="current_date" style="margin-top: 5px;" >
                     <strong>  Date: <span style="border:1px solid #ddd">  <?php echo date("d/m/Y"); ?></span>   </strong> </p>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-11 col-md-11 col-lg-offset-1 col-md-offset-1">

                <table class="table table-bordered moha_tbl_inv" style="margin-top: 5px;">

                    <tbody>
                   @if ($order->courier)
                     <tr>
                        <td colspan="7" class="text-left" style="text-transform: capitalize;"><b> Courier  :   {{ $order->courier->name ?? "" }} </b>
                        </td>
                    </tr>
                   @endif
                    <tr>
                        <th  style="background-color: #04AA6D !important;" class="text-left">No</th>
                        <th  style="background-color: #04AA6D !important;" class="text-left">Product Code</th>
                        <th  style="background-color: #04AA6D !important;" class="text-center"> Name</th>
                        <th  style="background-color: #04AA6D !important;" class="text-left">Size</th>
                        <th  style="background-color: #04AA6D !important;" class="text-right">Price</th>
                        <th  style="background-color: #04AA6D !important;" class="text-right">Qty</th>
                        <th  style="background-color: #04AA6D !important;" class="text-right">Total</th>
                    </tr>
                    @foreach($order->SaleItems as $k=> $item)

                        <tr>

                            <td class="text-center">{{$k+1}}</td>
                            <td  class="text-center"> {{$item->product->product_code}} </td>
                            <td class="text-left">
                                {{$item->product->name}}
                            </td>
                            <td>
                                 {{$item->variant->name ?? '-'}}
                            </td>
                            <td class="text-center">
                                {{$item->price}}
                            </td>
                            <td class="text-center"><span style="border: 1px solid #ddd;"><b style="font-size: 16px;padding:5px 5px 5px 5px;"> {{$item->qty}}</b></span></td>
                            <td class="text-right">{{$item->qty*$item->price}} Tk</td>
                        </tr>
                    @endforeach



                    <tr>
                        <td colspan="5" class="text-right"> Total qty  </td>
                        <td colspan="2"> <strong> = {{ countOrderTotayQty($order->SaleItems) }} </strong> </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-right"> Sub Total :</td>
                        <td colspan="2" >
                         <span style="font-weight:bold;font-size:13px;">
                            {{   intval($order->total) }} TK</span>
                        </td>
                    </tr>


                        <tr>
                            <td colspan="5" class="text-right">Discount Amount ({{ ceil(($order->discount / $order->total) * 100)}}%) :</td>
                            <td colspan="2" > {{$order->discount}} Tk</td>
                        </tr>


                         <tr>
                            <td colspan="5" class="text-right">Payable amount :</td>
                            <td colspan="2" > {{ intval($order->total) - intval($order->discount) }} Tk</td>
                        </tr>
                         <tr>
                            <td colspan="5" class="text-right">Paid Amount:</td>
                            <td colspan="2" > {{$order->paid}} Tk</td>
                        </tr>
                         <tr>
                            <td colspan="5" class="text-right">Due Amount :</td>
                            <td colspan="2" style="font-weight:bold;color:#000;background-color: #04AA6D !important" >{{ $order->total- ($order->paid + $order->discount) }} Tk</td>
                        </tr>

                    </tbody>
                </table>
                     <p style="margin-top:-8px;"> <b><i> **No replace will be accepted after 7 days</i></b></p>

                <table style="width:100%;margin-top:-8px;" >
                    <tr>
                        <td style="margin-left: 115px;display: block;"><p>
                                Approved by<br>

                                    {{$order->approvedBy->name ?? ""}}

                                </p></td>


                        <td>Accounts</td>
                    </tr>
                </table>

            </div>
        </div>


    </div>

 <div class="rotate-logo">
            <img src="{{ asset('rotatelogo.png') }}" alt="">
        </div>

<script>
    function allPrint() {
        window.print();
    };

    window.addEventListener('DOMContentLoaded', (event) => {
      window.print();
    });

</script>

</body>
</html>


