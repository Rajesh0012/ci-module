<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('checkparam')) {

    function checkparam($param, $type) {
        switch ($type) {
            case 'string':
                if (isset($param) && !empty($param) && is_string($param)) {
                    $response = TRUE;
                } else {
                    $response = FALSE;
                }
                break;
            case 'array':
                if (isset($param) && !empty($param) && is_array($param)) {
                    $response = TRUE;
                } else {
                    $response = FALSE;
                }
                break;
            case 'number':
                if (isset($param) && !empty($param) && is_numeric($param)) {
                    $response = TRUE;
                } else {
                    $response = FALSE;
                }
                break;
            default:
                break;
        }
        return $response;
    }

}

if (!function_exists('authenticate_key')) {


    function authenticate_key($header) {

        if (checkparam($header, 'array') == TRUE && !empty($header['Api-Key'])) {
            if (AUTH_KEY === $header['Api-Key']) {
                $response = true;
            } else {
                $response = false;
            }
        } else {
            $response = false;
        }
        return $response;
    }

}
if (!function_exists('auth_header')) {

    function auth_header($username = '', $password = '',$configdata) {

        if (checkparam($username, 'string') && checkparam($password, 'string')) {

            if (checkparam($configdata['username'], 'string') && checkparam($configdata['password'], 'string')) {
                      
                if ($configdata['username'] != $username || $configdata['password'] != $password) {
                    $response = FALSE;
                           
                } else {
                    $response = TRUE;
                }
            } else {
                $response = FALSE;
            }
        }
      
        return $response;
    }

}

if (!function_exists('validate_name')) {

    function validate_name($name){
        if(checkparam($name,'string')){
            if(preg_match('/^([a-zA-z ]{3,50})$/', $name)){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}

if (!function_exists('validate_email')) {

    function validate_email($email){
        if(checkparam($email,'string')){
            if(preg_match(' /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/', $email)){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}

if (!function_exists('validate_mobile')) {

    function validate_mobile($mobile){
        if(checkparam($mobile,'string')){
            if(preg_match('/^(\+\d{1,3}[- ]?)?\d{7,10}$/', $mobile)){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}

if (!function_exists('generate_string')) {
    
    function generate_string($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
}
