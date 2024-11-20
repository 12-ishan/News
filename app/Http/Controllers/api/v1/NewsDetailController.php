<?php
namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Admin\News;
use App\Models\Admin\NewsCategory;
use Illuminate\Http\Request;

class NewsDetailController extends Controller
{
    public function fetchNewsDetails($newsSlug)
{
   

     $news = News::where('slug', $newsSlug)->first();

    if (!$news) {
        $response = [
            'message' => 'news does not exist',
            'status' => '0',
        ];
    }

    $newsDetails = [
        'id' => $news->id,
        'slug' => $news->slug,
        'title' => $news->title,
        'metaDescription' => $news->meta_description,
        'description' => $news->description,
      //  'newsCategory' => newsCategory($news->category_id),
        'image' => url('/') . "/uploads/newsImage/" . getMediaName($news->imageId),
        'status' => $news->status,
    ];

    $response = [
        'message' => 'news exists',
        'status' => '1',
        'response' => [
            'news' => [
                'newsDetails' => $newsDetails,
            ]
        ]
    ];

   

    return response()->json($response, 200);
}


}
