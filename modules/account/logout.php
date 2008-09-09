<?php
if (!defined('FLUX_ROOT')) exit;

$session->logout();
$metaRefresh = array('seconds' => 2, 'location' => $this->basePath);
?>