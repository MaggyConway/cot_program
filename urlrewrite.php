<?php
$arUrlRewrite=array (
  2 => array(
      "CONDITION"   =>   "#^/test",
      "RULE"   =>   "",
      "ID"   =>   "",
      "PATH"   =>   "/test.php",
   ),
  1 => 
  array (
    'CONDITION' => '#^/tests/results/#',
    'RULE' => '',
    'ID' => 'aelita:test.profile',
    'PATH' => '/tests/results/index.php',
    'SORT' => 100,
  ),
  0 => 
  array (
    'CONDITION' => '#^/rest/#',
    'RULE' => '',
    'ID' => NULL,
    'PATH' => '/bitrix/services/rest/index.php',
    'SORT' => 100,
  ),
);
