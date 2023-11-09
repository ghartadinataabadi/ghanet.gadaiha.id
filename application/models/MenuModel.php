<?php
require_once 'Master.php';
class MenuModel extends Master
{
	/**
	 * @var string
	 */

	public $table = 'menus';

	/**
	 * @var string
	 */

	public $primary_key = 'id';

	public $hirarki = true;
}
