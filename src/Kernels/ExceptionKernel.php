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
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        if (!$request->wantsJson())
            parent::convertValidationExceptionToResponse($exception, $request);

        return response()->json([
            'message' => $exception->validator->errors()->getMessages(),
        ])->setStatusCode(422);
    }
}
