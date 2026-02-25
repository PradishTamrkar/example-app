<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Models\Student;
use Inertia\Inertia;
use Exception;
use App\Http\Controllers\Controller;

class StudentControllerWeb extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $students = Student::when($request->student_name, function($q) use($request) {
            $q->where('student_name','like','%'.$request->student_name.'%');
        })->simplePaginate(10);

        return Inertia::render('Students/Index', [
            'students'=>$students
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

            $student = new Student();
            $student -> student_name = $validated['student_name'];
            $student -> student_email = $validated['student_email'];
            $student -> phone_no = $validated['phone_no'];
            $student->save();

            return redirect()->route('students.index')
                -> with('success','Student Created Successfully');
        }

        catch(Exception $e){
            return redirect()->back()
                ->with('failed','Failed to create Student info');
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(int $id)
    // {
    //     $student = Student::find($id);

    //     if(!$student){
    //          return redirect()->route('students.index')
    //             ->with('failed','failed to fetch the student details');
    //     }

    //      return Inertia::render('Students/Show',[
    //         'student'=>$student,
    //      ]);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $validated = $request->validate([
                'student_name'=>'required|string|max:255',
                'student_email'=>'required|string|max:255',
                'phone_no'=>'required|string|max:255'
            ]);

        try{
           $student = Student::find($id);

            if(!$student){
             return redirect()->route('students.index')
                ->with('failed','failed to fetch the student details');
            }

            $student -> student_name = $validated['student_name'];
            $student -> student_email = $validated['student_email'];
            $student -> phone_no = $validated['phone_no'];
            $student->save();

            return redirect()->route('students.index')
                ->with('success','Student details updated successfully');
        }
        catch(\Exception $e){
            return redirect()->back()
                -> with('failed','Failed to update Student info');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        try{
            $student = Student::find($id);

            $student->delete();

            return redirect()->route('students.index')
                ->with('success','Student Info Deleted Successfully');
        }catch(\Exception $e){
            return redirect()->back()
                ->with('failed','Failed to delete student info');
        }
    }
}
