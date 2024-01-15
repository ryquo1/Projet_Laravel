<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PretogativeRequest;
use App\Http\Resources\Api\PrerogativeResource;
use App\Models\web\AcnPrerogative;
use Exception;

class AcnPrerogativeController extends Controller
{
    /**
     * Display a listing of prerogatives.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PrerogativeResource::collection(AcnPrerogative::all());
    }

    /**
     * Store a newly created prerogative in storage.
     *
     * @param  \App\Http\Requests\Api\PretogativeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PretogativeRequest $request)
    {
        //
    }

    /**
     * Display the specified prerogative.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $prerogative = AcnPrerogative::findOrFail($id);
            return new PrerogativeResource($prerogative);
        } catch (Exception $e) {
            return response(["message" => "Resource requested does not exist."], 404);
        }
    }

    /**
     * Update the specified prerogative in storage.
     *
     * @param  \App\Http\Requests\Api\PretogativeRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PretogativeRequest $request, $id)
    {
        //
    }

    /**
     * Remove the specified prerovative from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response(["message" => "This action is unauthorized."], 403);
    }
}
