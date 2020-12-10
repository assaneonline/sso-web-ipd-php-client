<?php

namespace SSOWeb;

use Exception;

class Client {

    const SSO_BASE_PATH = 'http://10.1.7.42/sso-dev/index.php';

    protected $applicaiton_id = "";
    protected $applicaiton_secret = "";
    protected $redirect_url = "";

    function get_auth_url ($configs = []){
        $default_configs = [
            "nonce" => rand(10000, 99999)
        ];
        $configs = array_merge($default_configs, $configs);

        $this->applicaiton_id = $configs["applicaiton_id"];
        $this->application_secret = $configs["application_secret"];
        $this->redirect_url = $configs["redirect_url"] . (strpos($configs["redirect_url"], "?") === false ? "?" : "") . "&nnc={nnc}&hsh={hsh}&usr={usr}&";

        if(!$this->applicaiton_id) throw new Exception("SSO Client missing application ID.");
        if(!$this->application_secret) throw new Exception("SSO Client missing application secret.");
        if(!$this->redirect_url) throw new Exception("SSO Client missing redirect url.");

        return self::SSO_BASE_PATH . "?application_id=sso-dev&nnc=" . $configs["nonce"] . "&redirect=" . urlencode($this->redirect_url);
    }

    function authenticate ($configs = []){
        $auth_url = $this->get_auth_url($configs);

        header("Location: " . $auth_url);
        die();
    }

    function validate (){
        $usr = $_GET['usr'];
        $nonce = $_GET['nnc'];
        $hash = $_GET['hsh'];

        // Secretly shared with SSO server :
        $application_secret = $this->applicaiton_secret;

        // Chech hash :
        return $hash == sha1($application_secret . $usr . $nonce);
    }
}