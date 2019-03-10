<?php

namespace Core;

class Model extends \ActiveRecord\Model{
  static $primary_key = 'id';
  static $connection = 'db';
  private $errorMsg = [];

  public function load($data){
    $attributes_rule=$this->getAttributesRule();

    $attributes = $this->is_new_record()?
        (isset($attributes_rule['on_create'])?$attributes_rule['on_create']:array_keys($this->attributes())):
        (isset($attributes_rule['on_update'])?$attributes_rule['on_update']:array_keys($this->attributes()));

    foreach ($attributes as $attr){
      if(isset($data[$attr])){
        $this->$attr = $data[$attr];
      }
    }

    return true;
  }

  public function getAttributesRule(){
      return [];
  }

  public function is_valid()
  {
    $result = parent::is_valid();

    $this->errorMsg = $result?[]:$this->errors->get_raw_errors();
    return $result;
  }

  public function getAttributeErrorMsg($attribute){
    if(empty($this->errorMsg[$attribute])){
      return false;
    }

    $error = $this->errorMsg[$attribute];
    if(is_array($error)){
      $error = $error[0];
    }

    return $error;
  }

  public function canEditAttribute($attribute){
    $attributes_rule=$this->getAttributesRule();

    $attributes = $this->is_new_record()?
        (isset($attributes_rule['on_create'])?$attributes_rule['on_create']:array_keys($this->attributes())):
        (isset($attributes_rule['on_update'])?$attributes_rule['on_update']:array_keys($this->attributes()));

    return in_array($attribute,$attributes);
  }
}
