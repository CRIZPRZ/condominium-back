<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Charge;
use App\Models\Movements;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ChargeController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $charges = Charge::with('state')->orderBy('id', 'DESC')->get();

            foreach ($charges as $value) {
                $paymentsTotal = Payment::where('charge_id', $value->id)->sum('amount');
                $value->paymentsTotal = $paymentsTotal;
            }
            return $this->successResponse($charges, 'Cargos');

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



            $charge = Charge::create($request->all());
            DB::commit();
            return $this->successResponse($charge, 'Cargo creado');

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
    public function update(Request $request, Charge $charge)
    {

        try {

            DB::beginTransaction();



            $charge->update($request->all());
            DB::commit();
            return $this->successResponse($charge, 'Cargo Actualizado');

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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $charge = Charge::findOrfail($id);

            $charge->delete();

            DB::commit();

            return $this->successResponse($charge, 'Cargo eliminado');
        }  catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th, $th->getMessage());

        }
    }
}
