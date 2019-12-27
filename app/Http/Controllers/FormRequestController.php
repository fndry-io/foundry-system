<?php
namespace Foundry\System\Http\Controllers;

use Foundry\Core\Exceptions\FormRequestException;
use Foundry\Core\Facades\FormRequestHandler;
use Foundry\Core\Requests\Contracts\ViewableFormRequestInterface;
use Foundry\Core\Requests\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;

/**
 * Class FormRequestController
 *
 * This class is responsible for loading a form request based on its static:name method value and will call either the
 * handle method or return the view method with the forms schema
 *
 * @package Foundry\System\Http\Controllers
 */
class FormRequestController extends Controller
{

	/**
	 * Handles a form request
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 * @throws FormRequestException
	 * @throws \Illuminate\Auth\Access\AuthorizationException
	 * @throws \Illuminate\Contracts\Container\BindingResolutionException
	 * @throws \Illuminate\Validation\ValidationException
	 */
	public function resolve(Request $request)
	{
		$name = $request->route()->getName();

		$form = FormRequestHandler::form($name, $request);

		$form->setContainer(app())->setRedirector(app()->make(Redirector::class));

		$form->validateAuthorization();

		if ($request->input('_form', false)) {

			if ( $form instanceof ViewableFormRequestInterface ) {
				return Response::success( $form->view() )->toJsonResponse($request);
			} else {
				throw new FormRequestException( sprintf( 'Requested form %s must be an instance of ViewableFormRequestInterface to be viewable', get_class($form) ) );
			}

		} else {
			$form->validateInputs();

			return $form->handle( )->toJsonResponse($request);

		}
	}

	/**
	 * Return a list of the registered forms
	 *
	 * This is typically used for testing
	 *
	 * @return Response
	 */
	public function all()
	{
		$forms = FormRequestHandler::forms();
		$uris = [];
		/**
		 * @var \Illuminate\Support\Facades\Route $routes
		 */
		$routes = Route::getRoutes();
		foreach ($routes as $route) {
			/**
			 * @var \Illuminate\Routing\Route $route
			 */
			if (in_array($route->getName(), $forms)) {
				$uris[$route->uri()] = $route->getName();
			}
		}
		ksort($uris);
		return Response::success($uris);
	}

}