<?php

namespace Properos\Commerce\Controllers\Discount;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Classes\CDiscount;
use Properos\Base\Classes\Api;
use Properos\Commerce\Models\Discount;
use Properos\Commerce\Models\Order;
use Properos\Base\Classes\Helper;

class DiscountController extends Controller
{
    public function store(Request $request){
        $data = $request->all();
        $rules = [
            "code" => 'required|string',
            "discount_type" => 'required|in:percentage,fixed_amount,free_shipping,buy_x_get_y',
            "discount_value" => 'required|numeric',
            "apply_to" => 'required|in:order,products,categories',
            "get_what" => 'nullable|in:same,other',
            "min_requirement" => 'required|in:none,purchase_amount,qty_items',
            "eligible_customers" => 'required|in:everyone,specific,group',
            "eligible_locations" => 'required|in:any,specific',
            "usage_limit" => 'nullable|numeric',
            "user_limit" => 'required|numeric',
            "start_date" => 'required|string',
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cDiscount = new CDiscount();
            $result = $cDiscount->store($data);
            return Api::success('Discount created successfully', $result);
        }

        throw new ApiException($validator->errors(),'001',$data);
    }

    public function update(Request $request, $id){
        $data = $request->all();
        $data['id'] = $id;
        $rules = [
            "id" => 'required|numeric|min:1',
            "code" => 'required|string',
            "discount_type" => 'required|in:percentage,fixed_amount,free_shipping,buy_x_get_y',
            "discount_value" => 'required|numeric',
            "apply_to" => 'required|in:order,products,categories',
            "get_what" => 'nullable|in:same,other',
            "min_requirement" => 'required|in:none,purchase_amount,qty_items',
            "eligible_customers" => 'required|in:everyone,specific,group',
            "eligible_locations" => 'required|in:any,specific',
            "usage_limit" => 'nullable|numeric',
            "user_limit" => 'required|numeric',
            "start_date" => 'required|string',
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            $cDiscount = new CDiscount();
            $result = $cDiscount->update($data);
            return Api::success('Discount update successfully', $result);
        }

        throw new ApiException($validator->errors(),'001',$data);
    }

    public function destroy($id)
    {
        if ($id > 0) {
            $cDiscount = new CDiscount();
            $result = $cDiscount->destroy($id);
            if ($result == true) {
                return Api::success('Discount deleted successfully', []);
            } else {
                return Api::error('001', 'Error removing the discount');
            }
        }
    }

    public function checkCode(Request $request, $code){
        $discount = Discount::where('code',$code)->first();
        
        $order = $request->all();
        if($discount){
            $cDiscount = new CDiscount();
            return $cDiscount->processDiscount($discount, $order);
            

            
        }else{
            $getOrder = Order::where('id',$order['id'])->first();
            if($getOrder){
                $getOrder->discount_code = null;
                $getOrder->total_amount = $getOrder->total_amount + $getOrder->discount_amount;
                $getOrder->discount_amount = 0.00;
                $getOrder->save();
            }
        }
        return Api::error('020', 'Invalid coupon', ['code'=>$code]);
    }
}
