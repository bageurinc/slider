<?php
namespace Bageur\Slider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Bageur\Slider\model\slider;
use Bageur\Slider\Processors\UploadProcessor;
use Validator;
class SliderController extends Controller
{

    public function index(Request $request)
    {
       $query = slider::datatable($request);
       return $query;
    }

    public function store(Request $request)
    {
        $rules    	= [
                        'gambar'                => 'required|mimes:jpg,jpeg,png|max:2000',
                    ];

        $messages 	= [];
        $attributes = [];

        $validator = Validator::make($request->all(), $rules,$messages,$attributes);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $slider              		= new slider;
            $slider->caption	        = $request->caption;
            $upload                     = UploadProcessor::go($request->file('gambar'),'slider');
            $slider->gambar             = $upload;
            $slider->save();
            return response(['status' => true ,'text'    => 'has input'], 200); 
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
        return slider::findOrFail($id);
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
        $rules      = [];
        if($request->file('gambar') != null){
            $rules['gambar'] = 'mimes:jpg,jpeg,png|max:2000';
        }  
        $messages   = [];
        $attributes = [];

        $validator = Validator::make($request->all(), $rules,$messages,$attributes);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response(['status' => false ,'error'    =>  $errors->all()], 200);
        }else{
            $slider                     = slider::findOrFail($id);
            $slider->caption            = $request->caption;
            if($request->file('gambar') != null){
                $upload                     = UploadProcessor::go($request->file('gambar'),'slider');
                $slider->gambar             = $upload;
            }
            $slider->save();
            return response(['status' => true ,'text'    => 'has input'], 200); 
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
          $delete = slider::findOrFail($id);
          $delete->delete();
          return response(['status' => true ,'text'    => 'has deleted'], 200); 
    }

}