<?php

namespace Properos\Commerce\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\CCategory;
use Properos\Commerce\Models\Category;
use Properos\Base\Classes\Api;

class ApiCategoryController extends Controller
{
    private $category;
    private $cCategory;
    /*
    |--------------------------------------------------------------------------
    | ApiCategoryController Controller
    |--------------------------------------------------------------------------
    | 
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Category $category, CCategory $cCategory)
    {
        $this->category = $category;
        $this->cCategory = $cCategory;
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
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'active' => 'numeric',
        ]);

        $category = $this->cCategory->store($request->all());

        if ($category != null) {
            return Api::success('Category created successfully', $category);
        }
        return Api::error('Error creating the category', []);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'id' => 'required',
            'name' => 'required|max:255',
            'description' => 'required',
        ]);

        $data = $request->all();
        $data['id'] = $id;

        $category = $this->cCategory->update($data);

        if ($category != null) {
            return Api::success('Category updated successfully', $category);
        }
        return Api::error('Error updating the category', []);
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
            $result = $this->cCategory->destroy($id);
            if ($result == true) {
                return Api::success('Category deleted successfully', []);
            } else {
                return Api::error('001', 'Error removing the category');
            }
        }
    }
}
