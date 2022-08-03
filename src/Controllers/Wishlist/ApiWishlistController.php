<?php

namespace Properos\Commerce\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Commerce\Classes\CWishlist;

class ApiWishlistController extends Controller
{

	private $wishlist;
	
	function __construct() 
	{
		$this->wishlist = new CWishlist();		
	}
	
	public function get() 
	{
		return json_encode($this->wishlist->get());
	}
	
	public function getCount() 
	{
		return $this->wishlist->getCount();
	}

	public function set($sku) 
	{
		return json_encode($this->wishlist->set($sku));
	}

	public function remove($item_id) 
	{
		return json_encode($this->wishlist->remove($item_id));
	}
	
	public function removeAll() 
	{
		return json_encode($this->wishlist->removeAll());
	}	
}