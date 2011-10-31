<?php
/**
 * Dashboard controller
 * @inherits Controller
 * @access public
 */

class Dashboard extends Controller {

  // Functions for the dashboards users module - Start

  public function index($httpData) {
    $this->includeExtJS('dashboard/index');
    $this->includeExtJSLib('ux/portal');
    G::RenderPage('publish', 'extJs');
  }

  public function renderDashletInstance($dasInsUid) {
    //
  }

  // Functions for the dashboards users module - End

  // Functions for the dasboards administration module - Start

  public function dashletsList() {
    $headPublisher =& headPublisher::getSingleton();
    $headPublisher->addExtJsScript('dashboard/dashletsList', false);
    $headPublisher->addContent('dashboard/dashletsList');
    G::RenderPage('publish', 'extJs');
  }

  public function getDashletsInstances() {
    //
  }

  public function dashletInstanceForm($dasInsUid) {
    $headPublisher =& headPublisher::getSingleton();
    $headPublisher->addExtJsScript('dashboard/dashletInstanceForm', false);
    $headPublisher->addContent('dashboard/dashletInstanceForm');
    if ($dasInsUid != '') {
      // load data before render the form
    }
    G::RenderPage('publish', 'extJs');
  }

  public function saveDashletInstance($data) {
    //
  }

  public function deleteDashletInstance($dasInsUid) {
    //
  }

  public function getOwnersByType($type) {
    //
  }

  // Functions for the dasboards administration module - End

}