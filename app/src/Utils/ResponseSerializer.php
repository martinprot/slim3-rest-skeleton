<?php

namespace App\Utils;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseSerializer {

	private $response;

	public function __construct(Response $response) {
		$this->response = $response;
	}

	public function error($code, $error) {
		$results = array();
		$results["code"] = $code;
		$results["error"] = $error;
		return $this->response->withJson($results, $code);
	}

	public function success($data = "ok") {
		$results = array();
		$results["code"] = 200;
		$results["content"] = $data;
		return $this->response->withJson($results, 200);
	}
};

?>