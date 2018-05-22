<?php

namespace App\Utils;

use Psr\Http\Message\ResponseInterface as Response;

class ResponseSerializer {

	private $response;

	public function __construct(Response $response) {
		$this->response = $response;
	}

	// ******************
	// Handling success
	// ******************

	public function success($data = "ok") {
		$results = array();
		$results["code"] = 200;
		$results["content"] = $data;
		return $this->response->withJson($results, 200);
	}

	public function created() {
		$results = array();
		$results["code"] = 201;
		$results["content"] = "created";
		return $this->response->withJson($results, 201);
	}
	
	public function updated() {
		$results = array();
		$results["code"] = 200;
		$results["content"] = "updated";
		return $this->response->withJson($results, 200);
	}

	public function deleted() {
		$results = array();
		$results["code"] = 204;
		$results["content"] = "deleted";
		return $this->response->withJson($results, 204);
	}

	// ******************
	// Handling error
	// ******************

	public function error($code, $errorMessage) {
		$results = array();
		$results["code"] = $code;
		$results["error"] = 0;
		$results["message"] = $errorMessage;
		return $this->response->withJson($results, $code);
	}

	public function pdoError(\PDOException $exception) {
		switch ($exception->getCode()) {
			case "23000": // duplicate entry
			$message = "duplicate field";
			break;
			case "42S22": // column not found
			$message = "column not found";
			break;
			default:
			$message = $exception->getMessage();
			break;
		}
		$results["code"] = 403;
		$results["error"] = $exception->getCode();
		$results["message"] = $message;
		return $this->response->withJson($results, $code);
	}
};

?>