<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Properties;
use App\Models\propertyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UserPropertyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

        try {
            $properties = propertyUser::with('user', 'property')->where('user_id', $id)->get();

            return $this->successResponse($properties, 'Propiedades');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
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

            DB::beginTransaction();

            $request->validate([
                'user_id'       => 'required',
                'property_id'   => 'required',
            ]);

            $propertyUser = propertyUser::create([
                'user_id' => $request->user_id,
                'property_id' => $request->property_id,
                'can_vote' => $request->can_vote,
            ]);
            DB::commit();
            return $this->successResponse($propertyUser, 'Propiedad usuario creada');

        } catch (ValidationException $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'msg'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);

        }  catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

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
        try {



            $propertyUser = propertyUser::findOrFail($id)->delete();
            return $this->successResponse($propertyUser, 'propiedad eliminada');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    public function checkCanVoteProperties($id)
    {

        try {

            $can_vote = propertyUser::where('property_id', $id)->where('can_vote', 1)->count();


            return $this->successResponse($can_vote > 0 ? true : false, 'propiedad cuenta con usuario que puede votar');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }

    }
}
