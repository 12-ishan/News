<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin\NewsCategory;
use App\Models\Admin\News;
use Illuminate\Http\Request;

class NewsCategoryController extends Controller
{
    public function getNewsCategories(Request $request)
    {
        $categories = NewsCategory::where('status', 1)->get();
    
        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'Categories do not exist',
                'status' => '0',
            ], 200);
        }
    
        $categoryData = [];
    
        $parentCategories = $categories->filter(function ($category) {
            return $category->parent_id === null;  
        });
    
        foreach ($parentCategories as $parentCategory) {
            $subcategories = $categories->filter(function ($category) use ($parentCategory) {
                return $category->parent_id === $parentCategory->id; 
            });
    
            $subCategoryData = $subcategories->map(function ($subcategory) {
                return [
                    'id' => $subcategory->id,
                    'name' => $subcategory->name,
                    'slug' => $subcategory->slug,
                ];
            });
    
            $categoryData[] = [
                'parent_category' => [
                    'id' => $parentCategory->id,
                    'name' => $parentCategory->name,
                    'slug' => $parentCategory->slug,
                ],
                'subcategories' => $subCategoryData,
            ];
        }
    
        return response()->json([
            'message' => 'Categories exist',
            'status' => '1',
            'categories' => $categoryData,
        ], 200);
    }
    
    

    public function getNewsByCategory($slug)
    {
        $category = NewsCategory::where('slug', $slug)->first();
    
        if (empty($category)) {
            $response = [
                'message' => 'Category not exists',
                'status' => '0',
            ];
        }
    
    
        // $allNews = News::where('category_id', $category->id)->get();
    
       
        // if ($allNews->isEmpty()) {
        //     $response = [
        //         'message' => 'News not found',
        //         'status' => '0',
        //     ];
        // }
    
    
        $news = News::where('category_id', $category->id)->get();
    
        $data = [];
        foreach ($news as $newsData) {
            $mediaName = url('/') . "/uploads/newsImage/" . getMediaName($newsData['imageId']);
            $newsCategoryName = $category ? $category->name : 'null'; 
    
            $data[] = [
                'id' => $newsData['id'],
                'title' => $newsData['title'],
                'slug' => $newsData['slug'],
                'media_name' => $mediaName,
                'description' => $newsData['description'],
                'news_category' => $newsCategoryName,
            ];
        }
    
      
        $response = [
            'message' => 'News found',
            'status' => '1',
            'news' => $data,
            'categoryName' => $category->name,
            'categorySlug' => $category->slug,
            // 'currentPage' => $news->currentPage(),
            // 'lastPage' => $news->lastPage(),
        ];
    
        return response()->json($response, 200);
    }
    
    
        

public function search(Request $request)
{
    $query = $request->input('query');

    $news = News::where('title', 'LIKE', "{$query}%")->get();

    $data = [];

    foreach ($news as $newsData) {
       
        $category = $newsData->category;
        
        $mediaName = url('/') . "/uploads/newsImage/" . getMediaName($newsData['imageId']);
        $newsCategoryName = $category ? $category->name : 'null'; 
        $categorySlug = $category ? $category->slug : 'null'; 

        $data[] = [
            'id' => $newsData['id'],
            'title' => $newsData['title'],
            'slug' => $newsData['slug'],
            'media_name' => $mediaName,
            'price' => $newsData['price'],
            'metaDescription' => $newsData['meta_description'],
            'description' => $newsData['description'],
            'news_category' => $newsCategoryName,
            'category_slug' => $categorySlug, 
        ];
    }

    return response()->json([
        'query' => $query,
        'results' => $data,
    ]);
}




   
}  

