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

class AbonoController extends BaseController
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $abonos = Payment::with('charge')->ofProperty(request('property_id'))->orderBy('id', 'DESC')->get();

            return $this->successResponse($abonos, 'Abonos');

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

            $charge = Charge::findOrfail($request->charge_id);

            $paymentsTotal = Payment::where('charge_id', $charge->id)->sum('amount');

            $paymentPending = $charge->amount - $paymentsTotal;

            if ( (float) $request->amount > (float)$paymentPending ) {

                $charge->status_id = 2;
                $balance = (float) $request->amount - (float)$paymentPending;
                Payment::create([
                    'date' => $request->date,
                    'description' => $request->description,
                    'amount' => $balance,
                    'property_id' => $request->property_id,
                ]);

                $charge->save();
            }
            if ( (float)$charge->amount === (float) $request->amount ) {


                $charge->status_id = 2;
                $charge->save();
            }
            if ( (float) $request->amount < (float)$charge->amount ) {

                if ( (float) $request->amount === (float)$paymentPending ) {
                    $charge->status_id = 2;
                }else{

                    $charge->status_id = 3;
                }
                $charge->save();
            }

            $abono = Payment::create($request->all());
            DB::commit();
            return $this->successResponse($abono, 'Abono creado');

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
    public function update(Request $request, Movements $abono)
    {

        try {

            DB::beginTransaction();



            $abono->update($request->all());
            DB::commit();
            return $this->successResponse($abono, 'Abono Actualizado');

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

            $payment = Payment::findOrfail($id);


            $charge = Charge::where('id', $payment->charge_id)->first();

            $payment->delete();
            if ( !is_null($charge) ) {

                $paymentsTotal = Payment::where('charge_id', $charge->id)->sum('amount');

                if ( $paymentsTotal === 0) {
                    $charge->status_id = 1;
                }

                if ( $paymentsTotal > 0) {
                    $charge->status_id = 3;
                }

                $charge->save();
            }
            DB::commit();

            return $this->successResponse($payment, 'Pago eliminado');
        }  catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse($th, $th->getMessage());

        }
    }
}
