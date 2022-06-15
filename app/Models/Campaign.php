<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
     public function campaignProducts()
    {
        return $this->hasMany('App\Models\CampaignProducts','campaign_id');
    }
    public function image(){
        return $this->hasMany('App\Models\CampaignImage','campaign_id');

    }
}
