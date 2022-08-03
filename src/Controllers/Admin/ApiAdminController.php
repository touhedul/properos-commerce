<?php

namespace Properos\Commerce\Controllers\Admin;

use App\Http\Controllers\Controller;
use Properos\Base\Classes\Api;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Validator;
use Carbon\Carbon as CCarbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Properos\Users\Models\User;
use Properos\Commerce\Models\PaymentMethod;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\Category;
use Properos\Commerce\Classes\CCategory;
use Properos\Commerce\Classes\CItem;
use Properos\Commerce\Classes\COrder;
use Properos\Commerce\Classes\Integrations\Shipping\UPS\ShippingUPS;
use Properos\Cms\Models\BlogPost;
use Properos\Commerce\Jobs\ExportData;
use Properos\Commerce\Classes\CSubscribers;
use Illuminate\Validation\ValidationException;
use Properos\Commerce\Classes\CDiscount;
use Properos\Users\Classes\CUser;
use Properos\Commerce\Classes\CLocation;
use Properos\Commerce\Models\StoreLocation;
use Properos\Commerce\Classes\CTax;
use Properos\Commerce\Models\Tax;
use Properos\Commerce\Classes\CPackage;
use Properos\Commerce\Classes\COption;
use Properos\Users\Classes\CUserActivityLog;
use Properos\Commerce\Models\Option;
use Properos\Commerce\Jobs\UpdateItemsOptions;
use Properos\Commerce\Classes\COrderItem;

class ApiAdminController extends Controller
{

