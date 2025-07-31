<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'subject'=>'string|required',
            'emp_id' => 'required|string|unique:teachers,emp_id',
            'date_of_join' => 'required|date|before_or_equal:today',
            'status' => 'required|in:active,inactive',
          
        ]);
        dd($validatedData);

        $user=User::create([
            'username'=>$validatedData['username'],
            'email'=>$validatedData['email'],
            'password'=>bcrypt($validatedData['password']),
            'role'=>'teacher'

        ]);
        $teacher=Teacher::create([
            'first_name'=>$validatedData['first_name'],
            'last_name'=>$validatedData['last_name'],
            'email'=>$validatedData['email'],
            'phoneno'=>$validatedData['phoneno'],
            'subject'=>$validatedData['subject'],
            'emp_id'=>$validatedData['emp_id'],
            'date_of_join'=>$validatedData['date_of_join'],
            'status'=>$validatedData['status'],
            'user_id'=>$user->id

        ]);
        return response()->json([
            'message' => 'Teacher created successfully',
            'teacher' => $teacher
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
