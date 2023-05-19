<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\property\StoreRequest;
use App\Http\Requests\property\UpdateRequest;
use App\Models\Properties;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class propertyController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $properties = Property::all();

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
    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();

            $property = Property::create($request->all());
            DB::commit();
            return $this->successResponse($property, 'Propiedad creada');

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
        try {
            $property = Property::findOrfail($id);

            return $this->successResponse($property, 'Propiedad');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            $property = Property::findOrFail($id);


            $property->update($request->all());

            DB::commit();

            return $this->successResponse($property, 'Propiedad Actualizada');

        } catch (ValidationException $exception) {

            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'msg'    => 'Error',
                'errors' => $exception->errors(),
            ], 422);

        }  catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property)
    {
        try {

            $property->delete();

            return $this->successResponse($property, 'Propiedad eliminada');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    public function getForUser($id)
    {
        try {

            $properties = Property::select('properties.*')
            ->whereNotIn('id', function($query) use ($id) {
                $query->select('property_id')
                      ->from('property_users')
                      ->where('user_id', $id);
            })
            ->get();

            return $this->successResponse($properties, 'Propiedades');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

}