    private $item;
    private $category;
    private $order;
    private $paymentMethod;
    private $shippingMethod;
    private $cItem;
    private $cOrder;
    private $shipping_ups;
    private $blog;
    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    | 
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Category $category, Item $item, Order $order, User $user, CItem $cItem, COrder $cOrder, ShippingUPS $shipping_ups, BlogPost $blog)
    {
        $this->middleware(['role:admin']);

        $this->item = $item;
        $this->order = $order;
        $this->category = $category;
        $this->user = $user;
        $this->cItem = $cItem;
        $this->cOrder = $cOrder;
        $this->shipping_ups = $shipping_ups;
        $this->blog = $blog;
    }

    public function addCustomer(Request $request)
    {
        try{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
            ]);
        }catch(ValidationException $exception){
            return response()->json([
                'status' => 0,
                'msg' => 'Error',
                'errors' => $exception->errors(),
                'code' => '002'
            ], 200);
        }
        

        $data = $request->all();
        $hashed_random_password = bcrypt(str_random(8));
        $user = User::create([
            'firstname' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => $hashed_random_password,
            'phone' => array_key_exists('phone', $data) ? $data['phone'] : null,
            'avatar' => array_key_exists('avatar', $data) ? $data['avatar'] : null,
            'company' => array_key_exists('company', $data) ? $data['company'] : null
        ]);
        if ($user) {
            $user->assignRole('customer');
            return Api::success('Customer-User created successfully', $user);
        }
        return Api::error('Error creating customer/user', []);
    }

    public function orders(PaymentMethod $paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
        $orders = $this->order->with('customer')->with('paymentMethod')->with('orderItems')->orderBy('created_at', 'desc')->paginate(10);
        $payment_methods = $this->paymentMethod->all();
        $result['orders'] = isset($orders) ? $orders : [];
        $result['payment_methods'] = count($payment_methods) > 0 ? $payment_methods : [];

        return $result;
    }

    public function recentOrders()
    {
        $recent_orders_db = $this->order->with('customer')->with('orderItems.item.category')
            /* ->where('created_at', '>', Carbon::now()->subDays(3)) */
            ->orderBy('created_at', 'desc')
            ->paginate(4);

        return $recent_orders_db;
    }

    public function items()
    {
        $items = $this->item->with(['files' => function ($q) {
            $q->where('files.type', '=', 'image')->where('files.owner_type', '=', 'item');
        }])->with('marketplaceItems')->paginate(10)->toJson();

        return $items;
    }

    public function categories()
    {
        $categories = $this->category->has('items')->paginate(1)->toJson();
        return $categories;
    }

    public function categoriesSearch(Request $request)
    {
        $cCategory = new CCategory();
        $options = $cCategory->standardize_search($request);
        $categories = $cCategory->find($options);

        return response()->json(Api::success('Categories search result.', $categories, $cCategory->get_paginator()->toArray()));
    }

    public function itemsSearch(Request $request)
    {
        $options = $this->cItem->standardize_search($request);

        $items = $this->cItem->find($options);

        return response()->json(Api::success('Items search result.', $items, $this->cItem->get_paginator()->toArray()));
    }

    public function ordersSearch(Request $request)
    {
        $options = $this->cOrder->standardize_search($request);

        $orders = $this->cOrder->find($options);

        return response()->json(Api::success('Orders search result.', $orders, $this->cOrder->get_paginator()->toArray()));
    }

    public function discountsSearch(Request $request)
    {
        $cDiscount = new CDiscount();
        $options = $cDiscount->standardize_search($request);

        $discounts = $cDiscount->find($options);

        return response()->json(Api::success('Discounts search result.', $discounts, $cDiscount->get_paginator()->toArray()));
    }

    //Reports
    public function saleDateRange(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $s_date = new CCarbon($request->start_date);
        $e_date = new CCarbon($request->end_date);

        if ($s_date > $e_date) {
            return Api::error('001', 'End date could not be less than start date');
        }

        if ($s_date->toDateTimeString() == true && $e_date->toDateTimeString() == true) {
            $start_date = $s_date->toDateTimeString();
            $end_date = $e_date->toDateTimeString();
            if (!$request->export) {
                $options['where'] = [
                    ['created_at', '>=', $start_date],
                    ['created_at', '<=', $end_date],
                    ['status', 'paid']
                ];
                $options['with'] = ['orderItems', 'customer'];
                $options['orderby'] = [
                    'created_at' => 'DESC',
                ];
                $options['limit'] = $request->get('limit', 10);
                $options['page'] = $request->get('page', 1);

                $orders = $this->cOrder->find($options);

                return response()->json(Api::success('Sales in this range of dates.', $orders, $this->cOrder->get_paginator()->toArray()));

            } else {
                dispatch(new ExportData(\Auth::user(), 'orders', ['start_date' => $start_date, 'end_date' => $end_date]));
                return Api::success('Sales in this range of dates', ['exported' => true]);
            }


        } else {
            return Api::error('001', 'Error getting sales on this range of dates');
        }
    }

    public function productsSoldDateRange(Request $request)
    {
        $validatedData = $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);

        $s_date = new CCarbon($request->start_date);
        $e_date = new CCarbon($request->end_date);

        if ($s_date > $e_date) {
            return Api::error('001', 'End date could not be less than start date');
        }

        if ($s_date->toDateTimeString() == true && $e_date->toDateTimeString() == true) {
            $start_date = $s_date->toDateTimeString();
            $end_date = $e_date->toDateTimeString();
            if (!$request->export) {
                $options['where'] = [
                    ['created_at', '>=', $start_date],
                    ['created_at', '<=', $end_date],
                ];
                $options['orderby'] = [
                    'name' => 'ASC',
                ];
                $options['group_by'] = $request->get('group_by', []);;
                $options['fields_raw'] = $request->get('fields_raw', '*');
                $options['limit'] = $request->get('limit', 10);
                $options['page'] = $request->get('page', 1);
                $cOrderItem = new COrderItem();
                
                $products = $cOrderItem->find($options);

                return response()->json(Api::success('Products sold in this range of dates.', $products, $cOrderItem->get_paginator()->toArray()));

            } else {
                dispatch(new ExportData(\Auth::user(), 'products_sold', ['start_date' => $start_date, 'end_date' => $end_date]));
                return Api::success('Products Solds in this range of dates', ['exported' => true]);
            }


        } else {
            return Api::error('001', 'Error getting sales on this range of dates');
        }
    }

    public function subscribersSearch(Request $request)
    {
        if ($request->export == null) {
            $cSubscriber = new CSubscribers();
            $options = $cSubscriber->standardize_search($request);

            $subscribers = $cSubscriber->find($options);

            return response()->json(Api::success('Subscribers search result.', $subscribers, $cSubscriber->get_paginator()->toArray()));
        } else {
            dispatch(new ExportData(\Auth::user(), 'subscribers'));
            return Api::success('Export Customers', ['exported' => true]);
        }
    }

    public function getCarrierServices(Request $request, $carrier)
    {
        $shipping_services = [];
        switch ($carrier) {
            case 'ups':
                if (config('shipping.ups.api_integration') == true) {

                    $data['customer_address'] = $request->input('customer_address', '');
                    $data['customer_city'] =$request->input('customer_city', '');
                    $data['customer_zip'] = $request->input('customer_zip', '');
                    $data['customer_state'] = $request->input('customer_state', '');
                    $data['customer_country'] = $request->input('customer_country', '');

                    if (config('shipping.ups.shipper_address')) {
                        $items = $request->input('items', []);
                        foreach($items as $item){
                            $ids[] = $item['item_id'];
                        }
                        $cart_items = Item::whereIn('id', $ids)->get()->toArray();
                       
                        if (count($cart_items) > 0) {
                            $length = reset($cart_items)['length'];
                            $width = 0.0;
                            $height = reset($cart_items)['height'];
                            $weight = 0.0;

                            foreach ($cart_items as $key => $cart_item) {
                                foreach($items as $item){
                                    if($item['item_id'] == $cart_item['id']){
                                        $qty = $item['qty'];
                                    }
                                }
                                $width += $cart_item['width'] * $qty;
                                $weight += $cart_item['weight'] * $qty;
                                if ($cart_item['length'] > $length) {
                                    $length = $cart_item['length'];
                                }
                                if ($cart_item['height'] > $height) {
                                    $height = $cart_item['height'];
                                }
                            }
                            $data['package_length'] = (string)$length;
                            $data['package_width'] = (string)$width;
                            $data['package_height'] = (string)$height;
                            $data['package_weight'] = (string)$weight;

                            $data['shipper_address'] = config('shipping.ups.shipper_address.address');
                            $data['shipper_city'] = config('shipping.ups.shipper_address.city');
                            $data['shipper_zip'] = config('shipping.ups.shipper_address.zip');
                            $data['shipper_state'] = config('shipping.ups.shipper_address.state');
                            $data['shipper_country'] = config('shipping.ups.shipper_address.country');
                            
                        }
                        $shipping_services = $this->shipping_ups->getShippingRates($data);
                    }
                }
                break;
        }
        return response()->json(Api::success('Shipping services', $shipping_services));
    }

    public function usersSearch(Request $request)
    {
        if ($request->export == null) {
            $cUser = new CUser();
            $options = $cUser->standardize_search($request);
            $users = $cUser->find($options);
            foreach($users as $key => $user){
                if(isset($user['orders']) && count($user['orders']) > 0){
                    $res = [];
                    foreach($user['orders'] as $order){
                        if($order['status'] == 'paid'){
                            $res[] = $order;
                        }
                    }
                    $users[$key]['orders'] = $res;
                }
            }
            return response()->json(Api::success('Users search result.', $users, $cUser->get_paginator()->toArray()));
        } else {
            dispatch(new ExportData(\Auth::user(), 'customers'));
            return Api::success('Export Customers', ['exported' => true]);
        }

    }

    public function locationSearch(Request $request)
    {
        $cLocation = new CLocation();
        $options = $cLocation->standardize_search($request);

        $locations = $cLocation->find($options);

        return response()->json(Api::success('Locations search result.', $locations, $cLocation->get_paginator()->toArray()));
    }

    public function getTax(Request $request){
        $data = $request->all();
        $store = StoreLocation::where([['country',$request->input('shipping_country')],['state', $request->input('shipping_state')],['city',$request->input('shipping_city')]])
								->union(StoreLocation::where([['country',$request->input('shipping_country')],['state', $request->input('shipping_state')]]))
								->union(StoreLocation::where([['country',$request->input('shipping_country')],['state', null]]))
                                ->get();
        if($store && isset($store[0])){
            return Api::success('Tax', ['tax' => $store[0]->tax, 'id' => $store[0]->id]);
        }else{
            $rest = Tax::where([['country', null],['state',null],['city', null]])->first();
            if($rest){
                return Api::success('Tax', ['tax' => $rest->tax, 'id' => $rest->id]);
            }
            return Api::success('Tax', ['tax' => 0, 'id' => 0]);
        }
    }

    public function storeTaxes(Request $request){

        $data = $request->all();
        if(isset($data['world']) && $data['world']){
            $data['label'] = 'Rest of World';
            $data['country'] = null;
        }else{
            $data['label'] = $data['country'];
            if(isset($data['state']) && $data['state']){
                $data['label'] .= ', ' .$data['state'];
            }
            if(isset($data['city']) && $data['city']){
                $data['label'] .= ', ' .$data['city'];
            }
        }
        $cTaxes = new CTax();
        $res = Tax::where('label', $data['label'])->first();
        if($res){
            $data['id'] = $res->id;
            $result = $cTaxes->updateModel($data);
            $store = StoreLocation::where('tax_id', $res->id)
                                    ->update(['tax' => $data['tax']]);
        }else{
            $result = $cTaxes->createModel($data);
            // $store = StoreLocation::whereRaw('LOWER(country) like ? AND LOWER(state) like ? AND LOWER(city) like ?', [$data['country'], $data['state'], $data['city']])
            //                         ->orWhereRaw('LOWER(country) like ? AND LOWER(state) like ? AND tax = \'"', [$data['country'], $data['state']])
            //                         ->orWhereRaw('LOWER(country) like ? AND tax = ""', [$data['country']])
            //                         ->update(['tax' => $data['tax'], 'tax_id' => $result['id']]);
            \DB::update('UPDATE store_locations SET tax = ?, tax_id = ? where (LOWER(country) like \'?\' AND LOWER(state) like \'?\' AND LOWER(city) like \'?\') OR (LOWER(country) like \'?\' AND LOWER(state) like \'?\' AND tax = 0.00) OR (LOWER(country) like \'?\' AND tax = 0.00)',[$data['tax'], $result['id'], strtolower($data['country']), strtolower($data['state']), strtolower($data['city']), strtolower($data['country']), strtolower($data['state']), strtolower($data['country'])]);
        }
        return  Api::success('Tax created',$result);
        
    }
    public function updateTaxes(Request $request){
        $data = $request->all();
        if(isset($data) && $data > 0){
            if(isset($data['world']) && $data['world']){
                $data['label'] = 'Rest of World';
                $data['country'] = null;
            }else{
                $data['label'] = $data['country'];
                if(isset($data['state']) && $data['state']){
                    $data['label'] .= ', ' .$data['state'];
                }
                if(isset($data['city']) && $data['city']){
                    $data['label'] .= ', ' .$data['city'];
                }
            }
            $cTaxes = new CTax();
            $res = Tax::where('label', $data['label'])->first();
            if($res && $res->id != $data['id']){
                return Api::error('001', 'Location is already exist with a tax');
            }
            $result = $cTaxes->updateModel($data);
            $store = StoreLocation::where('tax_id', $data['id'])
                                    ->update(['tax' => $data['tax']]);

            return  Api::success('Tax created',$result);
        }

        return Api::error('001', 'Invalid Query format');

    }

    public function deleteTax($id)
    {
        if($id > 0){
            $tax = Tax::where('id', $id)->first();
            if($tax){
                $tax->delete();
            }
            return Api::success('Tax deleted',$id);
        }
        return Api::error('001', 'Invalid Query format');
    }

    public function taxesSearch(Request $request)
    {
        $cTaxes = new CTax();
        $options = $cTaxes->standardize_search($request);

        $taxes = $cTaxes->find($options);

        return response()->json(Api::success('Taxes search result.', $taxes, $cTaxes->get_paginator()->toArray()));
    }

    public function storeLocations(Request $request){

        $rules = [
            'label' => 'required|max:255',
            'country' => 'required|max:100',
            'state' => 'required|max:100',
            'city' => 'required|max:100',
            'address' => 'required|max:255',
            'zip' => 'required|max:20',
        ];
        $data = $request->all();

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cStoreLocation = new CLocation();
            foreach($data as $key=>$val){
                $data[$key] = strtolower($val);
            }
            $res = StoreLocation::whereRaw('LOWER(country) like ?', [$data['country']])
                                ->whereRaw('LOWER(state) like ?', [$data['state']])
                                ->whereRaw('LOWER(city) like ?', [$data['city']])
                                ->whereRaw('LOWER(address) like ?', [$data['address']])
                                ->where('zip', $data['zip'])
                                ->first();
            
            
            $tax = Tax::whereRaw('LOWER(country) like ? AND LOWER(state) like ? AND LOWER(city) like ?', [$data['country'], $data['state'], $data['city']])
								->union(Tax::whereRaw('LOWER(country) like ? AND LOWER(state) like ?', [$data['country'], $data['state']]))
								->union(Tax::whereRaw('LOWER(country) like ?', [$data['country']]))
                                ->get();

            if($tax && count($tax) > 0){
                $data['tax'] = $tax[0]->tax;
                $data['tax_id'] = $tax[0]->id;
            }else{
                $data['tax'] = 0.00;
            }

            
            if($res){
                $data['id'] = $res->id;
                $result = $cStoreLocation->updateModel($data);
            }else{
                
                if(isset($data['id'])){
                    unset($data['id']);
                }
                $result = $cStoreLocation->createModel($data);
            }

            return  Api::success('Store Location created',$result);
        }

        return  Api::error('005',$validator->errors(), $data);
        
    }

    public function updateLocations(Request $request){

        $rules = [
            'id' => 'required|numeric',
            'label' => 'required|max:255',
            'country' => 'required|max:100',
            'state' => 'required|max:100',
            'city' => 'required|max:100',
            'address' => 'required|max:255',
            'zip' => 'required|max:20',
        ];
        $data = $request->all();

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cStoreLocation = new CLocation();
            foreach($data as $key=>$val){
                $data[$key] = strtolower($val);
            }
            $res = StoreLocation::whereRaw('LOWER(country) like ?', [$data['country']])
                                ->whereRaw('LOWER(state) like ?', [$data['state']])
                                ->whereRaw('LOWER(city) like ?', [$data['city']])
                                ->whereRaw('LOWER(address) like ?', [$data['address']])
                                ->where('zip', $data['zip'])
                                ->first();
            $tax = Tax::whereRaw('LOWER(country) like ? AND LOWER(state) like ? AND LOWER(city) like ?', [$data['country'], $data['state'], $data['city']])
                        ->union(Tax::whereRaw('LOWER(country) like ? AND LOWER(state) like ?', [$data['country'], $data['state']]))
                        ->union(Tax::whereRaw('LOWER(country) like ?', [$data['country']]))
                        ->get();
            if($tax){
                $data['tax'] = $tax[0]->tax;
                $data['tax_id'] = $tax[0]->id;
            }else{
                $data['tax'] = 0.00;
            }


            if($res && $res->id != $data['id']){
                return Api::error('001', 'Store Location is already exist.');
            }else{
                $result = $cStoreLocation->updateModel($data);
            }

            return  Api::success('Store Location updated',$result);
        }

        return  Api::error('005',$validator->errors(), $data);
        
    }

    public function deleteLocation($id)
    {
        if($id > 0){
            $storeLocation = StoreLocation::where('id', $id)->first();
            if($storeLocation){
                $storeLocation->delete();
            }
            return Api::success('Store location deleted',$id);
        }
        return Api::error('001', 'Invalid Query format');
    }

    //Package Types
    public function storePackage(Request $request){

        $data = $request->all();
        $rules = [
            'name' => 'required',
            'weight' => 'required',
            'width' => 'required',
            'length' => 'required',
            'height' => 'required',
        ];
        $cPackage = new CPackage();
        $result = $cPackage->createModel($data, $rules);
        return  Api::success('Package created',$result);
        
    }
    public function updatePackage(Request $request){

        $data = $request->all();
        $rules = [
            'id' => 'required',
            'name' => 'required',
            'weight' => 'required',
            'width' => 'required',
            'length' => 'required',
            'height' => 'required',
        ];
        $cPackage = new CPackage();
        $result = $cPackage->updateModel($data, $rules);
        return  Api::success('Package updated',$result);
        
    }
    public function deletePackage($id)
    {
        if($id > 0){
            $cPackage = new CPackage();
            $result = $cPackage->deleteModel($id);
            return  Api::success('Package deleted',$result);

        }
        return Api::error('001', 'Invalid Query format');
    }
    
    public function packageSearch(Request $request)
    {
        $cPackage = new CPackage();
        $options = $cPackage->standardize_search($request);
        $packages = $cPackage->find($options);
        return response()->json(Api::success('Packages search result.', $packages, $cPackage->get_paginator()->toArray()));
    }

    //Options
    public function optionsSearch(Request $request)
    {
        $cOption = new COption();
        $options = $cOption->standardize_search($request);
        $options = $cOption->find($options);
        return response()->json(Api::success('Options search result.', $options, $cOption->get_paginator()->toArray()));
    }

    public function deleteOption($id)
    {
        if($id > 0){
            $cOption = new COption();
            $option = Option::find($id);
            $cOption->deleteModel($id);
            if($option){
                dispatch(new UpdateItemsOptions($option->label));
            }
            return  Api::success('Option deleted',['id' => $id]);

        }
        return Api::error('001', 'Invalid Query format');
    }

    public function activitiesLogSearch(Request $request)
    {
        $cActivities = new CUserActivityLog();
        $options = $cActivities->standardize_search($request);
        if (!$request->export) {
            $activities = $cActivities->find($options);
            return response()->json(Api::success('Activities search result.', $activities, $cActivities->get_paginator()->toArray()));
        } else {
            dispatch(new ExportData(\Auth::user(), 'activities', $options));
            return Api::success('Activities Logs', ['exported' => true]);
        }
       
    }

}
