<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function getAllCategory()
    {
        try {
            $categories = Category::get();

            return response()->json([
                'categories' => CategoryResource::collection($categories),
            ], 200);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'List category error!',
            ], 500);
        }
    }

    public function getCategoryById($id){
        try {
            $category = Category::find($id);

            return response()->json([
                'data' => new CategoryResource($category),
            ], 200);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage() . ' ---- line: ' . $e->getLine());
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'get category by id error!',
            ], 500);
        }
    }
}
