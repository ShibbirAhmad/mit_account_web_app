<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>barcode print</title>
    <style>

        .barcode {
            height: 150px;
            width: 113px;
            margin: 0 auto;
        }
        .barcode_container{
            text-align: center;
            /*margin-left: 25px;*/
            margin-bottom: 16px;
        }

        span {
            font-size: 12px;
            text-transform: lowercase;
        }

    </style>
</head>

<body>
    <div class="barcode">
        @for ($i = 1; $i <= $howmany; $i++)
            <div class="barcode_container">
                <span > {{ substr($product->subCategory->name,0,18)  }}</span>
                <div style="width:130px;height:30px;">
                    {!! $product->productBarcode->barcode !!}
                </div>
                <span style="display:block; ">Code: {{ $product->product_code }} </span>
                <span style="margin-left:10px;font-weight:bold;" >price: {{ $product->price }} /=</span>
            </div>
        @endfor

    </div>
</body>

</html>
