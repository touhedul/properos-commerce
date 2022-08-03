<?php

namespace Properos\Commerce\Controllers\Plan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Models\Subscription;

class PlanController extends Controller
{
    public function show(){
        if(!\Auth::guest()){
            $subscription = Subscription::where('user_id',\Auth::user()->id)->first();
            if($subscription){
                return view('themes.'.Theme::get().'.plans')->with('subscription',$subscription);
            }
        }
        return view('themes.'.Theme::get().'.plans');
    }
}
