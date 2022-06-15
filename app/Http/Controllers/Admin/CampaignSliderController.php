<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignSlider;

class CampaignSliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        if($id=='slider'){
            $images=CampaignSlider::whereNull('campaign_id')->latest()->get();

        }else{
            $images=CampaignSlider::where('campaign_id',$id)->latest()->get();

        }
        return response()->json([
            'success' => true,
            'images' => $images,
            'message' => 'All Campaign  Slider'
        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // return $request->all();
        $this->validate($request, [
            'image' => 'required',
            'url' => 'required',
        ]);

        $campaign_slider=new CampaignSlider();
        $campaign_slider->url=$request->url;
        $campaign_slider->campaign_id=$request->campaign_id ?? null;
        $path=$request->file('image')->store('images/campaign','public');
        $campaign_slider->image=$path;
        $campaign_slider->save();
        return response()->json([
            'success' => true,
            'message' => 'image addedd',
        ]);
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $image=CampaignSlider::find($id);
       $image->delete();
       return response()->json([
           'success' => true,
           'message' => "image Success Fullye Delete"
       ]);
    }
}
