
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        
      * {
            margin: 0;
            padding: 0;

        }

        .address {
            margin-top: 50px;
            margin-bottom: 20px;
            line-height: 25px;
            text-align: center;
            
        }

        .logo {
            font-size: 32px;
            font-weight: bolder;

        }

        .container{ 
                      position:absolute;
                      width: 100%;
                      height:auto ;
                  
                  }
         .table_container{
            
             text-align: center;
             margin:5px;
         }

    

           th, td {
            border-bottom: 1px solid #ddd;
            padding: 3px;
            }

           th {
            height: 40px;
            width: 105px;
           }

 td {
             text-align: center;
            }
    </style>    
</head>
<body>
     <div class="container">

        <div class="address">
            <p class="logo">Mohasagor.com</p>
            <p class="address_line">
                Office: Houes:02, Lane:11,Block:A, Banarosi Polli, section-10,
                Mirpur,Dhaka.
            </p>
            <p>Email: support@mohasagor.com</p>
            <p>Hot Line: <strong> 09636 203040</strong></p>

        </div>

          <div class="table_container">
                  <table class="table table-striped">
             
                        <tr>
                            <th scope="col">Serial No</th>
                            <th scope="col">Date</th>
                            <th scope="col">Name</th>
                            <th scope="col">Purchase Price</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Present Price</th>
                            <th scope="col">Amount</th>
                        </tr>
               
                        <tbody>
                            
                        <?php $n=1 ;?>
                        @foreach ($assets as $key=> $asset)
                        <tr >
                            <td> {{ $key + 1 }}  </td>
                            <td> {{ $asset->purchase_date }}  </td>
                            <td> {{ $asset->name  }}   </td>
                            <td> {{ $asset->purchase_price  }}   </td>
                            <td> {{ $asset->quantity }}   </td>
                            <td> {{ $asset->present_price }}  </td>
                            <td> {{ $asset->amount }}   </td>
                         </tr>
                         @if($n % 25 == 0)
                         <div style="page-break-before:always;"></div>
                        @endif
                        <?php $n++ ?>
                        @endforeach
                      
                        <tr>
                            <td colspan="3"></td>
                            <td >
                               <strong> =  {{ $total_purchase_price }}</strong>
                            </td>
                            <td >
                                <strong> = {{ $total_present_price }}</strong>
                            </td>
                            <td >
                                <strong> = {{ $total_quantity }}</strong>
                            </td>
                            <td >
                                <strong> = {{ $total_amount }}</strong>
                            </td>
                        </tr>

                    </tbody>
                </table>

          </div>
     </div>
  
   </body>
</html>