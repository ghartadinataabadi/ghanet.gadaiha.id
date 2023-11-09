<?php
//require_once FCPATH.'vendor/autoload.php';

class XDbf
{
	public $path;
	public function __construct()
	{
		new \XBase\Table($this->path);
	}
}
