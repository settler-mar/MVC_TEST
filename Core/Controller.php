<?php

namespace Core;
use Core\Application;

abstract class Controller {
  protected function render(){
    echo SITE::$app->view;
  }
}