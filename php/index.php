<?php
if($_SERVER['REQUEST_METHOD']=="OPTIONS"){
    header('Access-Control-Allow-Origin: '.$_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Methods: GET, POST");
}else{
    ini_set('max_execution_time', 300);
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header("Content-Type:application/json");

    if($_SERVER['REQUEST_METHOD']=="PUT" || $_SERVER['REQUEST_METHOD']=="DELETE"){
        header("Access-Control-Allow-Methods: *");
        $request_data = $_REQUEST;
        parse_str(file_get_contents("php://input"),$putData);
        $_REQUEST = $putData;
        foreach($request_data as $k=>$v){
            $_REQUEST[$k] = $v;
        }
    }
    $result = array();
    try{
        require 'includes/functions.php';
        require 'controllers/main.controller.php';
        //clean the request

        $enc_request = $_REQUEST['enc_request'];
        $params = array();
        $str = explode('/', $enc_request);
        $resource = $str[0];

        $params = $_REQUEST;
        $return = array();
        if(isset($params)==false || isset($resource)==false){
            $ex="INCOMPLETE REQUEST.";
            error('2002');
        }else{
            $resource = ucfirst(strtolower($resource));

            if(file_exists("controllers/{$resource}.php")){
                //this is to clear the overhead that would arise with dynamic requires since the model is loosely coupled.
                require "controllers/$resource.php";
                $resource_instance = new $resource($params);
                $return = $resource_instance->processRequest();
            }else{
                //something went wrong
                //return an error or something
                $return['success'] = false;
                $return['message'] = "RESOURCE NOT FOUND.";
            }
        }

        $success = $return['success'];
        $message = isset($return['errorMsg']) ? $return['errorMsg'] : $return['message'];
        header("HTTP/1.1 200 $message");
        $result = $return;
        $json_response = @json_encode($result, JSON_PRETTY_PRINT);
        echo $json_response;
        exit;
    }catch (Exception $e){
        $result['success'] = 0;
        $result['errorMsg'] = $e->getMessage();
        echo @json_encode($result);
        exit();
    }
}
