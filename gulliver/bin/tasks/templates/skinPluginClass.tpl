<?php
/**
 * class.{className}.php
 *  
 */

  class {className}Class extends PMPlugin  {

    function __construct (  ) {
      set_include_path(
        PATH_PLUGINS . '{className}' . PATH_SEPARATOR .
        get_include_path()
      );
    }

    function setup()
    {
    }

  }