<?php

namespace Core;

class Controller
{
	protected $request;
    protected $response;

	public function __construct()
	{
		$this->request = new \Core\HTTP\Request();
        $this->response = new \Core\HTTP\Response();
	}
}