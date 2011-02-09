<?php
/*
 Plugin Name: rootsPersona
 Plugin URI: http://ed4becky.net/plugins/rootsPersona
 Description: Build one or more family history pages from a Gedcom file.
 Version: 1.3.2
 Author: Ed Thompson
 Author URI: http://ed4becky.net/
 License: GPLv2
 */

/*  Copyright 2010  Ed Thompson  (email : ed@ed4becky.org)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/personUtility.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/rootsPersonaInstaller.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/rootsOptionPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/rootsEditPage.php');

/**
 * First, make sure class exists
 */
if (!class_exists("rootsPersona")) {
    class rootsPersona {
        var $rootsPersonaVersion = '1.3.2';
        var $plugin_dir;
        var $utility;

        /**
         * Constructor
         */
        function __construct() {
            $this->plugin_dir = "wp-content/plugins/" . plugin_basename(dirname(__FILE__)) . "/";
            $this->utility = new personUtility();
        }

        /**
         * Entry point for the rootsPersona shortcode
         *
         * Displays a person represented in an XML file
         *
         * @param $atts
         * @param $content
         *
         * @return string formatted person page
         *
         * @example [rootsPersona personId='p392'/]
         */
        function rootsPersonaHandler( $atts, $content = null, $callback) {
            $rootsPersonId = $atts["personid"];

            if($this->isExcluded($rootsPersonId))
            	return $this->utility->returnDefaultEmpty('Privacy Protected.',$this->plugin_dir);

            $block = "rootsPersonaHandler: $rootsPersonId";
            if(isset($rootsPersonId)) {
                $block = $this->utility->buildPersonaPage($atts, $callback,
                								 site_url(),
                								 $this->getDataDir(),
                								 $this->plugin_dir,
                								 $this->getPageId());
            }
            return $block ;
        }

        function rootsPersonaIndexHandler( $atts, $content = null ) {
            $block = "";
            $block = $this->utility->buildPersonaIndexPage($atts,
                								 site_url(),
                								 $this->getDataDir(),
                								 $this->plugin_dir);
            return $block;
        }
        
        /**
         * Entry point for the rootsEditPersonaForm shortcode
         *
         * Presents the user with a page to add/edit the XML data for
         * a person when the page has already bene created.
         *
         * @return string prepopulated person form
         *
         * @example [rootsEditPersonaForm/]
         */
        function editPersonFormHandler() {
        	$isSystemOfRecord = get_option('rootsIsSystemOfRecord');
            if (!isset($_POST['submitPersonForm'])) {
                $id  = isset($_GET['personId'])  ? trim(esc_attr($_GET['personId']))  : '';

                if(!empty($id)) {
   	                $fileName = $this->getDataDir()  . $id . '.xml';
       	            if(file_exists($fileName)) {
           	            $xml_doc = new DomDocument;
               	        $xml_doc->load($fileName);
                   	    $p = $this->utility->paramsFromXML($xml_doc);

		                if(isset($_GET['srcPage']))
		            	{
		                   $p['srcPage'] = esc_attr($_GET['srcPage']);
		                   $page = get_post($p['srcPage']);
		                   $content = $page->post_content;
		                   for ($i= 1;$i <= 7; $i++) {
								$pf = 'picFile' . $i;

								if(preg_match("/$pf/", $content)) {
		                   			$p[$pf] = @preg_replace(
            							'/.*?' . $pf . '=[\'|"](.*)[\'|"].*?/US'
		                   				, '$1'
		                   				, $content);

		                   			$pc = 'picCap' . $i;
									if(preg_match("/$pc/", $content)) {
		                   				$p[$pc] = @preg_replace(
            							'/.*?' . $pc . '=[\'|"](.*)[\'|"].*?/US'
		                   				, '$1'
		                   				, $content);
									}
								}
		                   }
			            }
		    	        $p['action'] =  site_url() . '/?page_id=' . $this->getPageId();
		                $p['isSystemOfRecord'] = $isSystemOfRecord;
		                return showEditForm($p,site_url() . "/wp-content/plugins/rootspersona");
                	} else {
                		return "Missing file: $fileName";
                	}
   	            } else {
   	            	return "Missing required person ID.";
   	            }


            } else {
           		$p = $this->utility->paramsFromHTML($_POST);
           		$msg = '';
                if(strlen($p['personId']) < 1) $msg =  $msg .  "<br>Invalid Id.";
               	if(strlen($p['personName']) < 1) $msg = $msg . "<br>Name required.";
            	if($isSystemOfRecord == 'true') {
            		return "Wrong!";
//
//	                if(empty($msg)) {
//	                    $fileName = $this->getDataDir()  . $p['personId'] . '.xml';
//    	                $xml_doc = new DomDocument;
//        	            if(file_exists($fileName)) {
//            	            $xml_doc->load($fileName);
//                	    } else {
//                    	    $xml_doc->load($this->getDataDir() . 'templatePerson.xml');
//	                    }
//
//    	                $xml_doc = $this->utility->paramsToXML($xml_doc, $p);
//        	            $xml_doc->formatOutput = true;
//						$xml_doc->preserveWhiteSpace = false;
//                	    $xml_doc->save($fileName);
//                    	$p = $this->utility->paramsFromXML($xml_doc);
//
//	                    $msg = "<br>Saved.";
//    	            }
            	} else {
  					$my_post = array();
  					$my_post['ID'] = $p['srcPage'];
  					$content = "[rootsPersona personId='" . $p['personId'] . "'";
  					for ($i= 1;$i <= 7; $i++) {
						$pf = 'picFile' . $i;
						if(isset($p[$pf]) && !empty($p[$pf])) {
							$content = $content . ' ' . $pf . "='" . $p[$pf] . "'";
							$pc = 'picCap' . $i;
							if(isset($p[$pc]) && !empty($p[$pc])) {
								$content = $content . ' ' . $pc . "='" . $p[$pc] . "'";
							}
						}
  					}
  					$content = $content . "/]";
  					$my_post['post_content'] = $content;
  					wp_update_post( $my_post );
  					$location = site_url() . '/?page_id=' . $p['srcPage'];
  					// The wp_redirect command uses a PHP redirect at its core,
  					// therefore, it will not work either after header information
  					// has been defined for a page.
					return '<script type="text/javascript">window.location="' . $location . '"; </script>';
  					//$msg = $msg . "<br>Saved.";
            	}
                $p['action'] =  site_url() . '/?page_id=' . $this->getPageId();
                $p['isSystemOfRecord'] = $isSystemOfRecord;

                return showEditForm($p, site_url() . "/wp-content/plugins/roostpersona/", "<div class='truncate'>" . $msg . "</div>");
            }
        }

        /**
         * Entry point for the createPageForm shortcode
         *
         * Presents the user a selection list of unprocessed XML
         * records and creates a wordpress page for that person
         *
         * @return string prepopulated create page form
         *
         * @example [rootsAddPageForm/]
         */
        function addPageFormHandler() {
            $action =  site_url() . '/?page_id=' . $this->getPageId();
            $msg ='';
            if (isset($_POST['submitAddPageForm']))
            {
                $fileNames  = $_POST['fileNames'];

                if(!isset($fileNames) || count($fileNames) == 0) {
                	$msg = 'No files selected.';
                } else {
	                foreach($fileNames as $fileName) {
	                    $page = $this->utility->addPage($fileName, $this->getDataDir());
	                    if($page != false) $msg = $msg . "<br/>Page $page created for " . $page;
	                    else $msg = $msg . "<br/>Error creating page for" . $fileName;
	                }
                }
            }
            $files = $this->utility->getMissing($this->getDataDir());
            return $this->utility->showAddPageForm($action,$files,$this->getDataDir(),$msg);
        }

    	// shortcode [rootsUploadGedcomForm/]
		function uploadGedcomFormHandler() {
			if (!current_user_can('upload_files'))
				wp_die(__('You do not have permission to upload files.'));

			$action =  site_url() . '/?page_id=' . $this->getPageId();

			$msg ='';

			if (isset($_POST['submitUploadGedcomForm']))
			{
				if(!is_uploaded_file($_FILES['gedcomFile']['tmp_name']))
					$msg = 'Empty File.';
				else {
					$fileName = $_FILES['gedcomFile']['tmp_name'];
					$stageDir = $this->plugin_dir . "stage/";
					$this->utility->processGedcomForm($fileName, $stageDir, $this->getDataDir());
					unlink($_FILES['gedcomFile']['tmp_name']);

				}
			}
			if(empty($msg) && isset($_POST['submitUploadGedcomForm'])) {
				// The wp_redirect command uses a PHP redirect at its core,
  				// therefore, it will not work either after header information
  				// has been defined for a page.
				$location = site_url() . '/?page_id=' . get_option("rootsCreatePage");
				return '<script type="text/javascript">window.location="' . $location . '"; </script>';

			} else {
				return $this->utility->showUploadGedcomForm($action,$msg);
			}

		}

        /**
         * Called on behalf of the the_content filter hook
         * to check for a custom field called "permissions"
         * and insure a user is logged in if the field is set.
         *
         * @param $content page content retrieved from DB
         *
         * @return string reformatted (filtered) page content
         */
        function checkPermissions($content='') {

            $perms = get_post_meta($this->getPageId(), 'permissions', true);
            if ( !empty($perms) && !is_user_logged_in() ) {
                $content = "<br/>Content reserved for registered members.<br/>"
                . "<br/><div class='personBanner'><br/></div>";
            }
            return $content;
        }

        /**
         * Called on behalf of the wp_print_styles hook to dynamically
         * insert the style sheets the plugin needs
         *
         * @return void
         */
        function insertRootsPersonaStyles() {
            wp_register_style('rootsPersona-1', plugins_url('css/familyGroup.css',__FILE__), false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPersona-1');
            wp_register_style('rootsPersona-2', plugins_url('css/ancestors.css',__FILE__), false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPersona-2');
            wp_register_style('rootsPersona-3', plugins_url('css/person.css',__FILE__), false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPersona-3');            
            wp_register_style('rootsPersona-4', plugins_url('css/sortableTable.css',__FILE__), false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPersona-4');
        }        
        
        function insertRootsPersonaScripts() {
       		wp_register_script('sortable_us', plugins_url('scripts/sortable_us.js',__FILE__));
            wp_enqueue_script('sortable_us');
        }

        /**
         * Determines if a person is flagged as excluded
         *
         * @param $personId
         *
         * @return boolean true indicates the person is to be excluded
         */
        function isExcluded($personId) {
            $fileName= $this->getDataDir() . "idMap.xml";
            $mapDom = new DomDocument();
            $mapDom->load($fileName);
            $xpath = new DOMXPath($mapDom);
            $xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
            $nodeList = $xpath->query('/map:idMap/map:entry[@personId="' . $personId . '"]');
            $node = $nodeList->item(0);
            $isExcluded = $node->getAttribute('excludeLiving');
            return $isExcluded=='true'?true:false;
        }

        /**
         * Common method to get the current page id
         *
         * @return string ID of current page
         */
        function getPageId() {
            global $wp_query;
            $curr_page_id = $wp_query->get_queried_object_id();
            return $curr_page_id;
        }

        /**
         * Common method to retrieve the data directory
         *
         * @return string plugin directory containing person data files
         */
        function getDataDir() {
            return get_option("rootsDataDir");
        }

        /**
         * Install the plugin
         */
        function rootsPersonaActivate () {
			$installer = new rootsPersonaInstaller();
			$installer->rootsPersonaInstall(ABSPATH . $this->plugin_dir, 
											$this->rootsPersonaVersion);
        }
        
        function rootsPersonaUpgrade() {
        	$currentVersion = get_option('rootsPersonaVersion');
        	if(!isset($currentVersion) || empty($currentVersion)
        		|| $this->rootsPersonaVersion != $currentVersion) {
				$installer = new rootsPersonaInstaller();
				$installer->rootsPersonaUpgrade(ABSPATH . $this->plugin_dir,
												$this->rootsPersonaVersion);
        	}
        }
        
        /**
         * Uninstall (cleanup) the plugin
         */
        function rootsPersonaDeactivate() {
			$installer = new rootsPersonaInstaller();
			$installer->rootsPersonaUninstall();
        }
        
        function __toString() {
            return __CLASS__;
        }

    	function rootsPersonaOptionsPage() {
			add_options_page('rootsPersona Options',
						'rootsPersona',
						'manage_options',
						__FILE__,
						'buildRootsOptionsPage');
		}

		function rootsPersonaOptionsInit() {
			register_setting( 'rootsPersonaOptions', 'rootsPersonaParentPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsIsSystemOfRecord');
			register_setting( 'rootsPersonaOptions', 'rootsDataDir', 'wp_filter_nohtml_kses');
			register_setting( 'rootsPersonaOptions', 'rootsUploadGedcomPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsCreatePage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsEditPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsPersonaIndexPage', 'intval' );	
			register_setting( 'rootsPersonaOptions', 'rootsHideHeader');
			register_setting( 'rootsPersonaOptions', 'rootsHideFacts');
			register_setting( 'rootsPersonaOptions', 'rootsHideAncestors');
			register_setting( 'rootsPersonaOptions', 'rootsHideFamily');
			register_setting( 'rootsPersonaOptions', 'rootsHidePictures');	
			register_setting( 'rootsPersonaOptions', 'rootsPersonaHideDates');				
			register_setting( 'rootsPersonaOptions', 'rootsPersonaHidePlaces');				
		}		
    }
}

/**
 * Second, instantiate a reference to an instance of the class
 */
if (class_exists("rootsPersona")) {
    $rootsPersonaplugin = new  rootsPersona();
}

/**
 * Third, activate the plugin and any actions or filters
 */
if (isset($rootsPersonaplugin)) {
    register_activation_hook(__FILE__,array($rootsPersonaplugin, 'rootsPersonaActivate'));
    register_deactivation_hook(__FILE__, array($rootsPersonaplugin, 'rootsPersonaDeactivate') ) ;
	add_action('admin_init', array($rootsPersonaplugin, 'rootsPersonaUpgrade' ));
    add_action('admin_init', array($rootsPersonaplugin, 'rootsPersonaOptionsInit' ));
	
    add_shortcode('rootsPersona', array($rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsPersonaHeader', array($rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsPersonaFacts', array($rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsPersonaAncestors', array($rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsPersonaFamily', array($rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsPersonaPictures', array($rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsPersonaEvidence', array($rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsPersonaIndexPage', array($rootsPersonaplugin, 'rootsPersonaIndexHandler'));
    add_shortcode('rootsEditPersonaForm', array($rootsPersonaplugin, 'editPersonFormHandler'));
    add_shortcode('rootsAddPageForm', array($rootsPersonaplugin, 'addPageFormHandler'));
    add_shortcode('rootsUploadGedcomForm', array($rootsPersonaplugin, 'uploadGedcomFormHandler'));
    add_action('admin_menu', array($rootsPersonaplugin, 'rootsPersonaOptionsPage'));
    add_action('wp_print_styles', array($rootsPersonaplugin, 'insertRootsPersonaStyles'));
    add_action('wp_print_scripts', array($rootsPersonaplugin, 'insertRootsPersonaScripts'));
    add_filter( 'the_content', array($rootsPersonaplugin, 'checkPermissions'), 2 );
}
?>
