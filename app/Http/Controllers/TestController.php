<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Product;
use App\Http\Resources\Product as ProductResource;

class TestController extends Controller
{
    public function index()
    {
        // Получаем продукты
        $products = Product::orderBy('created_at', 'desc')->paginate(25);

        //Возвращаем коллекцию продуктов как ресурс
        return ProductResource::collection($products);
    }

    public function search($name)
    {
        $products = Product::where('name', 'like', '%' . $name . '%')->orderBy('created_at', 'desc')->paginate(25);
         return ProductResource::collection($products);
    }

    public function store(Request $request)
    {
        $product = $request->isMethod('put') ? Product::findOrFail($request->product_id) : new Product;

        $product->id = $request->input('product_id');
        $product->name = $request->input('name');
        $product->price = $request->input('price');

        if($product->save()){
            return new ProductResource($product);
        }

    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if($product->delete()){
            return new ProductResource($product);
        }
    
    }

}
