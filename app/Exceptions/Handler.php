<?php

namespace App\Exceptions;

use App\Utils\Code;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response
    {
        $code = str_pad($e->getCode(), 4, '0', STR_PAD_LEFT);
        return match (get_class($e)) {
            ValidationException::class, BadRequestHttpException::class => response()->json(
                ['result' => false, 'message' => $e->getMessage()],
                400
            ),
            AuthenticationException::class => response()->json(['result' => false, 'message' => $e->getMessage()], 401),
            NotFoundHttpException::class => response()->json(['result' => false, 'message' => 'Page Not Found'], 404),
            MethodNotAllowedHttpException::class => response()->json(
                ['result' => false, 'message' => $e->getMessage()],
                405
            ),
            MobilXException::class => response()->json(['result' => 'failure', 'interfaceCode' => $e->getMessage(), 'resultMessage' => $e->getContext() ?? Code::message("AP{$code}"), 'code' => $e->getCode(), ]),
            TMapException::class => response()->json(['result' => '0', 'errcode' => 'E0'.$e->getCode(), 'errcode_detail' => $e->getMessage(), 'ip' => !empty($request->server('SERVER_ADDR')) ? substr($request->server('SERVER_ADDR'), -3) : '']),
            default => response()->json(
                ['result' => false, 'message' => $e->getMessage()],
                500,
                [],
                JSON_UNESCAPED_UNICODE
            ),
        };
    }
}
