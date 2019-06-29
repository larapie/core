<?php

namespace Larapie\Core\Kernels;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

class ExceptionKernel extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return $request->wantsJson() ? $this->jsonErrorResponse($exception) : parent::render($request, $exception);
    }

    protected function jsonErrorResponse(Exception $exception)
    {
        return response()->json([
            'error' => [
                'message' => $exception->getMessage(),
                'status_code' => $exception->getStatusCode()
            ]
        ])->setStatusCode($exception->getStatusCode());
    }

    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        return response()->json([
            'error' => [
                'message'     => $exception->validator->errors()->getMessages(),
                'status_code' => 422,
            ],
        ])->setStatusCode(422);
    }
}
