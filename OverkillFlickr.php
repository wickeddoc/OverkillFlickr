<?php

class OverkillFlickr {

    # the API key we got from flickr
    private $API = "";

    static private $instance = false;

        # singleton constructor
    static function instance($api_key = "") {
      if(!OverkillFlickr::$instance) {
        OverkillFlickr::$instance = new OverkillFlickr($api_key);
      }
      return OverkillFlickr::$instance;
    }

    function __construct($api_key = "") {
      $this->API  = $api_key;
    }

      # the __call method allows us to dynamically create any
      # flickr api function we want.
      # takes an array of arguments
      # example: to call the flickr.people.findByEmail service
      # $flickr = OverkillFlickr::instance("my API key"); // API key only needed on the first call
      # $result = $flickr->people_findByEmail(array("find_email" => "my.pattern@domain.com"));
    function __call($method, $arguments) {

      $method = str_replace("_", ".", $method);
      $argument = $arguments[0];

      if (empty($method) || empty($argument) || !is_array($argument)) {
        return false;
      }

      $params = array_merge($params = array(), $argument);

      $params['api_key']  = $this->API;
      $params['method']   = "flickr." . $method;
      $params['format']   = "php_serial";

      $encoded_params = array();

      foreach ($params as $k => $v){
              $encoded_params[] = urlencode($k).'='.urlencode($v);
      }

      # call the API and decode the response
      $url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
      $rsp = file_get_contents($url);
      $rsp_obj = unserialize($rsp);

      #
      # return the response from flickr
      #
      return $rsp_obj;
    }

  }

