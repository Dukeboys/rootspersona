<?php

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class rootsPersonaInstaller {
	function rootsPersonaInstall ($pluginDir, $version) {
		add_option('rootsPersonaVersion', $version);
		$this->createDataDir($pluginDir, WP_CONTENT_DIR ."/rootsPersonaData/");

		add_option('rootsDataDir', "wp-content/rootsPersonaData/");
		 
		$page = $this->createPage(__('Edit Person Page'),'[rootsEditPersonaForm/]');
		add_option('rootsEditPage', $page);
		$page = $this->createPage(__('Add Person Pages'),'[rootsAddPageForm/]');
		add_option('rootsCreatePage', $page);
		$page = $this->createPage(__('Upload GEDCOM File'),'[rootsUploadGedcomForm/]');
		add_option('rootsUploadGedcomPage', $page);
		$page = $this->createPage(__('Include Person Page'),'[rootsIncludePageForm/]');
		add_option('rootsIncludePage', $page);
		$page = $this->createPage(__('Persona Index'),'[rootsPersonaIndexPage/]');
		add_option('rootsPersonaIndexPage', $page);
		$page = $this->createPage(__('Persona Utility'),'[rootsUtilityPage/]');
		add_option('rootsUtilityPage', $page);
		
		add_option('rootsPersonaParentPage', "0");
		add_option('rootsIsSystemOfRecord', 'false');
	}

	function rootsPersonaUpgrade($pluginDir, $version, $currVersion) {
		update_option('rootsPersonaVersion', $version);

		$opt = get_option('rootsDataDir');
		if(!isset($opt) || empty($opt) || !is_dir($opt)) {
			$this->createDataDir($pluginDir, WP_CONTENT_DIR ."/rootsPersonaData/");
			add_option('rootsDataDir', "wp-content/rootsPersonaData/");
		} else {
			$this->createDataDir($pluginDir, $opt);
		}

		$page = get_option('rootsEditPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Edit Person Page'),'[rootsEditPersonaForm/]');
			add_option('rootsEditPage', $page);
		} else {
			$this->createPage(__('Edit Person Page'),'[rootsEditPersonaForm/]',$page);
		}

		unset($page);
		$page = get_option('rootsCreatePage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Add Person Page'),'[rootsAddPageForm/]');
			add_option('rootsCreatePage', $page);
		} else {
			$this->createPage(__('Add Person Page'),'[rootsAddPageForm/]',$page);
		}

		unset($page);
		$page = get_option('rootsUploadGedcomPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Upload GEDCOM File'),'[rootsUploadGedcomForm/]');
			add_option('rootsUploadGedcomPage', $page);
		} else {
			$this->createPage(__('Upload GEDCOM File'),'[rootsUploadGedcomForm/]',$page);
		}
		
		unset($page);
		$page = get_option('rootsIncludePage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Include Person Page'),'[rootsIncludePageForm/]');
			add_option('rootsIncludePage', $page);
		} else {
			$this->createPage(__('Include Page Form'),'[rootsIncludePageForm/]',$page);
		}
		
		unset($page);
		$page = get_option('rootsUtilityPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Persona Utility'),'[rootsUtilityPage/]');
			add_option('rootsUtilityPage', $page);
		} else {
			$this->createPage(__('Persona Utility'),'[rootsUtilityPage/]',$page);
		}
		
		unset($page);
		$page = get_option('rootsPersonaIndexPage');
		if(!isset($page) || empty($page)) {
			$page = $this->createPage(__('Persona Index'),'[rootsPersonaIndexPage/]');
			add_option('rootsPersonaIndexPage', $page);
		} else {
			$this->createPage(__('Persona Index'),'[rootsPersonaIndexPage/]',$page);
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

	function createDataDir($pluginDir, $rootsDataDir) {
		if(!is_dir($rootsDataDir)) {
			$this->recurse_copy($pluginDir . "rootsData/", $rootsDataDir);
		} else {
			copy($pluginDir . "rootsData/p000.xml", $rootsDataDir ."p000.xml");
			copy($pluginDir . "rootsData/f000.xml", $rootsDataDir ."f000.xml");
			copy($pluginDir . "rootsData/templatePerson.xml", $rootsDataDir ."templatePerson.xml");
			copy($pluginDir . "rootsData/README.txt", $rootsDataDir ."README.txt");
			if(!is_file($pluginDir . "rootsData/idMap.xml"))
				copy($pluginDir . "rootsData/idMap.xml", $rootsDataDir ."idMap.xml");
		}
	}
	
	function createPage($title, $contents,$page='') {
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
		$page = get_option('rootsUtilityPage');
		wp_delete_post($page);
		delete_option('rootsUtilityPage');
		delete_option('rootsPersonaIndexPage');

		delete_option('rootsPersonaParentPage');
		delete_option('rootsIsSystemOfRecord');
		delete_option('rootsDisplayHeader');
		delete_option('rootsDisplayFacts');
		delete_option('rootsDisplayAncestors');
		delete_option('rootsDisplayFamily');
		delete_option('rootsDisplayPictures');
		delete_option('rootsDisplayEvidence');
		delete_option('rootsHideEditLinks');
		
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
}
?>
