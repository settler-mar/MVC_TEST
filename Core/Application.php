<?php
/**
 * Created by PhpStorm.
 * User: max
 */

namespace Core;

use App\controllers\MainController;
use App\models\User;
use ActiveRecord;

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
    define("ROOT", dirname(dirname(__FILE__)));
    define("DEBUG", empty($config['debug'])?false:$config['debug']);
  }

  /**
   * Главная функция старта работы
   */
  public function run()
  {
    //Загрузка рендера
    $this->view = new View();

    //Обработчики ошибок
    error_reporting(E_ALL);
    set_error_handler('Core\Error::errorHandler');
    set_exception_handler('Core\Error::exceptionHandler');

    //Старт сессии
    session_start();

    //базаданных
    $db_config = $this->config['db'];
    $this->db = ActiveRecord\Config::instance();
    $this->db->set_model_directory(ROOT.'/App/models');
    $this->db->set_connections(
        array(
            'db' => $db_config['type'].'://'.$db_config['user'].':'.$db_config['password'].'@'.$db_config['host'].'/'.$db_config['db_name']
        )
    );

    //Загрузка вспомогательных функций
    $this->helper = new Helper();

    //Грузим данные о юзере
    $this->user = new User();

    //Обработка адреса
    $route = explode('/', $this->request->url);
    $controllerName = $this->helper->makeName($route[0]).'Controller';

    if (
        $controllerName!=="MainController" &&
        file_exists(ROOT . '/App/controllers/' . $controllerName . '.php')
    ) {
      $controllerName = 'App\controllers\\'.$controllerName;
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