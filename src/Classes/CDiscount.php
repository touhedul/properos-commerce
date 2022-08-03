<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Discount;
use Properos\Commerce\Models\DiscountCustomer;
use Properos\Commerce\Models\DiscountLocation;
use Properos\Commerce\Models\DiscountApplicable;
use Properos\Commerce\Models\DiscountLog;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Models\Item;
use Properos\Users\Models\User;
use Properos\Commerce\Models\Order;
use Properos\Base\Classes\Helper;
use Properos\Base\Classes\Api;
use Properos\Commerce\Models\StoreLocation;

class CDiscount extends Base
{
	function __construct()
	{
		parent::__construct(Discount::class, 'Discount');     
	}

	public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }

	public function store(array $data)
	{
		$discount = new Discount();

		if(isset($data['code'])){
			$discount->code = $data['code'];
		}
		$discount->user_id = \Auth::user()->id;
		$discount->save();
		if(isset($data['discount_type'])){
			$discount->discount_type = $data['discount_type'];

			if($data['discount_type'] == 'buy_x_get_y'){
				if(isset($data['buy_qty'])){
					$discount->buy_qty = $data['buy_qty'];
				}
				if(isset($data['get_qty'])){
					$discount->get_qty = $data['get_qty'];
				}
				if(isset($data['get_what'])){
					$discount->get_what = $data['get_what'];

					if(isset($data['get_list'])){
						foreach($data['get_list'] as $list){
							$discount->get_item_id = $list['id'];
							$discount->get_item_name = $list['name'];
						}
					}
				}
			}
		}
		if(isset($data['discount_value'])){
			$discount->discount_value = $data['discount_value'];
		}
		if(isset($data['apply_to'])){
			$discount->apply_to = $data['apply_to'];

			if($data['apply_to'] != 'order' && isset($data['applicables_list'])){
				if($data['apply_to'] == 'products'){
					$type = 'item';
				}else{
					$type = 'category';
				}
				$insert = [];
				$sql ='';
				foreach($data['applicables_list'] as $app){
					$insert[] = $discount->id;
					$insert[] = $discount->code;
					$insert[] = $app['id'];
					$insert[] = $type;
					$sql .= '(?,?,?,?,NOW(),NOW()),';
				}
				\DB::insert("INSERT IGNORE INTO discount_applicables (discount_id, discount_code, applicable_id, applicable_type, created_at, updated_at) values ".rtrim($sql, ','), $insert);
			}
		}

		if(isset($data['min_requirement'])){
			$discount->min_requirement = $data['min_requirement'];

			if($data['min_requirement'] != 'none'){
				if(isset($data['requirement_amount'])){
					$discount->requirement_amount = $data['requirement_amount'];
				}
			}
		}

		if(isset($data['eligible_customers'])){
			$discount->eligible_customers = $data['eligible_customers'];

			if($data['eligible_customers'] != 'everyone'  && isset($data['customers_list'])){
				$insert = [];
				$sql ='';
				foreach($data['customers_list'] as $app){
					$insert[] = $discount->id;
					$insert[] = $discount->code;
					$insert[] = $app['id'];
					$sql .= '(?,?,?,NOW(),NOW()),';
				}
				\DB::insert("INSERT IGNORE INTO discount_customers (discount_id, discount_code, customer_id, created_at, updated_at) values ".rtrim($sql, ','), $insert);
			}
		}

		if(isset($data['eligible_locations'])){
			$discount->eligible_locations = $data['eligible_locations'];

			if($data['eligible_locations'] != 'any' && isset($data['locations_list'])){
				$insert = [];
				$sql ='';
				foreach($data['locations_list'] as $app){
					$insert[] = $discount->id;
					$insert[] = $discount->code;
					if(isset($app['country'])){
						$insert[] = (isset($app['country']['name'])) ? $app['country']['name'] : null;
						$insert[] = (isset($app['country']['code'])) ? $app['country']['code'] : null;
					}else{
						$insert[] = null;
						$insert[] = null;
					}
					if(isset($app['state'])){
						$insert[] = (isset($app['state']['name'])) ? $app['state']['name'] : null;
						$insert[] = (isset($app['state']['abbr'])) ? $app['state']['abbr'] : null;
					}else{
						$insert[] = null;
						$insert[] = null;
					}
					if(isset($app['city'])){
						$insert[] = (isset($app['city']['name'])) ? $app['city']['name'] : null;
					}else{
						$insert[] = null;
					}
					
					$sql .= '(?,?,?,?,?,?,?,NOW(),NOW()),';
				}
				\DB::insert("INSERT IGNORE INTO discount_locations (discount_id, discount_code, country_name, country_code, state_name, state_abbr, city_name, created_at, updated_at) values ".rtrim($sql, ','), $insert);
			}
		}

		if(isset($data['usage_limit'])){
			$discount->usage_limit = $data['usage_limit'];
		}
		if(isset($data['user_limit'])){
			$discount->user_limit = $data['user_limit'];
		}

		if(isset($data['start_date'])){
			$discount->start_date = date('Y-m-d H:i:s', strtotime($data['start_date']));
		}
		if(isset($data['end_date'])){
			$discount->end_date = date('Y-m-d H:i:s', strtotime($data['end_date']));
		}
		if($discount->save()){
			DiscountLog::create([
				"discount_id" => $discount->id,
				"discount_code" => $discount->code,
				"user_id" => \Auth::user()->id,
				"action" => 'create',
				"discount_data" => json_encode($data),
			]);
		}

		return $discount;
	}

	public function update(array $data)
	{
		$discount = Discount::find($data['id']);

		if ($discount) {

			if(isset($data['code'])){
				if($discount->code != null && $discount->code != $data['code']){
					$discount->usage_count = 0;
				}
				$discount->code = $data['code'];
			}
			
			if(isset($data['discount_type'])){
				$discount->discount_type = $data['discount_type'];

				$discount->get_item_id = null;
				$discount->get_item_name = null;
	
				if($data['discount_type'] == 'buy_x_get_y'){
					if(isset($data['buy_qty'])){
						$discount->buy_qty = $data['buy_qty'];
					}
					if(isset($data['get_qty'])){
						$discount->get_qty = $data['get_qty'];
					}
					if(isset($data['get_what'])){
						$discount->get_what = $data['get_what'];

						if(isset($data['get_list'])){
							foreach($data['get_list'] as $list){
								$discount->get_item_id = $list['id'];
								$discount->get_item_name = $list['name'];
							}
						}
					}
				}
			}
			if(isset($data['discount_value'])){
				$discount->discount_value = $data['discount_value'];
			}
			if(isset($data['apply_to'])){
				$discount->apply_to = $data['apply_to'];
				
				DiscountApplicable::where([['discount_id',$discount->id], ['discount_code', $discount->code]])->delete();

				if($data['apply_to'] != 'order' && isset($data['applicables_list']) && count($data['applicables_list']) >0){
					if($data['apply_to'] == 'products'){
						$type = 'item';
					}else{
						$type = 'category';
					}
					$insert = [];
					$sql ='';
					foreach($data['applicables_list'] as $app){
						$insert[] = $discount->id;
						$insert[] = $discount->code;
						$insert[] = $app['id'];
						$insert[] = $type;
						$sql .= '(?,?,?,?,NOW(),NOW()),';
					}
					\DB::insert("INSERT IGNORE INTO discount_applicables (discount_id, discount_code, applicable_id, applicable_type, created_at, updated_at) values ".rtrim($sql, ','), $insert);
				}
			}
	
			if(isset($data['min_requirement'])){
				$discount->min_requirement = $data['min_requirement'];
	
				if($data['min_requirement'] != 'none'){
					if(isset($data['requirement_amount'])){
						$discount->requirement_amount = $data['requirement_amount'];
					}
				}
			}
	
			if(isset($data['eligible_customers'])){
				$discount->eligible_customers = $data['eligible_customers'];
				DiscountCustomer::where([['discount_id',$discount->id], ['discount_code', $discount->code]])->delete();

				if($data['eligible_customers'] != 'everyone'  && isset($data['customers_list'])  && count($data['customers_list']) >0){
					$insert = [];
					$sql ='';
					foreach($data['customers_list'] as $app){
						$insert[] = $discount->id;
						$insert[] = $discount->code;
						$insert[] = $app['id'];
						$sql .= '(?,?,?,NOW(),NOW()),';
					}
					\DB::insert("INSERT IGNORE INTO discount_customers (discount_id, discount_code, customer_id, created_at, updated_at) values ".rtrim($sql, ','), $insert);
				}
			}
	
			if(isset($data['eligible_locations'])){
				$discount->eligible_locations = $data['eligible_locations'];
				
				DiscountLocation::where([['discount_id',$discount->id], ['discount_code', $discount->code]])->delete();

				if($data['eligible_locations'] != 'any' && isset($data['locations_list'])  && count($data['locations_list']) >0){
					$insert = [];
					$sql ='';
					foreach($data['locations_list'] as $app){
						$insert[] = $discount->id;
						$insert[] = $discount->code;
						if(isset($app['country'])){
							$insert[] = (isset($app['country']['name'])) ? $app['country']['name'] : null;
							$insert[] = (isset($app['country']['code'])) ? $app['country']['code'] : null;
						}else{
							$insert[] = null;
							$insert[] = null;
						}
						if(isset($app['state'])){
							$insert[] = (isset($app['state']['name'])) ? $app['state']['name'] : null;
							$insert[] = (isset($app['state']['abbr'])) ? $app['state']['abbr'] : null;
						}else{
							$insert[] = null;
							$insert[] = null;
						}
						if(isset($app['city'])){
							$insert[] = (isset($app['city']['name'])) ? $app['city']['name'] : null;
						}else{
							$insert[] = null;
						}
						
						$sql .= '(?,?,?,?,?,?,?,NOW(),NOW()),';
					}
					\DB::insert("INSERT IGNORE INTO discount_locations (discount_id, discount_code, country_name, country_code, state_name, state_abbr, city_name, created_at, updated_at) values ".rtrim($sql, ','), $insert);
				}
			}
	
			if(isset($data['usage_limit'])){
				$discount->usage_limit = $data['usage_limit'];
			}
			if(isset($data['user_limit'])){
				$discount->user_limit = $data['user_limit'];
			}
	
			if(isset($data['start_date'])){
				$discount->start_date = date('Y-m-d H:i:s', strtotime($data['start_date']));
			}
			if(isset($data['end_date'])){
				$discount->end_date = date('Y-m-d H:i:s', strtotime($data['end_date']));
			}else{
				$discount->end_date = null;
			}
			if($discount->save()){
				DiscountLog::create([
					"discount_id" => $discount->id,
					"discount_code" => $discount->code,
					"user_id" => \Auth::user()->id,
					"action" => 'update',
					"discount_data" => json_encode($data),
				]);
			}
		return $discount;
		}
	}

	public function destroy($id)
	{
		$discount = Discount::find($id);
		DiscountApplicable::where([['discount_id',$discount->id], ['discount_code', $discount->code]])->delete();
		DiscountCustomer::where([['discount_id',$discount->id], ['discount_code', $discount->code]])->delete();
		DiscountLocation::where([['discount_id',$discount->id], ['discount_code', $discount->code]])->delete();
		$result = $discount->delete();
		if($result){
			DiscountLog::create([
				"discount_id" => $discount->id,
				"discount_code" => $discount->code,
				"user_id" => \Auth::user()->id,
				"action" => 'delete',
			]);
		}
		return $result;
	}

	public function checkDiscount($discount, $order, $return = true){
		if($discount['start_date'] > date('Y-m-d H:i:s')){
			if($return)
				throw new ApiException('Invalid discount code','020', []);
			return false;
		}
		if($discount['end_date'] != null && $discount['end_date'] != ''){
			if($discount['end_date'] < date('Y-m-d H:i:s')){
				if($return)
					throw new ApiException('Invalid discount code','020',[]);
				return false;
			}
		}
		
		if($discount['apply_to'] != 'order' && $discount['discount_type'] != 'buy_x_get_y'){
			$ids = [];
			if($discount['apply_to'] == 'categories'){
				foreach($order['order_items'] as $v){
					if(!isset($v['order_id'])){
						$cat = Item::where('id', $v['id'])->first();
					}else{
						$cat = Item::where('id', $v['item_id'])->first();
					}
					$ids[] = $cat->category_id;
					$items_id[] = $cat->id;
				}
				$type= 'category';
			}else{
				foreach($order['order_items'] as $v){
					$ids[] = $v['item_id'];
				}
				$type= 'item';
			}
			
			$res = DiscountApplicable::where([['discount_code', $discount['code']],['applicable_type', $type]])
										->whereIn('applicable_id', $ids)->get();
		
			if(!$res){
				if($return)
					throw new ApiException('Discount does not apply','021',$order);
				return false;
			}
		}
		
		if($discount['min_requirement'] == 'purchase_amount'){
			if($discount['requirement_amount'] > $order['total_amount']){
				if($return)
					throw new ApiException('Discount does not apply','021',$order);
				return false;
			}
		}
		
		if($discount['min_requirement'] == 'qty_items'){
			if($discount['requirement_amount'] > count($order['order_items'])){
				if($return)
					throw new ApiException('Discount does not apply','021',$order);
				return false;
			}
		}
		
		if($discount['eligible_customers'] != 'everyone'){
			// $items = $discount['applicables();
			if(\Auth::guest()){
				if($return)
					throw new ApiException('Discount does not apply','021',$order);
				return false;
			}
		
			$customer = DiscountCustomer::where('discount_code', $discount['code'])->whereIn('customer_id', [\Auth::user()->id])->first();
		
			if(!$customer){
				if($return)
					throw new ApiException('Discount does not apply','021',$order);
				return false;
			}
		}
		
		if($discount['eligible_locations'] != 'any'){
			if(isset($order['shipping_country']) || isset($order['shipping_state']) || isset($order['shipping_city'])){
				$sql = 'SELECT count(id) as total FROM discount_locations WHERE discount_code = \''.$discount['code'].'\' AND ((country_code like \''.$order['shipping_country'].'\' AND state_name IS NULL AND city_name IS NULL) OR '
						.'(country_code like \''.$order['shipping_country'].'\' AND state_abbr like \''.$order['shipping_state'].'\' AND city_name IS NULL) OR '
						.'(country_code like \''.$order['shipping_country'].'\' AND state_abbr like \''.$order['shipping_state'].'\' AND city_name like \''.$order['shipping_city'].'\'))';
			
				$location = \DB::select(\DB::raw($sql));
			
				if(isset($location) && $location[0]->total < 1){
					if($return)
						throw new ApiException('Discount does not apply','021',$order);
					return false;
				}
			}else{
				if($return)
					throw new ApiException('Discount does not apply','021',$order);
				return false;
			}
		}
		
		
		if($discount['usage_limit'] > 0 && $discount['usage_limit'] == $discount['usage_count']){
			if($return)
				throw new ApiException('Discount does not apply','021',$order);
			return false;
		}
		
		if($discount['user_limit'] > 0){
			if(!\Auth::guest()){
				$email = \Auth::user()->email;
			}else{
				if(isset($order['customer_email'])){
					$email = $order['customer_email'];
				}else{
					if($return)
						throw new ApiException('Discount does not apply','021',$order);
					return false;
				}
			}
			$orders = Order::where([['discount_code',$discount['code']],['customer_email',$email],['id','<>',$order['id']]])
							->whereNotIn('status', ['cart','cancelled'])->first();
			if($orders){
				if($return)
					throw new ApiException('Discount does not apply','021',$order);
				return false;
			}
		}

		$discount_taxable = 0.00;
		$discount_notaxable = 0.00;
		switch($discount['discount_type']){
			case 'percentage': 
			case 'free_shipping': 
				$flag = false;
				if(isset($res)){
					$itms = Item::whereIn('id', $ids)->get()->toArray();
				}else{
					$itms = $order['order_items'];
				}

				$count_ids = [];
				if($discount['apply_to'] == 'order'){
					foreach($itms as $it){
						foreach($order['order_items'] as $m){
							if(isset($res)){
								if($m['item_id'] == $it['id']){
									$qty = $m['qty'];
								}
							}else{
								$qty = $it['qty'];
							}
						}
						if((isset($it['taxable']) && $it['taxable'] > 0) || (isset($it['taxes']) && $it['taxes'] > 0)){
							if(isset($it['discount_percent']) && $it['discount_percent'] > 0){ 
								$discount_taxable += ($it['price'] - ($it['price']* $it['discount_percent']/100)) * $qty;
							}else{
								$discount_taxable += $it['price'] * $qty;
							}
						}else{
							if(isset($it['discount_percent']) && $it['discount_percent'] > 0){ 
								$discount_notaxable += ($it['price'] - ($it['price']* $it['discount_percent']/100)) * $qty;
							}else{
								$discount_notaxable += $it['price'] * $qty;
							}
						}
					}
				}else if ($discount['apply_to'] == 'categories'){
					foreach($order['order_items'] as $v){
						$count_ids[$v['item_id']] = $this->getTotalItemsAndPrice($count_ids, $v['item_id'], $v);
					}
					$type= 'category';
					$result = $this->percentageApplyTo($count_ids, $discount, $ids, $type, $items_id);
					$flag = $result['flag'];
					$products = $result['products'];
					$discount_taxable = $result['discount_taxable'];
					$discount_notaxable = $result['discount_notaxable'];

					if(!$flag){
						if($return)
							throw new ApiException('Discount does not apply','021',$order);
						return false;
					}

					$discount['discount_items'] = $products;
				}else{
					foreach($order['order_items'] as $v){
						$count_ids[$v['item_id']] = $this->getTotalItemsAndPrice($count_ids, $v['item_id'], $v);
					}
					$type= 'item';
					$result = $this->percentageApplyTo($count_ids, $discount, $ids, $type);

					$flag = $result['flag'];
					$products = $result['products'];
					$discount_taxable = $result['discount_taxable'];
					$discount_notaxable = $result['discount_notaxable'];

					if(!$flag){
						if($return)
							throw new ApiException('Discount does not apply','021',$order);
						return false;
					}

					$discount['discount_items'] = $products;
				}
				
				break;
			case 'fixed_amount': 
				// if($discount['discount_value'] > $order['total_amount']){
				// 	if($return)
				// 		throw new ApiException('Discount does not apply','021',$order);
				// 	return false;
				// }
				if(isset($res)){
					$itms = Item::whereIn('id', $ids)->get()->toArray();
				}else{
					$itms = $order['order_items'];
				}
				$count_ids = [];
				if($discount['apply_to'] == 'order'){
					foreach($itms as $it){
						foreach($order['order_items'] as $m){
							if($m['id'] == $it['id']){
								$qty = $m['qty'];
							}
						}
						if((isset($it['taxable']) && $it['taxable'] > 0) || (isset($it['taxes']) && $it['taxes'] > 0)){
							if(isset($it['discount_percent']) && $it['discount_percent'] > 0){ 
								$discount_taxable += ($it['price'] - ($it['price']* $it['discount_percent']/100)) * $qty;
							}else{
								$discount_taxable += $it['price'] * $qty;
							}
						}else{
							if(isset($it['discount_percent']) && $it['discount_percent'] > 0){ 
								$discount_notaxable += ($it['price'] - ($it['price']* $it['discount_percent']/100)) * $qty;
							}else{
								$discount_notaxable += $it['price'] * $qty;
							}
						}
					}
				}else if ($discount['apply_to'] == 'categories'){
					foreach($order['order_items'] as $v){
						$count_ids[$v['item_id']] = $this->getTotalItemsAndPrice($count_ids, $v['item_id'], $v);
					}
					$type= 'category';
					$result = $this->percentageApplyTo($count_ids, $discount, $ids, $type, $items_id);
					
					
					$flag = $result['flag'];
					$products = $result['products'];
					$discount_taxable = $result['discount_taxable'];
					$discount_notaxable = $result['discount_notaxable'];

					if(!$flag){
						if($return)
							throw new ApiException('Discount does not apply','021',$order);
						return false;
					}

					$discount['discount_items'] = $products;
				}else{
					foreach($order['order_items'] as $v){
						$count_ids[$v['item_id']] = $this->getTotalItemsAndPrice($count_ids, $v['item_id'], $v);
					}
					$type= 'item';
					$result = $this->percentageApplyTo($count_ids, $discount, $ids, $type);

					$flag = $result['flag'];
					$products = $result['products'];
					$discount_taxable = $result['discount_taxable'];
					$discount_notaxable = $result['discount_notaxable'];

					if(!$flag){
						if($return)
							throw new ApiException('Discount does not apply','021',$order);
						return false;
					}

					$discount['discount_items'] = $products;
				}
				break;
			case 'buy_x_get_y':
				$count_ids = [];
				$count_total = 0;
				if($discount['apply_to'] == 'categories'){
					foreach($order['order_items'] as $v){
						if(!isset($v['order_id'])){
							$cat = Item::where('id', $v['id'])->first();
						}else{
							$cat = Item::where('id', $v['item_id'])->first();
						}
						$ids[] = $cat->category_id;
						$items_id[] = $cat->id;
						$count_ids[$v['item_id']] = $this->getTotalItemsAndPrice($count_ids, $v['item_id'], $v);
					}
					$type= 'category';
				}else{
					foreach($order['order_items'] as $v){
						$ids[] = $v['item_id'];
						$count_ids[$v['item_id']] = $this->getTotalItemsAndPrice($count_ids, $v['item_id'], $v);
					}
					$type= 'item';
				}
				$total = 0;
				if($discount['get_what'] != 'same'){
					$item_price = Item::where('id',$discount['get_item_id'])->first();
				}
				$res = DiscountApplicable::select('applicable_id')->where([['discount_code', $discount['code']],['applicable_type', $type]])
											->whereIn('applicable_id', $ids)->groupBy('applicable_id')->pluck('applicable_id')->toArray();

				$flag = false;
				$itms = Item::whereIn('id', $ids)->get();
				
				if($discount['apply_to'] != 'order'){
					$get_total = 0;
					$get_total_items = 0;
					foreach($count_ids as $key=> $v){
						if(in_array($key, $res)){
							$count_total = $count_total + $v['count'];
						}
						if($key == $discount['get_item_id']){
							$get_total = $get_total + $v['count'];
							$get_total_items = $get_total_items + $v['count'];
							
						}
					}
					if($discount['get_what'] == 'same'){
						$get_total = $discount['get_qty'];
					}
					if($type == 'category'){
						$res = Item::select('id')->whereIn('category_id', $res)->whereIn('id', $items_id)->pluck('id')->toArray();
						$ids = $items_id;
					}
					// dd($res,$ids, isset($count_ids[$discount['get_item_id']]), $count_ids, ($discount['buy_qty']), $count_total, $discount['get_qty'],$get_total);
					if(count($res)){
						$products=[];
						$total = 0;
						$buy_get = 0;
						foreach($ids as $id){
							$taxable = 0;
							foreach($itms as $it){
								if($it->id == $id){
									$taxable = $it->taxable;
								}
							}
							// dd($count_ids);
							if ((in_array($id, $res) && isset($count_ids[$id]) && $discount['buy_qty'] <= $count_total && $discount['get_qty'] <= $get_total) || isset($count_ids[$discount['get_item_id']])){
							// if (in_array($id, $res) && isset($count_ids[$id]) && ($discount['buy_qty'] + $discount['get_qty']) <= $count_ids[$id]["count"]){
								$flag = true;
								if($discount['get_what'] != 'same'){
									$total += $count_ids[$id]["count"];
									if(in_array($id, $res) ){
										$buy_get += $count_ids[$id]["count"];
									}
									$price = (isset($item_price->discount_percent)) ? $item_price->price - ($item_price->price*$item_price->discount_percent/100) : $item_price->price;
									// $taxes =$item_price->taxable;
								}else{
									$total += $count_ids[$id]["count"];
									if(in_array($id, $res) ){
										$buy_get += $count_ids[$id]["count"];
									}

									$mod = $count_ids[$id]["count"] / ($discount['buy_qty'] + $discount['get_qty']);
									if($mod % 1 != 0){
										$p = $discount['get_qty'];
									}else{
										$p = floor($mod) * $discount['get_qty'];
									}
									
									$products[] = [
										$p, ($count_ids[$id]["price"]), $taxable
									];
								}
							}
							if($taxable > 0){
								$discount_taxable += $count_ids[$id]["price"] * $count_ids[$id]["count"];
							}else{
								$discount_notaxable += $count_ids[$id]["price"] * $count_ids[$id]["count"];
							}
						}
						
						if($total < $discount['buy_qty'] + $discount['get_qty']){
							$flag = false;
						}

					}
				}else{
					foreach($count_ids as $key=> $v){
						$count_total = $count_total + $v['count'];
					}

					foreach($count_ids as $key => $v){
						foreach($itms as $it){
							if($it->id == $key){
								$taxable = $it->taxable;
							}
						}
						$get_total = 0;
						// if (($discount['buy_qty'] + $discount['get_qty']) <= $v["count"]){
						if($discount['get_what'] == 'same'){
							$count_total = $v["count"];
						}
						$taxes = $taxable;
						
						if (($discount['buy_qty'] + $discount['get_qty']) <= $count_total){
							$flag = true;
							if($discount['get_what'] != 'same'){
								$total = $total + $v["count"];
								$buy_get = $total;
								$price = (isset($item_price->discount_percent)) ? $item_price->price - ($item_price->price*$item_price->discount_percent/100) : $item_price->price;
								$taxes = $item_price->taxable;
								if($key == $discount['get_item_id']){
									$get_total_items = $v['count'];
								}
								// if($taxable > 0){
								// 	$discount_taxable += $count_ids[$key]["price"] * $v["count"];
								// }else{
								// 	$discount_notaxable += $count_ids[$key]["price"] * $v["count"];
								// }
							}else{
								$mod = $v["count"] / ($discount['buy_qty'] + $discount['get_qty']);
								if($mod % 1 != 0){
									$p = $discount['get_qty'];
								}else{
									$p = floor($mod) * $discount['get_qty'];
								}
								$products[] = [
									$p, $v["price"], $taxes
								];
								// if($taxable > 0){
								// 	$discount_taxable += $v["price"] * $v["count"];
								// }else{
								// 	$discount_notaxable += $v["price"] * $v["count"];
								// }
							}
						}
						if($taxable > 0){
							$discount_taxable += $count_ids[$key]["price"] * $v["count"];
						}else{
							$discount_notaxable += $count_ids[$key]["price"] * $v["count"];
						}
					}
				}
				
				if(!$flag){
					if($return)
						throw new ApiException('Discount does not apply','021',$order);
					return false;
				}

				if($discount['get_what'] != 'same'){
					$mod = ($buy_get >= $discount['buy_qty'] + $discount['get_qty']) ? ($buy_get / ($discount['buy_qty']+ $discount['get_qty'])) : $discount['get_qty'];
					if($mod > $get_total_items){
						$mod = $get_total_items;
					}
					if($mod % 1 != 0){
						$p = $discount['get_qty'];
					}else{
						$p = floor($mod) * $discount['get_qty'];
					}
					$discount['discount_items'][] = [
						$p, ($price), $item_price->taxable
					]; 
				}else{
					$apply_disc = false;
					foreach($products as $product){
						if($product[0] > 0){
							$apply_disc = true;
						}
					}
					if(!$apply_disc){
						if($return)
							throw new ApiException('Discount does not apply','021',$order);
						return false;
					}
					$discount['discount_items'] = $products;
				}
				
				break;
		}
		
		$discount['discount_taxable'] = $discount_taxable;
		$discount['discount_notaxable'] = $discount_notaxable;
		// dd($discount);
		return $discount;

	}

	public function getTotalItemsAndPrice($count_ids, $item_id, $v){
		if(isset($count_ids[$item_id])){
			return [
				"count" => $count_ids[$item_id] + $v['qty'],
				"price" => (!isset($v['discount_percent']) || $v['discount_percent'] == null) ? $v['price'] : ($v['price'] - ($v['price']* $v['discount_percent']/100))
			];
		}else{
			return [
				"count" => $v['qty'],
				"price" => (!isset($v['discount_percent']) || $v['discount_percent'] == null) ? $v['price'] : ($v['price'] - ($v['price']* $v['discount_percent']/100))
			];
		}
	}

	public function percentageApplyTo($count_ids, $discount, $ids, $type, $items_id=[]){
		$flag = false;
		$discount_taxable = 0.00;
		$discount_notaxable = 0.00;
		
		$itms = Item::whereIn('id', $ids)->get();
		$res = DiscountApplicable::select('applicable_id')->where([['discount_code', $discount['code']],['applicable_type', $type]])
									->whereIn('applicable_id', $ids)->groupBy('applicable_id')->pluck('applicable_id')->toArray();
		$products=[];
		
		if($type == 'category'){
			$res = Item::select('id')->whereIn('category_id', $res)->whereIn('id', $items_id)->pluck('id')->toArray();
			$ids = $items_id;
			$itms = Item::whereIn('id', $ids)->get();
		}
		
		if(count($res)){
			
			foreach($ids as $id){
				$taxable = 0;
				foreach($itms as $it){
					if($it->id == $id){
						$taxable = $it->taxable;
					}
				}
				if (in_array($id, $res) && isset($count_ids[$id])){
					$flag = true;
					$products[] = [
						$count_ids[$id]["count"], ($count_ids[$id]["price"]), $taxable
					];
				}
				

				if($taxable > 0){
					$discount_taxable += $count_ids[$id]["price"] * $count_ids[$id]["count"];
				}else{
					$discount_notaxable += $count_ids[$id]["price"] * $count_ids[$id]["count"];
				}
			}
		}
		return [
			'flag' => $flag,
			'products' => $products,
			'discount_taxable' => $discount_taxable,
			'discount_notaxable' => $discount_notaxable,
		];
	}

	public function processDiscount($discount, $order){
		if(isset($order['order_items'])){
			foreach($order['order_items'] as $key=>$v){
				if(!isset($v['item_id']))
				$order['order_items'][$key]['item_id'] = $v['id'];
			}
		}
		$getOrder = Order::where('id',$order['id'])->first();
		$tax = 0;
		$store_id = 0;
		if(isset($order['store_id']) && $order['store_id'] > 0){
			$store = StoreLocation::where('id', $order['store_id'])->first();
			$tax = $store->tax;
			$store_id = $store->id;
		}
		if($getOrder){
			$getOrder->total_amount = $getOrder->total_amount + $getOrder->discount_amount - $getOrder->total_tax_amount;
			$getOrder->tax = $getOrder->tax; 
			$getOrder->store_id = $store_id; 
		}else{
			$getOrder = new Order();
			$getOrder->total_amount = $order['total_amount'];
			$getOrder->total_tax_amount = $order['total_tax_amount'];
			$getOrder->order_number = Helper::generate_random_string(8);
			$getOrder->origin = 'store';
			$getOrder->status = 'cart';
			$getOrder->tax = $tax; 
			$getOrder->store_id = $store_id; 
			$getOrder->save();
		}
		try{
			$result = $this->checkDiscount($discount->toArray(), $order);
			$discount_amount = 0.00;
			$discount_taxable = 0.00;
			$subtotal = 0;
			if(isset($order['order_items'])){
				foreach($order['order_items'] as $item){
					if(isset($item['discount_percent']) && $item['discount_percent'] > 0){ 
						$subtotal += ($item['price'] - ($item['price']*$item['discount_percent']/100)) * $item['qty'];
					}else{
						$subtotal += $item['price'] * $item['qty'];
					}
				}
			}
			switch($result['discount_type']){
				case 'percentage':
					// $discount_amount =  ($getOrder->total_amount - $getOrder->total_tax_amount - $getOrder->shipping_cost_estimated) * $result['discount_value']/100;
					if(isset($result['discount_items'])){
						$discount_taxable = 0;
					    foreach($result['discount_items'] as $item){
							if($item[2] == 0){
								$discount_amount += $discount_amount + (($item[1] * $result['discount_value']/100)*$item[0]);
							}else{
								$discount_taxable += $discount_taxable + (($item[1] * $result['discount_value']/100)*$item[0]);
							}
						}
						$discount_amount += $discount_taxable;
					}else{
						$discount_amount +=  $result['discount_notaxable'] * $result['discount_value']/100;
						$discount_taxable = $result['discount_taxable'] * $result['discount_value']/100;
						$discount_amount +=  $discount_taxable;
					}
					break;
				case 'fixed_amount':
					$discount_percent =  $result['discount_value'] *100 / ($result['discount_notaxable'] +  $result['discount_taxable']);
					$discount_amount +=  $result['discount_notaxable'] * $discount_percent/100;
					$discount_taxable = $result['discount_taxable'] * $discount_percent/100;
					$discount_amount +=  $discount_taxable;
					if(isset($result['discount_items'])){
						$amount = 0;
					    foreach($result['discount_items'] as $item){
							$amount += $item[1]*$item[0];
						}
						if($amount < $result['discount_value']){
							$discount_amount = 0;
							$discount_percent =  $amount *100 / ($result['discount_notaxable'] +  $result['discount_taxable']);
							$discount_amount +=  $result['discount_notaxable'] * $discount_percent/100;
							$discount_taxable = $result['discount_taxable'] * $discount_percent/100;
							$discount_amount +=  $discount_taxable;
							// $discount_amount = $amount;
						}
					}
					// $discount_amount =  $result['discount_value'];
					break;
				case 'free_shipping':
					$discount_amount = $order['shipping_cost_estimated'];
					break;
				case 'buy_x_get_y':
					// $discount_amount +=  $result['discount_notaxable'] * $result['discount_value']/100;
					// $discount_taxable = $result['discount_taxable'] * $result['discount_value']/100;
					// $discount_amount +=  $discount_taxable;
					$discount_taxable = 0;
					if(isset($result['discount_items'])){
					    foreach($result['discount_items'] as $item){
							if($item[2] == 0){
								$discount_amount += (($item[1] * $result['discount_value']/100)*$item[0]);
							}else{
								$discount_taxable += (($item[1] * $result['discount_value']/100)*$item[0]);
							}
						}
						$discount_amount += $discount_taxable;
					}
					break;
				}
			$getOrder->tax_base = $result['discount_taxable'] -  $discount_taxable;
			$getOrder->total_tax_amount = ($getOrder->tax > 0 && $result['discount_taxable'] > 0) ? ($result['discount_taxable'] - $discount_taxable)* $getOrder->tax/100 : 0.00;
			$getOrder->subtotal = $subtotal;
			$getOrder->total_amount = $subtotal;
			$getOrder->discount_code = $discount->code;
			$getOrder->discount_amount = $discount_amount;
			if($discount_amount >= 0){
				$getOrder->total_amount = $getOrder->total_amount - $discount_amount;
			}else{
				$getOrder->total_amount = $getOrder->total_amount + $discount_amount;
			}
			$getOrder->total_amount += $getOrder->total_tax_amount;
			$getOrder->shipping_city = isset($order['shipping_city']) ? $order['shipping_city'] : '';
			$getOrder->shipping_state = isset($order['shipping_state']) ? $order['shipping_state'] : '';
			$getOrder->shipping_country = isset($order['shipping_country']) ? $order['shipping_country'] : '';
			$getOrder->save();
			return Api::success('Valid discount code', ['order'=>$getOrder,'discount'=>$discount]);
		}catch (ApiException $e){
			$getOrder->discount_code = null;
			$getOrder->total_amount = $getOrder->total_amount + $getOrder->discount_amount;
			$getOrder->discount_amount = 0.00;
			$getOrder->save();
		   
			return Api::error('021', $e->message(), $getOrder);
		}
	}
}