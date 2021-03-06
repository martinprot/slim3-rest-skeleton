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

/**
 * Class UserController.
 */
final class UserController extends GenericController
{

    public function __construct(LoggerInterface $logger, PDO $pdo)
    {
        $this->logger = $logger;
        $this->dataaccess = new UserAccess($logger, $pdo);
	}

    public function update(Request $request, Response $response, $args)
    {
		$serializer = new ResponseSerializer($response);

		$authentifiedUser = $request->getAttribute("user");;
		if ($authentifiedUser["id"] != $args["id"] && $authentifiedUser["admin"] == false) {
			// wrong connected user
			return $serializer->error(403, "not authorized.");
		}
		return parent::update($request, $response, $args);
	}
	
	public function delete(Request $request, Response $response, $args)
    {
		$serializer = new ResponseSerializer($response);
		$authentifiedUser = $request->getAttribute("user");;
		if ($authentifiedUser["id"] != $args["id"] && $authentifiedUser["admin"] == false) {
			// wrong connected user
			return $serializer->error(403, "not authorized.");
		}
		return parent::delete($request, $response, $args);
	}
}