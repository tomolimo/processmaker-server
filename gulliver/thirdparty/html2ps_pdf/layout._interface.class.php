<?php
class LayoutEngine {
  function process(&$tree, &$media) {
    die("Oops. Inoverridden 'process' method called in ".is_object($this) && get_class($this));
  }
}
?>