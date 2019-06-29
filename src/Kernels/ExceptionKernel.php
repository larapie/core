<?php

namespace Larapie\Core\Kernels;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Str;
use Larapie\Core\Internals\LarapieManager;

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
        return ($request->expectsJson() || $this->requestComesFromApiDomain($request)) ?
            $this->jsonErrorResponse($exception) :
            parent::render($request, $exception);
    }

    protected function requestComesFromApiDomain($request){
        return Str::contains($this->getFormattedRequestUrl($request),$this->getFormattedApiUrl());
    }

    protected function getFormattedApiUrl()
    {
        $larapie = new LarapieManager();
        return parse_url($larapie->getApiUrl())['host'] . (parse_url($larapie->getApiUrl())['path'] ?? '');
    }

    protected function getFormattedRequestUrl($request)
    {
        return parse_url($request->url())['host'] . parse_url($request->url())['path'];
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
}
