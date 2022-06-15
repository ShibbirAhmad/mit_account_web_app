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

        table {
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

        th {
            border: .5px solid #ddd;
            padding: 5px 5px;
            background: #ec4816;
            color: #fff;
            text-align: center;
        }

        td {
            color: #000;
            border: .5px solid #ddd;
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



        .client_info_left {
            float: right;
            width: 50%;
        }


        .client_info_right {
            margin-left: 60%;
            width: 40%;
            float: left;
        }


        .start_date {
            width: 50%;
            float: left;
            border-bottom: 1px dotted #000;
        }

        .end_date {
            width: 40%;
            border-bottom: 1px dotted #000;
        }

        .agency_amount_row{
            margin-top: -80px;
            margin-bottom: 100px;
        }



    </style>
    @php

        function accountTotalAmount($transactions)
        {
            $amount = 0;
            foreach ($transactions as $item) {
                $amount += $item->amount;
            }
            return $amount;
        }

        function accountTotalDollar($transactions)
        {
            $dollar = 0;
            foreach ($transactions as $item) {
                $dollar += $item->dollar;
            }
            return $dollar;
        }

    @endphp

</head>

<body>
    <div class="container">
        <div class="row">
            <div class="address">
                <p class="logo"> Mohasagor - {{ $agency->name }} </p>
                <p class="address_line">
                    Office: House:02, Lane:11,Block:A, Banarosi Polli, section-10,
                    Mirpur,Dhaka.
                </p>
                <p>Email: support@mohasagor.com</p>
                <p>Hot Line: <strong> 09636 203040</strong></p>

            </div>


            @if (!empty($start_date) && !empty($end_date))

                <div style="margin-top: -20px;" class="row">
                    <div class="start_date"> Date From: {{ date('d-m-Y', strtotime($start_date)) }} </div>
                    <div class="end_date"> Date To : {{ date('d-m-Y', strtotime($end_date)) }} </div>
                </div>
            @endif

            <div class="row agency_amount_row">
                <div style="margin-left:30%"> <strong> Total Dollars: {{ $agency->total_dollar }} BDT </strong> </div>
                <div style="margin-left:30%"> <strong> Total Amounts: {{ $agency->total_amount }} BDT </strong> </div>
                <div style="margin-left:30%"> <strong> Total Payment Paid Amount: {{ $agency->total_payment }} BDT </strong> </div>
                <div style="margin-left:30%"> <strong> Total Due Amount: {{ $agency->total_amount - $agency->total_payment }} BDT </strong> </div>
            </div>



            @foreach ($resellers as $reseller)

                <div style="margin-top:-40px" class="row">
                    <div class="client_info_left">
                        Name: <strong>{{ $reseller->name ?? $reseller->name }}</strong>
                        <br />
                        Company Name: <strong>{{ $reseller->company_name ?? $reseller->company_name }}</strong>
                        <br />
                        Phone : <strong>{{ $reseller->phone }} </strong>
                        <br />

                    </div>

                    <div class="client_info_right">
                        Total Dollar: <strong> {{ $reseller->total_dollar }} </strong> BDT
                        <br>
                        Total Amount: <strong> {{ $reseller->total_amount }} </strong> BDT
                        <br>
                        Total Paid: <strong> {{ $reseller->total_payment }} </strong> BDT
                        <br>
                        Total Due: <strong>
                            {{ intval($reseller->total_amount) - intval($reseller->total_payment) }} </strong>
                        BDT
                    </div>


                </div>



                @foreach ($reseller->accounts as $account)
                    @if (count($account->transactions) > 0)
                        <div class="col-lg-12">
                            <p class="p-h"> {{ $account->name }} Transactions</p>
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
                                    @foreach ($account->transactions as $k => $item)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ date('d-m-Y', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->dollar }}</td>
                                            <td>{{ $item->rate }}</td>
                                            <td>{{ $item->amount }}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="2"></td>
                                        <td><strong> = {{ accountTotalDollar($account->transactions) }}</strong></td>
                                        <td colspan="2"><strong style="float:right;margin-right:50px;"> =
                                                {{ accountTotalAmount($account->transactions) }}</strong></td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    @endif
                @endforeach



            @endforeach
        </div>
    </div>

</body>

</html>
