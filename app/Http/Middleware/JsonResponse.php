<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\JsonResponse as HttpJsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class JsonResponse
{

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$response = $next($request);

		// 忽略对重定向的处理。
		if ($response instanceof RedirectResponse) {
			return $response;
		}

		// 忽略对二进制响应的处理。
		if ($response instanceof BinaryFileResponse) {
			return $response;
		}

		// 忽略纯文本响应。
		if ($response instanceof Response) {
			if (str_contains($response->headers->get('Content-Type'), 'text/plain')) {
				return $response;
			}
		}

		// 忽略对已经是JSON的200响应处理。
		if ($response instanceof HttpJsonResponse && $response->getStatusCode() === 200) {
			return $response;
		}

		// 对数NULL类型进行处理。
		$recstr = null;
		$recstr = function ($data) use (&$recstr) {
			if ($data instanceof Arrayable) {
				$data = $data->toArray();
			}
			if (is_array($data)) {
				return array_map($recstr, $data);
			} elseif (is_null($data)) {
				return '';
			}
			return $data;
		};

		// JSON封装。
		$data = [
			'code' 	=> 200,
			'msg' 	=> '',
			'data' 	=> '',
			'success' => true
		];
		if ($response instanceof Response || $response instanceof SymfonyResponse || $response instanceof HttpJsonResponse) {
			$data['code'] = $response->getStatusCode();
			if ($data['code'] === 200) {
				$data['data'] = $response->getContent();
				if ($response->headers->get('Content-Type') === 'application/json') {
					$data['data'] = json_decode($data['data']);
				}
			} else {
				if (! ($response instanceof Response) || ! $response->exception) {
					$data['msg'] = $response->getContent();
				} else {
					$data['msg'] = $response->exception->getMessage();
				}
				$data['data'] = '';

				$msg = @json_decode($data['msg']);
				if (json_last_error() === JSON_ERROR_NONE) {
					do {
						$data['msg'] = $msg;
					} while ($msg = @head($data['msg']));
				}

				// 返回错误信息对应的字段。
				if ($data['code'] === 422) {
					if (isset($response->exception)) {
						$data['errors'] = $response->exception->validator->errors();
					} else {
						$errors = @json_decode($response->getContent());
						if (json_last_error() === JSON_ERROR_NONE) {
							$data['errors'] = $errors;
						}
					}
				}
			}
		} else {
			$data['data'] = $response;
		}
		
		if($data['code'] != 200)$data['success'] = false;
		$response->setStatusCode(200);
		$response->headers->set('Content-Type', 'application/json');
		$content = json_encode($recstr($data));
		$response = $response->setContent($content);
		return $response;
	}
}
