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

  public function actionLogin()
  {

    if (!SITE::$app->user->isGuest()) {
      return SITE::$app->goHome();
    }

    $data = [
        'title' => 'Авторизация'
    ];

    if (SITE::$app->request->isPost()) {
      $username = SITE::$app->request->post('username');
      $password = SITE::$app->request->post('password');

      if (empty($username) || empty($password)) {
        $data['error'] = 'Имя пользователя и пароль не могут быть пустыми';
      } elseif (!SITE::$app->user->tryLogin($username, $password)) {
        $data['error'] = 'Пользователь с такими даннми не найден';
      } else {
        return SITE::$app->goHome();
      }
    }

    SITE::$app->view->render('login', $data);
  }

  public function actionLogout()
  {
    SITE::$app->user->logout();
    return SITE::$app->goHome();
  }
}

?>