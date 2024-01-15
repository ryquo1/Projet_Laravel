<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PeriodRequest;
use App\Http\Resources\Api\PeriodResource;
use App\Models\web\AcnPeriod;
use Exception;

class AcnPeriodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PeriodResource::collection(AcnPeriod::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\PeriodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PeriodRequest $request)
    {
        //
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
            $period = AcnPeriod::findOrFail($id);
            return new PeriodResource($period);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\PeriodRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PeriodRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(["message" => "This action is unauthorized."], 403);
    }
}
