<?php
/*********************************************************************
**
** This file is part of the UpdateMediaWiki extension for MediaWiki
* @file
* @ingroup Extensions
* @author Miguel PelÃ¡ez <miguel2706outlook.com>
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
* @link http://www.mediawiki.org/wiki/Extension:UpdateMediaWiki Documentation
**********************************************************************/

class SpecialUpdateMediaWiki extends SpecialPage {

	function __construct() {
		parent::__construct( 'UpdateMediaWiki', 'updatecore' );
	}

	function execute( $par ) {
		// ...
		if ( !$this->userCanExecute( $this->getUser() ) ) {
			$this->displayRestrictionError();
			return;
		}
		// ...
		$request = $this->getRequest();
		$output = $this->getOutput();
		$this->setHeaders();

		global $wgVersion, $IP;
		$getVersion = json_decode( file_get_contents( 'https://www.mediawiki.org/w/api.php?action=parse&format=json&text=%7B%7B%23invoke%3AVersion%7Cget%7Cstable%7Cversion%7D%7D&prop=text&disablelimitreport=1&disableeditsection=1&preview=1&disabletoc=1&contentmodel=wikitext' ), true ) or die ( 'ERROR' );
		$aV = $getVersion["parse"]["text"]["*"];
		$updated = false;
		$found = false;
		if ( !empty( $aV ) ) {
			//If we managed to access that file, then lets break up those release versions into an array.
			$output->addWikiText( "== " . $this->msg( 'updatemediawiki-current', $wgVersion )->text() . " ==" );
			$aV = str_replace( "<p>", "", $aV );
			$aV = str_replace( "</p>", "", $aV );
			$aV = str_replace( "\n", "", $aV );
			$mainVersion = substr( $aV, 0, 4 );
			if ( $aV > $wgVersion ) {
				$output->addWikiText( "<p>" . $this->msg( 'updatemediawiki-update-found', "v{$aV}" )->text() . "</p>" );
				$found = true;
				 //Download The File If We Do Not Have It
				if ( !is_file( __DIR__ . "/Updates/mediawiki-{$aV}.tar.gz" ) ) {
					$output->addWikiText( "<p>" . wfMessage( 'updatemediawiki-update-downloading' )->text() . "</p>" );
					$newUpdate = file_get_contents( "http://download.wikimedia.org/mediawiki/{$mainVersion}/mediawiki-{$aV}.tar.gz" );
					if ( !is_dir( __DIR__ . '/Updates' ) ) {
						mkdir( __DIR__ . "/Updates", 0755 );
					}
					$dlHandler = fopen( __DIR__ . "/Updates/mediawiki-{$aV}.tar.gz", 'w');
					if ( !fwrite( $dlHandler, $newUpdate ) ) {
						$output->addWikiText( "<p>" . $this->msg( 'updatemediawiki-update-aborted' )->text() . "</p>" );
						exit();
					}
					fclose( $dlHandler );
					$output->addWikiText( "<p>" . $this->msg( 'updatemediawiki-update-saved' )->text() . "</p>" );
				} else {
					$output->addWikiText( "<p>" . $this->msg( 'updatemediawiki-update-exist' )->text() . "</p>" );
				}

				if ( $par == "doUpdate" ) {
					//Open The File And Do Stuff
					$gz = new PharData( "/Updates/mediawiki-{$aV}.tar.gz" );
					$output->addWikiText( "<ul>" );

					//Make the directory if we need to...
					if ( !is_dir( "/Updates{$aV}" ) ) {
						mkdir( __DIR__ . "/Updates{$aV}", 0755 );
					}

					//Overwrite the file

					$gz = decompress(); // creates files.tar
					// unarchive from the tar
					$phar = new PharData( "Updates/mediawiki-{$aV}.tar" );
					$phar->extractTo( $IP );
					$output->addWikiText( "<p>" . $this->msg( 'updatemediawiki-update-ready' )->text() . " [$IP/mw-config " . wfMessage( 'updatemediawiki-update-database' )->text() . "]</p>" );

					$output->addWikiText( "</ul>" );
					$updated = true;
				} else {
					$output->addWikiText( "<p>" . $this->msg( 'updatemediawiki-update-updateready' )->text() . " [[Special:UpdateMediaWiki/doUpdate|" . $this->msg( 'updatemediawiki-update-install' )->text() . "]]</p>" );
				}
			}
		}

		if ( $updated === true ) {
			$output->addWikiText( '<p class="success">&raquo; ' . $this->msg( 'updatemediawiki-update-updated', $aV )->text() . '</p>' );
		} elseif ( $found !== true ) {
			$output->addWikiText( '<p>' . $this->msg( 'updatemediawiki-update-nofound' )->text() . '</p>' );
		} else {
			$output->addWikiText( '<p>' . $this->msg( 'updatemediawiki-update-error' )->text() . '</p>' );
		}
	}

	protected function getGroupName() {
		return 'wiki';
	}

}
