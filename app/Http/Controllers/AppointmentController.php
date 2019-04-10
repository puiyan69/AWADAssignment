<?php

namespace App\Http\Controllers;

use DB;
use App\Appointment;
use App\Http\Resources\AppointmentCollection;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $reason = $request->input('appReason');

        $appointment =Appointment::with(['doctor', 'patient'])
            ->when($reason, function($query) use ($reason){
                return $query->where('appReason', 'like', "%$reason%");
            })->paginate(5);

        return new AppointmentCollection($appointment);
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
        try{        
            
            $request->validate([
                'appTime' => 'required',
                'appDate' => 'required',
                'appReason' => 'required|max: 200',
                'doctor_id' => 'required|integer',
                'patient_id' => 'required|integer',
            ]);

            $appointment = new Appointment;
            $appointment-> fill($request->all());
            $appointment->doctor_id = $request->doctor_id;
            $appointment->patient_id = $request->patient_id;
            $appointment->saveOrFail();

            return response()->json([
                'id' => $appointment->id,
                'created_at' => $appointment->created_at,
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
            $appointment = Appointment::with('doctor')->with('patient')->find($id);
            if(!$appointment) throw new ModelNotFoundException;

            //return response()->json($appointment);
            return new AppointmentResource($appointment);
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
            $appointment = Appointment::with('doctor')->with('patient')->find($id);
            if(!$appointment) throw new ModelNotFoundException;

            $appointment -> update($request->json()->all());

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
        $appointment =Appointment::find($id);

        if(!$appointment) {
            return response()->json([
                'error' => 404,
                'message' => 'Not found'
            ], 404);
        }

        $appointment->delete();

        return response()->json(null, 204);
    }
}
