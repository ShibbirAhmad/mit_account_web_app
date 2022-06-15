<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title> Mohasagor </title>
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

        table {
            border: .5px solid #000;
            padding: 5px 5px;

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
            font-size: 22px;
        }


        .ad_account {
            margin-left: 2%;
        }


        .p_sc_name {
            margin-top: 10%;
        }

    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="address">
                <p class="logo">Mohasagor Ltd. </p>
                <p class="address_line">
                    Office: House:02, Lane:11,Block:A, Banarosi Polli, section-10,
                    Mirpur,Dhaka.
                </p>
                <p>Email: support@mohasagor.com</p>
                <p>Hot Line: <strong> 09636 203040</strong></p>

            </div>

            <div class="ad_account">
                Category Name: <strong>{{ $category->name }}</strong>
                <br />
                 Current Total Stock Quantity : <strong>{{ number_format( $category->total_stock,0) }}</strong>
                <br />
                 Current Total Stock Amount : <strong>{{  number_format($category->total_amount,0) }} </strong>

            </div>


            <div class="col-lg-10">
                <p class="p-h"> sub categories report of {{ $category->name }} </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Stock</th>
                            <th>Amount</th>
                            <th>Sub Details</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($category->subCategory as $k => $item)
                            <tr>

                                <td>
                                    <p class="p_sc_name">{{ $item->name }} </p>
                                </td>
                                <td>
                                    <p class="p_sc_name"> {{ number_format($item->total_stock,0) }} </p>
                                </td>
                                <td>
                                    <p class="p_sc_name">{{ number_format($item->total_amount,0) }} </p>
                                </td>
                                <td>
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td>Name</td>
                                                <td>Stock</td>
                                                <td>Amount</td>
                                            </tr>
                                            @foreach ($item->subSubCategory as $k => $s_item)
                                                <tr>
                                                    <td>{{ $s_item->name }}</td>
                                                    <td>{{ number_format($s_item->total_stock ,0) }}</td>
                                                    <td>{{ number_format($s_item->total_amount,0)  }}</td>
                                                </tr>
                                            @endforeach
                                        </thead>
                                    </table>
                                </td>
                            </tr>
                        @endforeach



                    </tbody>
                </table>
            </div>





        </div>
    </div>

</body>

</html>
