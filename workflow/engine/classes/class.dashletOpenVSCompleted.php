<?php

require_once 'interfaces/dashletInterface.php';

class DashletOpenVSCompleted implements DashletInterface {

  /**
      width
    */
    var $w = 610;

    /**
      height
    */
    var $h = 300;

    /**
      value of gauge
    */
    var $value = 50;

    /**
      maxValue
    */
    var $maxValue = 100;

    /**
      redFrom
    */
    var $redFrom = 80;

    /**
      redTo
    */
    var $redTo = 100;

    /**
      yellowFrom
    */
    var $yellowFrom = 60;

    /**
      yellowTo
    */
    var $yellowTo = 80;

  function setup($config) {
    //$this->w = $config['w'];
    //loadData
  }

  function render () {
    /*G::LoadClass('gauge');
    $g = new Gauge();
    $g->w = $w;
    //others
    $g->render();*/
  }

}