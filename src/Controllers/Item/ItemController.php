<?php

namespace Properos\Commerce\Controllers\Item;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Commerce\Models\Category;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Classes\CCart;
use Properos\Commerce\Classes\CItem;
use Properos\Commerce\Classes\CWishlist;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Models\Setting;

class ItemController extends Controller
{
	private $item;
	private $categories;
	private $cItem;

	public function __construct(Item $item, Category $categories, CItem $cItem)
	{
		$this->item = $item;
		$this->cItem = $cItem;
		$this->categories = $categories;
	}

	public function items()
	{
		$sort = [
			'name' => 'newest',
			'field' => 'created_at',
			'value' => 'DESC'
		];
		$settings = Setting::where('key', 'communication')->first();
		if($settings){
			$s_data = json_decode($settings->data,true);
			if(isset($s_data['products_sort'])){
				switch($s_data['products_sort']){
					case 'oldest':
						$sort = [
							'field' => 'created_at',
							'value' => 'ASC'
						];
						break;
					case 'cheapest':
						$sort = [
							'field' => 'price',
							'value' => 'ASC'
						];
						break;
					case 'priciest':
						$sort = [
							'field' => 'price',
							'value' => 'DESC'
						];
						break;
				}
				$sort['name'] = $s_data['products_sort'];
			}
		}
		$settings = Setting::where('key', 'inventory')->first();
		$qoh = 0;
		if($settings){
			$i_data = json_decode($settings->data,true);
			if(isset($i_data['qoh'])){
				$qoh = $i_data['qoh'];
			}
		}

		$categories = $this->categories->has('items')->where('active', '1')->get();
		$items = Item::where('active', 1)->whereHas('category', function ($q) use($qoh) {
			if($qoh == 1){
				$q->where('total_qty', '>', 0);
			}
			return $q->where('active', 1);
		})->with(['category' => function ($q) {
			return $q->where('active', '=', 1);
		}, 'images'])->orderBy($sort['field'],$sort['value'])->paginate(8)->toJson(); 
		
		return view('themes.'.Theme::get().'.items')->with(['categories' => $categories, 'items' => $items, 'theme'=>Theme::get(), 'sort' => $sort['name'], 'qoh' => $qoh]);
	}

	public function showItem($id)
	{
		$item = $this->item->where('sku',$id)->with(['files' => function ($q) {
			return $q->where('type', 'image')->where('owner_type', 'item');;
		}, 'category'])->first();
		if ($item) {
			if ($item->active == 1) {
				$settings = Setting::where('key', 'inventory')->first();
				$qoh = 0;
				if($settings){
					$i_data = json_decode($settings->data,true);
					if(isset($i_data['qoh'])){
						$qoh = $i_data['qoh'];
					}
				}
				if($qoh == 1){
					$similar_items = $this->item->where('total_qty', '>', 0)->with(['files' => function ($q) {
						return $q->where('type', 'image')->where('owner_type', 'item');;
					}])->with('category')->where('id', '!=', $item->id)->where('category_id', $item->category_id)->where('active',1)->get();
				}else{
					$similar_items = $this->item->with(['files' => function ($q) {
						return $q->where('type', 'image')->where('owner_type', 'item');;
					}])->with('category')->where('id', '!=', $item->id)->where('category_id', $item->category_id)->where('active',1)->get();
				}

				return view('themes.'.Theme::get().'.item')->with(['item' => $item, 'similar_items' => $similar_items, 'theme' => Theme::get()]);
			}
		}

		return abort(404);
	}

