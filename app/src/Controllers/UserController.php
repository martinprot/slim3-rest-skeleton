<?php

namespace App\Controllers;

use Psr\Log\LoggerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

use App\DataAccess\UserAccess;
use App\Utils\ResponseSerializer;
use App\Utils\APIDate;
use App\DataAccess\InputException;

use PDO;
use PDOException;

// TODO: Do not extend GenericController anymore.
// Extends a controller like "ResourceController" for example

/**
 * Class GenericController.
 */
final class UserController extends GenericController
{

    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
        $this->logger = $logger;
        $this->dataaccess = new UserAccess($logger, $pdo);
	}

	public function getAll(Request $request, Response $response, $args)
    {
		$arrparams = $request->getParams();
		try {
			$all = $this->dataaccess->getAll(null, $arrparams);
		}
		catch (PDOException $e) {
			$serializer = new ResponseSerializer($response);
			return $serializer->pdoError($e);
		}
		catch (InputException $e) {
			$serializer = new ResponseSerializer($response);
			return $serializer->inputError($e);
		}
		$serializer = new ResponseSerializer($response);
		return $serializer->success($all);
    }
	
	public function add(Request $request, Response $response, $args) 
	{
		$request_data = $request->getParsedBody();
		try {
			$last_inserted_id = $this->dataaccess->add(null, $request_data);
			if ($last_inserted_id > 0) {
				$user = $this->dataaccess->getById($last_inserted_id);
				$serializer = new ResponseSerializer($response);
				return $serializer->created($user);
			} else {
				$serializer = new ResponseSerializer($response);
				return $serializer->error(403, "the resource cannot be created");
			}
		}
		catch (PDOException $e) {
			$serializer = new ResponseSerializer($response);
            return $serializer->pdoError($e);
		}
		catch (InputException $e) {
			$serializer = new ResponseSerializer($response);
            return $serializer->inputError($e);
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
		$serializer = new ResponseSerializer($response);

		$authentifiedUser = $request->getAttribute("user");;
		if ($authentifiedUser["id"] != $args["id"] && $authentifiedUser["admin"] == false) {
			// wrong connected user
			return $serializer->error(403, "not authorized.");
		}
		$request_data = $request->getParsedBody();
		try {
			$isupdated = $this->dataaccess->update(null, $args, $request_data);
			if ($isupdated) {
				return $serializer->updated();
			} else {
				return $serializer->error(404, "the resource cannot be found");
			}
		}
		catch (PDOException $e) {
            return $serializer->pdoError($e);
		}
		catch (InputException $e) {
            return $serializer->inputError($e);
		}
	}
	
	public function delete(Request $request, Response $response, $args)
    {
		$authentifiedUser = $request->getAttribute("user");;
		if ($authentifiedUser["id"] != $args["id"] && $authentifiedUser["admin"] == false) {
			// wrong connected user
			return $serializer->error(403, "not authorized.");
		}
		$serializer = new ResponseSerializer($response);
		try {
			$isdeleted = $this->dataaccess->delete(null, $args);
			if ($isdeleted) {
				return $serializer->deleted();
			} else {
				return $serializer->error(404, "the resource cannot be found");
			}
		}
		catch (PDOException $e) {
			$serializer = new ResponseSerializer($response);
            return $serializer->pdoError($e);
		}
		catch (InputException $e) {
			$serializer = new ResponseSerializer($response);
            return $serializer->inputError($e);
		}
	}
}