<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponder
{
  public function successResponse($data, int $code = Response::HTTP_OK): JsonResponse {
    return response()->json([
      'status' => true,
      'code' => $code,
      'data' => $data,
    ], $code)->header('Content-Type', 'application/json');
  }

  public function errorResponse($error, int $code): JsonResponse
  {
    return response()->json([
      'status' => false,
      'code'  => $code,
      'error' => $error,
    ], $code)->header('Content-Type', 'application/json');
  }
}
