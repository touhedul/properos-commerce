<?php

namespace Properos\Commerce\Classes;

use Properos\Commerce\Models\Plan;
use Properos\Base\Classes\Filters;
use Properos\Base\Classes\Base;
use Properos\Commerce\Models\PlanItem;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\Discount;
use Properos\Base\Classes\Helper;
use Properos\Base\Exceptions\ApiException;
use Illuminate\Support\Str;

class CPlan extends Base
{
    public function __construct()
    {
        parent::__construct(Plan::class, 'Plan');
    }

    public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            switch ($key) {
                case 'price':
                    $this->fillable[$key] = '0';
                    break;
                case 'interval_count':
                    $this->fillable[$key] = 1;
                    break;
                case 'interval':
                    $this->fillable[$key] = 'month';
                    break;
                case 'status':
                    $this->fillable[$key] = 'public';
                    break;
                default:
                    $this->fillable[$key] = '';
                    break;
            }
        }
    }

    public function create(array $data)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'interval_count' => 'required|integer',
            'interval' => 'required|in:day,week,month,year,contact_us',
            'status' => 'required|in:private,public,cancelled',
        ];

        $item = new Item();
        $item->name = $data['title'];
        $sort_name = $this->createShortName($data['title']);
        $current_item = Item::where('short_name', $sort_name)->first();
        if ($current_item) {
            $sort_name = $sort_name . '_' . Str::random(3);
        }
        $item->short_name = $sort_name;
        $item->description = $data['description'];
        $item->price = $data['price'];
        $item->taxable = 0;
        $item->active = 0;
        $item->type = 'plan';
        $item->publish_date = date('Y-m-d H:i:s');
        $item->save();
        $item->sku = Helper::base2base(1000 * $item->id, 10, 36) . strtoupper(Str::random(4));
        $item->save();

        $data['item_id'] = $item->id;
        $plan = $this->createModel($data, $rules);

        if(isset($data['items_list']) && count($data['items_list'])>0){
            foreach($data['items_list'] as $item){
                $plaItem = new PlanItem();
                $plaItem->plan_id = $plan['id'];
                $plaItem->item_id = $item['id'];
                $plaItem->name = $item['name'];
                $plaItem->qty = $item['qty'];
                $plaItem->price = 0.00;
                $plaItem->save();
            }
        }

        if(isset($data['discount']) && isset($data['discount']['discount_type'])){
            switch($data['discount']['discount_type']){
                case 'percentage':
                case 'fixed_amount':
                case 'free_shipping':
                    $discount = new Discount();
                    $discount->type ='plan';
                    $discount->user_id =$plan['id'];
                    $discount->discount_type =$data['discount']['discount_type'];
                    $discount->discount_value =$data['discount']['discount_value'];
                    $discount->min_requirement =$data['discount']['min_requirement'];
                    $discount->requirement_amount =$data['discount']['requirement_amount'];
                    $discount->save();
                    break;
            }

        }

        return  $plan;
    }

    public function update(array $data)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric',
            'interval_count' => 'required|integer',
            'interval' => 'required|in:day,week,month,year,contact_us',
            'status' => 'required|in:private,public, cancelled',
        ];

        PlanItem::where('plan_id',$data['id'])->delete();

        if(isset($data['items_list']) && count($data['items_list'])>0){
            foreach($data['items_list'] as $item){
                $plaItem = new PlanItem();
                $plaItem->plan_id = $data['id'];
                $plaItem->item_id = $item['id'];
                $plaItem->name = $item['name'];
                $plaItem->qty = $item['qty'];
                $plaItem->price = 0.00;
                $plaItem->save();
            }
        }

        $plan = $this->updateModel($data, $rules);

        if(isset($data['discount']) && isset($data['discount']['discount_type'])){
            switch($data['discount']['discount_type']){
                case 'percentage':
                case 'fixed_amount':
                case 'free_shipping':
                    $discount = Discount::where([['user_id',$data['id'],['type','plan']]])->first();
                    $discount->discount_type =$data['discount']['discount_type'];
                    $discount->discount_value =$data['discount']['discount_value'];
                    $discount->min_requirement =$data['discount']['min_requirement'];
                    $discount->requirement_amount =$data['discount']['requirement_amount'];
                    $discount->save();
                    break;
            }

        }

        return  $plan;
    }

    public function delete($id)
    {
        return $this->deleteModel($id);
    }

    // public function formatting($data)
    // {
    //     $res = [];

    //     foreach ($data as $key => $value) {
    //         switch ($key) {
    //             case 'title':
    //                 $res[$key] = Filters::cleanString($value,['strtolower']);
    //                 break;
    //             default:
    //                 $res[$key] = $value;
    //                 break;
    //         }
    //     }
    //     return $res;
    // }

    public function createShortName($name)
    {
        if (strlen($name) > 0) {
            return str_replace(" ", "_", trim(strtolower($name)));
        }
    }
}
