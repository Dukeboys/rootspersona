<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class rootsPersonaInstaller {
	function rootsPersonaInstall ($pluginDir, $version) {
		add_option('rootsPersonaVersion', $version);
		$utility = new PersonUtility();
		$utility->createDataDir($pluginDir, WP_CONTENT_DIR ."/rootsPersonaData/");

		add_option('rootsDataDir', "wp-content/rootsPersonaData/");
			
		$page = $this->createPage(__('Edit Person Page', 'rootspersona'),'[rootsEditPersonaForm/]');
		add_option('rootsEditPage', $page);
		$page = $this->createPage(__('Add Person Pages', 'rootspersona'),'[rootsAddPageForm/]');
		add_option('rootsCreatePage', $page);
		$page = $this->createPage(__('Upload GEDCOM File', 'rootspersona'),'[rootsUploadGedcomForm/]');
		add_option('rootsUploadGedcomPage', $page);
		$page = $this->createPage(__('Include Person Page', 'rootspersona'),'[rootsIncludePageForm/]');
		add_option('rootsIncludePage', $page);
		$page = $this->createPage(__('Persona Index', 'rootspersona'),'[rootsPersonaIndexPage/]');
		add_option('rootsPersonaIndexPage', $page);
		$page = $this->createPage(__('Evidence Page', 'rootspersona'),'[rootsEvidencePage/]','','publish');
		add_option('rootsEvidencePage', $page);
		$page = $this->createPage(__('Persona Utility', 'rootspersona'),'[rootsUtilityPage/]');
		add_option('rootsUtilityPage', $page);

		add_option('rootsPersonaParentPage', "0");
		add_option('rootsIsSystemOfRecord', 'false');
	}

	function rootsPersonaUpgrade($pluginDir, $version, $currVersion) {
		update_option('rootsPersonaVersion', $version);

		$opt = get_option('rootsDataDir');
		$utility = new PersonUtility();
		if(!isset($opt) || empty($opt) || !is_dir($opt)) {
			$utility->createDataDir($pluginDir, WP_CONTENT_DIR ."/rootsPersonaData/");
			add_option('rootsDataDir', "wp-content/rootsPersonaData/");
		} else {
			$utility->createDataDir($pluginDir, $opt);
		}

		$page = get_option('rootsEditPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Edit Person Page', 'rootspersona'),'[rootsEditPersonaForm/]');
			add_option('rootsEditPage', $page);
		} else {
			$this->createPage(__('Edit Person Page', 'rootspersona'),'[rootsEditPersonaForm/]',$page);
		}

		unset($page);
		$page = get_option('rootsCreatePage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Add Person Page', 'rootspersona'),'[rootsAddPageForm/]');
			add_option('rootsCreatePage', $page);
		} else {
			$this->createPage(__('Add Person Page', 'rootspersona'),'[rootsAddPageForm/]',$page);
		}

		unset($page);
		$page = get_option('rootsUploadGedcomPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Upload GEDCOM File', 'rootspersona'),'[rootsUploadGedcomForm/]');
			add_option('rootsUploadGedcomPage', $page);
		} else {
			$this->createPage(__('Upload GEDCOM File', 'rootspersona'),'[rootsUploadGedcomForm/]',$page);
		}

		unset($page);
		$page = get_option('rootsIncludePage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Include Person Page', 'rootspersona'),'[rootsIncludePageForm/]');
			add_option('rootsIncludePage', $page);
		} else {
			$this->createPage(__('Include Page Form', 'rootspersona'),'[rootsIncludePageForm/]',$page);
		}

		unset($page);
		$page = get_option('rootsEvidencePage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Evidence Page', 'rootspersona'),'[rootsEvidencePage/]','','publish');
			add_option('rootsEvidencePage', $page);
		} else {
			$this->createPage(__('Evidence Page', 'rootspersona'),'[rootsEvidencePage/]',$page,'publish');
		}

		unset($page);
		$page = get_option('rootsUtilityPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Persona Utility', 'rootspersona'),'[rootsUtilityPage/]');
			add_option('rootsUtilityPage', $page);
		} else {
			$this->createPage(__('Persona Utility', 'rootspersona'),'[rootsUtilityPage/]',$page);
		}

		unset($page);
		$page = get_option('rootsPersonaIndexPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Persona Index', 'rootspersona'),'[rootsPersonaIndexPage/]');
			add_option('rootsPersonaIndexPage', $page);
		} else {
			$this->createPage(__('Persona Index', 'rootspersona'),'[rootsPersonaIndexPage/]',$page);
		}

		unset($opt);
		$opt = get_option('rootsPersonaParentPage');
		if (!isset($opt) || empty($opt))
		add_option('rootsPersonaParentPage', "0");

		unset($opt);
		$opt = get_option('rootsIsSystemOfRecord');
		if (!isset($opt) || empty($opt))
		add_option('rootsIsSystemOfRecord', 'false');

		if($currVersion < '1.4.0') {
			delete_option('rootsHideFamily');
			unregister_setting( 'rootsPersonaOptions', 'rootsHideFamily');
		}
	}

	function createPage($title, $contents, $page='',$status='private') {
		// Create post object
		$my_post = array();
		$my_post['post_title'] = $title;
		$my_post['post_content'] = $contents;
		$my_post['post_status'] = 'private';
		$my_post['post_author'] = 0;
		$my_post['post_type'] = 'page';
		$my_post['ping_status'] = 'closed';
		$my_post['comment_status'] = 'closed';
		$my_post['post_parent'] = 0;

		$pageID = '';
		if(empty($page)) {
			$pageID = wp_insert_post( $my_post );
		} else {
			$my_post['ID'] = $page;
			wp_update_post( $my_post );
			$pageID = $page;
		}
		return $pageID;
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
		$page = get_option('rootsIncludePage');
		wp_delete_post($page);
		delete_option('rootsIncludePage');
		$page = get_option('rootsPersonaIndexPage');
		wp_delete_post($page);
		delete_option('rootsPersonaIndexPage');
		$page = get_option('rootsUtilityPage');
		wp_delete_post($page);
		delete_option('rootsUtilityPage');
		$page = get_option('rootsEvidencePage');
		wp_delete_post($page);
		delete_option('rootsEvidencePage');

		delete_option('rootsPersonaParentPage');
		delete_option('rootsIsSystemOfRecord');
		delete_option('rootsHideHeader');
		delete_option('rootsHideFacts');
		delete_option('rootsHideAncestors');
		delete_option('rootsHideFamily');
		delete_option('rootsHideFamilyC');
		delete_option('rootsHideFamilyS');
		delete_option('rootsHidePictures');
		delete_option('rootsHideEvidence');
		delete_option('rootsPersonaHideDates');
		delete_option('rootsPersonaHidePlaces');
		delete_option('rootsHideEditLinks');

		$args = array( 'numberposts' => -1, 'post_type'=>'page','post_status'=>'any');
		$pages = get_posts($args);
		foreach($pages as $page) {
			if(preg_match("/rootsPersona/", $page->post_content)) {
				wp_delete_post($page->ID);
			}
		}

	}
}
?>
