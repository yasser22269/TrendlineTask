<?php

namespace App\Exceptions;

use App\Consts\ResponseMessageConsts;
use App\Consts\ResponseStatusConsts;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {

    }

    public function render($request, Throwable $exception)
    {
        if ($request->is('api/*') ||
            $request->is('admin/*') ||
            $request->expectsJson()) {
            return $this->handleApiException($request, $exception);
        }
        return parent::render($request, $exception);
    }

    private function handleApiException($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);


        if ($exception instanceof HttpResponseException) {
            return $exception->getResponse();
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'success' => false,
                'message' => null,
                'errors' => ['Not Found'],
                'data' => null,
            ], 404);
        }

//        if ($exception instanceof UnauthorizedException) {
//            return response()->json([
//                'success' => false,
//                'message' => null,
//                'errors' => ['You do not have the required permissions.'],
//                'data' => null,
//            ], 403);
//        }

        if ($exception instanceof RouteNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'The requested route does not exist. Or you are not authenticated.',
                'errors' => ['The specified route is not defined. Or you are not authenticated.'],
                'data' => null,
            ], 404);
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => "Not authenticated",
                'errors' =>  ['Not authenticated'],
                'data' => null,
            ], 401);
        }

        if ($exception instanceof ValidationException) {
            return response()->json([
                'success' => false,
                'message' => null,
                'errors' => $exception->validator->errors()->all(),
                'data' => null,
            ], 422);
        }

        if ($exception instanceof \Illuminate\Database\QueryException) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred.',
                'errors' => [$exception->getMessage()],
                'data' => null,
            ], 500);
        }

        return $this->customApiResponse($exception);
    }

    private function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }
        $response = [
            'success' => false,
            'message' => null,
            'data' => null
        ];

        switch ($statusCode) {
            case ResponseStatusConsts::UNAUTHORIZED_RESPONSE:
                $response['errors'] = ['برجاء تسجيل الدخول اولا حتي يمكنك استكمال طلبك'];
                break;

            case ResponseStatusConsts::FORBIDDEN_RESPONSE:
            case ResponseStatusConsts::FAILURE_RESPONSE:
                $response['errors'] = collect($exception->errors())
                    ->flatten()
                    ->toArray();
                break;
            case ResponseStatusConsts::NOT_FOUND_RESPONSE:
                $response['errors'] = [ResponseMessageConsts::NOT_FOUND_MESSAGE()];
                break;
            default:
                $response['errors'] = [$exception->getMessage()];
                $response['message'] = $statusCode == 500
                    ? 'Whoops, looks like something went wrong'
                    : $exception->getMessage();
                break;
        }

        return response()->json($response, $statusCode);
    }


}
