<?php
namespace App\controllers;

use Core\Controller;
use Core\SITE;

class MainController extends Controller {

  public function actionIndex () {
    SITE::$app->view->render('index');
  }
}
?>