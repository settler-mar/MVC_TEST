<?php

namespace App\controllers;

use App\models\Tasks;
use Core\Controller;
use Core\SITE;

class MainController extends Controller
{

  public function actionIndex()
  {

    $paginateion = Tasks::getPagination();
    $tasks = Tasks::getList();
    $orderList = Tasks::getOrderList();

    SITE::$app->view->render('index', [
        'title' => 'Задачник',
        'tasks' => $tasks,
        'orderList' => $orderList,
        'orderName' => $orderList[SITE::$app->request->order],
        'pagination' => $paginateion,
        'canCreate' => SITE::$app->user->isGuest(),
        'canEdit' => !SITE::$app->user->isGuest(),
    ]);
  }

}

?>