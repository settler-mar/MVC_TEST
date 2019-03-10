<?php

namespace App\controllers;

use App\models\Tasks;
use Core\Controller;
use Core\SITE;

class TasksController extends Controller{

  public function actionCreate(){
    if(!SITE::$app->user->isGuest()){
      throw new \Exception("Page not found");
    }

    $task = new Tasks();

    if (SITE::$app->request->isPost()) {
      if(
          $task->load(SITE::$app->request->post()) &&
          $task->is_valid() &&
          $task->save()
      ){
        return SITE::$app->goHome();
      }
    }

    SITE::$app->view->render('create', [
        'title' => 'Создать задание',
        'h1' => 'Создать задание',
        'model' => $task,
    ]);
  }

  public function actionUpdete(){
    if(SITE::$app->user->isGuest()){
      throw new \Exception("Page not found");
    }

    $id = SITE::$app->request->params['id'];

    if(
        !$id ||
        !($task = Tasks::find_by_pk(['id'=>$id],[]))
    ){
      throw new \Exception("Page not found");
    }

    if (SITE::$app->request->isPost()) {
      if(
          $task->load(SITE::$app->request->post()) &&
          $task->is_valid() &&
          $task->save()
      ){
        return SITE::$app->goHome();
      }
    }

    SITE::$app->view->render('update', [
        'title' => 'Обновление задание №'.$id,
        'h1' => 'Обновление задание №'.$id,
        'model' => $task,
    ]);
  }
}