<?php namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler {

	/**
	 * A list of the exception types that should not be reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		'Symfony\Component\HttpKernel\Exception\HttpException',
		'Illuminate\Session\TokenMismatchException',
	];

	/**
	 * Report or log an exception.
	 *
	 * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
	 *
	 * @param  \Exception  $e
	 * @return void
	 */
	public function report(Exception $e)
	{
		return parent::report($e);
	}

	/**
	 * Render an exception into an HTTP response.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Exception  $e
	 * @return \Illuminate\Http\Response
	 */
	public function render($request, Exception $e)
	{
		// $baseClass = class_basename($e);
		// var_dump($baseClass);
		if ($this->isHttpException($e))
		{
			return $this->renderHttpException($e);
		}
		else
		{
			if (!env("APP_DEBUG",false) && view()->exists("errors.500"))
			{
				return response()->view("errors.500", [], 500);
			}
			return parent::render($request, $e);
		}
	}

}
