<html>

<head>

    <title>
        Print
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        * {
            margin: 0;
            padding: 0;
        }

    </style>

    {{-- @php
        dd($products);
    @endphp --}}

</head>




<body>
      <div class="page" style="text-align:center;margin-top:4.4%;margin-left:4.5%;">

        @foreach ($products as $item)

            @for ($i = 1; $i <= $item['total_stock']; $i++)
                <div style="text-align:center;width:24%;display:inline-block;height:6%;padding:0;margin:0;">

                    {!! $item['product']['product_barcode']['barcode'] !!}
 
                    <b style="float: left;margin-left: 25px;letter-spacing: 10px">
                         {{ $item['product']['product_code'] }}</b>
                </div>

            @endfor

        @endforeach
    </div>
</body>

<script type="text/javascript">
    window.addEventListener('DOMContentLoaded', (event) => {
        window.print();
    });
</script>

</html>
