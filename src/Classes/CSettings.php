<?php

namespace Properos\Commerce\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Properos\Commerce\Models\Setting;
use Properos\Base\Classes\Base;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\Option;

class CSettings extends Base
{

	function __construct()
	{
		parent::__construct(Setting::class, 'Setting');
	}

	public function store(array $data)
	{
		switch($data['key']){
			case 'invoicing':
				// if(!isset($data['data']['first_order_id']) || $data['data']['first_order_id'] == ''){
				// 	$data['data']['first_order_id'] = 10;
				// }
				$orders = Order::count('id');
				if($orders == 0 && isset($data['data']['first_order_id']) && $data['data']['first_order_id'] != ''){
					\DB::select(\DB::raw('ALTER TABLE orders AUTO_INCREMENT= '.$data['data']['first_order_id']));
				}
				break; 
			case 'communication':
				if(isset($data['data']['announcement'])){
					$settings = Setting::where('key', $data['key'])->first();
					if($settings){
						$serial = explode("-",json_decode($settings->data,true)['announcement']['serial']);
						$num = (int)$serial[1] + 1;
						$new_serial = str_random(4).'-'.$num;
						$data['data']['announcement']['serial'] = $new_serial;
					}else{
						$data['data']['announcement']['serial'] = str_random(4).'-1';
					}
				}
				if(isset($data['data']['keywords'])){
					$new_data['data']['keywords'] = $data['data']['keywords'];
					unset($data['data']['keywords']);
					$settings = Setting::where('key', 'homepage')->first();
					$s_data = json_decode($settings->data,true);
					if(isset($s_data['collections'])){
						$new_data['data']['collections'] = $s_data['collections'];
					}
					$new_data['data'] = json_encode($new_data['data']);
					Setting::updateOrCreate(['key' => 'homepage'],['homepage', 'data'=>$new_data['data']]);
				}
				break;
			case 'inventory':
				if(isset($data['data']['sku'])){
					$orders = Order::count('id');
					if($orders == 0 && isset($data['data']['sku']['first_sku_id']) && $data['data']['sku']['first_sku_id'] != ''){
						\DB::select(\DB::raw('ALTER TABLE orders AUTO_INCREMENT= '.$data['data']['sku']['first_order_id']));
					}
				}
				if(isset($data['data']['options']) && count($data['data']['options']) > 0){
					foreach($data['data']['options'] as $opt){
						if($opt['id'] == 0){
							Option::updateorCreate(['label' => strtolower($opt['label'])],['label' => strtolower($opt['label'])]);
						}
					}
					unset($data['data']['options']);
				}
				break;
			case 'homepage':
				$settings = Setting::where('key', $data['key'])->first();
				$s_data = json_decode($settings->data,true);
				if(!isset($data['data']['collections']) && isset($s_data['collections'])){
					$data['data']['collections'] = $s_data['collections'];
				}
				if(!isset($data['data']['keywords']) && isset($s_data['keywords'])){
					$data['data']['keywords'] = $s_data['keywords'];
				}
				break;
		}
		
		$data['data'] = json_encode($data['data']);
		$settings = Setting::updateOrCreate(['key'=> $data['key']],['key'=>$data['key'], 'data'=>$data['data']]);

		return $settings;
	}


	public function updatePost(array $data, $id)
	{
		if (Auth::check()) {
			$user = Auth::user();
			$blog_post = $this->blog_post->find($id);
			if ($blog_post) {
				$blog_post->slug = str_slug($data['title']);
				$getBlog = $blog_post->where('slug',$blog_post->slug)->first();
				if($getBlog){
					$max = $blog_post->max('id');
					$blog_post->slug .='-'.($max+1);
				}
				$blog_post->title = $data['title'];
				$blog_post->header_media_type = $data['header_media_type'];
				if ($data['header_media_type'] == 'video') {
					$blog_post->header_media_path = $data['file'];
				} else if ($data['header_media_type'] == 'picture') {
					if (isset($data['file'])) {
						$old_path = $blog_post->header_media_path;
						$data['picture'] = $data['file'];
						$file_path = $this->cFile->uploadFile($data);
						if ($file_path) {
							$removed_path = Storage::disk('public')->delete($old_path);
							$blog_post->header_media_path = $file_path;
						}
					}
				}
				$blog_post->summary = substr(strip_tags($data['content']), 0, 150) . '...';
				$blog_post->content = $data['content'];
				$blog_post->user_id = $user->id;
				$blog_post->active = $data['active'];
				$blog_post->save();
				return $blog_post;
			}
		}
	}
}