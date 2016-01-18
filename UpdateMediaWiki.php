<?php

/*********************************************************************
**
** This file is part of the UpdateMediaWiki extension for MediaWiki
* @file
* @ingroup Extensions
* @author Miguel Peláez <miguel2706outlook.com>
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
* @link http://www.mediawiki.org/wiki/Extension:UpdateMediaWiki Documentation
**********************************************************************/

# Alert the user that this is not a valid access point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
		echo <<<EOT
To install updatemediawiki extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/updatemediawiki/updatemediawiki.php" );
EOT;
	exit( 1 );
}

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'UpdateMediaWiki',
	'author' =>'Miguel Peláez',
	'url' => 'https://www.mediawiki.org/wiki/Extension:UpdateMediaWiki',
	'descriptionmsg' => 'updatemediawiki-desc',
	'version'  => '0.3.0',
);

$wgAutoloadClasses[ 'Specialupdatemediawiki' ] = __DIR__ . '/specialupdatemediawiki.php';
$wgMessagesDirs['UpdateMediaWiki'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles[ 'updatemediawiki' ] = __DIR__ . '/updatemediawiki.i18n.php';
$wgSpecialPages[ 'updatemediawiki' ] = 'Specialupdatemediawiki';
$wgExtensionMessagesFiles['updatemediawikiAlias'] = __DIR__ . '/updatemediawiki.alias.php';
