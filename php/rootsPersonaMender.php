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
        
		foreach($nodeList as $entryEl) {
			$valOutput = null;
			$personId = $entryEl->getAttribute('personId');
			if(!isset($personId) || $personId == '') {
				//report issue or remove node
				$valOutput[] = __("Invalid personId in idMap.xml");
			} else {
				$fileName =  $dataDir . $personId . ".xml";
            	if(!file_exists($fileName)) {
            		$valOutput[] = __("Missing file: ") . $fileName;
            	}
			}
			
			$pageId = $entryEl->getAttribute('pageId');
			$exclude = $entryEl->getAttribute('excludeLiving');
			if(!isset($pageId) || $pageId == '') {
				//report issue or remove node IF NOT excludeLiving
				if(!isset($exclude) || $exclude != 'true') {
					//report issue or remove node
					$valOutput[] = __("Invalid pageId in idMap.xml");
				} 
			} elseif (isset($exclude) && $exclude == 'true') {
					$valOutput[] = __("pageId defined in idMap.xml for excluded person.");	
			}

			$surName = $entryEl->getAttribute('surName');	
			if(!isset($surName) || $surName = '') {		
				//report issue or FLAG surName needed
				$valOutput[] = __("Missing surName in idMap.xml");				
			}
			$fullName = $entryEl->nodeValue;
			if(!isset($fullName) || $fullName == '') {		
				//report issue or FLAG fullName needed
				$valOutput[] = __("Invalid name in idMap.xml");	
			}
	
			if(isset($pageId) && !empty($pageId)) {
				$post = get_post($pageId);
				if(!isset($post) && $exclude != 'true') {
					//report issue or remove node		
					$valOutput[] = __("Expected post for page (") .$pageId.__(") does not exist. Add page or delete entry in idMap.xml");		
				} else if(isset($post) && $exclude == 'true') {
					//report issue or remove PAGE
					$valOutput[] = __("Page defined in idMap.xml for excluded person. Delete page to avoid security risk.");	
				}
				
				if($post->post_title != $fullName) {
					$valOutput[] = __("Page title (") .$post->post_title  
							.__(") does no reflect the name in idMap.xml (").$fullName .")";					
				}
				
				$content = $post->post_content;
				if(!preg_match("/rootsPersona /", $content)) {
					$valOutput[] = __("Invalid persona page for ") . $fullName  
							.__(" (page ").$pageId .")";	
				}
				
				$pagePerson = @preg_replace(
           							'/.*?personId=[\'|"](.*)[\'|"].*?/US'
           							, '$1'
           							, $content);
           		if($pagePerson != $personId) {
 					$valOutput[] = __("personId referenced in idMap.xml (").$personId.__(") does not reference ") . $fullName  
							.__(" (pageId ").$pageId . ", personId ".$pagePerson.")";          			
           		}
			}

			if(!$isRepair) {
				$output = $valOutput;
			} else {
				$output = $repOutput;
			}
			if(isset($output)) {
				foreach ($output as $line) {
					if($isFirst) {
						echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
							. __('Issues found with your rootsPersona setup.')."</p>";	
						$isFirst = false;			
					}	
					echo __("Entry "). $cnt . ": " .$line . "<br/>";
				}
			}
			$cnt++;
		}
		echo $this->getFooter($isFirst,$isRepair);
	}
	
	function getFooter($isValid,$isRepaired) {
		$footer = null;
		if($isValid) {
			$footer =  "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
				. __('Your rootsPersona setup is VALID.')."</p>";
		} else {
			$footer =   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>";
			if(!$isRepaired) {
				$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' " 
					. site_url() . "?page_id=" . get_option('rootsUtilityPage') 
					. "&utilityAction=repair'>" . __('Repair') . "</a></span>"
					. "<span>&#160;&#160;</span>";
			}
			
			$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' " 
				. admin_url() . "tools.php?page=rootsPersona'>" . __('Return') . "</a></span>"
				.  "</div>";
		}
		return $footer;
	}
}
?>
