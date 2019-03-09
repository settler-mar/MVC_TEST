<?php
/**
 * Created by PhpStorm.
 * User: max
 */

namespace Core;

use App\controllers\MainController;
use App\model\User;

class Application
{
  private $config;

  public $app;

  public $db; //базаданных
  public $helper; //вспомогательные функции
  public $view; //отрисовщик страниц
  public $user; //польователь в системе
  public $request; //данные запроса

  public function __construct($config)
  {
    SITE::$app = $this;

    $this->config = $config;

    $this->request = new Request();
    define("ROOT", dirname($_SERVER['DOCUMENT_ROOT']));
    define("DEBUG", empty($config['debug'])?false:$config['debug']);
  }

  /**
   * Главная функция старта работы
   */
  public function run()
  {
    //Обработчики ошибок
    error_reporting(E_ALL);
    set_error_handler('Core\Error::errorHandler');
    set_exception_handler('Core\Error::exceptionHandler');

    //Старт сессии
    session_start();

    //Загрузка вспомогательных функций
    $this->helper = new Helper();

    //Загрузка рендера
    $this->view = new View();

    //Грузим данные о юзере
    $this->user = new User();

    //Обработка адреса
    $route = explode('/', $this->request->url);
    $controllerName = $this->helper->makeName($route[0]).'Controller';

    if (
        $controllerName!=="MainController" &&
        file_exists(ROOT . '/App/controllers/' . $controllerName . '.php')
    ) {
      require(ROOT . '/App/controllers/' . $controllerName . '.php');
      $controller = new $controllerName();

      $action = 'action'.(empty($route[1])?'Index':$this->helper->makeName($route[1]));
      if(!method_exists($controller,$action)){
        unset ($controller);
        unset ($action);
      }else{
        $this->view->setControllerViewPath($this->helper->makeName($route[0]));
      }
    }else{
      $action = 'action'.(empty($route[0])?'Index':$this->helper->makeName($route[0]));
    }

    if(!isset($controller)){
      $controller = new MainController();
    }

    if(!isset($action) || !method_exists($controller,$action)){
      throw new \Exception("Page not found");
    }else{
      $controller->$action();
    }
  }

  public function goHome(){
    header('Location: /');
    exit;
  }
}