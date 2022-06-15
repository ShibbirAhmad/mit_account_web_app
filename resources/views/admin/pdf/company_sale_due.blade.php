
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
                            <th scope="col">Company Name</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Address</th>
                            <th scope="col">Due  Amount </th>
                        </tr>

                        <tbody>

                        <?php $n=1 ;?>
                        @foreach ($due_companies as $key=> $company)
                        <tr >
                            <td> {{ $key + 1 }}  </td>
                            <td> {{ $company->name }}  </td>
                            <td> {{ $company->phone }}  </td>
                            <td> {{ $company->address }}  </td>
                            <td> {{ $company->due_amount }}   </td>

                         </tr>
                         @if($n % 25 == 0)
                         <div style="page-break-before:always;"></div>
                        @endif
                        <?php $n++ ?>
                        @endforeach


                    </tbody>
                </table>

          </div>
     </div>

   </body>
</html>