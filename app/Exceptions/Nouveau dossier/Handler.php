<?php



use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use NunoMaduro\Collision\Adapters\Laravel\ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Enregistrement des gestionnaires d'exceptions pour l'application.
     */
                    public function register(): void
                    {
                        $this->reportable(function (Throwable $e) {
                            //
                        });
                    }

    /**
     * Personnalisation du rendu des exceptions HTTP.
     */
            // public function render($request, Throwable $exception)
            // {
            //     // Gestion spécifique pour les erreurs 404
            //     if ($this->isHttpException($exception) && $exception->getStatusCode() == 404) {
            //         if (!Auth::check()) {
            //             return redirect('/')->with('status', 'Vous devez être connecté pour accéder à cette page.');
            //         }
            //         return response()->view('errors.404', [], 404);
            //     }

            //     return parent::render($request, $exception);
            // }


            public function render($request, Throwable $exception)
                        {
                            // Vérifie si l'exception est bien une erreur HTTP
                            if ($exception instanceof HttpException) {
                                if ($exception->getStatusCode() == 404) {
                                    if (!auth()->check()) {
                                        return redirect('/login')->with('status', 'Vous devez être connecté pour accéder à cette page.');
                                    }
                                    return response()->view('errors.404', [], 404);
                                }
                            }

                            return parent::render($request, $exception);
                        }


}



