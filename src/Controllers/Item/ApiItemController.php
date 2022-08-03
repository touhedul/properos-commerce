<?php

namespace Properos\Commerce\Controllers\Item;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\CItem;
use Properos\Commerce\Models\Item;
use Properos\Base\Classes\Api;
use Illuminate\Validation\ValidationException;

class ApiItemController extends Controller
{
    private $cItem;
    private $item;

    public function __construct(CItem $cItem, Item $item)
    {
        $this->cItem = $cItem;
        $this->item = $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|max:255',
                'description' => 'required',
                'price' => 'required|numeric',
                'type' => 'required',
                'msrp' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'last_sku' => 'nullable|integer',
                'tax_percent' => 'nullable|numeric',
                'cost' => 'nullable|numeric',
                'discount_percent' => 'nullable|numeric',
            ];
            if(isset($data['type']) && $data['type'] == 'digital'){
                $rules['total_qty'] = 'required|numeric';
            }
            $validatedData = $request->validate($rules);
            
            $item = $this->cItem->store($request->all());
            if ($item != null) {
                return Api::success('Item created successfully', $item);
            }
            return Api::error('Error creating the item', []);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 0,
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id > 0) {
            $result = $this->item->where('id',$id)->with(['files' => function ($q) {
                return $q->where('type', 'image');
            }])->first();
            if ($result == true) {
                return Api::success('Item info', $result);
            } else {
                return Api::error('001', 'Error removing the itemItem not found');
            }
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function items()
    {
        $items = Item::where('active', 1)->whereHas('category', function ($q) {
            return $q->where('active', 1);
        })->with(['category' => function ($q) {
            return $q->where('active', '=', 1);
        }, 'files' => function ($q) {
            return $q->where('type', 'image');
        }])->orderBy('id','DESC')->paginate(8)->toJson();
        
        return $items;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'id' => 'required|integer',
                'name' => 'required|max:255',
                'description' => 'required',
                'price' => 'required|numeric',
                'msrp' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'height' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'length' => 'nullable|numeric',
                'last_sku' => 'nullable|integer',
                'tax_percent' => 'nullable|numeric',
                'cost' => 'nullable|numeric',
                'discount_percent' => 'nullable|numeric',
            ]);

            $data = $request->all();
            $data['id'] = $id;

            $item = $this->cItem->update($data);

            if ($item != null) {
                return Api::success('Item updated successfully', $item);
            }
            return Api::error('Error updating the item', []);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id > 0) {
            $result = $this->cItem->destroy($id);
            if ($result == true) {
                return Api::success('Item deleted successfully', []);
            } else {
                return Api::error('001', 'Error removing the item');
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function filterByCategory($ids, $min = null, $max = null)
    // {
    //     if (!$min && !$max) {
    //         if (count(json_decode($ids)) > 0) {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             }])->whereIn('category_id', json_decode($ids))->orderBy('id','DESC')->paginate(8);
    //         } else {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             }])->orderBy('id','DESC')->paginate(8);
    //         }
    //     } else {
    //         if (count(json_decode($ids)) > 0) {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             }])->whereBetween('price', [$min, $max])->whereIn('category_id', json_decode($ids))->orderBy('id','DESC')->paginate(8);
    //         } else {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             }])->orderBy('id','DESC')->paginate(8);
    //         }
    //     }
    //     if ($items) {
    //         return Api::success('Items by category', ['items' => $items]);
    //     } else {
    //         return Api::error('001', 'Error getting items by category');
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function filterByPriceRange($min, $max, $ids = null)
    // {
    //     if (!$ids) {
    //         $ids = [];
    //         $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //             return $q->where('active', 1);
    //         })->with(['category', 'files' => function ($q) {
    //             return $q->where('type', 'image');
    //         }])->whereBetween('price', [$min, $max])->orderBy('id','DESC')->paginate(8);
    //     } else {
    //         $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //             return $q->where('active', 1);
    //         })->with(['category', 'files' => function ($q) {
    //             return $q->where('type', 'image');
    //         }])->whereBetween('price', [$min, $max])->whereIn('category_id', json_decode($ids))->orderBy('id','DESC')->paginate(8);
    //     }
    //     if ($items) {
    //         return Api::success('Items by price range', ['items' => $items]);
    //     } else {
    //         return Api::error('001', 'Error getting items by price range');
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function searchByString($string = " ")
    {
        if ($string != " ") {
            $items = $this->item->where('active', 1)
                ->where('name', 'like', '%' . $string . '%')
                ->orWhere('description', 'like', '%' . $string . '%')
                ->whereHas('category', function ($q) {
                    return $q->where('active', 1);
                })
                ->with(['category', 'files' => function ($q) {
                    return $q->where('type', 'image')->where('owner_type', '=', 'item');
                }])
                ->where('active', 1)->orderBy('id','DESC')->get();

            if ($items) {
                return Api::success('Items', ['items' => $items]);
            } else {
                return Api::error('001', 'Error getting items', []);
            }
        } else {
            $items = [];
            return Api::success('Items', ['items' => $items]);
        }
    }

    /**
     * Sync an item with it's counterpart in a marketplace by marketplace identifier
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function marketplaceLink(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'marketplace_id' => array(
                    'required',
                    'regex:/^[A-Za-z0-9]+$/'
                ),
                'marketplace' => 'required',
                'item_id' => 'required'
            ]);

            $result = $this->cItem->marketplaceLink($request->all());

            if ($result['status'] == 1) {
                return Api::success($result['message'], $result['data']);
            } else if ($result['status'] == 2) {
                return Api::error($result['code'], $result['message'], []);
            }

        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Error',
                'errors' => $exception->errors(),
            ], 422);
        }
    }

    /**
     * Sync an item with it's counterpart in a marketplace by marketplace identifier
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function marketplaceRemove($id)
    {
        if ($id > 0) {
            $result = $this->cItem->marketplaceRemove($id);
            if ($result['status'] == 1) {
                return Api::success($result['message'], $result['data']);
            } else if ($result['status'] == 2) {
                return Api::error($result['code'], $result['message'], []);
            }
        }
    }

    /**
     * Sync an item with it's counterpart in a marketplace by marketplace identifier
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function activate($id)
    {
        if ($id > 0) {
            $item = $this->item->find($id);
            if ($item) {
                if ($item->active == false) {
                    $item->active == true;
                } else {
                    $item->active == false;
                }
                return Api::success('Item visibility changed successfully', $item);
            } else if ($result['status'] == 2) {
                return Api::error('Error changing item visibility', []);
            }
        }
    }

    // public function itemsCollection($slug)
    // {
    //     $items = Item::where('active', 1)->with(['files' => function ($q) {
	// 		return $q->where('type', 'image')->where('owner_type', 'item');;
	// 	}, 'category', 'collections' => function($q) use($slug) {
	// 		return $q->where('slug','/'.$slug);
	// 	}])->whereHas('collections',function($q) use($slug) {
	// 		return $q->where('slug','/'.$slug);
	// 	})->orderBy('id','DESC')->paginate(8)->toJson();
        
    //     return $items;
    // }

    // public function filterByCollectionCategory($collection_id, $ids, $min = null, $max = null)
    // {
    //     if ((!$min && !$max) || ($min == 0 && $max == 0)) {
    //         if (count(json_decode($ids)) > 0) {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->whereHas('collections',function($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             },'collections' => function ($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             }])->whereIn('category_id', json_decode($ids))->orderBy('id','DESC')->paginate(8);
    //         } else {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->whereHas('collections',function($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             }, 'collections' => function ($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             }])->orderBy('id','DESC')->paginate(8);
    //         }
    //     } else {
    //         if (count(json_decode($ids)) > 0) {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->whereHas('collections',function($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             },'collections' => function ($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             }])->whereBetween('price', [$min, $max])->whereIn('category_id', json_decode($ids))->orderBy('id','DESC')->paginate(8);
    //         } else {
    //             $items = $this->item->where('active', 1)->whereHas('category', function ($q) {
    //                 return $q->where('active', 1);
    //             })->whereHas('collections',function($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             })->with(['category', 'files' => function ($q) {
    //                 return $q->where('type', 'image');
    //             }, 'collections' => function ($q) use($collection_id) {
    //                 return $q->where('collections.id', $collection_id);
    //             }])->whereBetween('price', [$min, $max])->orderBy('id','DESC')->paginate(8);
    //         }
    //     }
    //     if ($items) {
    //         return Api::success('Items by category', ['items' => $items]);
    //     } else {
    //         return Api::error('001', 'Error getting items by category');
    //     }
    // }

    public function searchItems(Request $request)
    {
        $options = $this->cItem->standardize_search($request);
        $items = $this->cItem->find($options);
        return response()->json(Api::success('Items search result.', $items, $this->cItem->get_paginator()->toArray()));
    }


}
