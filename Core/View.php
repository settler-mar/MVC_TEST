<?php

namespace Core;

class View
{
  private $viewController = 'Main';

  public function render($template, $args = [])
  {
    static $twig = null;
    if ($twig === null) {
      $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/views/'.$this->viewController);
      $twig = new \Twig_Environment($loader);
    }
    $args['content']= $twig->render($template.'.twig', $args);

    $loader = new \Twig_Loader_Filesystem(dirname(__DIR__) . '/App/views/');
    $twig = new \Twig_Environment($loader);

    $args['user'] = SITE::$app->user;

    echo $twig->render('layout.twig', $args);
  }

  public function setControllerViewPath($controllerName){
    $this->viewController = $controllerName;
  }
}