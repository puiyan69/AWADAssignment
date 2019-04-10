<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;
use App\Http\Resources\PatientCollection;
use App\Http\Resources\PatientResource;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $patient = Patient::orderBy('lastName')->get();
        return new PatientCollection($patient);
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


            $patient = new Patient;
            $patient->fill($request->all());

            $patient->saveOrFail();

            return response()->json([
                'id' => $patient->id,
                'created_at' => $patient->created_at,
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
            $patient = Patient::with('appointments')->find($id);
            if(!$patient) throw new ModelNotFoundException;

            return new PatientResource($patient);
        }
        catch(ModelNotFoundException $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ], 404);
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
        try {
            $patient = Patient::find($id);
            if(!$patient) throw new ModelNotFoundException;

            $patient->update($request->json()->all());

            //$patient->saveOrFail();

            return response()->json(null, 204);
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
        $patient = Patient::find($id);

        if(!$patient) {
            return response()->json([
                'error' => 404,
                'message' => 'Not found'
            ], 404);
        }

        $patient->delete();

        return response()->json(null, 204);
    }
}
