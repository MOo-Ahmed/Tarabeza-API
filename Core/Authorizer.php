<?php

namespace Core;

use \Firebase\JWT\JWT;

class Authorizer
{
	/**
     * Authorize for the incoming request.
     *
     * @param  Request  $_request
     * @param  string  $_roles
     * @return bool
     */
    public static function isAuthorized($_request, $_roles)
    {
		$payload = Authorizer::getPayload();
        
        if(!is_null($payload) && in_array($payload->role, $_roles))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * retrun PAYLOAD
     * @param  string  $_jwt
     * @return string
     */
    public static function getPayload()
    {
        try
        {
            $jwt = Authorizer::getJWT();

            if(!isset($jwt))
                return NULL;
            
            $payload = JWT::decode($jwt, $GLOBALS["app"]["JWT_secret_key"], array($GLOBALS["app"]["JWT_cipher"]));

            return $payload;
        }
        catch (\Exception $e)
        {
            return NULL;
        }

        
    }

    /**
     * retrun JWT
     *
     * @return bool | NULL
     */
    public static function getJWT()
    {
        $headers = apache_request_headers();
       
        if(!isset($headers['Authorization']))
            return NULL;

        $arr = explode(" ", $headers['Authorization']);

        if(!isset($arr[0]) || $arr[0] != "Bearer")
            return NULL;

        if(!isset($arr[1]))
            return NULL;

        // JWT
        return $arr[1];
    }

    public static function createJWT($_data)
    {
        $issuedAtTime = time();

        $payload = array(
            "id" => $_data["id"],
            "email" => $_data["email"],
            "role" => $_data["role"],
            "iat" => $issuedAtTime,
            "exp" => $issuedAtTime + $GLOBALS["app"]["JWT_time_to_live"],
        );

        $jwt = JWT::encode($payload, $GLOBALS["app"]["JWT_secret_key"]);

        return $jwt;
    }


}