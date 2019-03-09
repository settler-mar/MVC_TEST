<?php

namespace App\model;

class User
{

  private $userList = [
      'admin' => [
          'name' => 'Владыка',
          'password' => '123',
      ]
  ];

  private $user = false;
  public $name;

  public function __construct()
  {
    if (
        !empty($_SESSION['user_id']) &&
        isset($this->userList[$_SESSION['user_id']])
    ) {
      $this->user = $_SESSION['user_id'];
      $this->name = $this->userList[$this->user]['name'];
    }
  }

  /*
   * Попытка авторизироваться. В случае успеха вернет true
   */
  public function tryLogin($username, $password)
  {
    if (
        !isset($this->userList[$username]) ||
        $this->userList[$username]['password'] != $password
    ) {
      return false;
    }
    $_SESSION['user_id'] = $username;
    return true;
  }

  /*
   * выход с пользователя
   */
  public function logout()
  {
    unset($_SESSION['user_id']);
  }

  /*
   * Проверка наличия авторизации
   */
  public function isGuest()
  {
    return !$this->user;
  }
}
