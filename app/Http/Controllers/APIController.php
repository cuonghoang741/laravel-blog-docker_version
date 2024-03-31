<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class APIController extends Controller
{
    protected $statusCode = 200;
    protected $user;
    protected $data = [];

    public function __construct()
    {
        // $this->middleware('cache.headers:max_age=3600;etag');
        $this->middleware(function ($request, $next) {
            $this->dataView['user'] = null;
            $this->user = Auth::user();
            if (!empty(Auth::user()) && !Auth::guest()) {
                $this->user = Auth::user();
                $this->dataView['user'] = $this->user;
            }

            return $next($request);
        });

    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondError($message, $data)
    {
        return $this->respond([
            'data' => $data,
            'message' => $message,
            'status' => false
        ]);
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondErrorWithMessage($message)
    {
        return $this->respond([
            'message' => $message,
            'status' => false
        ]);
    }

    /**
     * @param $nameObject
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($nameObject)
    {
        return $this->setStatusCode(Response::HTTP_NOT_FOUND)->respond([
            'message' => "Không tìm thấy " . $nameObject,
            "status" => false
        ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccessWithStatus($data)
    {
        return $this->respond([
            'data' => $data,
            'status' => true
        ]);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess($data)
    {
        if (empty($data["status"])) {
            $data["status"] = true;
        }
        return $this->respond($data);
    }

    public function responseWithError($message)
    {
        return response()->json([
            'error' => $message
        ], $this->getStatusCode());
    }

    /**
     * @param $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccessWithMessage($message)
    {
        return $this->respond([
            'message' => $message,
            "status" => true
        ]);
    }

    public function responseUnAuthorized($message = 'Unauthorized!')
    {
        return $this->setStatusCode(Response::HTTP_UNAUTHORIZED)->responseWithError($message);
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return $this
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $headers
     * @return \Illuminate\Http\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return response()->json($data, $this->getStatusCode(), $headers);
    }

    public function renderCard($card,$item,$responsiveClass = "",$variableName="item"){
        if (isset($responsiveClass)){
            $responsive='<div class="'.$responsiveClass.'">';
            $render = ($responsiveClass ? $responsive : '' ). view("components.colorme.".$card, [$variableName => $item])->render() . ($responsiveClass ? "</div>" : '');
        } else{
            switch ($card){
                default:
                    $render = view("components.colorme.".$card, ['item' => $item])->render();
                    break;
            }
        }
        return $render;
    }

    public function renderCards($card,$items,$responsiveClass = "",$variableName="item"){
        $render = "";
        foreach ($items as $item){
            $render = $render.$this->renderCard($card,$item,$responsiveClass,$variableName);
        }
        return $render;
    }
}
