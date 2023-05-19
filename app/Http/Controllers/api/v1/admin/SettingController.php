<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\settings\StoreRequest;
use App\Http\Requests\settings\UpdateRequest;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SettingController extends BaseController
{
    public function get()
    {
        try {

            $settings = Setting::all();

            return $this->successResponse($settings, 'Settings');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    public function show($id)
    {
        try {
            $setting = Setting::findOrfail($id);

            return $this->successResponse($setting, 'Setting');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    public function store(StoreRequest $request)
    {
        try {

            DB::beginTransaction();

            $fileName = null;

            if ( $request->file('logo')) {

                $fileName = saveImgages($request->file('logo'), '/assets/configs');

            }



            $config = Setting::create([
                'name'      => $request->name,
                'street'    => $request->street,
                'number'    => $request->number,
                'colony'    => $request->colony,
                'city'      => $request->city,
                'state'     => $request->state,
                'country'   => $request->country,
                'zip'       => $request->zip,
                'logo'      => $fileName,

            ]);
            DB::commit();
            return $this->successResponse($config, 'Configuracion');

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

    public function update(UpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            $setting = Setting::findOrFail($request->id);

            if ( $request->file('logo') ) {
                $fileName = saveImgages($request->file('logo'), '/assets/configs');
                $setting->update([
                    'logo' => $fileName,
                ]);
            }

            $setting->update([
                'name'      => $request->name,
                'street'    => $request->street,
                'number'    => $request->number,
                'colony'    => $request->colony,
                'city'      => $request->city,
                'state'     => $request->state,
                'country'   => $request->country,
                'zip'       => $request->zip,
            ]);

            DB::commit();

            return $this->successResponse($setting, 'Setting');

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

    public function delete()
    {

        try {

            $setting = Setting::findOrfail(request('id'));
            $setting->delete();

            return $this->successResponse($setting, 'Settings');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }
}
