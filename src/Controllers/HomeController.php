<?php

namespace Properos\Commerce\Controllers;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\Category;
use Properos\Commerce\Models\File;
use Properos\Base\Classes\Theme;
use Properos\Commerce\ViewModel\ContactEmailVM;
use Properos\Commerce\Mail\ContactEmail;
use Properos\Commerce\Models\Subscriber;
use Properos\Commerce\ViewModel\SubscriberEmailVM;
use Properos\Commerce\Mail\SubcriberEmail;
use App\Http\Controllers\Controller;
use Properos\Base\Classes\Api;
use Properos\Commerce\Models\Setting;
use Properos\Commerce\Models\Collection;

class HomeController extends Controller
{
    public function about()
	{
		return view('themes.'.Theme::get().'.about')->with(['theme'=>Theme::get()]);
	}

	public function howTo()
	{
		return view('themes.'.Theme::get().'.how-to')->with(['theme'=>Theme::get()]);
	}

	public function terms()
	{
		return view('themes.'.Theme::get().'.terms')->with(['theme'=>Theme::get()]);
	}

	public function privacy()
	{
		return view('themes.'.Theme::get().'.privacy')->with(['theme'=>Theme::get()]);
	}

	public function contact(Request $request)
	{
		$type = $request->query('type', 'user');
		return view('themes.'.Theme::get().'.contact')->with(['type' => $type])->with(['theme'=>Theme::get()]);
	}

	public function ourStory()
	{
		return view('themes.'.Theme::get().'.our-story')->with(['theme'=>Theme::get()]);
	}

	public function ourPrinciples()
	{
		return view('themes.'.Theme::get().'.our-principles')->with(['theme'=>Theme::get()]);
	}

	public function help()
	{
		return view('themes.'.Theme::get().'.help')->with(['theme'=>Theme::get()]);
	}

	public function faq()
	{
		return view('themes.'.Theme::get().'.faq')->with(['theme'=>Theme::get()]);
	}

	public function resources()
	{
		return view('themes.'.Theme::get().'.resources')->with(['theme'=>Theme::get()]);
	}

	public function shippingReturnPolicy()
	{
		return view('themes.'.Theme::get().'.shipping-return-policy')->with(['theme'=>Theme::get()]);
	}

	public function siteMap()
	{
		return view('themes.'.Theme::get().'.site-map')->with(['theme'=>Theme::get()]);
	}

	public function engageUs()
	{
		return view('themes.'.Theme::get().'.engage-us')->with(['theme'=>Theme::get()]);
	}

	public function homePage(Request $request) 

	{
		$settings = Setting::where('key', 'inventory')->first();
		$qoh = 0;
		if($settings){
			$i_data = json_decode($settings->data,true);
			if(isset($i_data['qoh'])){
				$qoh = $i_data['qoh'];
			}
		}
		$settings = Setting::where('key', 'homepage')->first();
		$keywords = '';
       	$collections = [];
        if($settings){
			$settings->data = json_decode($settings->data, true);
            if(isset($settings->data['collections']) && count($settings->data['collections']) > 0){
				$collections = [];
                foreach($settings->data['collections'] as $collect){
					$getCollection = Collection::where('id', $collect['collection_id'])->with(['items' => function($query) use($collect,$qoh){
						if($qoh == 1){
							$query->where('total_qty', '>', 0);
						}
						$query->where('active',1)
								->with(['files' => function ($q) {
										return $q->where('type', 'image');
									}, 'category' => function ($q) {
										return $q->where('active', 1);
									}])
								->whereHas('category',function($q) {
									return $q->where('active', 1);
								});
						if(!$collect['random']){
							$query->orderBy('pivot_sort_order','ASC');
						}else{
							$query->orderBy(\DB::raw('RAND()'));
						}
						$query->limit($collect['max_items']);
					}])->first();
					if($getCollection){
						$getCollection['title'] = $collect['title'];
						$collections[] = $getCollection->toArray();
					}
				}
			}
			if(isset($settings->data['keywords'])){
				$keywords = $settings->data['keywords'];
			}
		}
		
		$recommended_product = null;
		
		if(count($collections) == 0){
			if($qoh == 1){
				$recommended_product = Item::where('active',1)->where('total_qty', '>', 0)->whereHas('files', function ($q) {
					return $q->where('type', 'image');
				})->with(['category' => function ($q) {
					return $q->where('active', '=', 1);
				}, 'files' => function ($q) {
					return $q->where('type', 'image')->where('owner_type', '=', 'item');
				}])->whereHas('category', function ($q) {
					return $q->where('active', 1);
				})->limit(8)->get();
			}else{
				$recommended_product = Item::where('active',1)->whereHas('files', function ($q) {
					return $q->where('type', 'image');
				})->with(['category' => function ($q) {
					return $q->where('active', '=', 1);
				}, 'files' => function ($q) {
					return $q->where('type', 'image')->where('owner_type', '=', 'item');
				}])->whereHas('category', function ($q) {
					return $q->where('active', 1);
				})->limit(8)->get();
			}
		}

		$_categories = Category::with(['files' => function ($q) {
			return $q->where('type', 'image')->where('owner_type', 'category');
		}])->where('active', 1)->whereHas('items')->get();

		$categories = [];
		if (count($_categories) > 0) {
			foreach ($_categories as $key => $category) {
				if (count($category->files) == 0) {
					if($qoh == 1){
						$cat_item = Item::where('total_qty', '>', 0)->with(['files' => function ($q) {
							return $q->where('type', 'image')->where('owner_type', 'item');
						}])->whereHas('files', function ($q) {
							return $q->where('type', 'image')->where('owner_type', 'item');
						})->where('active', 1)->where('category_id', $category->id)->first();
					}else{
						$cat_item = Item::with(['files' => function ($q) {
							return $q->where('type', 'image')->where('owner_type', 'item');
						}])->whereHas('files', function ($q) {
							return $q->where('type', 'image')->where('owner_type', 'item');
						})->where('active', 1)->where('category_id', $category->id)->first();
					}
					
					if ($cat_item) {
						$path = 'pictures/category/thumbs/' . str_random(40) . '.jpg';
						if (\Storage::disk('public')->copy($cat_item->files[0]->thumb_path, $path)) {
							File::create([
								'owner_type' => 'category',
								'owner_id' => $category->id,
								'type' => 'image',
								'path' => $path,
								'thumb_path' => $path,
								'label' => '',
								'description' => ''
							]);
						}
					}
				}
				$categories[] = $category;
			}
		}
		
		return view('themes.'.Theme::get().'.index')->with(['categories' => $categories, 'recommended_product' => $recommended_product, 'collections' => $collections, 'keywords' => $keywords])
														->with('theme',Theme::get());
	}

