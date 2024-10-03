<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryControler extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(5);

        //response
        $response = [
            'status' => 'success',
            'message' => 'List all character',
            'data' => $categories,
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
            'category' => 'required|unique:categories|min:2',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ],422);
        }

        $categori = Category::create([
            'category' => $request->category,

        ]);


        //response
        $response = [
            'status' => 'success',
            'success'   => 'Add categori success',
            'data'      => $categori,
        ];


        return response()->json($response, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //find gameplay by ID



        $category = Category::find($id);

        //response
        $response = [
            'status' => 'success',
            'success'   => 'Detail category found',
            'data'      => $category,
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
            'category' => 'required|unique:categories|min:2',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //find gameplay by ID
        $category = Category::find($id);

        $category->update([
            'category' => $request->category,
        ]);

        //response
        $response = [
            'status' => 'success',
            'success'   => 'Update category success',
            'data'      => $category,
        ];

        return response()->json($response, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id)->delete();
        $response = [
            'status' => 'success',
            'success'   => 'Delete category Success',
        ];

        return response()->json($response, 200);
    }
}
