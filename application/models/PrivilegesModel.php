<?php
require_once 'Master.php';
class PrivilegesModel extends Master
{
	/**
	 * @var string
	 */

	public $table = 'levels_privileges';

	/**
	 * @var string
	 */

	public $primary_key = 'id';
}
