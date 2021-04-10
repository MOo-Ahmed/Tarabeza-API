<?php

namespace Core\HTTP;

class Response
{
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_NOT_FOUND = 404;
    
    const HTTP_OK = 200;
    const HTTP_CREATED = 201;


    public function statusCode(int $_code)
    {
        http_response_code($_code);
    }

    public function render($_data, int $_code)
    {
    	header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: *");

    	$this->statusCode($_code);
    	echo json_encode($_data, JSON_PRETTY_PRINT);
    }

    public function renderFail(int $_code, string $_message = "Please read the API documentation.")
    {
        $data = array("status" => "Fail", "errors" => array("message" => $_message));
        $this->render($data, $_code);
        exit();
    }

    public function renderErrors(int $_code, array $_array)
    {
        $data = array("status" => "Fail", "errors" => $_array);
        $this->render($data, $_code);
        exit();
    }

    public function renderOk(int $_statusCode, $_data)
    {
        $data = array("status" => "Ok", "logos_base_url" => $GLOBALS["app"]["logos_base_url"], "data" => $_data);
        $this->render($data, $_statusCode);
    }


}