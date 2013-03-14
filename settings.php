<?php

OC_Util::checkAdminUser();

OCP\Util::addScript('rondin', 'settings');
OCP\Util::addStyle('rondin', 'settings');

$tmpl = new OCP\Template('rondin', 'settings');
$tmpl->assign('handlers', RondinConfig::getHandlers());
return $tmpl->fetchPage();
