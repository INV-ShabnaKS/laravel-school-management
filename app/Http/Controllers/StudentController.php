<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Gate;



class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth('api')->user();
        if ($user->role === 'admin') {
            return Student::paginate(10);
        }
        if ($user->role === 'teacher') {
            $teacher = Teacher::where('user_id', $user->id)->first();
            if (!$teacher) {
                return response()->json(['message' => 'No teacher record found'], 404);
            }
            return Student::where('teacher_id', $teacher->id)->paginate(10);
        }
        if ($user->role === 'student') {
            $student = Student::where('user_id', $user->id)->first();
            if (!$student) {
                return response()->json(['message' => 'Student record not found'], 404);
            }
            return response()->json($student);
        }
        return response()->json(['message' => 'Unauthorized'], 403);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData=$request->validate([
            'username'=>'string|required|unique:users,username',
            'password'=>'required|string|min:6',
            'first_name'=>'required|string|min:2',
            'last_name'=>'string|nullable',
            'email'=> 'email|required|unique:users,email|unique:teachers,email',
            'phoneno'=>'string|required|digits:10',
            'rollno' => 'required|string|unique:students,rollno',
            'class'=>'string|required',   
            'date_of_birth' => 'required|date|before_or_equal:today',
            'admission_date' => 'required|date|before_or_equal:today',
            'status' => 'required|in:active,inactive',
            'teacher_id' => 'nullable|exists:teachers,id',
          
        ]);
        $user=User::create([
            'username'=>$validatedData['username'],
            'email'=>$validatedData['email'],
            'password'=>bcrypt($validatedData['password']),
            'role'=>'student'

        ]);
        $student = Student::create([
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'phoneno' => $validatedData['phoneno'],
            'rollno' => $validatedData['rollno'],
            'class' => $validatedData['class'],
            'date_of_birth' => $validatedData['date_of_birth'],
            'admission_date' => $validatedData['admission_date'],
            'status' => $validatedData['status'],
            'teacher_id' => $validatedData['teacher_id'] ?? null, 
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'Student created successfully',
            'student' => $student
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student=Student::find($id);
        if(!$student){
            return response()->json(['message'=>'No student record found'],404);
        }
        return response()->json($student);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $student=Student::find($id);
        if(!$student){
            return response()->json(['message'=>'No matching student found'],404);
        }
        $request->validate([
            'first_name'=>'sometimes|string|min:2',
            'last_name'=>'string|nullable',
            'email' => ['sometimes','email','unique:students,email,' . $id,'unique:users,email,' . $student->user_id],
            'phoneno'=>'sometimes|string|digits:10',
            'rollno' => 'sometimes|string|unique:students,rollno,'.$id,
            'class'=>'sometimes|string',
            'date_of_birth' => 'sometimes|date|before_or_equal:today',
            'admission_date' => 'sometimes|date|before_or_equal:today',
            'status' => 'sometimes|in:active,inactive',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);
        if ($request->filled('email') && $request->email !== $student->email) {
            $student->email = $request->email;
            if ($student->user_id) {
                $user = User::find($student->user_id);
                if ($user) {
                    $user->email = $request->email;
                    $user->save();
                }
            }
        }
        $student->update($request->except('email'));
        return response()->json([
            'message' => 'Student updated successfully',
            'student' => $student
        ]);


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student=Student::find($id);
        if(!$student){
            return response()->json(['message'=>'No such student'],404);
        }
        if($student->user_id){
            $user=User::find($student->user_id);
            if($user){
                $user->delete();
            }
        }
        $student->delete();
        return response()->json(['message' => 'Student deleted successfully']);


    
    }
}
