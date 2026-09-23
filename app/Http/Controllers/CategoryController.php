<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json([
            "categories" => Category::paginate(10)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json($category, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Category::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);
        $categoryRequest = new CategoryRequest();
        $validator = Validator::make($request->all(), $categoryRequest->rules());

        if (
            $category->name === $request->name &&
            $category->description === $request->description
        ) {
            return response()->json([
                "message" => "Nothing changed.",
            ], 400);
        } elseif ($validator->fails()) {
            return response()->json([
                "message" => $validator->errors(),
            ], 422);
        }

        $category->update($request->all());
        return response()->json([
            "message" => "Category updated successfully",
            "category" => $category
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json([
            "message" => "Category deleted successfully"
        ], 200);
    }
}
