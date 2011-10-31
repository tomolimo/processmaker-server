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
      G::LoadClass($dashletInstance['DAS_CLASS']);
      $this->dashletObject = new $dashletInstance['DAS_CLASS']();
      $this->dashletObject->setup($dashletInstance);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function render() {
    try {
      if (is_null($this->dashletObject)) {
        throw new Exception('Please call to the function "setup" before call the function "render".');
      }
      $this->dashletObject->render();
    }
    catch (Exception $error) {
      throw $error;
    }
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
      $this->dashletObject->createOrUpdate($data);
    }
    catch (Exception $error) {
      throw $error;
    }
  }

  public function deleteDashletInstance($dasInsUid) {
    try {
      $this->dashletObject->remove($dasInsUid);
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