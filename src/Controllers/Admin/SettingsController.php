<?php

namespace Properos\Commerce\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Models\Setting;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Classes\CSettings;
use Properos\Base\Classes\Api;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Models\Tax;
use Properos\Commerce\Models\Option;
use Properos\Commerce\Models\Collection;

class SettingsController extends Controller
{
    public function invoicing(){
        $invoicing = Setting::where('key', 'invoicing')->first();

        return view('be.settings.invoicing')->with(['invoicing' => $invoicing])->with(['theme'=>Theme::get()]);
    }

    public function store(Request $request){

        $data = $request->all();
        $rules = [
            'key'=>'required|in:invoicing,communication,inventory,homepage'
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cSettings = new CSettings();
            $result = $cSettings->store($data);

            return Api::success('Set up '.$data['key'].' settings successfully', $result);
        }

        throw new ApiException($validator->errors(), '001', $data);
        
    }

    public function taxes(){
        $taxes = Tax::get();

        return view('be.settings.taxes')->with(['taxes' => $taxes])->with(['theme'=>Theme::get()]);
    }
    
    public function general(){
        return view('be.settings.general')->with(['theme'=>Theme::get()]);
    }

    public function inventory(){
        $inventory = Setting::where('key', 'inventory')->first();
        $options = Option::get();
        return view('be.settings.inventory')->with(['inventory' => $inventory,'options' => $options])->with(['theme'=>Theme::get()]);
    }

    public function shipping(){
        $shipping = Setting::where('key', 'shipping')->first();

        return view('be.settings.shipping')->with(['shipping' => $shipping])->with(['theme'=>Theme::get()]);
    }

    public function communicationsPage(){
        $communication = Setting::where('key', 'communication')->first();

        return view('be.settings.communication')->with(['communication' => $communication])->with(['theme'=>Theme::get()]);
    }

    public function homepageSettings(){
        $settings = Setting::where('key', 'homepage')->first();
       
        if($settings){
            $settings->data = json_decode($settings->data, true);
            if(isset($settings->data['collections']) && count($settings->data['collections']) > 0){
                return  Api::success('Collections',$settings->data);
            }
        }
        return  Api::success('Collections',[]);
    }
}
   
