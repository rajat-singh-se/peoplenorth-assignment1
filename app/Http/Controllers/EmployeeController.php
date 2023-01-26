<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    //

    public function employees(Request $request){
        $data=Employee::when(!empty($request->search_key),function($q)use($request){
            $q->where('name','like','%'.$request->search_key.'%')
            ->orWhere('email','like','%'.$request->search_key.'%')
            ->orWhere('phone','like','%'.$request->search_key.'%');
        })->get();
        return response()->json(['status'=>'success','data'=>$data]);
    }

    public function createEmployee(Request $request){

        $val=$request->validate(['name'=>'required','email'=>'required|email','phone'=>'required|numeric|digits:10']);

        $emp=new Employee();
        $emp->name=$request->name;
        $emp->email=$request->email;
        $emp->phone=$request->phone;
        $emp->gender=$request->gender;

        $emp->save();
        $file=$request->file('img');

        if($file){
            $filename='profileImg-'.$emp->id.'.'.$file->getClientOriginalExtension();
            $path='images/profile/';
            $file->move($path,$filename);
            $emp->img=$path.$filename;
            $emp->save();
        }

        return response()->json(['status'=>'success']);



    }

    public function updateEmployeeData($id,Request $request){
        $val=$request->validate(['name'=>'required','email'=>'required|email','phone'=>'required|numeric|digits:10']);

        $emp=Employee::find($id);
        $emp->name=$request->name;
        $emp->email=$request->email;
        $emp->phone=$request->phone;
        $emp->gender=$request->gender;


        $file=$request->file('img');

        if($file){
            if (File::exists($emp->img)) {
                File::delete($emp->img);
            }
            $filename='profileImg-'.$emp->id.'.'.$file->getClientOriginalExtension();
            $path='images/profile/';
            $file->move($path,$filename);
            $emp->img=$path.$filename;
        }
        $emp->save();
        return response()->json(['status'=>'success']);




    }

    public function deleteEmployee($id){

        $emp=Employee::find($id);
        if(empty($emp))
        return response()->json(['status'=>'error']);

        if (File::exists($emp->img)) {
            File::delete($emp->img);
        }
        if($emp->delete()){
            return response()->json(['status'=>'success']);
        }

        return response()->json(['status'=>'error']);


    }


}
