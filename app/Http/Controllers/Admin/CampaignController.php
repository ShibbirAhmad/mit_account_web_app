<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignProducts;
use App\Models\Product;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $items = $request->item ?? 20;
        $campaigns = Campaign::orderBy('id', 'DESC')->paginate($items);
        return response()->json($campaigns);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // return  $request->all();
        $validatedData = $request->validate([
            'campaign_name' => 'required|unique:campaigns',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);

        $campaign = new Campaign();
        $campaign->campaign_name = $request->campaign_name;
        $campaign->slug = str_slug($request->campaign_name);
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->terms_and_condition	 = $request->terms_and_condition;
        $campaign->bg_color	 = $request->bg_color;
        $campaign->text_color	 = $request->text_color;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/campaign', 'public');
            $campaign->image = $path;
        }

        if ($campaign->save()) {
            return response()->json([
                'status' => 'OK',
                'message' => 'Campaign Was created',
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $campaign = Campaign::where('id', $id)->first();
        return response()->json([

            'SUCCESS' => "OK",
            'campaign' => $campaign,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // return  $request->all();
        $validatedData = $request->validate([
            'name' => 'required ',
            'start_date' => 'required',
            'end_date' => 'required',

        ]);

        $campaign = Campaign::find($id);
        $campaign->campaign_name = $request->name;
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->terms_and_condition = $request->terms_and_condition;
        $campaign->bg_color = $request->bg_color;
        $campaign->text_color	 = $request->text_color;

        if ($campaign->save()) {
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images/campaign', 'public');
                $campaign->image = $path;
            }
            return response()->json([
                'status' => 'OK',
                'message' => 'Campaign Was updated',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Campaign::where('id', $id)->delete();
       
        CampaignProducts::where('campaign_id', $id)->delete();
        return \response()->json([
            'success' => "OK",
            'message' => ' Campaign was remove',

        ]);

    }

    public function removeProductfromProduct(Request $request, $id)
    {

        $product = Product::find($id);
        $campagin_products = CampaignProducts::where(['product_id' => $id, 'campaign_id' => $request->campaign_id])->delete();
        return response()->json([
            'status' => 'OK',
            'message' => 'Product was remove',
        ]);
       

    }

    public function assignProduct(Request $request, $code)
    {

        $product = Product::where('product_code', $code)->first();

        if (!$product) {
            return \response()->json([
                'success' => false,
                'message' => "product was not found",
            ]);
        }
       
       
        $product_campaign = new CampaignProducts();
        $product_campaign->campaign_id = $request->campaign_id;
        $product_campaign->product_id = $product->id;
        $product_campaign->save();
        return \response()->json([
            'success' => "OK",
            'message' => "product was added",
        ]);

       

    }
    public function campaignProducts($id)
    {

        $campaign=Campaign::find($id);
        $products_id=CampaignProducts::where('campaign_id', $id)->select('product_id')->pluck('product_id');
        $products=Product::whereIn('id', $products_id)->select('id', 'product_code')->get();
        return \response()->json([
            'campaign' => $campaign,
            'products' => $products,
        ]);
    }

    public function active($id)
    {

        $campaign = Campaign::find($id);
        if ($campaign) {
            $campaign->status = 1;
            if ($campaign->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Campaign active successfully',
                ]);
            }
        }
    }

    public function deactive($id)
    {

        $campaign = Campaign::find($id);
        if ($campaign) {
            $campaign->status = 0;
            if ($campaign->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'Campaign de-active successfully',
                ]);
            }
        }
    }

}
