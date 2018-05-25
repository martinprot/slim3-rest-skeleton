<?php

namespace App\DataAccess;

use Psr\Log\LoggerInterface;
use PDO;

/**
 * Class IssueTypeAccess.
 */
class IssueTypeAccess extends ResourceAccess {
	public function tablename() {
		return "issue_type";
	}
	public function idColumn() {
		return "id";
	}
	public function requiredFields() {
		return ["category", "sub_category", "explanation"];
	}
	public function optionalFields() {
		return [];
	}
}