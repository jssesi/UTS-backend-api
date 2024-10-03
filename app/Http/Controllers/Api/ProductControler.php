<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->paginate(5);

        //response
        $response = [
            'message' => 'List all product',
            'data' => $products,
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi data
        $validator = Validator::make($request->all(),[
            'category_id' => 'required',
            'product' => 'required|min:2|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $product = Product::create([
           'category_id' => $request->category_id,
            'product' => $request->product ,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'image'    => $image->hashName(),

        ]);


        //response
        $response = [
            'status' => 'success',
            'success'   => 'Add product success',
            'data'      => $product,

        ];


        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        //find gameplay by ID
        $products = Product::find($id);
        $categori = Category::find($products);

        //response
        $response = [
            'message' => 'List all product',
            'data' => $products,
            'categori'  => $categori,
        ];

        return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'product' => 'required|min:2|unique:products',
            'description' => 'required',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpeg,jpg,png|max:2048',


        ]);


        if ($validator->fails()){
            return response()->json($validator->errors(), 422);

        }

        $product = Product::find($id);

        if ($request->hasFile('image')) {

            //upload image
            $image = $request->file('image');
            $image->storeAs('public/posts', $image->hashName());

            //delete old image
            Storage::delete('public/posts/' . basename($product->image));

            //update product with new image
            $product->update([
               'category_id' => $request->category_id,
                'product' => $request->product,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'image' => $image->hashName(),

            ]);

        }else {

                //update post without image
                $product->update([
                    'category_id' => $request->category_id,
                    'product' => $request->product,
                    'description' => $request->description,
                    'price' => $request->price,
                    'stock' => $request->stock,
                ]);

        }

        $response =[
            'status' => 'success',
            'message' => 'Update product success',
            'data' => $product
        ];

        return response()->json($response, 201);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id)->delete();
        $response = [
            'status' => 'success',
            'success'   => 'Delete product Success',
        ];

        return response()->json($response, 200);
    }
}
