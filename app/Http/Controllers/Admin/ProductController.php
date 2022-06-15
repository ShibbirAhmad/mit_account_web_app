<?php

namespace App\Http\Controllers\Admin;

use Picqer;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variant;
use App\Models\Category;
use App\Models\Customer;
use App\Models\SubCategory;
use Illuminate\Support\Str;
use App\Models\ProductImage;
use App\Models\Purchaseitem;
use Illuminate\Http\Request;
use App\Models\ProductBarcode;
use App\Models\ProductVariant;
use App\Models\SubSubCategory;
use Illuminate\Validation\Rule;
use App\Models\ProductAttribute;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ProductStockTransfer;
use Intervention\Image\Facades\Image;
use Throwable;

class ProductController extends Controller
{


      public function ThumbnailImageMakerFromExistingFile(){

        $products=Product::query()->where('thumbnail_img',null)->get();
        foreach($products as $product){
            if($product->productImage->count()>0){
                $product_img=ProductImage::where('product_id',$product->id)->first();
                //make thumbnail image
                if (file_exists('storage/'.$product_img->product_image)) {
                    $filename =$product_img->product_image;
                    $explodeName = explode("/",$filename);
                    $image_resize = Image::make('storage/'.$product_img->product_image);
                    $image_resize->resize(400, 400, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $image_resize->save(public_path('storage/images/product_thumbnail_img/'.$explodeName[2]));
                    $data['thumbnail_img']=$explodeName[2];
                    DB::table('products')->where('id', $product->id)->update($data);
                }
            }
        }

       echo "completed" ;

    }



  public function index(Request $request){

        // return $request->all();
        $paginate = $request->item ?? 30;

        if ($request->status == "all") {
            $products = Product::orderBy('id', 'DESC')->with([ 'productBarcode', 'merchant','purchaseItem'])->paginate($paginate);
            if (!empty($request->merchant_id)) {
                $products = Product::where('merchant_id', $request->merchant_id)->orderBy('id', 'DESC')->with([ 'productBarcode', 'merchant','purchaseItem'])->paginate($paginate);
            }
        } elseif ($request->status == 0) {
            $products = Product::orderBy('id', 'DESC')->where('stock', '<=', 0)->with([ 'productBarcode', 'merchant','purchaseItem'])->paginate($paginate);
            if (!empty($request->merchant_id)) {
                $products = Product::orderBy('id', 'DESC')->where('stock', '<=', 0)->where('merchant_id', $request->merchant_id)->with([ 'productBarcode', 'merchant','purchaseItem'])->paginate($paginate);
            }
        } else {
            $products = Product::orderBy('id', 'DESC')->with([ 'productBarcode', 'merchant','purchaseItem'])->where('status', $request->status)->paginate($paginate);
            if (!empty($request->merchant_id)) {
                $products = Product::orderBy('id', 'DESC')->with([ 'productBarcode', 'merchant','purchaseItem'])->where('status', $request->status)->where('merchant_id', $request->merchant_id)->paginate($paginate);
            }
        }

        return response()->json([
            // 'status' => 'SUCCESS',
            'products' => $products,
        ]);
    }


    public function slugCreator($string, $delimiter = '-') {
        // Remove special characters
          $string = preg_replace("/[~`{}.'\"\!\@\#\$\%\^\&\*\(\)\_\=\+\/\?\>\<\,\[\]\:\;\|\\\]/", "", $string);
        // Replace blank space with delimiter
        $string = preg_replace("/[\/_|+ -]+/", $delimiter, $string);
        return $string;
    }



    public function store(Request $request)
    {
        //validation data
        // return $request->all();
        $validatedData = $request->validate([
            'name' => 'required ',
            // 'merchant' => 'required',
            'category' => 'required',
            //   'quantity' => 'required',
            //  'alert_quantity' => 'required',
            // 'purchase_price' => 'required',
            'sale_price' => 'required',
            'price' => 'required',
            'details' => 'required',
            'image' => 'required',


        ]);
        DB::transaction(function() use($request){

            //get products tables max id
            $id = Product::max('id') ?? 0;
            $product_code = 1001 + $id;
            //save product data
            $product = new Product();
            $product->name = $request->name;
            $product->merchant_id=282;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category ?? null;
            $product->sub_sub_category_id = $request->sub_sub_category ?? null;
            $product->product_code = $product_code;
            $product->slug = $this->slugCreator(strtolower($request->name)).'-'.$product_code;
            $product->stock = 0;
            $product->sale_price = $request->sale_price;
            $product->price = $request->price;
            $product->discount = $request->discount ?? null;
            $product->status = 1;
            $product->details = $request->details;
            $product->product_placement = $request->product_placement ?? 0;
            $product->product_position = $request->product_position ?? 0;
            $product->save();
            //if product save then generate product barcode
            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
            $barcode = $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128);
            $product_barcode = new ProductBarcode();
            $product_barcode->product_id = $product->id;
            $product_barcode->barcode = $barcode;
            $product_barcode->barcode_number = $product->product_code;
            $product_barcode->save();
            //save product multiple image in store directory
           //save product multiple image in store directory
            if ($request->hasFile('image')) {
                    $files = $request->file('image');
                    //make thumbnail image
                    $filename = time() .$files[0]->getClientOriginalName();
                    $image_resize = Image::make($files[0]->getRealPath());
                    $image_resize->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save(public_path('storage/images/product_thumbnail_img/'.$filename));
                $product->thumbnail_img = $filename;
                $product->save();

                foreach ($files as $file) {
                        $product_image = new ProductImage();
                        $product_image->product_id = $product->id;
                        $path = $file->store('images/products', 'public');
                        $product_image->product_image = $path;
                        $product_image->save();
                    }
                }

                //save the product properties
                if (isset($request->attribute) && !empty($request->attribute)) {
                        $product_attribute = new ProductAttribute();
                        $product_attribute->product_id = $product->id;
                        $product_attribute->attribute_id = $request->attribute;
                        $product_attribute->save();

                }
                if (isset($request->variant) && !empty($request->variant)) {
                    foreach ($request->variant as $item) {
                        $product_variant = new ProductVariant();
                        $product_variant->product_id = $product->id;
                        $product_variant->variant_id = $item;
                        $product_variant->save();
                    }
                }
        });
            //return success message
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'product added successfully'
            ]);

    }



    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {
            $productImage = ProductImage::where('product_id', $product->id)->get();
            $productAttribute = ProductAttribute::where('product_id', $product->id)->get();
            $productVariant = ProductVariant::where('product_id', $product->id)->get();
            return response()->json([
                'status' => 'SUCCESS',
                'product' => $product,
                'productImage' => $productImage,
                'productAttribute' => $productAttribute,
                'productVariant' => $productVariant,

            ]);
        }

    }




    public function search($search)
    {
        $products = Product::where('product_code',$search)->with([ 'productBarcode', 'purchaseItem'])->paginate(110);
        return response()->json([
            'status' => 'SUCCESS',
            'products' => $products
        ]);
    }


    public function publishUnpublish($id){

        $product = Product::findOrFail($id);
        if ($product->show_homepage==1) {
          $product->show_homepage = 0;
        } else {
           $product->show_homepage = 1;
        }
        $product->save();
        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'status changed'
        ]);

    }




    public function approved($id){
        $product = Product::findOrFail($id);
        $product->status = 1;
        $product->save();
        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'product approved successfully'
        ]);

    }




    public function pending($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->status = 2;
            if ($product->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'product pending successfully'
                ]);
            }
        }
    }

    public function deny($id)
    {
        $product = Product::find($id);
        if ($product) {
            $product->status = 3;
            if ($product->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'product deny successfully'
                ]);
            }
        }
    }

    public function stockUpdate(Request $request, $id)
    {

        $product = Product::find($id);
        if ($product) {
            $product->stock = $request->stock;
            if ($product->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'product - ' . $product->product_code . ' - stock updated'
                ]);
            }
        }
    }

    public function updateBasicInformation(Request $request, $id)
    {

    //  return $request->all();
        $validatedData = $request->validate([
            'name' => 'required ',
            'category' => 'required',
            'sale_price' => 'required',
            'price' => 'required',
            'details' => 'required',
        ]);

        //find product
        $product = Product::findOrFail($id);
        if ($product) {
            //update product basic information
            $product->update([
                'name' => $request->name,
                'merchant_id'=>$request->merchant_id,
                'category_id' => $request->category,
                'sub_category_id' => $request->sub_category ?? null,
                'sub_sub_category_id' => $request->sub_sub_category ?? null,
                'sale_price' => $request->sale_price,
                'price' => $request->price,
                'discount' => $request->discount ?? null,
                'details' => $request->details,
                'product_placement'=>$request->product_placement ?? 0,
                'product_position'=>$request->product_position ?? 0,
                'select_home_page'=>$request->select_home_page,
                'updated_at' => Carbon::now(),
                'created' => Carbon::now(),
                'slug' =>$this->slugCreator(strtolower($request->name)).'-'.$product->product_code,
                'expired_date' =>$request->expired_date,
                'single_checkout' => $request->single_checkout

            ]);
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'Product information updated'
            ]);

        }


    }

    public function updateProperties(Request $request, $id)
    {

        //return $request->all();
        $validatedData = $request->validate([
            'attribute' => 'required ',
            'variant' => 'required',
        ]);
        if (Product::findOrFail($id)) {
            //find product old attribute
            if (isset($request->attribute) && !empty($request->attribute)) {
                    $product_attribute =ProductAttribute::where('product_id',$id)->first();
                    if($product_attribute){
                         $product_attribute->attribute_id = $request->attribute;
                         $product_attribute->save();
                    }else{
                         $product_attribute =new ProductAttribute();
                         $product_attribute->attribute_id = $request->attribute;
                         $product_attribute->product_id = $id;
                        $product_attribute->save();
                     }

              }

              //find product old variant
            $product_old_variants = ProductVariant::whereIn('product_id', [$id])->get();
            //delete product old variant
            foreach ($product_old_variants as $product_old_variant) {
                $product_old_variant->delete();
            }
            //save the new variant
            foreach ($request->variant as $variant) {
                $p_variant = new ProductVariant();
                $p_variant->product_id = $id;
                $p_variant->variant_id = $variant;
                $p_variant->save();
            }
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'product properties updated successfully'
            ]);
        }


    }


    public function UpdateProductImage(Request $request, $id)
    {
        $product= Product::findOrFail($id);
        //save product multiple image in store directory
        if ($request->hasFile('image')) {
            $files = $request->file('image');
                //make thumbnail image
                $filename = time() .$files[0]->getClientOriginalName();
                $image_resize = Image::make($files[0]->getRealPath());
                $image_resize->resize(400, 400, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $image_resize->save(public_path('storage/images/product_thumbnail_img/'.$filename));
                $product->thumbnail_img = $filename;
                $product->save();

            foreach ($files as $file) {
                $product_image = new ProductImage();
                $product_image->product_id = $id;
                $path = $file->store('images/products', 'public');
                $product_image->product_image = $path;
                $product_image->save();
            }
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'product image updated successfully'
            ]);
        }

    }

    public function deleteImage(Request $request, $id)
    {

       $product_image=ProductImage::where('product_id',$id)->get();
       if ($product_image[$request->index]->delete()){
           return response()->json([
                'status'=>'SUCCESS',
               'message'=>'product image was deleted'
           ]);
       }

    }

    public function searchWithCode($code){
        $product=Product::where('product_code',$code)->where('status',1)->first();
        if($product){
            $product_attributes=ProductAttribute::where('product_id',$product->id)->with('attribute')->get();
            $product_variants=ProductVariant::where('product_id',$product->id)->with('variant')->get();
            $data[] = array_merge($product->toArray(),['attributes' => $product_attributes, 'variants' =>$product_variants]);
            return \response()->json([
                'status'=>'SUCCESS',
                'product'=>$data
               ]);
        }


    }



    // public function stockReportCategoryWise($category_id){

    //         $report=[];
    //         $report['category'] = Category::where('id',$category_id)->with('subCategory.subSubCategory')->first();
    //         //fetched products of this category and calculated it's stock and amount ;
    //         $products = Product::where('stock','>',0)->where('category_id',$category_id)->select('id','stock')->with(['purchaseItem:id,product_id,price,stock'])->get();
    //         $report['category_stock']=  $products->sum('stock');
    //         $report['category_amount']=  self::getCategoryStockAmount($products) ;
    //         //collecting sub categories stock and amount report
    //         $report['sub_categories'] = SubCategory::where('category_id',$category_id)->select('id','name','category_id')->get();
    //         self::getCategoryWiseProductStock('sub_category_id',$report['sub_categories']);
    //         //collecting sub sub categories stock and amount report
    //         $report['sub_sub_categories'] = SubSubCategory::where('category_id',$category_id)->select('id','name','category_id','subcategory_id')->get();
    //         self::getCategoryWiseProductStock('sub_sub_category_id',$report['sub_sub_categories']);

    //     //    return $report ;
    //          $pdf=PDF::loadView('admin.pdf.stock_report_category_wise',compact('report'));
    //          return $pdf->stream();

    // }



    public function stockReportCategoryWise($category_id){

            $category = Category::where('id',$category_id)->with('subCategory.subSubCategory')->first();
            //fetched products of this category and calculated it's stock and amount ;
            $products = Product::where('stock','>',0)->where('category_id',$category_id)->select('id','stock')->with(['purchaseItem:id,product_id,price,stock'])->get();
            $category->{'total_stock'}=  $products->sum('stock');
            $category->{'total_amount'}=  self::getCategoryStockAmount($products) ;
            //collecting sub categories stock and amount report
            self::getSubCategoryStock('sub_category_id',$category->subCategory);

            // return $category ;
             $pdf=PDF::loadView('admin.pdf.stock_report_category_wise',compact('category'));
             return $pdf->stream();

    }




    public function stockReportAllCategory(){

          $categories = Category::select('id','name')->with('subCategory.subSubCategory')->get();
          foreach ($categories as $key => $value) {
                $products = Product::where('stock','>',0)->where('category_id',$value->id)->select('id','stock')->with(['purchaseItem:id,product_id,price,stock'])->get();
                $value->{'total_stock'} = $products->sum('stock');
                $total_amount = 0;
                //fetched average purchase price
                foreach($products as $item){
                    count($item->purchaseItem) > 0 ? $total_amount += round($item->stock * self::averagePurchasePrice($item->purchaseItem),0) : 0 ;
                }
                $value->{'total_amount'} = $total_amount;
               //collecting sub categories stock and amounts
                 self::getSubCategoryStock('sub_category_id',$value->subCategory);
           }

            //  return $categories ;
             $pdf=PDF::loadView('admin.pdf.stock_report_all_category_wise',compact('categories'));
             return $pdf->stream();

    }





    public static function getSubCategoryStock($category_column_name,$categories) {
            foreach ($categories as $key => $value) {
                $products = Product::where('stock','>',0)->where($category_column_name,$value->id)->select('id','stock')->with(['purchaseItem:id,product_id,price,stock'])->get();
                $value->{'total_stock'} = $products->sum('stock');
                $total_amount = 0;
                //fetched average purchase price
                foreach($products as $item){
                    count($item->purchaseItem) > 0 ? $total_amount += round($item->stock * self::averagePurchasePrice($item->purchaseItem),0) : 0 ;
                }
                $value->{'total_amount'} = $total_amount;
                 //collecting sub sub categories stock and amounts
                $value->{'sub_sub_categories'}= self::getCategoryWiseProductStock('sub_sub_category_id',$value->subSubCategory);
            }
            return ;
    }








    public static function getCategoryStockAmount($products) {

            $total_amount = 0;
            //fetched average purchase price
            foreach($products as $item){
                count($item->purchaseItem) > 0 ? $total_amount += round($item->stock * self::averagePurchasePrice($item->purchaseItem),0) : 0 ;
            }
            return $total_amount   ;
    }





    public function productStockReports(Request $request){

          $item=$request->item??20;
          $categories = Category::select('id','name')->get();
          if (!empty($categories)) {
             self::getCategoryWiseProductStock('category_id',$categories);
          }
          $sub_categories='';
          $sub_sub_categories='';

          if ( !empty($request->category_id)  || !empty($request->sub_category_id)  || !empty($request->sub_sub_category_id) ) {
                //fetched sub category and stock
                $sub_categories = $request->category_id ? SubCategory::where('category_id',$request->category_id)->select('id','name')->get() : '';
                if (!empty($sub_categories)) {
                    self::getCategoryWiseProductStock('sub_category_id',$sub_categories);
                }

                $sub_sub_categories = $request->sub_category_id ? SubSubCategory::where('subcategory_id',$request->sub_category_id)->select('id','name')->get() : '' ;
                if (!empty($sub_sub_categories)) {
                    self::getCategoryWiseProductStock('sub_sub_category_id',$sub_sub_categories);
                }

                $category_column_name='';
                $category_id='';

                //only category wise
                if ( !empty($request->category_id) && $request->category_type=='category' ) {
                       $category_column_name='category_id' ;
                       $category_id=$request->category_id;
                       $products= self::getCategoryWiseProducts($category_column_name,$category_id,$item);
                 }
                //category and sub category wise
                if (!empty($request->sub_category_id) && $request->category_type=='sub_category' ) {
                       $category_column_name='sub_category_id' ;
                       $category_id=$request->sub_category_id;
                       $products= self::getCategoryWiseProducts($category_column_name,$category_id,$item);
                }

                //category and sub sub category wise
                if (!empty($request->sub_sub_category_id) && $request->category_type=='sub_sub_category' ) {
                       $category_column_name='sub_sub_category_id' ;
                       $category_id=$request->sub_sub_category_id;
                       $products= self::getCategoryWiseProducts($category_column_name,$category_id,$item);
                }

                return response()->json([
                    'categories' => $categories,
                    'products' => $products,
                    'sub_categories' => $sub_categories,
                    'sub_sub_categories' => $sub_sub_categories,
                ]);


          }else{

             $products=Product::where('stock','>',0)->select('id','name','category_id','sub_category_id','sub_sub_category_id','stock','product_code','price','sale_price')->with('purchaseItem')->paginate($item);
            return response()->json([
                'categories' => $categories,
                'products' => $products,
            ]);

          }


    }



    public static function  getCategoryWiseProducts($category_column_name,$category_id,$paginate_item){

       return Product::where($category_column_name,$category_id)
                 ->where('stock','>',0)
                 ->select('id','name','category_id','sub_category_id','sub_sub_category_id','stock','product_code','price','sale_price')
                 ->with('purchaseItem')->paginate($paginate_item);

    }


    public static function getCategoryWiseProductStock($category_column_name,$categories) {
            foreach ($categories as $key => $value) {
                $products = Product::where('stock','>',0)->where($category_column_name,$value->id)->select('id','stock')->with(['purchaseItem:id,product_id,price,stock'])->get();
                $value->{'total_stock'} = $products->sum('stock');
                $total_amount = 0;
                //fetched average purchase price
                foreach($products as $item){
                    count($item->purchaseItem) > 0 ? $total_amount += round($item->stock * self::averagePurchasePrice($item->purchaseItem),0) : 0 ;
                }
                $value->{'total_amount'} = $total_amount;
            }
            return ;
    }






    public static  function averagePurchasePrice($purchase_items){

            $total_amount = 0 ;
            $total_stock = 0 ;
            foreach ($purchase_items as $key => $purchase) {
                    $total_amount += intval($purchase->price *  $purchase->stock) ;
                    $total_stock +=  $purchase->stock ;
            }
            //average price
            $price = $total_amount / $total_stock ;
            return round($price,2) ;
    }









     public function printBarcode($id,$howmany){

        $product=Product::with(['productBarcode','subCategory:id,name'])->FindOrFail($id);
        $pdf=PDF::loadView('admin.pdf.g3print_barcode',compact('howmany','product'));
        $pdf->stream();
        return view('admin.pdf.g3print_barcode',compact('howmany','product'));

    }





    // public function printBarcode($id,$howmany){

    //     $product=Product::find($id);
    //     $product_barcode = ProductBarcode::where('product_id', $id)->first();
    //     $pdf = PDF::loadView('admin.pdf.barcode', compact('product_barcode', 'howmany','product'));
    //     return view('admin.pdf.barcode',\compact('product_barcode','howmany','product'));
    // }




    public function searchCustomer(Request $request,$number){

        if(!$request->ajax()){
            return \abort(404);
        }

       $customer=Customer::where('phone',$number)->first();

      if(!empty($customer)){
        $customer_order=Order::where('customer_id',$customer->id)->orderBy('id','DESC')->first();

       return \response()->json([
             'message'=>"customer already register.",
            'customer'=>$customer
       ]);

      }else{
        return \response()->json([
            'message'=>"new customer for us",

          ]);
      }

    }
    public function get_suggested_product(Request $request){

        $paginate_item= $request->item ?? 10 ;
        $products=Product::orderBy('id','DESC')->where('status', 1)->where('stock', '>=', 1 )->paginate($paginate_item);
        return response()->json([
               'status' => "OK",
               'products' => $products ,
        ]);
 }


 public function search_suggested_product($product_code){

    $products=Product::where('product_code', 'like', '%'.$product_code.'%')->paginate(20);
    return response()->json([
           'status' => "OK",
           'products' => $products ,
    ]);
}

   public function search_suggested_product_code_name(Request $request, $data)
    {

        $item = $request->item ?? 30;
        $products = Product::where('product_code', 'like', '%' . $data . '%')
            ->orWhere('name', 'like', '%' . $data . '%')
            ->with([ 'purchaseItem', 'productBarcode'])
            ->paginate($item);
        return response()->json($products);
    }

    public function searchSingleProduct($code){
        $product=Product::where('product_code',$code)->first();
            return response()->json([
                'status' => "OK",
                'product' => $product ,
            ]);
    }




    public function CopyProducts($id,$copyItmes){

        DB::transaction(function() use($id,$copyItmes){
        $find_product=Product::findOrFail($id);
        for ($i=1; $i <= $copyItmes; $i++) {
             //get products tables max id
                $id = Product::max('id') ?? 0;
                $product_code = 1001 + $id;
                //save product data
                $product = new Product();
                $product->name = $find_product->name;
                $product->thumbnail_img = $find_product->thumbnail_img;
                $product->merchant_id=$find_product->merchant_id;
                $product->category_id = $find_product->category_id;
                $product->sub_category_id = $find_product->sub_category_id ?? null;
                $product->sub_sub_category_id = $find_product->sub_sub_category_id ?? null;
                $product->product_code = $product_code;
                $product->slug =  $this->slugCreator(strtolower($find_product->name)).'-'. $product_code;
                $product->stock = 0;
                $product->sale_price = $find_product->sale_price;
                $product->price = $find_product->price;
                $product->discount = $find_product->discount ?? null;
                $product->status = $find_product->status;
                $product->details = $find_product->details;
                $product->product_placement = $find_product->product_placement ?? 0;
                $product->product_position = $find_product->product_position ?? 0;
                $product->save();
                //if product save then generate product barcode
                $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                $barcode = $generator->getBarcode($product->product_code, $generator::TYPE_CODE_128);
                $product_barcode = new ProductBarcode();
                $product_barcode->product_id = $product->id;
                $product_barcode->barcode = $barcode;
                $product_barcode->barcode_number = $product->product_code;
                $product_barcode->save();
                //copying the attributes
                $productAttributes=ProductAttribute::where('product_id',$find_product->id)->get();
                if(count($productAttributes)>0){
                    foreach($productAttributes as $productAttribute){
                        $attribute = new ProductAttribute();
                        $attribute->product_id = $product->id;
                        $attribute->attribute_id = $productAttribute->attribute_id;
                        $attribute->save();
                    }
                }
                //copying the variants
                $productVariants=ProductVariant::where('product_id',$find_product->id)->get();
                if(count($productVariants)>0){
                    foreach($productVariants as $productVariant){
                        $variant = new ProductVariant();
                        $variant->product_id = $product->id;
                        $variant->variant_id = $productVariant->variant_id;
                        $variant->save();
                    }
                }
        }
        return back();
      });

    }




  public function stockInAnatherProduct(Request $request){

        $validatedData = $request->validate([
            'from_product_code' => 'required ',
            'to_product_code' => 'required',
            'quantity' => 'required',
        ]);
        DB::beginTransaction();
        try{

            $from_product = Product::where('product_code', $request->from_product_code)->first();
            $to_product = Product::where('product_code', $request->to_product_code)->first();
            if (!$from_product) {
                return response()->json('From Product  Not Found Against This Code ' . $request->from_product_code);
            }
            if (!$to_product) {
                return response()->json('To  Product Found Against This Code ' . $request->to_product_code);
            }
            if ($from_product->stock < $request->quantity) {
                return response()->json($from_product->name . ' Stock available ' . $from_product->stock);
            }

            if ($from_product->category_id !== $to_product->category_id) {
                return response()->json("From & To Product Category Not Match");
            }
            //update from product stock
            $from_product->stock = $from_product->stock - $request->quantity;
            $from_product->save();
            //update from product stock
            $to_product->stock = $to_product->stock + $request->quantity;
            $to_product->save();

            $product_transfer = new ProductStockTransfer();
            $product_transfer->from_product_id = $from_product->id;
            $product_transfer->to_product_id = $to_product->id;
            $product_transfer->qauntity = $request->quantity;
            $product_transfer->creator_id = session()->get('admin')['id'];
            $product_transfer->comment = $request->comment;
            $product_transfer->save();

            DB::commit();
            return response()->json([
                'status' => "SUCCESS",
                'message' => "transfer SuccessFully",
            ]);

        }catch(Throwable $th){
            DB::rollBack();
            return response()->json([
                'status' => "FAILED",
                'message' => $th->getMessage(),
            ]);
        }

    }


    public function stockTransfer(){
        $data=ProductStockTransfer::latest()->with(['from_product','to_product','creator'])->paginate(20);
        return response()->json($data);
    }




    public function ckEditorUpload(Request $request){

        $originName = $request->file('upload')->getClientOriginalName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $request->file('upload')->getClientOriginalExtension();
        $fileName = $fileName.'_'.time().'.'.$extension;

        $request->file('upload')->move(public_path('images'), $fileName);

        $CKEditorFuncNum = $request->input('CKEditorFuncNum');
        $url = asset('public/images/'.$fileName);
        $msg = 'Image uploaded successfully';
        $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

        @header('Content-type: text/html; charset=utf-8');
        echo $response;

    }






    public function filterProductForPrintBarcode(Request $request){
        //   return $request->all();
        $products=PurchaseItem::whereIn('product_id',$request->products_id)
                                 ->whereDate('created_at','>=',$request->start_date)
                                 ->whereDate('created_at','<=',$request->end_date)
                                 ->select(DB::raw('SUM(insert_quantity) as total_stock, product_id'))
                                 ->groupBy('product_id')->get()->each(function($item){
                                       $item->{'product'} = Product::where('id',$item->product_id)->select('id','name','price','product_code','thumbnail_img')->with('productBarcode:product_id,barcode')->first();
                                    });

        return response()->json([
             'status' => 1,
             'products' => $products,
        ]);


    }




    public function bulkPrintBardCodeSetSession(Request $request){

            session()->forget('print_products');
            session()->put('print_products', (array)$request->params['products']) ;
            return response()->json([
                'status' => 1,
            ]);

    }




    public function bulkPrintBardCode(){

        $products = session()->get('print_products') ;
        $pdf=PDF::loadView('admin.pdf.bulk_barcode',compact('products'));
        $pdf->stream();
        return view('admin.pdf.bulk_barcode',compact('products'));


    }








    public function  stockTracking(Request $request){

         $product = Product::where('product_code',$request->product_code)->firstOrFail();
         if (empty($request->start_date) &&  empty($request->end_date) ) {
                //purchase records
                $reports=[];
                $reports['purchase_records'] = DB::table('purchaseitems')->join('purchases','purchaseitems.purchase_id','=','purchases.id')
                                                    ->where('purchaseitems.product_id',$product->id)
                                                    ->select('purchases.id','purchases.purchase_date','purchases.invoice_no','purchaseitems.stock','purchaseitems.price')->get();
                //order records
                $reports['order_records'] = DB::table('order_items')->join('orders','order_items.order_id','=','orders.id')
                                                    ->where('order_items.product_id',$product->id)
                                                    ->whereNotBetween('orders.status',[6,7])
                                                    ->select('orders.id','orders.created_at','orders.invoice_no','order_items.quantity','order_items.price')->get();

                //sales records
                $reports['sale_records'] = DB::table('sale_items')->join('sales','sale_items.sale_id','=','sales.id')
                                                    ->where('sale_items.product_id',$product->id)
                                                    ->select('sales.id','sales.created_at','sales.invoice_no','sale_items.qty','sale_items.price')->get();
                return response()->json([
                     'success' => true ,
                     'reports' => $reports ,
                     'product' => $product ,
                ]) ;

         } else {

                //purchase records
                $reports=[];
                $reports['purchase_records'] = DB::table('purchaseitems')->join('purchases','purchaseitems.purchase_id','=','purchases.id')
                                                    ->where('purchaseitems.product_id',$product->id)
                                                    ->whereDate('purchases.purchase_date','>=',$request->start_date)
                                                    ->whereDate('purchases.purchase_date','<=',$request->end_date)
                                                    ->select('purchases.id','purchases.purchase_date','purchases.invoice_no','purchaseitems.stock','purchaseitems.price')->get();
                //order records
                $reports['order_records'] = DB::table('order_items')->join('orders','order_items.order_id','=','orders.id')
                                                    ->where('order_items.product_id',$product->id)
                                                    ->whereNotBetween('orders.status',[6,7])
                                                    ->whereDate('orders.created_at','>=',$request->start_date)
                                                    ->whereDate('orders.created_at','<=',$request->end_date)
                                                    ->select('orders.id','orders.created_at','orders.invoice_no','order_items.quantity','order_items.price')->get();

                //sales records
                $reports['sale_records'] = DB::table('sale_items')->join('sales','sale_items.sale_id','=','sales.id')
                                                    ->where('sale_items.product_id',$product->id)
                                                    ->whereDate('sales.created_at','>=',$request->start_date)
                                                    ->whereDate('sales.created_at','<=',$request->end_date)
                                                    ->select('sales.id','sales.created_at','sales.invoice_no','sale_items.qty','sale_items.price')->get();
                return response()->json([
                     'success' => true ,
                     'reports' => $reports ,
                     'product' => $product ,
                ]) ;
         }



    }








}
