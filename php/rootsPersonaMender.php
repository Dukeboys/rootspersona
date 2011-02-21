<?php

class rootsPersonaMender {
	function validate ($dataDir, $isRepair=false) {
  		$dom = new DOMDocument();
		$dom->load($dataDir . "idMap.xml");
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
		$nodeList = $xpath->query('/map:idMap/map:entry');
		$cnt = 1;
		$isFirst = true;
		$isEmpty = $nodeList->length<= 0;
        
		foreach($nodeList as $entryEl) {
			$output = array();
			$personId = $entryEl->getAttribute('personId');
			if(!isset($personId) || $personId == '') {
				//report issue or remove node
				$output[] = __("Invalid personId in idMap.xml");
			} else {
				$fileName =  $dataDir . $personId . ".xml";
				if(!file_exists($fileName)) {
					$output[] = __("Missing file: ") . $fileName;
				}
			}
			
			$pageId = $entryEl->getAttribute('pageId');
			$exclude = $entryEl->getAttribute('excludeLiving');
			if(!isset($pageId) || $pageId == '') {
				//report issue or remove node IF NOT excludeLiving
				if(!isset($exclude) || $exclude != 'true') {
					//report issue or remove node
					$output[] = __("Invalid pageId in idMap.xml");
				} 
			} elseif (isset($exclude) && $exclude == 'true') {
					$output[] = __("pageId defined in idMap.xml for excluded person.");	
			}

			$surName = $entryEl->getAttribute('surName');	
			if(!isset($surName) || $surName = '') {		
				//report issue or FLAG surName needed
				$output[] = __("Missing surName in idMap.xml");				
			}
			$fullName = $entryEl->nodeValue;
			if(!isset($fullName) || $fullName == '') {		
				//report issue or FLAG fullName needed
				$output[] = __("Invalid name in idMap.xml");	
			}
	
			if(isset($pageId) && !empty($pageId)) {
				$post = get_post($pageId);
				if(!isset($post) && $exclude != 'true') {
					//report issue or remove node		
					$output[] = __("Expected post for page (") .$pageId.__(") does not exist. Add page or delete entry in idMap.xml");		
				} else if(isset($post) && $exclude == 'true') {
					//report issue or remove PAGE
					$output[] = __("Page defined in idMap.xml for excluded person. Delete page to avoid security risk.");	
				}
				
				if($post->post_title != $fullName) {
					$output[] = __("Page title (") .$post->post_title  
							.__(") does no reflect the name in idMap.xml (").$fullName .")";					
				}
				
				$content = $post->post_content;
				if(!preg_match("/rootsPersona /", $content)) {
					$output[] = __("Invalid persona page for ") . $fullName  
							.__(" (page ").$pageId .")";	
				}
				
				$pagePerson = @preg_replace(
           							'/.*?personId=[\'|"](.*)[\'|"].*?/US'
           							, '$1'
           							, $content);
           		if($pagePerson != $personId) {
 					$output[] = __("personId referenced in idMap.xml (").$personId.__(") does not reference ") . $fullName  
							.__(" (pageId ").$pageId . ", personId ".$pagePerson.")";          			
           		}
			}

				foreach ($output as $line) {
					if($isFirst) {
						echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
							. __('Issues found with your rootsPersona map file.')."</p>";	
						$isFirst = false;			
					}	
					echo __("Entry "). $cnt . ": " .$line . "<br/>";
				}
			$cnt++;
		}
		echo $this->getFooter($isFirst,$isEmpty, $isRepair);
	}
	
	function getFooter($isValid,$isEmpty, $isRepaired) {
		$footer =   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>";
		if($isEmpty) {
			$footer =  $footer . "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
				. __('idMap.xml is empty.')."</p>"
				. "<span>&#160;&#160;</span>";
		} else if($isValid) {
			$footer =$footer .  "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
				. __('Your rootsPersona setup is VALID.')."</p>"
				. "<span>&#160;&#160;</span>";
		} else if(!$isRepaired) {
			$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' " 
				. site_url() . "?page_id=" . get_option('rootsUtilityPage') 
				. "&utilityAction=repair'>" . __('Repair') . "</a></span>"
				. "<span>&#160;&#160;</span>";
		}
		$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' " 
			. admin_url() . "tools.php?page=rootsPersona'>" . __('Return') . "</a></span>"
			.  "</div>";
		return $footer;
	}

	function validatePages ($dataDir, $isRepair=false) {
                $args = array( 'numberposts' => -1, 'post_type'=>'page');
                $pages = get_posts($args);
		$cnt = 0;
		$isFirst = true;
                $dom = new DOMDocument();
                $dom->load($dataDir . "idMap.xml");
                $xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', 'http://ed4becky.net/idMap');

                foreach($pages as $page) {
			$output = array();

			if(!preg_match("/rootsPersona /", $page->post_content)) {
				continue;
			}
			$pid = @preg_replace( '/.*?personId=[\'|"](.*)[\'|"].*?/US'
						, '$1'
						, $page->post_content);
			$nodeList = $xpath->query('/map:idMap/map:entry[@personId="' . $pid . '"]');
			if($nodeList->length <= 0) {
				$output[] = __("No reference in idMap.xml.");
			}

				foreach ($output as $line) {
					if($isFirst) {
						echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
							. __('Issues found with your rootsPersona pages.')."</p>";	
						$isFirst = false;			
					}	
					echo __("Page "). $page->ID . ": " .$line . "<br/>";
				}
			$cnt++;
                }
		echo $this->getFooter($isFirst,false, $isRepair);
	}

        function delete ($pluginDir, $rootsDataDir) {
                $args = array( 'numberposts' => -1, 'post_type'=>'page');
                $pages = get_posts($args);
		$cnt = 0;
                foreach($pages as $page) {
			if(preg_match("/rootsPersona /", $page->post_content)) {
				wp_delete_post($page->ID);
				$cnt++;
			}
                }
		// since we know we just deleted everyting, 
		//	just copy over the idMap.xml template.
		copy($pluginDir . "rootsData/idMap.xml", $rootsDataDir ."idMap.xml");
		echo $cnt  . __(' pages deleted.<br/>');
		echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
			. "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' " 
			. site_url() . "?page_id=" . get_option('rootsUtilityPage') 
			. "&utilityAction=deleteFiles'>" . __('Delete persona files as well?') . "</a></span>"
			. "<span>&#160;&#160;</span>"
			.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' " 
			. admin_url() . "tools.php?page=rootsPersona'>" . __('Return') . "</a></span>"
			.  "</div>";
        }
        function deleteFiles ($pluginDir, $rootsDataDir) {
                $dir = opendir($rootsDataDir);
		$cnt = 0;
                while(false !== ( $file = readdir($dir)) ) {
                        if (is_file($rootsDataDir . '/' .$file)) {
				unlink($rootsDataDir . '/' . $file);
				$cnt++;
                        }
                }
                closedir($dir);
		copy($pluginDir . "rootsData/idMap.xml", $rootsDataDir ."idMap.xml");
		copy($pluginDir . "rootsData/p000.xml", $rootsDataDir ."p000.xml");
		copy($pluginDir . "rootsData/f000.xml", $rootsDataDir ."f000.xml");
		copy($pluginDir . "rootsData/templatePerson.xml", $rootsDataDir ."templatePerson.xml");
		copy($pluginDir . "rootsData/README.txt", $rootsDataDir ."README.txt");

		echo ($cnt-5)  . __(' files deleted.<br/>');
		echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
			.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' " 
			. admin_url() . "tools.php?page=rootsPersona'>" . __('Return') . "</a></span>"
			.  "</div>";

	}
}
?>
