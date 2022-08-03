<?php

namespace Properos\Commerce\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Base\Classes\Theme;
use Properos\Commerce\Classes\CWishlist;

class WishlistController extends Controller
{
	private $wishlist;
	
	function __construct() 
	{
		$this->wishlist = new CWishlist();		
	}

	public function wishlistPage(Request $request) 
	{
		$content = [];
		$wishlist = $this->wishlist->get();
		$content["wishlist"] = $wishlist["data"];
		return view('themes.'.Theme::get().'.wishlist', ['content'=>$content])->with(['theme'=>Theme::get()]);
	}	
}