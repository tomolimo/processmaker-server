<?php

interface DashletInterface {

  public function setup($dasInsUid);
  public function render();

}