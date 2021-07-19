<?php

namespace Core\HTTP;


class Request
{
    public function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getUrl()
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');

        if ($position !== false)
        {
            $path = substr($path, 0, $position);
        }

        return $path;
    }

    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    public function isPut()
    {
        return $this->getMethod() === 'put';
    }

    public function getInput()
    {
        $data = [];
        
        if($this->isGet())
        {
            foreach ($_GET as $key => $value)
            {
                $data[$key] = $value;
            }
        }

        if($this->isPost() || $this->isPut())
        {
            $data = json_decode(file_get_contents("php://input"), true);

            if(is_null($data))
                return [];
            
        }

        return $data;
    }
}