<?php
$list = new \ProcessMaker\BusinessModel\Migrator\ExportObjects();
$objects = $list->objectList();
echo $objects;
