<?php

namespace Properos\Commerce\Classes;

use Properos\Cms\Models\BlogPost;
use Properos\Cms\Models\Page;
use Properos\Commerce\Models\Collection;
use Properos\Commerce\Models\Setting;
use Properos\Commerce\Models\Item;

class CCreateSitemaps
{

	function __construct()
	{
	}

	function startsWith($haystack, $needle)
	{
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}

	public function generalSitemap(){
		$files = glob(public_path() . '/xml/sitemap*.xml');

		if(count($files) > 0){
			foreach($files as $d_file) {
				unlink($d_file);
			}
		}

		$count = 1;

		$xml_string = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
		
		if(!is_dir(public_path() . "/xml")){
			mkdir(public_path() . "/xml");
		}
		$sitemap_file = fopen(public_path() . "/xml/sitemap1.xml","w");

		$routes = array_map(function (\Illuminate\Routing\Route $route) { return $route->uri; }, (array) \Route::getRoutes()->getIterator());

		if(count($routes) > 0){
			foreach($routes as $route){
				if(!$this->startsWith($route, 'api/') && strpos($route, '{') === false && strpos($route, 'admin') === false){

					clearstatcache();
                    
                    $file = public_path() . '/xml/sitemap'.$count.'.xml';
                    //Get file size on MB
					$filesize = round((filesize($file)*.0009765625)* .0009765625, 1); 
					
					if($filesize >= 7){
                        $xml_string='</urlset>';
                        fputs($sitemap_file, $xml_string);
                        $xml_string = '';
                        // $id = $row['property_id'];
                        fclose($sitemap_file);
                        $count++;
                        $sitemap_file = fopen(public_path() . "/xml/sitemap".$count.".xml","w");
                        $file = public_path() . '/xml/sitemap'.$count.'.xml';
                        $xml_string = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
					}
					
					$xml_string .=' <url>'
						.'<loc>'.env('APP_URL').'/'.$route.'</loc>'
						.'<changefreq>daily</changefreq>'
						.'<priority>0.9</priority></url>';
				}
			}
		}
		fputs($sitemap_file, $xml_string);
		
		$xml_string = '';

		$model = new BlogPost();
		$from = 0;
		$to= $model::max('id');

        $model = $model::on();
        $total = $to;
        $take = 10000;

        $pages = ceil($total / $take);
        
        for ($i = 0; $i < $pages; $i++) {
            $skip = $from + ($take * $i);

            if (($take + $skip) > $to) {
                $take = $to - $skip;
            }
			
			$results = $model->where('active',1)->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get()->toArray();

			if(count($results) > 0){
				foreach($results as $blog){
					clearstatcache();
                    
                    $file = public_path() . '/xml/sitemap'.$count.'.xml';
                    //Get file size on MB
					$filesize = round((filesize($file)*.0009765625)* .0009765625, 1); 
					
					if($filesize >= 7){
                        $xml_string='</urlset>';
                        fputs($sitemap_file, $xml_string);
                        $xml_string = '';
                        // $id = $row['property_id'];
                        fclose($sitemap_file);
                        $count++;
                        $sitemap_file = fopen(public_path() . "/xml/sitemap".$count.".xml","w");
                        $file = public_path() . '/xml/sitemap'.$count.'.xml';
                        $xml_string = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
					}

					$xml_string .=' <url>'
						.'<loc>'.env('APP_URL').'/'.$blog['slug'].'</loc>'
						.'<lastmod>'.date('Y-m-d', strtotime($blog['updated_at'])).'</lastmod>'
						.'<changefreq>daily</changefreq>'
						.'<priority>0.9</priority>'
						.'<image:image>'
						.'<image:loc>'.env('APP_CDN').'/'.$blog['header_media_path'].'</image:loc>'
						.'<image:title>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $blog['title']).' | '.env('APP_NAME').'</image:title>'
						.'</image:image></url>';
				}
				fputs($sitemap_file, $xml_string);
				$xml_string = '';
			}
		}

		$model = new Page();
		$from = 0;
		$to= $model::max('id');

        $model = $model::on();
        $total = $to;
        $take = 10000;

        $pages = ceil($total / $take);
        
        for ($i = 0; $i < $pages; $i++) {
            $skip = $from + ($take * $i);

            if (($take + $skip) > $to) {
                $take = $to - $skip;
            }
			
			$results = $model->where('visibility',1)->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get()->toArray();

			if(count($results) > 0){
				foreach($results as $page){
					clearstatcache();
                    
                    $file = public_path() . '/xml/sitemap'.$count.'.xml';
                    //Get file size on MB
					$filesize = round((filesize($file)*.0009765625)* .0009765625, 1); 
					
					if($filesize >= 7){
                        $xml_string='</urlset>';
                        fputs($sitemap_file, $xml_string);
                        $xml_string = '';
                        // $id = $row['property_id'];
                        fclose($sitemap_file);
                        $count++;
                        $sitemap_file = fopen(public_path() . "/xml/sitemap".$count.".xml","w");
                        $file = public_path() . '/xml/sitemap'.$count.'.xml';
                        $xml_string = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
					}

					$xml_string .=' <url>'
						.'<loc>'.env('APP_URL').'/pages/'.$page['m_url'].'</loc>'
						.'<lastmod>'.date('Y-m-d', strtotime($page['updated_at'])).'</lastmod>'
						.'<changefreq>daily</changefreq>'
						.'<priority>0.9</priority>'
						.'<image:image>'
						.'<image:loc>'.env('APP_CDN').'/'.$page['header_media_path'].'</image:loc>'
						.'<image:title>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $page['p_title']).' | '.env('APP_NAME').'</image:title>'
						.'</image:image></url>';
				}

				fputs($sitemap_file, $xml_string);
				$xml_string = '';
			}
		}

		
		
		$settings = Setting::where('key','homepage')->first();
		if($settings){
			$settings->data = json_decode($settings->data, true);
			if($settings->data['collections'] && count($settings->data['collections']) >0){
				$collections_id = [];
				foreach($settings->data['collections'] as $col){
					$collections_id[$col['collection_id']] = $col['collection_id'];
				}

				$model = new Collection();
				// $from = 0;
				// $to= $model::max('id');

				// $model = $model::on();

				// $total = $to;
				// $take = 10000;

				// $pages = ceil($total / $take);
				
				// for ($i = 0; $i < $pages; $i++) {
				// 	$skip = $from + ($take * $i);

				// 	if (($take + $skip) > $to) {
				// 		$take = $to - $skip;
				// 	}
					
					// $results = $model->where('visibility',1)->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get()->toArray();
					$results = $model->whereIn('id',$collections_id)->get()->toArray();

					if(count($results) > 0){
						foreach($results as $collect){
							if(isset($collections_id[$collect['id']])){
								clearstatcache();
                    
								$file = public_path() . '/xml/sitemap'.$count.'.xml';
								//Get file size on MB
								$filesize = round((filesize($file)*.0009765625)* .0009765625, 1); 
								
								if($filesize >= 7){
									$xml_string='</urlset>';
									fputs($sitemap_file, $xml_string);
									$xml_string = '';
									// $id = $row['property_id'];
									fclose($sitemap_file);
									$count++;
									$sitemap_file = fopen(public_path() . "/xml/sitemap".$count.".xml","w");
									$file = public_path() . '/xml/sitemap'.$count.'.xml';
									$xml_string = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
								}

								$xml_string .=' <url>'
									.'<loc>'.env('APP_URL').'/items/'.$collect['slug'].'</loc>'
									.'<changefreq>daily</changefreq>'
									.'<priority>0.9</priority></url>';
							}
						}
						fputs($sitemap_file, $xml_string);
						$xml_string = '';
					}
				// }

			}
		}
		
		$xml_string='</urlset>';
		fputs($sitemap_file, $xml_string);
		fclose($sitemap_file);

	}

