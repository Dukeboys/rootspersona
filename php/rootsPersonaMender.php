<?php
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/personUtility.php');

class rootsPersonaMender {
	function validate ($dataDir, $isRepair=false) {
		$utility = new personUtility();

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
			while(sizeof($output) == 0) {
			$personId = $entryEl->getAttribute('personId');
			if(!isset($personId) || $personId == '') {
				if($isRepair) {
					$output[] = __("Invalid personId: deleting element from idMap.xml");	
					$entryEl->parentNode->removeChild($entryEl);
					break;		
				} else {
					$output[] = __("Invalid personId in idMap.xml");
				}
			} else {
				$fileName =  $dataDir . $personId . ".xml";
				if(!file_exists($fileName)) {
					if($isRepair) {
						$output[] = __("Missing file: deleting element from idMap.xml");	
						$entryEl->parentNode->removeChild($entryEl);
						break;		
					} else {
						$output[] = __("Missing file: ") . $fileName;
					}
				}
			}
			
			$pageId = $entryEl->getAttribute('pageId');
			$exclude = $entryEl->getAttribute('excludeLiving');
			if(!isset($pageId) || $pageId == '') {
				if(!isset($exclude) || $exclude != 'true') {
					if($isRepair) {
						$output[] = __("Invalid pageId: deleting element from idMap.xml");	
						$entryEl->parentNode->removeChild($entryEl);
						break;		
					} else {
						$output[] = __("Invalid pageId in idMap.xml");
					}
				} 
			} elseif (isset($exclude) && $exclude == 'true') {
					if($isRepair) {
						$output[] = __("pageId defined for excluded person: deleting page and removing pageId.");	
						wp_delete_post($pageId);
						$entryEl->setAttribute('pageId', '');		
					} else {
						$output[] = __("pageId defined in idMap.xml for excluded person.");	
					}
			}

			$surName = $entryEl->getAttribute('surName');	
			if(!isset($surName) || $surName == '') {		
					if($isRepair) {
						$surName = $utility->getSurname($personId . '.xml', $dataDir);	
						if(empty($surName)) $surName = 'Unknown';					
						$output[] = __("Missing surName: updating surName in idMap.xml to ") . $surName;	
						$entryEl->setAttribute('surName', $surName);	
					} else {
						$output[] = __("Missing surName in idMap.xml.");	
					}		
			}
			
			$fullName = $entryEl->nodeValue;
			if(!isset($fullName) || $fullName == '') {		
					if($isRepair) {
						$fullName = $utility->getName($personId . '.xml', $dataDir);						
						$output[] = __("Invalid name: updating name in idMap.xml to ") . $fullName;	
						$entryEl->nodeValue = $fullName;	
					} else {
						$output[] = __("Invalid name in idMap.xml.");	
					}		
			} else if($fullName != $utility->getName($personId . '.xml', $dataDir)) {
					if($isRepair) {
						$fullName = $utility->getName($personId . '.xml', $dataDir);						
						$output[] = __("Invalid name: updating name in idMap.xml to ") . $fullName;	
						$entryEl->nodeValue = $fullName;	
					} else {
						$output[] = __("Invalid name in idMap.xml.");	
					}				
			}
	
			if(isset($pageId) && !empty($pageId)) {
				$post = get_post($pageId);
				if(!isset($post) && $exclude != 'true') {
					if($isRepair) {
						$output[] = __("Page does not exist: deleting element from idMap.xml");	
						$entryEl->parentNode->removeChild($entryEl);
						break;		
					} else {
						$output[] = __("Expected post for page (") .$pageId.__(") does not exist.");		
					}	
				} else if(isset($post) && $exclude == 'true') {
					if($isRepair) {
						$output[] = __("Page defined for excluded person: deleting page.");	
						wp_delete_post($pageId);
						break;		
					} else {
						$output[] = __("Page defined in idMap.xml for excluded person. Delete page to avoid security risk.");		
					}	
				}
				
				if($post->post_title != $fullName) {
					if($isRepair) {
						$output[] = __("Page title out of synch: updating page title.");	
						$my_post = array();
  						$my_post['ID'] = $pageId;
						$my_post['post_title'] = $fullName;
						wp_update_post( $my_post );	
					} else {
						$output[] = __("Page title (") .$post->post_title  
							.__(") does no reflect the name in idMap.xml (").$fullName .")";	
					}	
				}
				
				$content = $post->post_content;
				if(!preg_match("/rootsPersona /", $content)) {
					if($isRepair) {
						$output[] = __("Invalid persona page: deleting element from idMap.xml");	
						$entryEl->parentNode->removeChild($entryEl);
						break;		
					} else {
						$output[] = __("Invalid persona page for ") . $fullName  
							.__(" (page ").$pageId .")";
					}
				}
				
				$pagePerson = @preg_replace(
           							'/.*?personId=[\'|"](.*)[\'|"].*?/US'
           							, '$1'
           							, $content);
           		if($pagePerson != $personId) {
           			if($isRepair) {
						$output[] = __("personId out of synch with personID on page: deleting element from idMap.xml");	
						$entryEl->parentNode->removeChild($entryEl);
						break;		
					} else {
 						$output[] = __("personId referenced in idMap.xml (").$personId.__(") does not reference ") . $fullName  
							.__(" (pageId ").$pageId . ", personId ".$pagePerson.")";   
					}       			
           		}
			}
			break;
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
		if(!$isFirst && $isRepair) {
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->save($dataDir . "/idMap.xml");
		}
		echo $this->getFooter($isFirst,$isEmpty, $isRepair);
	}
	
	function getFooter($isValid,$isEmpty, $isRepaired, $isPages=false) {
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
			if($isPages) {
				$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' " 
					. site_url() . "?page_id=" . get_option('rootsUtilityPage') 
					. "&utilityAction=repairPages'>" . __('Delete Orphaned Pages?') . "</a></span>"
					. "<span>&#160;&#160;</span>";			
			} else {
				$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' " 
					. site_url() . "?page_id=" . get_option('rootsUtilityPage') 
					. "&utilityAction=repair'>" . __('Repair Inconsistencies') . "</a></span>"
					. "<span>&#160;&#160;</span>";
			}
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
				if($isRepair) {
					$output[] = __("Deleted orphaned page with no reference in idMap.xml.");	
					wp_delete_post($page->ID);				
				} else {
					$output[] = __("No reference in idMap.xml.");
				}
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
		echo $this->getFooter($isFirst,false, $isRepair, true);
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
