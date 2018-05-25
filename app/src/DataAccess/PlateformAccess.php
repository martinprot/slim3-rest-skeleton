<?php

namespace App\DataAccess;

use Psr\Log\LoggerInterface;
use PDO;

/**
 * Class PlateformAccess.
 */
class PlateformAccess extends ResourceAccess {
	public function tablename() {
		return "plateform";
	}
	public function idColumn() {
		return "id";
	}
	public function requiredFields() {
		return ["name", "country_code"];
	}
	public function optionalFields() {
		return [];
	}
}