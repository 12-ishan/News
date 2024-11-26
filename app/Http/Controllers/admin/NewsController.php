<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\NewsCategory;
use App\Models\Admin\NewsSubCategory;
use App\Models\Admin\News;
use App\Models\Admin\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Session;
use Illuminate\Support\Facades\Gate;

//use App\Http\Requests\StorenewsRequest;


class NewsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->userId = Auth::user()->id;
            $this->accountId = Auth::user()->accountId;
            $currentRoute = $request->route()->getName(); 
        $currentUrl = $request->fullUrl(); 

        activity('news')->event(config('event.EVENT_UPDATED'))
            ->causedBy(auth()->user())
            ->withProperties([
                'route' => $currentRoute,
                'url' => $currentUrl,
            ])
            ->log("Accessed the '{$currentRoute}' page");

        return $next($request);
        });
    }

  
    public function index()
    {
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {

            $data = array();
        $data["news"] = News::orderBy('sortOrder')->get();
        $data["pageTitle"] = 'Manage News';
        $data["activeMenu"] = 'news';

        return view('admin.news.manage')->with($data);
        }
     
        else{
            return view('admin.permissionDenied');
        }
    }

    
    public function create()
    {
       if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {

            $data = array();

            $data["newsCategory"] = NewsCategory::where('status',1)->orderBy('sortOrder')->get();
           
            $data["pageTitle"] = 'Add News';
            $data["activeMenu"] = 'News';

            // activity('news')
            // ->causedBy(auth()->user())
            // ->log('Viewed news create page');

            return view('admin.news.create')->with($data);

           
        }
        else{
            return view('admin.permissionDenied');
        }

       
    }

    public function store(Request $request)
    { 
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {
           // abort(403, 'You do not have permission to add news.');
        
           $this->validate(request(), [
            'title' => 'required',
            'categoryId' => 'required',
           // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

         $news = new News();
        
        if ($request->hasFile('image')) { 

            $mediaId = imageUpload($request->image, $news->imageId, $this->userId, "uploads/newsImage/"); 
            $news->imageId = $mediaId;
         }
    
        $news->category_id = $request->input('categoryId');
        $news->parent_id = $request->input('parentId');
        $news->title = $request->input('title');
        $news->meta_description = $request->input('metaDescription');
        $news->slug = Str::slug($request->input('title'));
        $news->description = $request->input('description');
        $news->status = 1;
        $news->sortOrder = 1;
        $news->increment('sortOrder');
        $news->save();
    
        return redirect()->route('news.index')->with('message', 'news Added/Updated Successfully');

        }else{
            return view('admin.permissionDenied');
        }

      
    }
    
    
    public function edit($id)
    { 
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {
        $data = array();
       
        $data["news"] = News::find($id);
        $data["newsCategory"] = NewsCategory::orderBy('sortOrder')->get();
        $data["editStatus"] = 1;
        $data["pageTitle"] = 'Update News';
        $data["activeMenu"] = 'News';
        
        return view('admin.news.create')->with($data);
        }
        else{

            return view('admin.permissionDenied');
        }

    }
    

    public function update(Request $request, $id)
    {
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {

        $news = News::find($id);
    
        if ($request->hasFile('image')) {
            $mediaId = imageUpload($request->image, $news->imageId, $this->userId, "uploads/newsImage/");
            $news->imageId = $mediaId;
        }
    
        $news->category_id = $request->input('categoryId');
        $news->parent_id = $request->input('parentId');
        $news->title = $request->input('title');
        $news->meta_description = $request->input('metaDescription');
        $news->slug = Str::slug($request->input('title'));
        $news->description = $request->input('description');
        $news->status = 1;
        $news->save();
    
        return redirect()->route('news.index')->with('message', 'news Updated Successfully');
    }
    else{
        return view('admin.permissionDenied');
    }
    }


    public function destroy(Request $request)
    {
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {

            $id = $request->id;
        $news = News::find($id);
        $news->delete($id);

        return response()->json([
            'status' => 1,
            'message' => 'Delete Successfull',
            'response' => $request->id
        ]);
            
        }
        else{
            return response()->json([
                'status' => 0,
                'message' => 'You are not allowed to delete news.'
            ], 403);
        }

        
    }


    public function destroyAll(Request $request)
    {
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {
           

        $record = $request->input('deleterecords');

        if (isset($record) && !empty($record)) {

            foreach ($record as $id) {
                $news = News::find($id);
               // $news->delete();
               if ($news) {
                $news->delete();

                // Log activity for each deletion
                // activity('news')
                //     ->causedBy(auth()->user())
                //     ->log("News with ID: {$id} has been deleted.");
            }

            }
        }


        return response()->json([
            'status' => 1,
            'message' => 'Delete Successfull',
            'response' => ''
        ]);
    }
    else{
        return response()->json([
            'status' => 0,
            'message' => 'You are not allowed to delete news.'
        ], 403);
    }
    }

    
    public function updateSortorder(Request $request)
    {
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || auth()->user()->hasPermission(config('constants.EDIT_NEWS') || auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {
           
        $data = $request->records;
       
        $decoded_data = json_decode($data);
        $result = 0;

        if (is_array($decoded_data)) {
            foreach ($decoded_data as $values) {

                $id = $values->id;
                $news = News::find($id);
                $news->sortOrder = $values->position;
                $result = $news->save();
            }
        }

       

        if ($result) {
            $response = array('status' => 1, 'message' => 'Sort order updated', 'response' => $data);
        } else {
            $response = array('status' => 0, 'message' => 'Something went wrong', 'response' => $data);
        }

        return response()->json($response);
    }
    else{
        return response()->json([
            'status' => 0,
            'message' => 'You are not allowed to delete news.'
        ], 403);
    }
    }

    public function updateStatus(Request $request)
    {
        if ((isset(Auth::user()->roleId) && Auth::user()->roleId == 1) || !auth()->user()->hasPermission(config('constants.CREATE_NEWS')) || !auth()->user()->hasPermission(config('constants.EDIT_NEWS') || !auth()->user()->hasPermission(config('constants.DELETE_NEWS'))) || !auth()->user()->hasPermission(config('constants.DELETE_ALL_NEWS')) || !auth()->user()->hasPermission(config('constants.UPDATE_NEWS_STATUS')) || !auth()->user()->hasPermission(config('constants.UPDATE_NEWS_SORTORDER'))) {
           
        
        
        $status = $request->status;
        $id = $request->id;

        $news = News::find($id);
        $news->status = $status;
        $result = $news->save();
      

        if ($result) {
            $response = array('status' => 1, 'message' => 'Status updated', 'response' => '');
        } else {
            $response = array('status' => 0, 'message' => 'Something went wrong', 'response' => '');
        }

        return response()->json($response);
    }
    else{
        return response()->json([
            'status' => 0,
            'message' => 'You are not allowed to delete news.'
        ], 403);
    }
    }

}
