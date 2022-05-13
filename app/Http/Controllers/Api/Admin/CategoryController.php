<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(){
        //get categories
        $categories = Category::when(request()->q, function($categories) {
            $categories = $categories->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        
        //return with Api Resource
        return new CategoryResource(true, 'List Data Categories', $categories);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'image'=>'required|image|mimes:png,jpg,jpeg|max:2000',
            'name'=>'required|unique:categories'
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        $image = $request->file('image');
        $image->storeAs('public/categories',$image->hashName());

        $category = Category::create([
            'image'=>$image->hashName(),
            'name'=>$request->name,
            'slug'=>Str::slug($request->name,'-')
        ]);

        if($category){
            return new CategoryResource(true, 'Data category berhasil disimpan',$category);
        }

        return new CategoryResource(false,'data category gagal disimpan',null);
    }

    public function show($id){
        $category=Category::whereId($id)->first();
        if($category){
            return new CategoryResource(true,'Detail data category',$category);
        }

        return new CategoryResource(false,'detail data category tidak ditemukan',null);
    }

    public function update(Request $request, Category $category){
        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:categories,name,'.$category->id
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),422);
        }

        if($request->file('image')){
            Storage::disk('local')->delete('public/categories/'.basename($category->image));

            $image = $request->file('image');
            $image->storeAs('public/categories',$image->hashName());

            $category->update([
                'image'=>$image->hashName(),
                'name'=>$request->name,
                'slug'=>Str::slug($request->name,'-')
            ]);
        }

        $category->update([
            'name'=>$request->name,
            'slug'=>Str::slug($request->name,'-')
        ]);

        if($category){
            return new CategoryResource(true,'Data category berhasil diupdate',$category);
        }

        return new CategoryResource(false,'Data category gagal diupdate',null);
    }

    public function destroy(Category $category){
        Storage::disk('local')->delete('public/categories/'.basename($category->image));

        if($category->delete()){
            return new CategoryResource(true,'Data category berhasil dihapus',null);
        }
        return new CategoryResource(false,'Data category gagal dihapus',null);
        
    }
}
