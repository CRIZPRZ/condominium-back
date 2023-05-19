<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait apiResponser
{
    /**
     * successResponse
     *
     * @param  mixed $data
     * @param  mixed $code
     * @return void
     */
   /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @return \Illuminate\Http\Response
     */
    protected function successResponse($data, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $data,
            'message' => $message,
            'code'    => $code
        ];


        return response()->json($response, 200);
    }
    protected function successResponseJson($data, $message, $code = 200)
    {



        return response()->json($data, 200);
    }


    /**
     * return error response.
     *
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    protected function errorResponse($error, $errorMessages = [], $code = 422)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];


        if($errorMessages){
            $response['data'] = $errorMessages;
        }


        return response()->json($response, $code);
    }

    /**
     * showAll
     *
     * @param  mixed $collection
     * @param  mixed $code
     * @return void
     */
    protected function showAll(Collection $collection, $code = 200){
        return $this->successResponse(['data'=> $collection], $code);
    }

    /**
     * showOne
     *
     * @param  mixed $instance
     * @param  mixed $code
     * @return void
     */
    protected function showOne(Model $instance, $code = 200){
        return $this->successResponse(['data'=> $instance], $code);
    }


    protected function responseErrorValidate($errors) {
        return response()->json([
            'status' => 422,
            'msg'    => 'Error',
            'errors' => $errors->errors(),
        ], 422);
    }
}
