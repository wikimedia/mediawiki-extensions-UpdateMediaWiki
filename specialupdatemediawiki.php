<?
/*********************************************************************
**
** This file is part of the UpdateMediaWiki extension for MediaWiki
* @file
* @ingroup Extensions
* @author Miguel PelÃ¡ez <miguel2706outlook.com>
* @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
* @link http://www.mediawiki.org/wiki/Extension:UpdateMediaWiki Documentation
**********************************************************************/

class Specialupdatemediawiki extends SpecialPage {
        function __construct() {
                parent::__construct( $name = 'updatemediawiki', $restriction = 'updatecore' ); 
        }
 
        function execute( $par ) {
       
        // ...
        if (  !$this->userCanExecute( $this->getUser() )  ) {
                $this->displayRestrictionError();
                return;
        }
        // ...
                $request = $this->getRequest();
                $output = $this->getOutput();
                $this->setHeaders();
 
                $param = $request->getText( 'param' );
 
                global $wgVersion, $IP;
$getVersions = substr(file_get_contents('http://www.mediawiki.org/w/index.php?title=Template:MW_stable_release_number&action=raw'), 0, 6) or die ('ERROR');
if ($getVersions != '')
{
    //If we managed to access that file, then lets break up those release versions into an array.
    $output->addWikiText( "== ". wfMessage( 'updatemediawiki-current' )->text() ." ".$wgVersion." == " );
    $versionList = explode("\\n", $getVersions);
    $mainVersion = substr($getVersions, 0, 4);    
    foreach ($versionList as $aV)
    {
        if ( $aV > $wgVersion) {
            $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-found' )->text()."v".$aV."</p>" );
            $found = true;
             //Download The File If We Do Not Have It
            if ( !is_file( 'Updates/mediawiki-'.$aV.'.tar.gz' )) {
                $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-downloading' )->text()."</p>" );
                $newUpdate = file_get_contents('http://download.wikimedia.org/mediawiki/'.$mainVersion.'/mediawiki-'.$aV.'.tar.gz');
                if ( !is_dir( 'Updates/' ) ) mkdir ( 'Updates/' );
                $dlHandler = fopen('Updates/mediawiki-'.$aV.'.tar.gz', 'w');
                if ( !fwrite($dlHandler, $newUpdate) ) { $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-aborted' )->text()."</p>" ); exit(); }
                fclose($dlHandler);
                $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-saved' )->text()."</p>" );
            } else $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-exist' )->text()."</p>");    
           
            if ($par == "doUpdate") {
                //Open The File And Do Stuff
                $gz = new PharData('Updates/mediawiki-'.$aV.'.tar.gz');
                $output->addWikiText( "<ul>");
                
                //Make the directory if we need to...
                    if ( !is_dir ( "Updates/".$aV ) )
                    {
                         mkdir ( "Updates/".$aV );
                    }
                   
                    //Overwrite the file
                    
                    $gz = decompress(); // creates files.tar
                    // unarchive from the tar
                    $phar = new PharData('Updates/mediawiki-'.$aV.'.tar');
                    $phar->extractTo($IP);
                    $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-ready' )->text()." [".$IP."/mw-config ".wfMessage( 'updatemediawiki-update-database' )->text()."]</p>");
                    
                    
                     
                                           
                $output->addWikiText( "</ul>");
                $updated = TRUE;
            }
            else $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-updateready' )->text()." [[/doUpdate|".wfMessage( 'updatemediawiki-update-install' )->text()."]]</p>");
            break;
        }
    }
    
    if ($updated == true)
    {
        $output->addWikiText( "<p class=\"success\">&raquo; ".wfMessage( 'updatemediawiki-update-updated' )->text().$aV.'</p>');
    }
    else if ($found != true) $output->addWikiText( wfMessage( 'updatemediawiki-update-nofound' )->text());

    
}
else $output->addWikiText( "<p>".wfMessage( 'updatemediawiki-update-error' )->text().'</p>');


        }
}
