<?php
  //header("Content-type: image/png");

  $chartType = isset($_GET['chart'])? $_GET['chart'] : '1';

  //use the chart class to build the chart:
  include_once ("class.{className}.php");
  $chartsObj = new {className}Class();

  if (method_exists($chartsObj, $chartType)) {
    $chartsObj->{$chartType}();
    die;
  }
  