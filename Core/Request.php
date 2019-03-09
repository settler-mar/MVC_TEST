<?php

namespace Core;

class Request {

  public $url;
  public $params = [];
  public $page = 1;

  public function __construct()
  {
    $url = trim($_SERVER['REQUEST_URI'],'/');

    //Если есть гет параметры собираем из в парамз
    $url = explode('?',$url);
    if(!empty($url[1])){
      $this->params = parse_str($url[1]);
    }

    //Забираем из адреса номер страницы и id
    $url = explode('?',$url[0]);
    if(preg_match('/^(page_)([0-9]*)/i', $url[count($url)-1])){
      $this->page = intval(str_replace('page_','',$url[count($url)-1]));
      unset($url[count($url)-1]);
    }

    if(preg_match('/^(id:)([0-9]*)/i', $url[count($url)-1])){
      $this->params['id'] = intval(str_replace('id:','',$url[count($url)-1]));
      unset($url[count($url)-1]);
    }

    $this->url = implode('/',$url);
  }

  public function post($name){
    if(!isset($_POST[$name])){
      return null;
    };

    return $_POST[$name];
  }

  public function isGet(){
    return $_SERVER['REQUEST_METHOD']=="GET";
  }

  public function isPost(){
    return $_SERVER['REQUEST_METHOD']=="POST";
  }
}