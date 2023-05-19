<?php

namespace App\Http\Controllers\api\v1\admin;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\user\StoreUserRequest;
use App\Http\Requests\user\UpdateUserRequest;
use App\Models\propertyUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    public function get()
    {
        try {

            $users = User::all();

            return $this->successResponse($users, 'Users');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    public function show($id)
    {
        try {
            $users = User::findOrfail($id);

            return $this->successResponse($users, 'User');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }

    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => Hash::make($request->password),
            ]);


            DB::commit();
            return $this->successResponse($user, 'Users');

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

    public function update(UpdateUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::findOrFail($request->id);

            $password = $request->updatePassword ? Hash::make($request->password) : $user->password;

            $user->update([
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => $password,
            ]);

            DB::commit();

            return $this->successResponse($user, 'Users');

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

            $users = User::findOrfail(request('id'));
            $users->delete();

            return $this->successResponse($users, 'Users');

        } catch (\Throwable $th) {
            return $this->errorResponse($th, $th->getMessage());

        }
    }
}
