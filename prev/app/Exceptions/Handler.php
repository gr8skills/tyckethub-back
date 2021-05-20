<?php

namespace App\Exceptions;

use App\Traits\ApiResponser;
use Asm89\Stack\CorsService;
use Fruitcake\Cors\CorsServiceProvider;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $response = $this->handleException($request, $e);
        app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;
    }

    public function handleException($request, Throwable $e) {
        if ($e instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($e, $request);
        }
        if ($e instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($e->getModel()));
            return $this->errorResponse('Does not exist any ' . $modelName .' with that identification.', 404);
        }
        if ($e instanceof AuthenticationException) {
            return $this->errorResponse($e->getMessage(), 401);
        }
        if ($e instanceof AuthorizationException) {
            return $this->errorResponse($e->getMessage(), 403);
        }
        if ($e instanceof NotFoundHttpException) {
            return $this->errorResponse('The specified URL cannot be found', 404);
        }
        if ($e instanceof MethodNotAllowedException) {
            return $this->errorResponse('The specified method for the request is not allowed.', 405);
        }
        if ($e instanceof HttpException) {
            return $this->errorResponse($e->getMessage(), $e->getStatusCode());
        }
        if ($e instanceof QueryException) {
            $errorCode = $e->errorInfo[1];

            if ($errorCode === 1451) {
                return $this->errorResponse('Cannot remove this resource. It\'s related with some other
                    resource', 409);
            }
        }
        if (config('app.debug')) {
            return parent::render($request, $e);
        }

        return $this->errorResponse('An unexpected exception occurred. Please try again later.', 500);
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param ValidationException $e
     * @param  Request  $request
     * @return Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return \response()->json($errors, 422);
    }
}
