<?php

namespace App\DataAccess;

use Psr\Log\LoggerInterface;
use PDO;

/**
 * Class UserAccess.
 */
class UserAccess extends ResourceAccess {
	public function tablename() {
		return "user";
	}
	public function idColumn() {
		return "id";
	}
	public function requiredFields() {
		return ["email", "password", "name", "country_code", "language", "creation_date"];
	}
	public function optionalFields() {
		return ["date_birth"];
	}


}