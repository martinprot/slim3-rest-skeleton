<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\DataAccess\UserAccess;
use App\Utils\ResponseSerializer;
use App\Utils\APIDate;

use PDOException;

// TODO: Do not extend BaseController anymore.
// Extends a controller like "ResourceController" for example

/**
 * Class BaseController.
 */
class UserController extends BaseController
{
	// TODO: this should be part of an Interface (here?).
	private static $dbRequiredFields = ["email", "password", "name", "country_code", "language", "creation_date"];
	private static $dbOptionalFields = ["date_birth"];

	// TODO: this should be in ResourceController
	private function bodyFrom(Request $request) {
		$queryParams = $request->getParsedBody();
		$all_parameters = array_merge(self::$dbRequiredFields, self::$dbOptionalFields);
		return array_intersect_key($queryParams, array_flip($all_parameters));
	}
	
    /**
     * @param \Psr\Log\LoggerInterface       $logger
     * @param \App\UserAccess                $useraccess
     */
    public function __construct(LoggerInterface $logger, UserAccess $useraccess)
    {
        $this->logger = $logger;
        $this->dataaccess = $useraccess;
	}
	
	public function add(Request $request, Response $response, $args) 
	{
		$request_data = $this->bodyFrom($request);

		if (!array_keys_exist(self::$dbRequiredFields, $request_data)) {
			$serializer = new ResponseSerializer($response);
            return $serializer->error(400, "missing parameter");
		}

		try {
			$last_inserted_id = $this->dataaccess->add($path, $request_data);
			if ($last_inserted_id > 0) {
				$serializer = new ResponseSerializer($response);
				return $serializer->created();
			} else {
				$serializer = new ResponseSerializer($response);
				return $serializer->error(403, "the resource cannot be created");
			}
		}
		catch (PDOException $e) {
			$serializer = new ResponseSerializer($response);
            return $serializer->pdoError($e);
		}
	}

	/**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     * @param array                                    $args
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(Request $request, Response $response, $args)
    {
		// TODO: check if current user is the loggued one.
		// ...or an admin? 

		$request_data = $this->bodyFrom($request);
		
		if (!array_keys_exist(self::$dbRequiredFields, $request_data)) {
			$serializer = new ResponseSerializer($response);
            return $serializer->error(400, "missing parameter");
		}
		
		try {
			$serializer = new ResponseSerializer($response);
			$isupdated = $this->dataaccess->update($path, $args, $request_data);
			if ($isupdated) {
				return $serializer->updated();
			} else {
				return $serializer->error(404, "the resource cannot be found");
			}
		}
		catch (PDOException $e) {
			$serializer = new ResponseSerializer($response);
            return $serializer->pdoError($e);
		}
    }
}