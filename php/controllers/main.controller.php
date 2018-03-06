<?php


class main{
    private $paynow_integration_key = "paynow_integration_key_here";
    private $paynow_integration_id = "paynow_integration_id_here";

    public function __construct(){
        //this is where you can set your 'Global' variable, accessible accross all your child classes
        $this->request_method = $_SERVER['REQUEST_METHOD'];
    }

    protected function get_paynow_integration_key(){
        return $this->paynow_integration_key;
    }

    protected function get_paynow_integration_id(){
        return $this->paynow_integration_id;
    }

    public function get_client_ip() {
        $ipaddress = '';
        if ($_SERVER['HTTP_CLIENT_IP'])
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if($_SERVER['HTTP_X_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if($_SERVER['HTTP_X_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if($_SERVER['HTTP_FORWARDED_FOR'])
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if($_SERVER['HTTP_FORWARDED'])
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if($_SERVER['REMOTE_ADDR'])
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    function send_curl($url, $method, $headers, $data, $json=true){
        $params = array();
        foreach($data as $key=>$value){
            $params[$key] = is_array($key) ? http_build_query($key) : $value;
        }
        $ch = curl_init();
        $url = str_replace(' ', '', $url);
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($params));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        curl_close($ch);
        return ($result) ? (($json===true) ? (array) @json_decode($result) : $result) : null;
    }
}