	public function productsSitemap(){
		$files = glob(public_path() . '/xml/products*.xml');

		if(count($files) > 0){
			foreach($files as $d_file) {
				unlink($d_file);
			}
		}

		$count = 1;

		$xml_string = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
		
		if(!is_dir(public_path() . "/xml")){
			mkdir(public_path() . "/xml");
		}
		$product_file = fopen(public_path() . "/xml/products1-sitemap.xml","w");

		$model = new Item();
		$from = 0;
		$to= $model::max('id');

        $model = $model::on();
        $total = $to;
        $take = 10000;

        $pages = ceil($total / $take);
        
        for ($i = 0; $i < $pages; $i++) {
            $skip = $from + ($take * $i);

            if (($take + $skip) > $to) {
                $take = $to - $skip;
            }
			
			$results = $model->with(['files','category' => function($q){
				return $q->where('active', 1);
			}])->where('active',1)->where([['id', '>', $skip],['id', '<=', ($skip + $take)]])->get()->toArray();
			
			if(count($results) > 0){
				foreach($results as $product){

					if($product['category'] && count($product['category']) > 0){

						clearstatcache();
						
						$file = public_path() . '/xml/products'.$count.'-sitemap.xml';
						//Get file size on MB
						$filesize = round((filesize($file)*.0009765625)* .0009765625, 1); 
						
						if($filesize >= 7){
							$xml_string='</urlset>';
							fputs($product_file, $xml_string);
							$xml_string = '';
							// $id = $row['property_id'];
							fclose($product_file);
							$count++;
							$product_file = fopen(public_path() . "/xml/products".$count."-sitemap.xml","w");
							$file = public_path() . '/xml/products'.$count.'-sitemap.xml';
							$xml_string = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
						}
	
						$xml_string .=' <url>'
							.'<loc>'.env('APP_URL').'/items/'.$product['sku'].'</loc>'
							.'<lastmod>'.date('Y-m-d', strtotime($product['updated_at'])).'</lastmod>'
							.'<changefreq>daily</changefreq>'
							.'<priority>0.9</priority>';
						if($product['files'] && count($product['files']) > 0){
							$xml_string .='<image:image>'
								.'<image:loc>'.env('APP_CDN').'/'.$product['files'][0]['path'].'</image:loc>'
								.'<image:title>'.preg_replace('/&(?!#?[a-z0-9]+;)/', '&amp;', $product['name']).' | '.env('APP_NAME').'</image:title>'
								.'</image:image>';
						}
						$xml_string .='</url>';
					}
				}
				fputs($product_file, $xml_string);
				$xml_string = '';
			}
		}
		$xml_string='</urlset>';
		fputs($product_file, $xml_string);
		fclose($product_file);
	}

}