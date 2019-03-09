<?php

namespace App\models;

use ActiveRecord;
use Core\SITE;

class Tasks extends ActiveRecord\Model
{
  static $table_name = 'tasks';
  static $primary_key = 'id';
  static $connection = 'db';

  const LIMIT = 3;

  static function getOrderList()
  {
    return [
        '' => 'по-умолчанию',
        'name' => 'имени пользователя',
        'email' => 'email',
        'status' => 'статусу',
    ];
  }

  static function getList()
  {
    $params = [
        'conditions' => ['', []],
        'limit' => self::LIMIT,
        'offset' => (SITE::$app->request->page - 1) * self::LIMIT
    ];

    $orderList = self::getOrderList();
    if (!isset($orderList[SITE::$app->request->order])) {
      d(SITE::$app->request->order);
      throw new \Exception("Page not found");
    }
    if (!empty(SITE::$app->request->order)) {
      $params['order'] = SITE::$app->request->order;
    }


    return self::find(
        'all', $params);
  }

  static function getPagination()
  {
    $count = self::count();
    $totalPage = ceil($count / self::LIMIT);

    if (SITE::$app->request->page > $totalPage) {
      throw new \Exception("Page not found");
    }

    if ($totalPage == 1) {
      return false;
    }

    $url = SITE::$app->request->url .
        (empty(SITE::$app->request->order) ? '' : ('/order:' . SITE::$app->request->order));
    $page = SITE::$app->request->page;
    return [
        'url' => $url,
        'page' => $page,
        'total' => $totalPage,
        'prev_link' => ($page>2)?$url.'/page_'.($page-1):'/',
        'next_link' => $url.'/page_'.($page+1),
    ];
  }
}