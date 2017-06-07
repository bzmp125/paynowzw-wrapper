<?php
class Paynow extends main{
    var $_params;
    private $paynow_integration_key = "paynow_integration_key_here";
    private $paynow_integration_id = "paynow_integration_id_here";
    /*
     * This is one of the hosts that Paynow can use to POST data back to your system. Please bare in mind that this could change
      at any time and break your application if you are going to rely on checking whether the return is actually coming from PayNow
     */

    function Paynow($_params){
        parent::__construct();
        $this->_params = $_params;
    }

    private function get_paynow_integration_key(){
        return $this->paynow_integration_key;
    }

    private function get_paynow_integration_id(){
        return $this->paynow_integration_id;
    }

    function processRequest(){
        /*
        * If you are not using a routing framework like me, this is where you'd want to do the routing dependant on the HTTP Request Method
        * found in $this->request_method
        *
        *
        * eg GET  /topup/transasction_id could be finding the status of the transation
        *    POST /topup could initialise a transaction
        *    DELETE /topup could cancel a transaction
        *
        * This is totally up to you and not tied to paynow
        */

        //for now I'll simply return the initialise_transaction() method
        return $this->process_return();
    }

    function process_return(){
        //checking if the posting address is one of the trusted hosts
        $paynow_data = $_REQUEST;

        //checking if the passed hash is legit


        if($paynow_data['hash']==$this->CreateHash($paynow_data, $this->get_paynow_integration_key())){
            if($paynow_data['status']=="Paid"){
                /*
                 * The transaction was successful and the user has paid. This is where you can give access to your user etc.
                 */
            }
        }else{
            //either write to a log file or something. But the returned hash is not the same, so its possible that the transacation
            //was compromised or its just bad code.
        }

    }

    private function CreateHash($values, $IntegrationKey){
        $string = "";
        foreach($values as $key=>$value) {
            if( strtoupper($key) != "HASH" ){
                $string .= $value;
            }
        }
        $string .= $IntegrationKey;
        $hash = hash("sha512", $string);
        return strtoupper($hash);
    }
}
