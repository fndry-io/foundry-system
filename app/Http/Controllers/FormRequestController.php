<?php
namespace Foundry\System\Http\Controllers;

use Foundry\Core\Facades\FormRequestHandler;
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
		$id = $request->input('_id', null);
		return $handler->handle($name, $request, $id)->toJsonResponse();
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
		$id = $request->input('_id', null);
		return $handler::view($name, $request, $id)->toJsonResponse();
	}

}