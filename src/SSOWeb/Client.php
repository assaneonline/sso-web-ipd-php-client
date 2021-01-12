<?php

namespace SSOWeb;

use Exception;

class Client {

    const SSO_BASE_PATH = 'http://sso.dev.pasteur.sn/';
    const USERNAME_QUERY_STRING_KEY = "usr";
    const NONCE_QUERY_STRING_KEY = "nnc";
    const HASH_QUERY_STRING_KEY = "hsh";
    const ACCESS_TOKEN_QUERY_STRING_KEY = "ipdsso";

    protected $application_id = "";
    protected $application_secret = "";
    protected $redirect_url = "";
    protected $nonce = "";

    function __construct($configs = [])
    {
        $default_configs = [
            "nonce" => rand(10000, 99999)
        ];
        $configs = array_merge($default_configs, $configs);

        $this->application_id = $configs["application_id"];
        $this->application_secret = $configs["application_secret"];
        $this->redirect_url = $configs["redirect_url"] . (strpos($configs["redirect_url"], "?") === false ? "?" : "") . "&" . self::NONCE_QUERY_STRING_KEY . "={nnc}&" . self::HASH_QUERY_STRING_KEY . "={hsh}&" . self::USERNAME_QUERY_STRING_KEY . "={usr}&";
        $this->nonce = $configs["nonce"];

        if(!$this->application_id) throw new Exception("SSO Client missing application ID.");
        if(!$this->application_secret) throw new Exception("SSO Client missing application secret.");
        if(!$this->redirect_url) throw new Exception("SSO Client missing redirect url.");
        if(!$this->nonce) throw new Exception("SSO Client missing nonce.");
    }

    function get_auth_url (){
        return self::SSO_BASE_PATH . "?application_id=".$this->application_id."&nnc=" . $this->nonce . "&redirect=" . urlencode($this->redirect_url);
    }

    function authenticate (){
        $auth_url = $this->get_auth_url();

        header("Location: " . $auth_url);
        die();
    }

    function verify (){
        $usr = $_GET['usr'];
        $nonce = $_GET['nnc'];
        $hash = $_GET['hsh'];

        // Secretly shared with SSO server :
        $application_secret = $this->application_secret;

        // Chech hash :
        return $hash == sha1($application_secret . $usr . $nonce);
    }

    static function get_username(){
        return $_GET[self::USERNAME_QUERY_STRING_KEY] ?? null;
    }

    function get_access_token (){
        return $_GET[self::ACCESS_TOKEN_QUERY_STRING_KEY] ?? null;
    }
}