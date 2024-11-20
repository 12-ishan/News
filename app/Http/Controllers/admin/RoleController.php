<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\Role;
use App\Models\Admin\PermissionHead;
use App\Models\Admin\PermissionGroup;
use App\Models\Admin\RolePermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
    
            if ($user->roleId == 1) {
                $this->userId = $user->id;
                // $this->accountId = $user->accountId;
    
                return $next($request);
            }
    
            return response()->view('admin.permissionDenied', [], 403);
        });
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = array();
        $data["role"] = Role::orderBy('sortOrder')->get();
        $data["pageTitle"] = 'Manage Role';
        $data["activeMenu"] = 'role';
        return view('admin.role.manage')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $data = array();
      //  $data["permissionHead"] = PermissionHead::orderBy('sortOrder')->get();
       //$data["permissionHead"] = PermissionHead::with('permissionGroup')->get();
    //    echo '<pre>';
    //    print_r($data["permissionHead"]);
    //    die();
       $data["permissionHead"]= PermissionGroup::with('permissionByGroup')->get();
        $data["rolePermissions"] = []; 
        $data["pageTitle"] = 'Add Role';
        $data["activeMenu"] = 'role';
        return view('admin.role.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->validate(request(), [
            'roleName' => 'required',
        ]);

        $permission = $request->input('permissions');
        $role = new Role();
        $role->name = $request->input('roleName');
        $role->slug = str::slug($request->input('roleName'));
        $role->status = 1;
        $role->sortOrder = 1;
        $role->increment('sortOrder');
        $role->save();

        $roleId = $role->id;
        $role->permissions()->sync($request->permissions);
    //     if (is_array($permission) || is_object($permission)){
    //     foreach($permission as $value){

    //         $rolePermission = new RolePermission();
    //         $rolePermission->roleId = $roleId;
    //         $rolePermission->permissionId = $value;
    //         $rolePermission->save(); 
    //     }
    // }

    if ($request->has('permissions')) {
        $role->permissions()->sync($request->input('permissions'));
    }
    
        return redirect()->route('role.index')->with('message', 'Role Added Successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
        $data = array(); 
        $data["permissionHead"]= PermissionGroup::with('permissionByGroup')->get();
        $data['role'] = Role::find($id);
        $data['rolePermissions'] = $data['role']->permissions->pluck('id')->toArray();
        $data["editStatus"] = 1;
        $data["pageTitle"] = 'Update Role';
        $data["activeMenu"] = 'role';
        return view('admin.role.create')->with($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        //
        
        $this->validate(request(), [
            'roleName' => 'required',
        ]);
        
        $id = $request->input('id');

        $role = Role::find($id);

        $role->name = $request->input('roleName');
        $role->slug = str::slug($request->input('roleName'));
        $role->save();

        $role->permissions()->sync($request->permissions);
        return redirect()->route('role.index')->with('message', 'Role Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
        $id = $request->id;
        $role = Role::find($id);
        $role->delete($id);

        return response()->json([
            'status' => 1,
            'message' => 'Delete Successfull',
            'response' => $request->id
        ]);
    }

    public function destroyAll(Request $request)
    {

        $record = $request->input('deleterecords');

        if (isset($record) && !empty($record)) {

            foreach ($record as $id) {
                $role = Role::find($id);
                $role->delete();
            }
        }

        return response()->json([
            'status' => 1,
            'message' => 'Delete Successfull',
            'response' => ''
        ]);
    }

    public function updateSortorder(Request $request)
    {
        $data = $request->records;
        $decoded_data = json_decode($data);
        $result = 0;

        if (is_array($decoded_data)) {
            foreach ($decoded_data as $values) {

                $id = $values->id;
                $role = Role::find($id);
                $role->sortOrder = $values->position;
                $result = $role->save();
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
     */
    public function updateStatus(Request $request)
    {
        $status = $request->status;
        $id = $request->id;

        $role = Role::find($id);
        $role->status = $status;
        $result = $role->save();

        if ($result) {
            $response = array('status' => 1, 'message' => 'Status updated', 'response' => '');
        } else {
            $response = array('status' => 0, 'message' => 'Something went wrong', 'response' => '');
        }

        return response()->json($response);
    }

}


