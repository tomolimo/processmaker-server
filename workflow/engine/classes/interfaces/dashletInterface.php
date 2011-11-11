<?php

interface DashletInterface {

  public static function getAdditionalFields();
  public function setup($dasInsUid);
  public function render();

}