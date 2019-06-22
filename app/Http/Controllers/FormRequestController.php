<?php
namespace Foundry\System\Http\Controllers;

use Foundry\Core\Contracts\FormRequestHandler;
use Foundry\Core\Exceptions\FormRequestException;
use Foundry\Core\Requests\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormRequestController extends Controller
{

	/**
	 * Handles a form request
	 *
	 * @param Request $request
	 * @param FormRequestHandler $handler
	 *
	 * @return JsonResponse
	 */
	public function handle(Request $request, FormRequestHandler $handler)
	{
		$name = $request->input('_request');
		return $handler->handle($name, $request)->toJsonResponse();

		try {

		} catch (FormRequestException $e) {
			return Response::error('Request not found', 404)->toJsonResponse();
		}
	}

	/**
	 * Returns a Foundry Response containing a DocType instance of the requested form so it can be registered
	 *
	 * @param Request $request
	 * @param FormRequestHandler $handler
	 *
	 * @return JsonResponse
	 */
	public function view(Request $request, FormRequestHandler $handler)
	{
		$name = $request->input('_request');
		return $handler->view($name, $request)->toJsonResponse();

		try {

		} catch (FormRequestException $e) {
			return Response::error('Request not found', 404)->toJsonResponse();
		}
	}

	/**
	 * Handles a form view request
	 *
	 * @param Request $request
	 * @param FormRequestHandler $handler
	 *
	 * @return \Illuminate\Contracts\View\Factory|JsonResponse|\Illuminate\View\View
	 */
	public function display(Request $request, FormRequestHandler $handler)
	{
		$name = $request->input('_request');

		$response = $handler->view($name, $request);

		if (config('app.env') != 'local') abort(401);

		$list = $handler->forms();
		sort($list);

		return view('foundry_system::pages.form', [
			'name' => $name,
			'list' => $list,
			'form' => $response->getData()
		]);
	}

	public function all(FormRequestHandler $handler)
	{
		return Response::success($handler->forms());
	}

}