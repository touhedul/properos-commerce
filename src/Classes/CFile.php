<?php

namespace Properos\Commerce\Classes;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Image;
use Properos\Commerce\Models\File as MFile;
use Properos\Commerce\Models\Item;
use Properos\Commerce\Models\Order;
use Properos\Commerce\Models\Category;
use Properos\Base\Exceptions\ApiException;
use Validator;

class CFile
{
	private $file;

	function __construct(MFile $file)
	{
		$this->file = $file;
	}

	public function uploadFile(array $data)
	{
		$validator = Validator::make($data, [
			'picture' => 'sometimes|required|file|image|dimensions:min_width=640,min_height=480',
			'picture_base_64' => 'sometimes|required',
			'owner_id' => 'required_if:owner_type,item,profile,category,order|integer',
			'owner_type' => 'required|alpha|in:item,profile,category,order,post,page,template'
		]);
		if (!$validator->fails()) {
			/* try { */
			ini_set('memory_limit', '512M');
			if (isset($data['picture'])) {
				//$image = Storage::disk('public')->putFile('pictures', $data['picture']);
				$img = \Image::make($data['picture'])->orientate();
			} else if (isset($data['picture_base_64'])) {
				$img = \Image::make($data['picture_base_64'])->orientate();
			} else {
				throw new ApiException('File format not supported');
			}
			$owner_type = $data['owner_type'];

			switch ($data['owner_type']) {
				case 'item':
				case 'category':
					$stream_thumb = (string)$img->fit(690, 690, function ($constraint) {
						$constraint->aspectRatio();
					})->encode('jpg', 100);
					$data['thumb_path'] = 'pictures/' . $owner_type . '/thumbs/' . Str::random(40) . '.jpg';
					Storage::disk('public')->put($data['thumb_path'], $stream_thumb);

					$stream_image = (string)$img->resize(1024, 1024, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					})->encode('jpg', 100);

					$data['path'] = 'pictures/' . $owner_type . '/bigs/' . Str::random(40) . '.jpg';
					Storage::disk('public')->put($data['path'], $stream_image);
					break;
				case 'post':
				case 'template':
				case 'page':
					$stream_image = (string)$img->resize(1920, 720, function ($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					})->encode('jpg', 100);

					$data['path'] = 'pictures/' . $owner_type . '/bigs/' . Str::random(40) . '.jpg';
					Storage::disk('public')->put($data['path'], $stream_image);
					break;
				case 'order':
					$stream_image = (string)$img->encode('jpg', 100);
					$data['path'] = 'pictures/' . $owner_type . '/bigs/' . Str::random(40) . '.jpg';
					Storage::disk('public')->put($data['path'], $stream_image);
					break;

				case 'profile':
					break;

				default:
					break;
			}
			//Storage::disk('public')->delete($image);
			if ($data['owner_type'] == 'post') {
				return $data['path'];
			} else if ($data['owner_type'] == 'template') {
				return $data['path'];
			} else {
				$file = $this->file->store($data);
				if ($file != null) {
					return $file;
				} else {
					return false;
				}
			}
			/* } catch (\Exception $e) {
				throw new ApiException('Error saving file', '001', $data);
			} */
		} else {
			throw new ApiException($validator->errors());
		}
	}

	public function deleteFile($id)
	{
		try {
			if ($id) {
				$file = $this->file->findOrFail($id);
				if ($file) {

					$path = $file->path;
					if ($file->thumb_path) {
						$thumb_path = $file->thumb_path;

					}
					$owner_type = $file->owner_type;
					$removed = $file->delete();
					if ($removed) {
						$removed_path = Storage::disk('public')->delete($path);
						if ($removed_path) {
							if ($thumb_path) {
								$removed_thumb = Storage::disk('public')->delete($thumb_path);
							}
							if ($owner_type == 'item') {
								return new Item();
							} else if ($owner_type == 'category') {
								return new Category();
							} else if ($owner_type == 'post') {
								return new Post();
							} else if ($owner_type == 'order') {
								return new Order();
							}
						} else {
							return null;
						}
					}
				}
			}
		} catch (\Exception $e) {
			return false;
		}
	}

}