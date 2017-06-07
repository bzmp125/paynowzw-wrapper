<?php

function send_curl($url, $method, $headers, $data, $json=null){
    try {
        $ch = curl_init();

        if (FALSE === $ch)
            throw new Exception('failed to initialize');

        $params = array();
        $request_method =$method;
        foreach($data as $key=>$value){
            $params[$key] = is_array($key) ? json_encode($key) : $value;
        }

        //initialize and setup the curl handler
        $ch = curl_init();
        //authentication via http

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        if($request_method!='GET')
            curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
        else{
            $url .= "?".http_build_query($params);
        }
        $url = str_replace(' ', '', $url);
        curl_setopt($ch, CURLOPT_URL, $url);

        //execute the request
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);

        if (FALSE === $result)
            throw new Exception(curl_error($ch), curl_errno($ch));
        //if everything went great, return the data
        if($result){
            return ($json) ? json_decode($result) : $result;
        }else{
            return null;
        }
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with this error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }

}


function log_error(){
    //you can either write to a file or save on your database etc
}