<?php

require_once 'interfaces/dashletInterface.php';
require_once 'model/Dashlet.php';
require_once 'model/DashletInstance.php';

class PMDashlet extends DashletInstance implements DashletInterface {

  // Own properties
  private $dashletObject;

  // Interface functions

  public function setup($dasInsUid) {
    try {
      $dashletInstance = $this->getDashletInstance($dasInsUid);
      //$this->dashletObject = new $dashletInstance['DAS_CLASS']();
    }
    catch (Exception $error) {
      throw $error;
    }
    //recupera el registro
    /*$array = loadDB()
    //merge
    $c = new $className();
    $c->setup($array);*/
  }

  public function render() {
    try {
      //
    }
    catch (Exception $error) {
      throw $error;
    }
    //$this->c->render();
  }

  // Own functions

  public function getDashletsInstances() {
    try {
      //
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function getDashletsInstancesQuantity() {
    try {
      //
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function getDashletInstance($dasInsUid) {
    try {
      $dashletInstance = $this->load($dasInsUid);
      if (!isset($dashletInstance['DAS_UID'])) {
        new Exception('Error load the Dashlet Instance "' . $dasInsUid . '".');
      }
      $dashlet = new Dashlet();
      $dashletFields = $dashlet->load($dashletInstance['DAS_UID']);
      return array_merge($dashletFields, $dashletInstance);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function saveDashletInstance($data) {
    try {
      //
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function deleteDashletInstance($dasInsUid) {
    try {
      //
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function getDashletsInstancesForUser($userUid) {
    try {
      //
    }
    catch (Exception $error) {
      throw $error;
    }
  }

}