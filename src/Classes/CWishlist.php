<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\Wishlist;

class CWishlist
{
	function __construct() 
	{
		//
	}
	
	public function getCount()
	{
		$result = array("status"=>1,"data"=>["count"=>0]);;
		if (\Auth::check()) {
			$wishlist = \DB::select("SELECT id FROM wishlist WHERE user_id='". \Auth::user()->id ."'");
			foreach ($wishlist as $wishlist_item) {
				$result["data"]["count"]++;
			}
		} else {
			$wishlist = session()->get('wishlist',[]);
			if (!is_array($wishlist)) {
				$wishlist = [];
				session()->put('wishlist', []);
			}
			$result["data"]["count"] = count($wishlist);
		}
		return $result;
	}

	public function get()
	{
		$result = array("status"=>1,"data"=>["count"=>0,"wishlist"=>[]]);
		if (\Auth::check()) {
			$wishlist = [];
			$wishlist_items = \DB::select("SELECT items.id, items.price, items.sku, items.msrp, items.`name`, items.`description`, MIN(files.id), ANY_VALUE(files.`thumb_path`) as 'thumb_path', items.`discount_percent`
						   FROM wishlist, items 
						   LEFT JOIN files ON files.owner_id = items.id AND files.owner_type = 'item' AND files.type = 'image' AND files.deleted_at IS NULL
						   WHERE user_id='". \Auth::user()->id ."' AND wishlist.item_id=items.id GROUP BY items.id");
			$id = 0;
			foreach ($wishlist_items as $wishlist_item) {
				if($id != $wishlist_item->id){
					$result["data"]["wishlist"][$wishlist_item->id] = ["id"=>$wishlist_item->id, "price"=>$wishlist_item->price,"msrp"=>$wishlist_item->msrp,"name"=>$wishlist_item->name,
														   "description"=>$wishlist_item->description, "thumb_path" => $wishlist_item->thumb_path, "sku" => $wishlist_item->sku, "discount_percent" => $wishlist_item->discount_percent];
					$id = $wishlist_item->id;
				}
			}
			$result["data"]["count"]=count($result["data"]["wishlist"]);
		} else {
			$wishlist = session()->get('wishlist',[]);
			
			if (!is_array($wishlist)) {
				$wishlist = [];
				session()->put('wishlist', []);
			}
			$items = array_keys($wishlist);
			if (count($wishlist)>0) {
				
				$wishlist_items = \DB::select("SELECT items.id, items.price, items.sku, items.msrp, items.`name`, items.`description`,MIN(files.id), ANY_VALUE(files.`thumb_path`) as 'thumb_path', items.`discount_percent`
						   FROM items 
						   LEFT JOIN files ON files.owner_id = items.id AND files.owner_type = 'item' AND files.type = 'image' AND files.deleted_at IS NULL
						   WHERE items.id IN (".implode(",",$items).") GROUP BY items.id");
						   
				$id = 0;		
				foreach ($wishlist_items as $wishlist_item) {
					if($id != $wishlist_item->id){
						$result["data"]["wishlist"][$wishlist_item->id] = ["id"=>$wishlist_item->id,"price"=>$wishlist_item->price,"msrp"=>$wishlist_item->msrp,"name"=>$wishlist_item->name,
																   "description"=>$wishlist_item->description, "thumb_path" => $wishlist_item->thumb_path, "sku" => $wishlist_item->sku, "discount_percent" => $wishlist_item->discount_percent];
						$id = $wishlist_item->id;
					}
				}
			}
			$result["data"]["count"]=count($result["data"]["wishlist"]);
		}
		
		return $result;
	}

	public function set($sku)
	{
		$item = Item::where('sku',$sku)->first();
		
		if($item){
			$wishlist = session()->get('wishlist',[]);
			$wishlist[$item->id]=array("item"=>$item->id);
			session()->put('wishlist', $wishlist);
			$wishlist = session()->get('wishlist',[]);
			if (\Auth::check()) {
				Wishlist::updateOrCreate(["user_id"=>\Auth::user()->id,"item_id"=>$item->id],["user_id"=>\Auth::user()->id,"item_id"=>$item->id]);
			}
			return $this->get();
		}

		$result = array("status"=>0,"data"=>[], "errors"=>"Invalid Query format");
		return $result;
		
	}
	
	public function remove($item)
	{	
		$wishlist = session()->get('wishlist',[]);
		if (isset($wishlist[$item])) unset($wishlist[$item]);
		session()->put('wishlist', $wishlist);
		if (\Auth::check()) {
			Wishlist::where("user_id",\Auth::user()->id)->where("item_id",$item)->delete();
		}
		return $this->get();
	}

	public function removeAll()
	{
		$wishlist = [];
		session()->put('wishlist', $wishlist);
		if (\Auth::check()) {
			Wishlist::where("user_id",\Auth::user()->id)->delete();
		}
		return $this->get();
	}
		
	
}