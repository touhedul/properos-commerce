<?php

namespace Properos\Commerce\Controllers\Collection;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Properos\Base\Classes\Api;
use Properos\Commerce\Classes\CCollection;
use Properos\Commerce\Models\ItemCollection;
use Properos\Commerce\Models\Collection;
use Properos\Base\Exceptions\ApiException;
use Properos\Commerce\Models\Setting;

class ApiCollectionController extends Controller
{

    public function __construct()
    {
    }

    public function search(Request $request)
    {
        $cCollection = new CCollection();
        $options = $cCollection->standardize_search($request);
        $collections = $cCollection->find($options);
        return response()->json(Api::success('Collections search result.', $collections, $cCollection->get_paginator()->toArray()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $data = $request->all();
        $rules = [
            'name' => 'required|unique:collections|string|max:191'
        ];

        $validator = \Validator::make($data, $rules);
        if($validator->passes()){
            $cCollection = new CCollection();
            $result = $cCollection->createModel($data);
            if(isset($data['items']) && count($data['items']) > 0){
                $items = [];
                foreach($data['items'] as $v){
                    $items[] = [
                                'collection_id' => $result['id'],
                                'item_id' => $v['id'],
                                'sort_order' => $v['sort_order'],
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                }
                ItemCollection::insert($items);
            }
            return Api::success('Collection created successfully', $result);
        }

        return Api::error('005', $validator->errors(), null);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $data = $request->all();
        $rules = [
            'id' => 'required|min:1',
            'name' => 'required|string|max:191'
        ];

        $validator = \Validator::make($data, $rules);

        if($validator->passes()){
            if(Collection::where([['name',$data['name']],['id','<>',$data['id']]])->first()){
                throw new ApiException('Collection with that name exist','005',null);
            }
            $cCollection = new CCollection();
            $result = $cCollection->updateModel($data);
            if(isset($data['items']) && count($data['items']) > 0){
                ItemCollection::where('collection_id',$data['id'])->delete();
                $items = [];
                foreach($data['items'] as $v){
                    $items[] = [
                                'collection_id' => $data['id'],
                                'item_id' => $v['id'],
                                'sort_order' => $v['sort_order'],
                                'updated_at' => date('Y-m-d H:i:s'),
                            ];
                }
                ItemCollection::insert($items);
            }
            return Api::success('Collection updated successfully', $result);
        }

        return Api::error('005', $validator->errors(), null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        if($id > 0){
            Collection::where('id', $id)->delete();
            ItemCollection::where('collection_id', $id)->delete();
            $settings= Setting::where('key','homepage')->first();
            if($settings){
                $settings->data = json_decode($settings->data, true);
                if(isset($settings->data['collections']) && count($settings->data['collections']) > 0){
                    $collections['collections'] = [];
                    foreach($settings->data['collections'] as $v){
                        if($v['collection_id'] != $id){
                            $collections['collections'][] = $v;
                        }
                    }
                    $settings->data= json_encode($collections);
                    $settings->save();
                }
            }
            return Api::success('Collection deleted successfully', ['id' => $id]);
        }
        return Api::error('003', 'Invalid Query Format', null);
    }

}


