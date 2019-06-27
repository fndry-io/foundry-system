<?php
namespace Foundry\System\Http\Controllers;

use Foundry\Core\Exceptions\FormRequestException;
use Foundry\Core\Facades\FormRequestHandler;
use Foundry\Core\Requests\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class FormRequestController extends Controller
{

	/**
	 * Handles a form request
	 *
	 * @param Request $request
	 * @param null $_entity
	 *
	 * @return JsonResponse
	 */
	public function handle(Request $request, $_entity = null)
	{
		$name = $request->route()->getName();
		if ($request->input('_form', false)) {
			return FormRequestHandler::view($name, $request, $_entity)->toJsonResponse();
		} else {
			return FormRequestHandler::handle($name, $request, $_entity)->toJsonResponse();
		}

//		try {
//
//		} catch (FormRequestException $e) {
//			return Response::error('Request not found', 404)->toJsonResponse();
//		}
	}

//	/**
//	 * Handles a form view request
//	 *
//	 * @param Request $request
//	 * @param FormRequestHandler $handler
//	 *
//	 * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
//	 */
//	public function display(Request $request, FormRequestHandler $handler)
//	{
//		$name = $request->input('_request');
//
//		$response = $handler->view($name, $request);
//
//		if (config('app.env') != 'local') abort(401);
//
//		$list = $handler->forms();
//		sort($list);
//
//		return view('foundry_system::pages.form', [
//			'name' => $name,
//			'list' => $list,
//			'form' => $response->getData()
//		]);
//	}

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
		return Response::success($uris);
	}

}