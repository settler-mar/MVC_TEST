<?php
namespace Core;

class Helper {
  /*
   * Базовая функция для получения имен
   */
  public function makeName($str)
  {
    if(is_string($str)){
      $str = explode(' ',$str);
    }
    foreach ($str as &$item){
      $item = strtoupper(mb_substr($item, 0, 1)) .
      strtolower(mb_substr($item, 1));
    }
    return implode($str);
  }
}