	public function categoryItems($category_id)
	{
		$sort = [
			'name' => 'newest',
			'field' => 'created_at',
			'value' => 'DESC'
		];
		$settings = Setting::where('key', 'communication')->first();
		if($settings){
			$s_data = json_decode($settings->data,true);
			if(isset($s_data['products_sort'])){
				switch($s_data['products_sort']){
					case 'oldest':
						$sort = [
							'field' => 'created_at',
							'value' => 'ASC'
						];
						break;
					case 'cheapest':
						$sort = [
							'field' => 'price',
							'value' => 'ASC'
						];
						break;
					case 'priciest':
						$sort = [
							'field' => 'price',
							'value' => 'DESC'
						];
						break;
				}
				$sort['name'] = $s_data['products_sort'];
			}
		}

		$settings = Setting::where('key', 'inventory')->first();
		$qoh = 0;
		if($settings){
			$i_data = json_decode($settings->data,true);
			if(isset($i_data['qoh'])){
				$qoh = $i_data['qoh'];
			}
		}
		if($qoh == 1){
			$items = $this->item->where('total_qty', '>', 0)->where('active', 1)->with(['files' => function ($q) {
				return $q->where('type', 'image')->where('owner_type', 'item');;
			}, 'category'])->where('category_id', $category_id)->orderBy($sort['field'],$sort['value'])->paginate(8);
		}else{
			$items = $this->item->where('active', 1)->with(['files' => function ($q) {
				return $q->where('type', 'image')->where('owner_type', 'item');;
			}, 'category'])->where('category_id', $category_id)->orderBy($sort['field'],$sort['value'])->paginate(8);
		}
		if (count($items) > 0) {
			return view('themes.'.Theme::get().'.items')->with(['categories' => [], 'items' => $items->toJson(), 'category' => $items->items()[0]->category, 'sort'=>$sort['name'],'qoh' => $qoh])
																		->with(['theme' => Theme::get()]);
		}
		return abort(404);
	}

	public function itemsCollection($slug)
	{
		$sort = [
			'name' => 'newest',
			'field' => 'created_at',
			'value' => 'DESC'
		];
		$settings = Setting::where('key', 'communication')->first();
		if($settings){
			$s_data = json_decode($settings->data,true);
			if(isset($s_data['products_sort'])){
				switch($s_data['products_sort']){
					case 'oldest':
						$sort = [
							'field' => 'created_at',
							'value' => 'ASC'
						];
						break;
					case 'cheapest':
						$sort = [
							'field' => 'price',
							'value' => 'ASC'
						];
						break;
					case 'priciest':
						$sort = [
							'field' => 'price',
							'value' => 'DESC'
						];
						break;
				}
				$sort['name'] = $s_data['products_sort'];
			}
		}
		$categories = $this->categories->has('items')->where('active', '1')->get();

		$settings = Setting::where('key', 'inventory')->first();
		$qoh = 0;
		if($settings){
			$i_data = json_decode($settings->data,true);
			if(isset($i_data['qoh'])){
				$qoh = $i_data['qoh'];
			}
		}
		if($qoh == 1){
			$items = Item::where('total_qty', '>', 0)->where('active', 1)->whereHas('category', function ($q) {
				return $q->where('active', 1);
			})->with(['category' => function ($q) {
				return $q->where('active', '=', 1);
			}, 'images', 'collections' => function($q) use($slug) {
				return $q->where('slug',$slug);
			}])->whereHas('collections',function($q) use($slug) {
				return $q->where('slug',$slug);
			})->orderBy($sort['field'],$sort['value'])->paginate(8);
		}else{
			$items = Item::where('active', 1)->whereHas('category', function ($q) {
				return $q->where('active', 1);
			})->with(['category' => function ($q) {
				return $q->where('active', '=', 1);
			}, 'images', 'collections' => function($q) use($slug) {
				return $q->where('slug',$slug);
			}])->whereHas('collections',function($q) use($slug) {
				return $q->where('slug',$slug);
			})->orderBy($sort['field'],$sort['value'])->paginate(8);
		}
			

		if (count($items) > 0) {
			return view('themes.'.Theme::get().'.items')->with(['categories' => $categories,'items' => $items->toJson(), 'sort'=>$sort['name'], 'qoh' => $qoh])
																		->with(['theme' => Theme::get()]);
		}
		return view('themes.'.Theme::get().'.items')->with(['categories' => $categories, 'sort'=>$sort['name'], 'qoh' => $qoh])
																		->with(['theme' => Theme::get()]);
	}

	public function destroy($id)
	{

	}
}