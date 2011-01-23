<?php
/*
 Plugin Name: rootsPersona
 Plugin URI: http://ed4becky.net/plugins/rootsPersona
 Description: Build one or more family history pages from a Gedcom file.
 Version: 1.0.4
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
//require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/personUtility.php');


/**
 * First, make sure class exists
 */
if (!class_exists("rootsPersona")) {
    class rootsPersona {
        var $rootsPersonaVersion = '1.0.4';
        var $plugin_dir;
        var $utility;

        /**
         * Constructor
         */
        function __construct() {
            $this->plugin_dir = WP_PLUGIN_DIR . "/" . plugin_basename(dirname(__FILE__)) . "/";
            $this->utility = new PersonUtility();
        }

        // PLUGIN SHORTCODE HANDLERS

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
        function rootsPersonaHandler( $atts, $content = null ) {
            $rootsPersonId = $atts["personid"];

            if($this->isExcluded($rootsPersonId))
            	return $this->utility->returnDefaultEmpty('Privacy Protected.',$this->plugin_dir);

            $block = "rootsPersonaHandler: $rootsPersonId";
            $mysite = get_option('siteurl');
            if(isset($rootsPersonId)) {
                $block = $this->utility->buildPersonaPage($atts,
                								 $mysite,
                								 $this->getDataDir(),
                								 $this->plugin_dir,
                								 $this->getPageId());
            }
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
		    	        $p['action'] =  get_option('siteurl') . '/?page_id=' . $this->getPageId();
		                $p['isSystemOfRecord'] = $isSystemOfRecord;
		                return $this->utility->showForm($p,$this->plugin_dir);
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
  					$location = get_option('siteurl') . '/?page_id=' . $p['srcPage'];
  					// The wp_redirect command uses a PHP redirect at its core,
  					// therefore, it will not work either after header information
  					// has been defined for a page.
					return '<script type="text/javascript">window.location="' . $location . '"; </script>';
  					//$msg = $msg . "<br>Saved.";
            	}
                $p['action'] =  get_option('siteurl') . '/?page_id=' . $this->getPageId();
                $p['isSystemOfRecord'] = $isSystemOfRecord;

                return $this->utility->showForm($p, $this->plugin_dir, "<div class='truncate'>" . $msg . "</div>");
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
            $action =  get_option('siteurl') . '/?page_id=' . $this->getPageId();
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

			$action =  get_option('siteurl') . '/?page_id=' . $this->getPageId();

			$msg ='';

			if (isset($_POST['submitUploadGedcomForm']))
			{
				if(!is_uploaded_file($_FILES['gedcomFile']['tmp_name']))
					$msg = 'Empty File.';
				else {
					$fileName = $_FILES['gedcomFile']['tmp_name'];
					$stageDir = $this->plugin_dir . "/stage/";
					$this->utility->processGedcomForm($fileName, $stageDir, $this->getDataDir());
					//$msg = 'processing ' . $_FILES['gedcomFile']['name'] . ' Complete.';
					unlink($_FILES['gedcomFile']['tmp_name']);
				}
			}
			if(empty($msg) && isset($_POST['submitUploadGedcomForm'])) {
				// The wp_redirect command uses a PHP redirect at its core,
  				// therefore, it will not work either after header information
  				// has been defined for a page.
				$location = get_option('siteurl') . '/?page_id=' . get_option("rootsCreatePage");
				return '<script type="text/javascript">window.location="' . $location . '"; </script>';

			} else {
				return $this->utility->showUploadGedcomForm($action,$msg);
			}

		}

        // PLUGIN FILTERS

        /**
         * Called on behalf of the the_content filter hook
         * to check for a custom filed called "permissions"
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

        //PLUGIN ACTIONS

        /**
         * Called on behalf of the wp_print_styles hook to dynamically
         * insert the style sheets the plugin needs
         *
         * @return void
         */
        function insertRootsPersonaStyles() {
            $style_url = "/" . $this->plugin_dir . "/css/";

            wp_register_style('rootsPersona-1', $style_url . 'familyGroup.css', false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPersona-1');
            wp_register_style('rootsPersona-2', $style_url . 'ancestors.css', false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPersona-2');
            wp_register_style('rootsPersona-3', $style_url . 'person.css', false, '1.0', 'screen');
            wp_enqueue_style( 'rootsPersona-3');
        }

        // PLUGIN HELPERS

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

		// PLUGIN REGISTRATION

        /**
         * Install the plugin
         */
        function rootsPersonaInstall () {
        	$currVersion = get_option('rootsPersonaVersion');
        	if(!isset($currVersion) || empty($currVersion)) {
            	add_option('rootsPersonaVersion', $this->rootsPersonaVersion);
            	//mkdir(WP_CONTENT_DIR ."/rootsData/",0777);
            	$this->recurse_copy($this->plugin_dir . "/rootsData/", WP_CONTENT_DIR ."/rootsData/");
            	add_option('rootsDataDir', "wp-content/rootsData/");
            	$page = $this->addEditPage();
            	add_option('rootsEditPage', $page);
            	$page = $this->addAddPage();
            	add_option('rootsCreatePage', $page);
            	$page = $this->addUploadPage();
            	add_option('rootsUploadGedcomPage', $page);
            	add_option('rootsPersonaParentPage', "0");
            	add_option('rootsIsSystemOfRecord', 'false');
        	} else {
        		if ($currVersion != $this->rootsPersonaVersion)
        		{
        			update_option('rootsPersonaVersion', $this->rootsPersonaVersion);
        		}

        		$opt = get_option('rootsDataDir');
        		if(!isset($opt) || empty($opt)) {
        			//mkdir(WP_PLUGIN_DIR ."/rootsData/",0777);
            		$this->recurse_copy($this->plugin_dir . "/rootsData/", WP_CONTENT_DIR ."/rootsData/");
            		add_option('rootsDataDir', "wp-content/rootsData/");
        		}

        	    $page = get_option('rootsEditPage');
        		if(!isset($page) || empty($page)) {
            		$page = $this->addEditPage();
            		add_option('rootsEditPage', $page);
        		} else {
        			$this->updateEditPage($page);
        		}

        		unset($page);
        	    $page = get_option('rootsCreatePage');
        		if(!isset($page) || empty($page)) {
            		$page = $this->addAddPage();
            		add_option('rootsCreatePage', $page);
        		} else {
        			$this->updateAddPage($page);
        		}

        	    unset($page);
        	    $page = get_option('rootsUploadGedcomPage');
        		if(!isset($page) || empty($page)) {
            		$page = $this->addUploadPage();
            		add_option('rootsUploadGedcomPage', $page);
        		} else {
        			$this->updateUploadPage($page);
        		}

        		unset($opt);
        		$opt = get_option('rootsPersonaParentPage');
        		if (!isset($opt) || empty($opt))
        		   add_option('rootsPersonaParentPage', "0");

        		unset($opt);
        		$opt = get_option('rootsIsSystemOfRecord');
        		if (!isset($opt) || empty($opt))
        		   add_option('rootsIsSystemOfRecord', 'false');
        	}
        }

        function addUploadPage() {
        	// Create post object
            $my_post = array();
            $my_post['post_title'] = 'Upload gedcom File';
            $my_post['post_content'] = "[rootsUploadGedcomForm/]";
            $my_post['post_status'] = 'private';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            return $pageID;
        }

        function updateUploadPage($page) {
        	// Create post object
            $my_post = array();
            $my_post['ID'] = $page;
            $my_post['post_title'] = 'Upload gedcom File';
            $my_post['post_content'] = "[rootsUploadGedcomForm/]";
            $my_post['post_status'] = 'private';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            wp_update_post( $my_post );
        }

        function addAddPage() {
        	// Create post object
            $my_post = array();
            $my_post['post_title'] = 'Add Person';
            $my_post['post_content'] = "[rootsAddPageForm/]";
            $my_post['post_status'] = 'private';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            return $pageID;
        }

        function updateAddPage($page) {
        	// Create post object
            $my_post = array();
            $my_post['ID'] = $page;
            $my_post['post_title'] = 'Add Person';
            $my_post['post_content'] = "[rootsAddPageForm/]";
            $my_post['post_status'] = 'private';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            wp_update_post( $my_post );
        }

        function addEditPage() {
        	// Create post object
            $my_post = array();
            $my_post['post_title'] = 'Edit Person';
            $my_post['post_content'] = "[rootsEditPersonaForm/]";
            $my_post['post_status'] = 'private';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            return $pageID;
        }

        function updateEditPage($page) {
        	// Create post object
            $my_post = array();
            $my_post['ID'] = $page;
            $my_post['post_title'] = 'Edit Person';
            $my_post['post_content'] = "[rootsEditPersonaForm/]";
            $my_post['post_status'] = 'private';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = 'closed';
            $my_post['comment_status'] = 'closed';
            $my_post['post_parent'] = 0;

            // Insert the post into the database
            wp_update_post( $my_post );
        }

        /**
         * Uninstall (cleanup) the plugin
         */
        function rootsPersonaUninstall() {
            delete_option('rootsPersonaVersion');
            delete_option('rootsDataDir');
            $page = get_option('rootsEditPage');
            wp_delete_post($page);
            delete_option('rootsEditPage');
            $page = get_option('rootsCreatePage');
            wp_delete_post($page);
            delete_option('rootsCreatePage');
            $page = get_option('rootsUploadGedcomPage');
            wp_delete_post($page);
            delete_option('rootsUploadGedcomPage');
            delete_option('rootsPersonaParentPage');
            
            delete_option('rootsIsSystemOfRecord');
            remove_action('admin_menu', 'rootsPersonaOptionsPage');
        }
        
		function recurse_copy($src,$dst) {
    		$dir = opendir($src);
	    	@mkdir($dst);
    		while(false !== ( $file = readdir($dir)) ) {
    	    	if (( $file != '.' ) && ( $file != '..' )) {
            		if ( is_dir($src . '/' . $file) ) {
            	    	$this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
	    	        }
  		  	        else {
        	    	    copy($src . '/' . $file,$dst . '/' . $file);
            		}
    	    	}
 	   		}
    		closedir($dir);
		} 

        function __toString() {
            return __CLASS__;
        }

    	function rootsPersonaOptionsPage() {
			add_options_page('rootsPersona Options',
						'rootsPersona',
						'manage_options',
						__FILE__,
						array(&$this, 'buildRootsOptionsPage'));
		}

    	function buildRootsOptionsPage() {
			$block = "<html><head></head><body>";
			$block = $block . "<div class='wrap'><h2>rootsPersona</h2>";
			$block = $block . "<form method='post' action='options.php'>";
			$block = $block . wp_nonce_field('update-options');

			$block = $block . "<table class='form-table'>";

			$block = $block . "<tr valign='top'>";
			$block = $block . "<th scope='row'><label for='rootsPersonaVersion'>rootsPersona Version</label></th>";
			$block = $block . "<td><label class='regular-text' name='rootsPersonaVersion' id='rootsPersonaVersion'>";
			$block = $block . get_option('rootsPersonaVersion'). "</label></td>";
			$block = $block . "<td></td></tr>";

			$block = $block . "<tr valign='top'>";
			$block = $block . "<th scope='row'><label for='rootsPersonaParentPage'>rootsPersona Parent Page Id</label></th>";
			$block = $block . "<td><input type='text' size='5' name='rootsPersonaParentPage' id='rootsPersonaParentPage'";
			$block = $block . " value='" . get_option('rootsPersonaParentPage'). "' /></td>";
			$block = $block . "<td>Indicate the page you want persona pages to be organized under in a menu structure.  0 indicates no parent page.</td></tr>";

			$block = $block . "<tr valign='top'>";
			$block = $block . "<th scope='row'><label for='rootsDataDir'>rootsPersona Data Directory</label></th>";
			$block = $block . "<td><input type='text' size='25' name='rootsDataDir' id='rootsDataDir'";
			$block = $block . " value='" . get_option('rootsDataDir'). "' /></td>";
			$block = $block . "<td>Directory under the plugin where data files are stored. There is usually no need to change this.</td></tr>";

			$block = $block . "<tr valign='top'>";
			$block = $block . "<th scope='row'><label for='rootsEditPage'>Edit Person Page Id</label></th>";
			$block = $block . "<td><input type='text' size='5' name='rootsEditPage' id='rootsEditPage'";
			$block = $block . " value='" . get_option('rootsEditPage'). "' /></td>";
			$block = $block . "<td>Indicates the page with the  Edit Page shortcode. There is usually no need to change this.</td></tr>";

			$block = $block . "<tr valign='top'>";
			$block = $block . "<th scope='row'><label for='rootsCreatePage'>Add Person Page Id</label></th>";
			$block = $block . "<td><input type='text' size='5' name='rootsCreatePage' id='rootsCreatePage'";
			$block = $block . " value='" . get_option('rootsCreatePage'). "' /></td>";
			$block = $block . "<td>Indicates the page with the  Add Page shortcode. There is usually no need to change this.</td></tr>";

			$block = $block . "<tr valign='top'>";
			$block = $block . "<th scope='row'><label for='rootsUploadGedcomPage'>Upload Gedcom Page Id</label></th>";
			$block = $block . "<td><input type='text' size='5' name='rootsUploadGedcomPage' id='rootsUploadGedcomPage'";
			$block = $block . " value='" . get_option('rootsUploadGedcomPage'). "' /></td>";
			$block = $block . "<td>Indicates the page with the  Upload gedcom shortcode. There is usually no need to change this.</td></tr>";

			$block = $block . "<tr valign='top'>";
			$block = $block . "<th scope='row'><label for='rootsIsSystemOfRecord'>Is this the System Of Record?</label></th>";
			$block = $block . "<td><input type='text' size='5' name='rootsIsSystemOfRecord' id='rootsIsSystemOfRecord'";
			$block = $block . " value='" . get_option('rootsIsSystemOfRecord'). "' /></td>";
			$block = $block . "<td>true|false.  Only false is supporterd at this time (meaning some external program is the system of record).</td></tr>";

			$block = $block . "</table>";
			$block = $block . "<input type='hidden' name='action' value='update' />";
			$block = $block . "<input type='hidden' name='page_options' value='rootsPersonaParentPage,rootsIsSystemOfRecord,rootsDataDir,rootsUploadGedcomPage,rootsCreatePage,rootsEditPage' />";
			$block = $block . "<p class='submit'>";
			$block = $block . "<input type='submit' name='Submit' value='Save Changes'/>";
			$block = $block . "</p></form></div></body></html>";
			echo $block;
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
    register_activation_hook(__FILE__,array(&$rootsPersonaplugin, 'rootsPersonaInstall'));
    register_deactivation_hook(__FILE__, array(&$rootsPersonaplugin, 'rootsPersonaUninstall') ) ;

    add_shortcode('rootsPersona', array(&$rootsPersonaplugin, 'rootsPersonaHandler'));
    add_shortcode('rootsEditPersonaForm', array(&$rootsPersonaplugin, 'editPersonFormHandler'));
    add_shortcode('rootsAddPageForm', array(&$rootsPersonaplugin, 'addPageFormHandler'));
    add_shortcode('rootsUploadGedcomForm', array(&$rootsPersonaplugin, 'uploadGedcomFormHandler'));
    add_action('admin_menu', array(&$rootsPersonaplugin, 'rootsPersonaOptionsPage'));
    add_action('wp_print_styles', array(&$rootsPersonaplugin, 'insertRootsPersonaStyles'));
    add_filter( 'the_content', array(&$rootsPersonaplugin, 'checkPermissions'), 2 );
}
?>
