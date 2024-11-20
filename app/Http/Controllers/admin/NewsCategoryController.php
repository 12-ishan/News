<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\NewsCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class NewsCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $this->userId = Auth::user()->id;
            $this->accountId = Auth::user()->accountId;
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();

        $data["newsCategory"] = NewsCategory::orderBy('sortOrder')->get();

        $data["pageTitle"] = 'Manage News Category';
        $data["activeMenu"] = 'News Category';
        return view('admin.newsCategory.manage')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array();

        $data["newsParentCategory"] = NewsCategory::where('status',1)->orderBy('sortOrder')->get();

        $data["pageTitle"] = 'Add News Category';
        $data["activeMenu"] = 'News Category';
        return view('admin.newsCategory.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
 
        ]);

        $newsCategory = new NewsCategory();

        $newsCategory->name = $request->input('name');
        $newsCategory->parent_id = $request->input('parentId');
        $newsCategory->slug = str::slug($request->input('name'));
        $newsCategory->description = $request->input('description');
       
        $newsCategory->status = 1;
        $newsCategory->sortOrder = 1;

        $newsCategory->increment('sortOrder');

        $newsCategory->save();

        return redirect()->route('news-category.index')->with('message', 'Category Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        $data = array();

        $data['newsCategory'] = NewsCategory::find($id);
        $data["newsParentCategory"] = NewsCategory::where('status',1)->orderBy('sortOrder')->get();
        $data["editStatus"] = 1;
        $data["pageTitle"] = 'Update News Category';
        $data["activeMenu"] = 'news Category';
        return view('admin.newsCategory.create')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        $this->validate(request(), [
            'name' => 'required',
           
        ]);
        
        $id = $request->input('id');

        $newsCategory = NewsCategory::find($id);

        $newsCategory->name = $request->input('name');
        $newsCategory->parent_id = $request->input('parentId');
        $newsCategory->slug = urlencode($request->input('name'));
        $newsCategory->description = $request->input('description');

        $newsCategory->save();

        return redirect()->route('news-category.index')->with('message', 'category Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $newsCategory = NewsCategory::find($id);
        $newsCategory->delete($id);

        return response()->json([
            'status' => 1,
            'message' => 'Delete Successfull',
            'response' => $request->id
        ]);
    }

    /**
     * Remove all selected resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroyAll(Request $request)
    {

        $record = $request->input('deleterecords');

        if (isset($record) && !empty($record)) {

            foreach ($record as $id) {
                $newsCategory = NewsCategory::find($id);
                $newsCategory->delete();
            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'Delete Successfull',
            'response' => ''
        ]);
    }

    /**
     * Update SortOrder.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSortorder(Request $request)
    {
        $data = $request->records;
        $decoded_data = json_decode($data);
        $result = 0;

        if (is_array($decoded_data)) {
            foreach ($decoded_data as $values) {

                $id = $values->id;
                $newsCategory = NewsCategory::find($id);
                $newsCategory->sortOrder = $values->position;
                $result = $newsCategory->save();
            }
        }

        if ($result) {
            $response = array('status' => 1, 'message' => 'Sort order updated', 'response' => $data);
        } else {
            $response = array('status' => 0, 'message' => 'Something went wrong', 'response' => $data);
        }

        return response()->json($response);
    }

    /**
     * Update Status resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        $status = $request->status;
        $id = $request->id;

        $newsCategory = NewsCategory::find($id);
        $newsCategory->status = $status;
        $result = $newsCategory->save();

        if ($result) {
            $response = array('status' => 1, 'message' => 'Status updated', 'response' => '');
        } else {
            $response = array('status' => 0, 'message' => 'Something went wrong', 'response' => '');
        }

        return response()->json($response);
    }

}
