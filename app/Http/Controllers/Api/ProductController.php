<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    //
    public function index()
    {
       $products = Product::get();
       if($products->count() > 0){
        return ProductResource::collection($products);

       }
       else{
        return response()->json(['message' => 'No record available', 200]);

       }

    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock' => 'integer',
        ]);
        if($validator->fails()){
            return response()->json([
                ['message' => 'Fields are Required'],
                'error' => $validator->messages(),
            ]);
        }
        $product = Product::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'stock'=>$request->stock,
        ]);
        return response()->json([
            'message' => 'Product created Successfully',
            'data' => new ProductResource($product) 
             ], 200);
        
    }
    public function show(Product $product)
    {
        return new ProductResource($product);
        
    }
    public function update(Request $request, Product $product){
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'stock' => 'integer',
        ]);
        if($validator->fails()){
            return response()->json([
                ['message' => 'Fields are Required'],
                'error' => $validator->messages(),
            ]);
        }
        $product -> update([
            'name'=>$request->name,
            'description'=>$request->description,
            'price'=>$request->price,
            'stock'=>$request->stock,
        ]);
        return response()->json([
            'message' => 'Product updated Successfully',
            'data' => new ProductResource($product) 
             ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();   
        return response()->json([
            'message' => 'Product deleted Successfully',
            
             ], 200);
    }
}
