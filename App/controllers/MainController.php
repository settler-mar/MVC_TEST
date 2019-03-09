<?php
namespace App\controllers;

use Core\Controller;
use Core\SITE;

class MainController extends Controller {

  public function actionIndex () {
    SITE::$app->view->render('index',[
        'title' => 'Задачник',
        'h1' => 'Задачник',
    ]);
  }

  public function actionLogin(){

    if(!SITE::$app->user->isGuest()){
      return SITE::$app->goHome();
    }

    $data = [
        'title' => 'Авторизация'
    ];

    if(SITE::$app->request->isPost()){
      $username = SITE::$app->request->post('username');
      $password = SITE::$app->request->post('password');

      if(empty($username) || empty($password)){
        $data['error'] = 'Имя пользователя и пароль не могут быть пустыми';
      }elseif(!SITE::$app->user->tryLogin($username,$password)){
        $data['error'] = 'Пользователь с такими даннми не найден';
      }else{
        return SITE::$app->goHome();
      }
    }

    SITE::$app->view->render('login',$data);
  }

  public function actionLogout(){
    SITE::$app->user->logout();
    return SITE::$app->goHome();
  }
}
?>