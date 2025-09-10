<?php 

namespace Modules\DesaModuleTemplate\Services\Shared;

class ApiResponseService
{
    /**
     * Return a success JSON response with consistent structure.
     *
     * @param  mixed|null  $data
     * @param  string      $message
     * @param  int         $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data,
            'errors' => null,
        ], $code);
    }

    /**
     * Return a generic error JSON response with consistent structure.
     *
     * @param  string      $message
     * @param  int         $code
     * @param  mixed|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function error(string $message = 'Error', int $code = 400, $errors = null)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Return a 500 Internal Server Error response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function internalServerError(string $message = 'Internal Server Error')
    {
        return $this->error($message, 500);
    }

    /**
     * Return a 422 Validation Error response.
     *
     * @param  mixed   $errors
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function validationError($errors, string $message = 'Validation Error')
    {
        return $this->error($message, 422, $errors);
    }

    /**
     * Return a 404 Not Found response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function notFound(string $message = 'Not Found')
    {
        return $this->error($message, 404);
    }

    /**
     * Return a 401 Unauthorized response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorized(string $message = 'Unauthorized')
    {
        return $this->error($message, 401);
    }

    /**
     * Return a 403 Forbidden response.
     *
     * @param  string      $message
     * @param  mixed|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbidden(string $message = 'Forbidden', $errors = null)
    {
        return $this->error($message, 403, $errors);
    }

    /**
     * Return a 204 No Content response with optional message.
     *
     * @param  string  $message
     * @param  int     $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContent(string $message = 'No Content', int $statusCode = 204)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => null,
            'errors' => null,
        ], $statusCode);
    }
    /**
     * Return a 201 Created response.
     *
     * @param  mixed|null  $data
     * @param  string      $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function created($data = null, string $message = 'Resource Created')
    {
        return $this->success($data, $message, 201);
    }

    /**
     * Return a 409 Conflict response.
     *
     * @param  string      $message
     * @param  mixed|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function conflict(string $message = 'Conflict', $errors = null)
    {
        return $this->error($message, 409, $errors);
    }

    /**
     * Return a 429 Too Many Requests response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function toManyRequest()
    {
        return response()->json([
            'status' => 'error',
            'message' => 'Too many attempts. Please try again in 1 minute.',
            'data' => null,
            'errors' => null,
        ], 429);
    }
}