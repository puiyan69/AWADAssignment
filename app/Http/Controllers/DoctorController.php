<?php

namespace App\Http\Controllers;

use App\Doctor;
use Illuminate\Http\Request;
use App\Http\Resources\DoctorCollection;
use App\Http\Resources\DoctorResource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $doctor = Doctor::orderBy('lastName')->get();
        return new DoctorCollection($doctor);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $request->validate([
                'firstName' => 'required|min:4|max:200',
                'lastName' => 'required|min:4|max:200',
                'specialization' => 'required|min:4|max:200'
            ]);

            $doctor = new Doctor;
            $doctor->fill($request->all());

            $doctor->saveOrFail();

            return response()->json([
                'id' => $doctor->id,
                'created_at' => $doctor->created_at,
            ], 201);
        }
        catch(ValidationException $ex) {
            return response()->json([
                'errors' => $ex->errors(),
            ], 422);
        }
        catch(QueryException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 500);
        }
        catch(\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $doctor = Doctor::with('appointments')->find($id);
            if(!$doctor) throw new ModelNotFoundException;

            return new DoctorResource($doctor);
        }
        catch(ModelNotFoundException $ex){
            return response()->json([
                'message' => $ex -> getMessage(),ss
            ],404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $doctor = Doctor::find($id);
            if(!$doctor) throw new ModelNotFoundException;

            $doctor->update($request->json()->all());
            //$doctor->saveOrFail();

            return response()->json("Successfully Updated", 204);
        }
        catch(ModelNotFoundException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 404);
        }
        catch(QueryException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 500);
        }
        catch(\Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doctor = Doctor::find($id);

        if(!$doctor) {
            return response()->json([
                'error' => 404,
                'message' => 'Not found'
            ], 404);
        }

        $doctor->delete();

        return response()->json(null, 204);
    }
}
