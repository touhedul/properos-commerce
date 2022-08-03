<?php

namespace Properos\Commerce\Classes;

use Illuminate\Http\Request;
use Properos\Commerce\Models\ItemReview;
use Properos\Commerce\Models\Item;

class CReview
{

    function __construct()
    {

    }

    public function store(array $data)
    {
        $data['user_id'] = \Auth::user()->id;
        $data['fullname'] = \Auth::user()->firstname.' '.\Auth::user()->lastname; 

        $review = ItemReview::where([['item_id', $data['item_id']], ['user_id', $data['user_id']]])->first();
        $item = Item::where('id', $data['item_id'])->first();
        if(!$review){
            $review = ItemReview::create($data);
    
            if($item && $item->reviews_total > 0){
                $item->reviews_percent = ($data['rate'] + $item->reviews_percent) / 2;
            }else{
                $item->reviews_percent = $data['rate'];
            }
            $item->reviews_total = $item->reviews_total + 1;
            $item->save();
        }else{
            if($review->rate != $data['rate']){
                if($item && $item->reviews_total > 1){
                    $item->reviews_percent = $item->reviews_percent *2 - $data['rate'];
                }else{
                    $item->reviews_percent = $data['rate'];
                }
                $item->save();
            }
            ItemReview::where([['item_id', $data['item_id']], ['user_id', $data['user_id']]])
                                ->update([
                                    'title' => $data['title'],
                                    'rate' => $data['rate'],
                                    'comment' => $data['comment'],
                                ]);
        }
        
        return $review;
    }

}
