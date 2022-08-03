<?php

namespace Properos\Commerce\Controllers\Item;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\CReview;
use Properos\Base\Classes\Api;
use Illuminate\Validation\ValidationException;
use Properos\Commerce\Models\ItemReview;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'item_id' => 'required|integer|min:1',
                'title' => 'required|string|max:50',
                'comment' => 'required|max:1500',
                'rate' => 'required',
            ]);
            if(\Auth::check()){
                $cReview = new CReview();
                $review = $cReview->store($request->all());
                return Api::success('Item created successfully', $review);
            }else{
                return Api::success('Login', ['url' => '/login']);
            }
        } catch (ValidationException $exception) {
            return Api::error('Error', $exception->errors(),[]);
        }
    }

    public function getReviews($item_id)
	{
        if($item_id > 0){
            $reviews = ItemReview::select('id','fullname','rate','comment','title','created_at')->where('item_id', $item_id)->paginate(5);
            $pagination = [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ];
            return Api::success('Reviews List', $reviews->items(), $pagination);
        }
        
        return abort(404);
	}
}