	public function sendContactEmail(Request $request)
	{
		$validatedData = $request->validate([
			'name' => 'required',
			'message' => 'required',
			'email' => 'required|email',
			'type' => 'required'
		]);

		$type = $request->input('type');
		$to;
		$title;

		switch ($type) {
			case 'wholesale':
				$to = env('WHOLESALER_CONTACT_EMAIL', 'support@properos.com');
				$title = 'This is a wholesaler contact email';
				break;
			case 'feedback':
				$to = env('FEEDBACK_EMAIL', 'support@properos.com');
				$title = 'This is a feedback email';
				break;
			case 'affiliate':
				$to = env('AFFILIATE_EMAIL', 'support@properos.com');
				$title = 'This is an affiliate email';
				break;
			case 'user':
				$to = env('CONTACT_EMAIL', 'support@properos.com');
				$title = 'This is an user contact email';
				break;
			default:
				$to = env('CONTACT_EMAIL', 'support@properos.com');
				$title = 'This is an user contact email';
				break;
		}

		$email_data = new ContactEmailVM(
			$title,
			$request['email'],
			$request['name'],
			$request['phone'],
			$request['message']
		);

		\Mail::to($to)->queue(new ContactEmail($email_data));
		return redirect('/');
	}

	public function subscribe(Request $request)
	{
		$validatedData = $request->validate([
			'email' => 'required|email',
		]);
		$subsc = Subscriber::where('email', $request->input('email'))->first();
		if (!$subsc) {
			$subscriber = new Subscriber();
			$subscriber->email = $request->input('email');
			if ($subscriber->save()) {
				$email_data = new SubscriberEmailVM(
					env('SUBSCRIBER_WELCOME', 'Thanks for subscribing to our mailing list. We will keep you updated about our latest promotions and will not share your contact with anybody.')
				);

				\Mail::to($subscriber->email)->queue(new SubcriberEmail($email_data));
				\Mail::to(env('APP_EMAIL', 'support@properos.com'))->queue(new SubcriberEmail(new SubscriberEmailVM(
					'Your site has a new subscriber - '.$subscriber->email)));
			}

		}
		return redirect('/');
	}

	public function announcement()
	{
		$communication = Setting::where('key', 'communication')->first();
		if($communication){
			$data = json_decode($communication->data,true);
			if(isset($data['announcement'])){
				if(isset($data['announcement']['active']) && $data['announcement']['active'] > 0){
					$serial = explode("-",$data['announcement']['serial']);
					$num = (int)$serial[1];
					if(\Cookie::get('announcement') == null || \Cookie::get('announcement') == ''){
						// \Cookie::queue('announcement', $data['announcement']['serial']);
						return Api::success('Announcement', $data['announcement']);
					}else{
						$cookie_serial = explode("-",\Cookie::get('announcement'));
						if($num > (int)$cookie_serial[1]){
							return Api::success('Announcement', $data['announcement']);
						}else{
							return Api::success('Announcement', null);
						}
					}
				}
			}
		}
		\Cookie::queue('announcement', null);
		return Api::success('Announcement', null);
	}

	public function cookieRemove($serial){
		\Cookie::queue('announcement', $serial);
		return Api::success('Announcement', null);
	}
}
