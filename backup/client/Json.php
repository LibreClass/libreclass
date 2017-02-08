<?php

abstract class Json {

  protected static $table = "";
  public $__id = false;

  private static function getDir()
  {
    return storage_path() . "/json/";
  }

  private static function getName()
  {
    $name = get_called_class();

    return self::getDir() . strtolower($name) . "s.json";
  }

  private static function getJson()
  {

    try {
      return json_decode(file_get_contents(self::getName()));
    } catch (Exception $ex) {
      return [];
    }
  }

  public static function all()
  {
    return self::getJson();
  }

  public static function find($id)
  {
    $list = self::getJson();

    return $list[$id];
  }

  public function save()
  {
    $list = self::getJson();

    if ($this->__id === false)
      $this->__id = count($list);

    $list[$this->__id] = clone $this;

    try
    {
      file_put_contents(self::getName(), json_encode($list));
    }
    catch(Exception $e)
    {
      mkdir(self::getDir(), 0770, true);
      file_put_contents(self::getName(), json_encode($list));
    }

    return true;
  }

  public function __toString()
  {
    return json_encode($this);
  }

}
