<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\Category;
use Properos\Base\Classes\Base;

class CCategory extends Base
{
	function __construct()
	{
		parent::__construct(Category::class, 'Category');     
		$this->category = new Category();
	}

	public function init_fillable()
    {
        foreach ($this->model->getFillable() as $key) {
            $this->fillable[$key] = null;
        }
    }

	public function store(array $data)
	{
		if ($data['id'] > 0) {
			$category = $this->category->find($id);
		}
		if (count($data) > 0) {
			$this->category->name = $data['name'];
			$this->category->description = $data['description'];
			if (isset($data['active'])) {
				$this->category->active = $data['active'];
			} else {
				$this->category->active = 1;
			}


			$this->category->save();
		}
		return $this->category;
	}

	public function update(array $data)
	{
		if (count($data) > 0) {
			if ($data['id'] > 0) {
				$category = $this->category->find($data['id']);
				if ($category) {
					$category->name = $data['name'];
					$category->description = $data['description'];
					if (isset($data['active'])) {
						$category->active = $data['active'];
					} else {
						$category->active = 1;
					}

					$category->save();
				}
			}
		}
		return $category;
	}

	public function destroy($id)
	{
		$this->category = $this->category->find($id);
		return $this->category->delete();
	}
}