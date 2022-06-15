<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title> Mohasagor It Solution </title>
    <style>
        body {
            margin-top: 20px;
        }
        table{
            text-align: center;

        }

        * {
            margin: 0;
            padding: 0;

        }

        .col-lg-6 {
            width: 40%;
        }

        table {
            border: 1px solid #000;
            padding: 10px 10px;
            /* background-image: url("https://i.stack.imgur.com/fNC36.png"); */

        }

        td,th {
            border: 1px solid #000;
            padding: 5px 5px;
            text-align: center;
        }
        .address {
            line-height: 0.4;
            text-align: center;
        }

        .logo {
            font-size: 32px;
            font-weight: bolder;

        }

        .p-h {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 18px;
        }

    </style>
</head>

<body>
 <div class="container">
        <div class="row">
            <div class="address">
                <p class="logo">Mohasagor It Solution </p>
                <p class="address_line">
                    Office: House:02, Lane:11,Block:A, Banarosi Polli, section-10,
                    Mirpur,Dhaka.
                </p>
                <p>Email: support@mohasagor.com</p>
                <p>Hot Line: <strong> 09636 203040</strong></p>

            </div>

             <div class="ad_account">
                Name: <strong>{{ $reseller->name ?? $reseller->name }}</strong>
                <br />
                Account Name: <strong>{{ $ad_account->name ?? $ad_account->name }}</strong>
                <br />
                Page Name : <strong>{{ $ad_account->page_name ?? $ad_account->page_name }} </strong>
                <br/>
                <div class="ad_account_money" style="display: inline-block; margin-left:495px;margin-top:-22px;">
                    Total Dollar: <strong> {{   $ad_account->total_dollar }} </strong>  BDT
                    Total Amount: <strong>  {{  $ad_account->total_amount }}   </strong>  BDT
                 </div>
            </div>

            <div class="col-lg-6">
                <p class="p-h"> Transaction Report</p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Dollar</th>
                            <th>Rate</th>
                            <th>Amount</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $k => $item)


                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td>{{ date("d-m-Y", strtotime($item->created_at))}}</td>
                                <td>{{ $item->dollar }}</td>
                                <td>{{ $item->rate }}</td>
                                <td>{{ $item->amount }}</td>


                                </tr>
                        @endforeach

                        <tr>
                            <td colspan="2"></td>
                            <td ><strong> = {{ $ad_account->total_dollar }}</strong></td>
                            <td colspan="2" ><strong style="float:right;margin-right:50px;"> = {{ $ad_account->total_amount }}</strong></td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </div>
    </div>

</body>

</html>
