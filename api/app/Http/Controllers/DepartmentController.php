<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use App\Department;

class DepartmentController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        return $this->user
            ->departments()
            ->get(['name', 'dept_manager'])
            ->toArray();
    }
    public function show($id)
    {
        $department = $this->user->departments()->find($id);
    
        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, department with id ' . $id . ' cannot be found'
            ], 400);
        }
    
        return $department;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'dept_manager' => 'required',
            
        ]);
    
        $department = new Department();
        $department->name = $request->name;
        $department->dept_manager = $request->dept_manager;
      
    
        if ($this->user->departments()->save($department))
            return response()->json([
                'success' => true,
                'department' => $department
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, department could not be added'
            ], 500);
    }

    public function update(Request $request, $id)
    {
        $department = $this->user->departments()->find($id);
        // $department = Department::find($id);
        $department->name = request('name');
        $department->dept_manager = request('dept_manager');
        $department->save();
        $request->validate([
            'name' => 'required',
            'dept_manager' => 'required',
     ]);
        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, department with id ' . $id . ' cannot be found'
            ], 400);
        }
        // $updated = $department->update(['name' => $name, 'dept_manager' => $dept_manager]);
        $updated = $department->update($request->all());
    
        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, department could not be updated'
            ], 500);
        }
    }

    public function destroy($id)
    {
        $department = $this->user->departments()->find($id);
    
        if (!$department) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, department with id ' . $id . ' cannot be found'
            ], 400);
        }
    
        if ($department->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'department could not be deleted'
            ], 500);
        }
    }
}
