<?php
/**
 * Dashboard controller
 * @inherits Controller
 * @access public
 */

class Dashboard extends Controller {
  /**
   * getting default list
   * @param object $httpData
   */
  public function index($httpData) {
    $this->includeExtJS('dashboard/index');
    $this->includeExtJSLib('ux/portal');
    G::RenderPage('publish', 'extJs');
  }

  public function dashletsList() {
    echo 'dashletsList';
    G::RenderPage('publish', 'extJs');
  }

}