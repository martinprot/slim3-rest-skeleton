<?php

namespace App\Utils;

use Psr\Http\Message\ResponseInterface as Response;
use App\DataAccess\InputException;

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

	public function created($id) {
		$results = array();
		$results["code"] = 201;
		$results["content"] = ["id" => intval($id)];
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
		$results["code"] = 200;
		$results["content"] = "deleted";
		return $this->response->withJson($results, 200);
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

	public function inputError(InputException $exception) {
		$results["code"] = 400;
		$results["error"] = $exception->getCode();
		$results["message"] = $exception->getMessage();
		return $this->response->withJson($results, 400);
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
		return $this->response->withJson($results, 403);
	}
};

?>