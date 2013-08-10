<?

/*********************************************************************
**
** This file is part of the Update MediaWiki extension for MediaWiki
* @file
* @ingroup Extensions
* @author Miguel PelÃ¡ez <miguel2706outlook.com>
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
* @link http://www.mediawiki.org/wiki/Extension:Ads Documentation
**********************************************************************/

# Alert the user that this is not a valid access point to MediaWiki if they try to access the special pages file directly.
if ( !defined( 'MEDIAWIKI' ) ) {
        echo <<<EOT
To install updatemediawiki extension, put the following line in LocalSettings.php:
require_once( "\$IP/extensions/updatemediawiki/updatemediawiki.php" );
EOT;
        exit( 1 );
}

$wgExtensionCredits['validextensionclass'][] = array(
       'path' => __FILE__,
       'name' => 'Update MediaWiki',
       'author' =>'Miguel PelÃ¡ez', 
       'url' => 'https://www.mediawiki.org/wiki/Extension:UpdateMediaWiki', 
       'description' => 'Allows administrators and authorized users upgrade from a special page MediaWiki',
       'version'  => 0.1,
       );
$wgAutoloadClasses[ 'Specialupdatemediawiki' ] = __DIR__ . '/specialupdatemediawiki.php';
$wgExtensionMessagesFiles[ 'updatemediawiki' ] = __DIR__ . '/updatemediawiki.i18n.php';
$wgSpecialPages[ 'updatemediawiki' ] = 'Specialupdatemediawiki';
$wgExtensionAliasesFiles['updatemediawiki'] = dirname( __FILE__ ) . '/updatemediawiki.alias.php';
$wgSpecialPageGroups['updatemediawiki']='wiki';
