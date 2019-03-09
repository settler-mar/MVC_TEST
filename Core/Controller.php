<?php

namespace Core;

abstract class Controller {
  protected function render(){
    echo SITE::$app->view;
  }
}