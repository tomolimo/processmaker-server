<?php

interface DashletInterface {

  public static function getAdditionalFields($className);
  public function setup($dasInsUid);
  public function render();

}