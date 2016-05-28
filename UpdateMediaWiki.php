<?php

if ( function_exists( 'wfLoadExtension' ) ) {
	wfLoadExtension( 'UpdateMediaWiki' );
	// Keep i18n globals so mergeMessageFileList.php doesn't break
	$wgMessagesDirs['UpdateMediaWiki'] = __DIR__ . '/i18n';
	$wgExtensionMessagesFiles['updatemediawikiAlias'] = __DIR__ . '/updatemediawiki.alias.php';
	wfWarn(
		'Deprecated PHP entry point used for FooBar extension. ' .
		'Please use wfLoadExtension instead, ' .
		'see https://www.mediawiki.org/wiki/Extension_registration for more details.'
	);
	return;
} else {
	die( 'This version of the UpdateMediaWiki extension requires MediaWiki 1.25+' );
}
