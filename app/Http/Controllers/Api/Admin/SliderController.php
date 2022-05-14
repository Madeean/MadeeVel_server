<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{
    public function index(){
        $sliders = Slider::latest()->paginate(5);

        return new SliderResource(true,'list data sliders',$sliders);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'image'=>'required|image|mimes:png,jpg,jpeg|max:2000'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $image = $request->file('image');
        $image->storeAs('public/sliders',$image->hashName());

        $slider = Slider::create([
            'image'=>$image->hashName(),
            'user_id'=>auth()->guard('api')->user()->id,
        ]);

        if($slider){
            return new SliderResource(true,'data slider berhasil disimpan',$slider);
        }

        return new SliderResource(false,'data slider gagal disimpann',null);
    }
    
    public function destroy(Slider $slider)
    {
        //remove image
        Storage::disk('local')->delete('public/sliders/'.basename($slider->image));

        if($slider->delete()) {
            //return success with Api Resource
            return new SliderResource(true, 'Data Slider Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new SliderResource(false, 'Data Slider Gagal Dihapus!', null);
    }
}
