<?php

// Check if we are a user
OCP\User::checkLoggedIn();

/*$somesetting = OCP\Config::getSystemValue( "somesetting", '' );
OCP\App::setActiveNavigationEntry( 'apptemplate' );
$tmpl = new OCP\Template( 'apptemplate', 'main', 'user' );
$tmpl->assign( 'somesetting', $somesetting );
$tmpl->printPage();
*/