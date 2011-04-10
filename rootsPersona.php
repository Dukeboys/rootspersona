<?php
/*
 Plugin Name: rootsPersona
 Plugin URI: http://ed4becky.net/plugins/rootsPersona
 Description: Build one or more family history pages from a Gedcom file.
 Version: 1.6.3
 Author: Ed Thompson
 Author URI: http://ed4becky.net/
 License: GPLv2
 */

/*  Copyright 2010-2011  Ed Thompson  (email : ed@ed4becky.org)

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
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/personUtility.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/rootsPersonaInstaller.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/rootsPersonaMender.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsOptionPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsToolsPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsEditPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsAddPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsIncludePage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsUploadPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsIndexPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/pages/rootsPersonaPage.php');
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/paramParser.php');

/**
 * First, make sure class exists
 */
if (!class_exists("rootsPersona")) {
	class rootsPersona {
		var $rootsPersonaVersion = '1.6.3';
		var $plugin_dir;
		var $data_dir;
		var $utility;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->plugin_dir = strtr(WP_PLUGIN_DIR,'\\','/') . "/rootspersona/";
			$this->data_dir = strtr(ABSPATH,'\\','/') . get_option("rootsDataDir");
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
			$block = "";
			if(isset($rootsPersonId)) {
				if($this->utility->isExcluded($rootsPersonId, $this->data_dir))
				return $this->utility->returnDefaultEmpty( __('Privacy Protected.', 'rootspersona'),plugins_url(),$this->plugin_dir);

				$block = buildPersonaPage($atts, $callback,
				site_url(),
				$this->data_dir,
				$this->plugin_dir,
				$this->getPageId());
			}
			return $block ;
		}

		function rootsIndexPageHandler( $atts, $content = null ) {
			$block = buildPersonaIndexPage($atts,
			site_url(),
			$this->data_dir,
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
		function rootsEditPersonaPageHandler() {
			if (!isset($_POST['submitPersonForm'])) {
				$id  = isset($_GET['personId'])  ? trim(esc_attr($_GET['personId']))  : '';
				$editAction = isset($_GET['action'])  ? trim(esc_attr($_GET['action']))  : '';
				$srcPage = isset($_GET['srcPage'])  ? trim(esc_attr($_GET['srcPage']))  : '';
				if(!empty($id)) {
					if($editAction == 'edit') {
						return $this->showEdit($id);
					} elseif($editAction == 'exclude') {
						$this->utility->updateExcluded($id, 'true', $this->data_dir );
						return $this->showPage($srcPage);
					} elseif($editAction == 'delete') {
						wp_delete_post($srcPage);
						$this->utility->deleteIdMapNode($id, $this->data_dir );
						unlink($this->data_dir  . $id . '.xml');
						return $this->showPage($srcPage);
					} elseif($editAction == 'makePrivate') {
						update_post_meta($srcPage, 'permissions', 'true');
						return $this->showPage($srcPage);
					} elseif($editAction == 'makePublic') {
						delete_post_meta($srcPage, 'permissions');
						return $this->showPage($srcPage);
					}
				} else {
					return  __('Missing', 'rootspersona') . " personId:". $id;
				}
			} else {
				return processEdit();
			}
		}

		function showPage($srcPage) {
			$location = site_url() . '/?page_id=' . $srcPage;
			// The wp_redirect command uses a PHP redirect at its core,
			// therefore, it will not work either after header information
			// has been defined for a page.
			return '<script type="text/javascript">window.location="' . $location . '"; </script>';
		}

		function showEdit($id) {
			$fileName = $this->data_dir  . $id . '.xml';
			if(file_exists($fileName)) {
				$xml_doc = new DomDocument;
				$xml_doc->load($fileName);
				$p = paramsFromXML($xml_doc);
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
				$p['isSystemOfRecord'] = get_option('rootsIsSystemOfRecord');
			} else {
				return  __('Missing file:', 'rootspersona') . $fileName;
			}
			return showEditForm($p,plugins_url() . "rootspersona/");
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
		function rootsAddPageHandler() {
			$action =  site_url() . '/?page_id=' . $this->getPageId();
			$msg ='';
			if (isset($_POST['submitAddPageForm']))
			{
				$fileNames  = $_POST['fileNames'];

				if(!isset($fileNames) || count($fileNames) == 0) {
					$msg = __('No files selected.', 'rootspersona');
				} else {
					$dataDir = $this->data_dir;
					foreach($fileNames as $fileName) {
						$page = $this->utility->addPage($fileName, $dataDir);
						if($page != false) $msg = $msg . "<br/>" . __('Page created for', 'rootspersona') . " " .$fileName;
						else $msg = $msg . "<br/>" . __('Error creating page for', 'rootspersona') . " " .$fileName;
						set_time_limit(60);
					}
				}
			} /*else if (isset($_POST['submitExcPageForm']))
			{
			$fileNames  = $_POST['fileNames'];

			if(!isset($fileNames) || count($fileNames) == 0) {
			$msg = __('No persons selected.', 'rootspersona');
			} else {

			$dataDir = $this->data_dir;
			foreach($fileNames as $fileName) {
			$pid = substr($fileName,0,-4);
			$this->utility->updateExcluded($pid, "ture", $dataDir);
			$msg = $msg . "<br/>" . sprintf(__('Person %s excluded.', 'rootspersona'),$pid);
			}
			}
			}*/
			$files = $this->utility->getMissing($this->data_dir);
			return showAddPageForm($action,$files,$this->data_dir,$msg);
		}

		function rootsIncludePageHandler() {
			$action =  site_url() . '/?page_id=' . $this->getPageId();
			$msg ='';
			$dataDir = $this->data_dir;
			if (isset($_POST['submitIncludePageForm']))
			{
				$persons  = $_POST['persons'];

				if(!isset($persons) || count($persons) == 0) {
					$msg = __('No people selected.', 'rootspersona');
				} else {

					foreach($persons as $person) {
						$this->utility->updateExcluded($person, 'false', $dataDir);
					}
				}
			}
			$persons = $this->utility->getExcluded($dataDir);
			return showIncludePageForm($action,$persons,$msg);
		}

		function rootsUtilityPageHandler() {
			$action =  site_url() . '/?page_id=' . $this->getPageId();
			$msg ='';
			$dataDir = $this->data_dir;
			if (isset($_GET['utilityAction'])) {
				$action  = $_GET['utilityAction'];

				if($action == 'validate') {
					$mender = new rootsPersonaMender();
					return $mender->validateMap($dataDir, false);
				} else if($action == 'repair') {
					$mender = new rootsPersonaMender();
					return $mender->validateMap($dataDir, true);
				} else if($action == 'validatePages') {
					$mender = new rootsPersonaMender();
					return $mender->validatePages($dataDir, false);
				}else if($action == 'repairPages') {
					$mender = new rootsPersonaMender();
					return $mender->validatePages($dataDir, true);
				} else if($action == 'validateEvidencePages') {
					$mender = new rootsPersonaMender();
					return $mender->validateEvidencePages($dataDir, false);
				} else if($action == 'repairEvidencePages') {
					$mender = new rootsPersonaMender();
					return $mender->validateEvidencePages($dataDir, true);
				} else if($action == 'delete') {
					$mender = new rootsPersonaMender();
					return $mender->deletePages($this->plugin_dir, $dataDir);
				} else if($action == 'deleteFiles') {
					$mender = new rootsPersonaMender();
					return $mender->deleteFiles($this->plugin_dir, $dataDir);
				}
			}
			return 'For internal use only.<br/>';
		}

		function rootsEvidencePageHandler($atts, $content = null, $callback) {
			$block = null;
			if(isset($atts['sourceid'])) {
				$xp = new XsltProcessor();
				// create a DOM document and load the XSL stylesheet
				$xsl = new DomDocument;
				if(isset($atts["xsl"]))
				$xslFile = $atts["xsl"];
				if(!isset($xslFile) || $xslFile == '')
				$xslFile = $this->plugin_dir . 'xsl/evidencePage.xsl';
				if($xsl->load($xslFile) === false) {
					throw new Exception("Unable to load " . $xslFile);
				}

				// import the XSL stylesheet into the XSLT process
				$xp->importStylesheet($xsl);
				$xp->setParameter('','site_url',site_url());
				$xp->setParameter('','data_dir',$this->data_dir);
				$xp->setParameter('','sid',$atts['sourceid']);

				// create a DOM document and load the XML data
				$xml_doc = new DomDocument;
				$fileName =  $this->data_dir . '/evidence.xml';
				try {
					if($xml_doc->load($fileName) === false)
					{
						throw new Exception('Unable to load ' . $fileName);
					}
					// transform the XML into HTML using the XSL file
					if ((($html = $xp->transformToXML($xml_doc)) !== false)
					|| empty($html)) {
						$block = $html;
					} else {
						$block = '';
					}
				} catch (Exception $e) {
					$block = $this->returnDefaultEmpty(__('No Information available.', 'rootspersona'),$mysite,$pluginDir);
				}
			} else {
				$xp = new XsltProcessor();
				// create a DOM document and load the XSL stylesheet
				$xsl = new DomDocument;
				if(isset($atts["xsl"]))
				$xslFile = $atts["xsl"];
				if(!isset($xslFile) || $xslFile == '')
				$xslFile = $this->plugin_dir . 'xsl/evidenceIndexPage.xsl';
				if($xsl->load($xslFile) === false) {
					throw new Exception("Unable to load " . $xslFile);
				}

				// import the XSL stylesheet into the XSLT process
				$xp->importStylesheet($xsl);
				$xp->setParameter('','site_url',site_url());
				$xp->setParameter('','data_dir',$this->data_dir);

				// create a DOM document and load the XML data
				$xml_doc = new DomDocument;
				$fileName =  $this->data_dir . '/evidence.xml';
				try {
					if($xml_doc->load($fileName) === false)
					{
						throw new Exception('Unable to load ' . $fileName);
					}
					// transform the XML into HTML using the XSL file
					if ((($html = $xp->transformToXML($xml_doc)) !== false)
					|| empty($html)) {
						$block = $html;
					} else {
						$block = '';
					}
				} catch (Exception $e) {
					$block = $this->returnDefaultEmpty(__('No Information available.', 'rootspersona'),$mysite,$pluginDir);
				}
			}
			return $block;
		}

		// shortcode [rootsUploadGedcomForm/]
		function rootsUploadGedcomHandler() {
			if (!current_user_can('upload_files'))
			wp_die(__('You do not have permission to upload files.', 'rootspersona'));

			$action =  site_url() . '/?page_id=' . $this->getPageId();

			$msg ='';

			if (isset($_POST['submitUploadGedcomForm']))
			{
				if(!is_uploaded_file($_FILES['gedcomFile']['tmp_name'])) {
					$msg =  __('Empty File.', 'rootspersona');
				}
				else {
					$fileName = $_FILES['gedcomFile']['tmp_name'];
					$stageDir = $this->plugin_dir . "stage/";
					$this->utility->processGedcomForm($fileName, $stageDir, $this->data_dir);
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
				return showUploadGedcomForm($action,$this->data_dir,$this->plugin_dir . "stage/",$msg);
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
			if ( !empty($perms) && $perms == 'true' && !is_user_logged_in() ) {
				$content = "<br/>" . __('Content reserved for registered members.', 'rootspersona') ."<br/>"
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
			wp_register_script('rootsUtilities', plugins_url('scripts/rootsUtilities.js',__FILE__));
			wp_enqueue_script('rootsUtilities');
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
		 * Install the plugin
		 */
		function rootsPersonaActivate () {
			$installer = new rootsPersonaInstaller();
			$installer->rootsPersonaInstall($this->plugin_dir,
			$this->rootsPersonaVersion);
		}

		function rootsPersonaUpgrade() {
			$currentVersion = get_option('rootsPersonaVersion');
			if(!isset($currentVersion) || empty($currentVersion)
			|| $this->rootsPersonaVersion != $currentVersion) {
				$installer = new rootsPersonaInstaller();
				$installer->rootsPersonaUpgrade($this->plugin_dir,
				$this->rootsPersonaVersion,
				$currentVersion);
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

		function rootsPersonaMenus() {
			add_options_page('rootsPersona Options',
						'rootsPersona',
						'manage_options',
						'rootsPersona',
						'buildRootsOptionsPage');

			$page = add_submenu_page( 'tools.php',
								'rootsPersona Tools',
								'rootsPersona',
								'manage_options',
								'rootsPersona',
								'buildRootsToolsPage');
			/* Using registered $page handle to hook stylesheet loading */
			add_action( 'admin_print_styles-' . $page, array($this,'insertRootsPersonaStyles') );

		}

		function rootsPersonaOptionsInit() {
			register_setting( 'rootsPersonaOptions', 'rootsPersonaParentPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsIsSystemOfRecord');
			register_setting( 'rootsPersonaOptions', 'rootsDataDir', 'wp_filter_nohtml_kses');
			register_setting( 'rootsPersonaOptions', 'rootsUploadGedcomPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsCreatePage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsEditPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsIncludePage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsPersonaIndexPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsUtilityPage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsEvidencePage', 'intval' );
			register_setting( 'rootsPersonaOptions', 'rootsHideHeader');
			register_setting( 'rootsPersonaOptions', 'rootsHideFacts');
			register_setting( 'rootsPersonaOptions', 'rootsHideAncestors');
			register_setting( 'rootsPersonaOptions', 'rootsHideFamilyC');
			register_setting( 'rootsPersonaOptions', 'rootsHideFamilyS');
			register_setting( 'rootsPersonaOptions', 'rootsHideEvidence');
			register_setting( 'rootsPersonaOptions', 'rootsHidePictures');
			register_setting( 'rootsPersonaOptions', 'rootsHideEditLinks');
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
	add_shortcode('rootsPersonaFamilyC', array($rootsPersonaplugin, 'rootsPersonaHandler'));
	add_shortcode('rootsPersonaFamilyS', array($rootsPersonaplugin, 'rootsPersonaHandler'));
	add_shortcode('rootsPersonaPictures', array($rootsPersonaplugin, 'rootsPersonaHandler'));
	add_shortcode('rootsPersonaEvidence', array($rootsPersonaplugin, 'rootsPersonaHandler'));
	add_shortcode('rootsPersonaIndexPage', array($rootsPersonaplugin, 'rootsIndexPageHandler'));
	add_shortcode('rootsEditPersonaForm', array($rootsPersonaplugin, 'rootsEditPersonaPageHandler'));
	add_shortcode('rootsEvidencePage', array($rootsPersonaplugin, 'rootsEvidencePageHandler'));
	add_shortcode('rootsAddPageForm', array($rootsPersonaplugin, 'rootsAddPageHandler'));
	add_shortcode('rootsUploadGedcomForm', array($rootsPersonaplugin, 'rootsUploadGedcomHandler'));
	add_shortcode('rootsIncludePageForm', array($rootsPersonaplugin, 'rootsIncludePageHandler'));
	add_shortcode('rootsUtilityPage', array($rootsPersonaplugin, 'rootsUtilityPageHandler'));
	add_action('admin_menu', array($rootsPersonaplugin, 'rootsPersonaMenus'));
	add_action('wp_print_styles', array($rootsPersonaplugin, 'insertRootsPersonaStyles'));
	add_action('wp_print_scripts', array($rootsPersonaplugin, 'insertRootsPersonaScripts'));
	add_filter( 'the_content', array($rootsPersonaplugin, 'checkPermissions'), 2 );
	load_plugin_textdomain('rootspersona', null, "rootspersona/localization/");
}
?>
