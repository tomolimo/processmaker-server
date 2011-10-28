<?php
/**
 * Dashborad controller
 * @inherits Controller
 * @access public
 */

class Dashboard extends Controller
{
  /**
   * getting default list
   * @param string $httpData->PRO_UID (opional)
   */
  public function index($httpData)
  {
    $this->includeExtJS('dashboard/index');
    $this->includeExtJSLib('ux/portal');
    //$this->setView('dashboard/index');

    //render content
    G::RenderPage('publish', 'extJs');
  }
}