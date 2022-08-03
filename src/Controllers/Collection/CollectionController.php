<?php

namespace Properos\Commerce\Controllers\Collection;

use App\Http\Controllers\Controller;
use Properos\Commerce\Models\Collection;

class CollectionController extends Controller
{
    
    /*
    |--------------------------------------------------------------------------
    | Admin Controller
    |--------------------------------------------------------------------------
    | 
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function index()
    {
        return view('be.commerce.collections');
    }

    public function create()
    {
        return view('be.commerce.add-collection');
    }

    public function edit($id)
    {
        if ($id > 0) {
            $collection = Collection::where('id',$id)->with(['items' => function($query){
                $query->orderBy('pivot_sort_order', 'ASC');
            }])->first();
            if ($collection) {
                return view('be.commerce.add-collection')->with(['editable_collection' => $collection]);
            }
        }
        return view('be.commerce.add-collection');
    }
}
