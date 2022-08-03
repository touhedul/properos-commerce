<?php

namespace Properos\Commerce\Controllers\File;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Properos\Commerce\Classes\CFile;
use Properos\Base\Classes\Api;

class ApiFileController extends Controller
{
    private $file;
    /*
    |--------------------------------------------------------------------------
    | ApiFileController Controller
    |--------------------------------------------------------------------------
    | 
     */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CFile $file)
    {
        $this->file = $file;
    }

    public function upload(Request $request)
    {
        $validatedData = $request->validate([
            'picture' => 'required|file|image|dimensions:min_width=640,min_height=480',
            'owner_id' => 'required|integer',
            'owner_type' => 'required|alpha|in:item,profile,category,page'
        ]);

        $file = $this->file->uploadFile($request->all());

        $result['id'] = $file->id;
        $result['path'] = $file->path;
        $result['thumb'] = $file->thumb_path;
        return Api::success('File saved successfully', $result);
        
    }

    public function deleteFile($id)
    {
       if($id > 0){
        $delete_result = $this->file->deleteFile($id);
        if ($delete_result) {
            $object_type = get_class($delete_result);
            $reflection = new \ReflectionClass($object_type);
            return Api::success('File removed successfully', $reflection->getShortName());
          }
       }
        return Api::error('001', 'Error removing the file');
    }

}
