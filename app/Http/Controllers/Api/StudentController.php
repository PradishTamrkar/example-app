<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Controllers\Controller;
// use Illuminate\Validation\ValidationException; Valdiation Exception is not needed laravel automatically detects validation error and provides error log
use Exception;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Student::when($request->student_name, function($q) use($request) {
            $q->where('student_name','like','%'.$request->student_name.'%');
        })->simplePaginate(10);

        // $query = Student::query();

        // if( $request -> has('student_name')){
        //     $query->where('student_name','like','%'.$request->student_name.'%');
        // }

        // $students = $query -> simplePaginate(4);

        return response()->json([
            'success'=>true,
            'message'=>'Student info found',
            'data'=>$students
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validated = $request->validate(
            [
                'student_name'=>'required|string|max:255',
                'student_email'=>'required|string|max:255',
                'phone_no'=>'required|string|max:255'
            ]
        );
        try{
            // if validation fail

            // $student = Student::create($validated);

            $student = new Student();
            $student -> student_name = $validated['student_name'];
            $student -> student_email = $validated['student_email'];
            $student -> phone_no = $validated['phone_no'];
            $student->save();
            // $student = new Student();
            // $student->name = '';
            // $student->save();

            return response()->json([
                'success'=>true,
                'message'=>'Student Created Successfully',
                'data'=>$student
            ],200);
        }
        // catch(ValidationException $e){
        //     return response()->json([
        //         'success'=>false,
        //         'message'=>'Validation Failed',
        //         'data'=>$e->errors()
        //     ],422);
        // }
        catch(Exception $e){
            // info($e->messages());
            return response()->json([
                'success'=>false,
                'message'=>'Failed to create student data',
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        // findandfail & find
        //find- throws null if not found and and doesnot throw exception, but find use garda kheri chei afnei error message throw gerna payo
        //findAndFail-throws model not found exception and automatically converts that exception into 404 not found doesnot thorw null value

        $student = Student::find($id);

        if(!$student){
             return response()->json([
                'success'=>false,
                'message'=>'Student not found.',
            ],400);
        }

         return response()->json([
                'success'=>true,
                'message'=>'Student show Successfully',
                'data'=>$student
            ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {

        $validated = $request->validate([
                'student_name'=>'required|string|max:255',
                'student_email'=>'required|string|max:255',
                'phone_no'=>'required|string|max:255'
            ]);

        try{
           $student = Student::find($id);

            if(!$student){
                return response()->json([
                    'success'=>false,
                    'message'=>'Student not found'
                ],404);
            }

            // ->update() ->save()
            // update() a convienent way of updating data & save() is used for both INSERT and UPDATE, it updated based on the id and if there is no id then it created a new data
            // $student->update($validated);
            $student -> student_name = $validated['student_name'];
            $student -> student_email = $validated['student_email'];
            $student -> phone_no = $validated['phone_no'];
            $student->save();

            return response()->json([
                'success'=>true,
                'message'=>'Student Info Updated Successfully',
                'data'=>$student
            ],200);
        }
        // catch(ValidationException $e){
        //     return response()->json([
        //         'success'=>false,
        //         'message'=>'Validation Failed',
        //         'data'=>$e->errors()
        //     ],422);
        catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Failed to update student info',
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        // $student = Student::find($id);

        // if(!$student){
        //     return response()->json([
        //         'success'=>false,
        //         'message'=>'Student not found'
        //     ],404);
        // }

        try{
            $student = Student::find($id);

            $student->delete();

            return response()->json([
                'success'=>true,
                'message'=>'Student Info deleted successfully'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'success'=>false,
                'message'=>'Failed to delete student info'
            ]);
        }
    }
}
