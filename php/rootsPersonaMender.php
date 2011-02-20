<?php

class rootsPersonaMender {
	function validate ($dataDir) {
  		$dom = new DOMDocument();
		$dom->load($dataDir . "idMap.xml");
		$xpath = new DOMXPath($dom);
        $xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
        $nodeList = $xpath->query('/map:idMap/map:entry');
        $output = array();
        $cnt = 1;
        $isFirst = true;
		foreach($nodeList as $entryEl) {
			
			$personId = $entryEl->getAttribute('personId');
			if(!isset($personId) || $personId = '') {
				//report issue or remove node
				$output[] = __("Invalid personId in entry $cnt of idMap.xml");
				
			}
			
			$pageId = $entryEl->getAttribute('pageId');
			$exclude = $entryEl->getAttribute('excludeLiving');
			if(!isset($pageId) || $pageId = '') {
				//report issue or remove node IF NOT excludeLiving
				if(!isset($exclude) || $exclude != 'true') {
					//report issue or remove node
					$output[] = __("Invalid pageId in entry $cnt of idMap.xml");
				} elseif (isset($exclude) || $exclude == 'true') {
					$output[] = __("pageId defined for excluded person (entry $cnt).  Delete pageId in idMap.xml to avoid security risk.");	
				}
			}

			$surName = $entryEl->getAttribute('surName');	
			if(!isset($surName) || $surName = '') {		
				//report issue or FLAG surName needed
				$output[] = __("Missing surName in entry $cnt of idMap.xml");				
			}
			$fullName = $entryEl->nodeValue;
			if(!isset($fullName) || $fullName = '') {		
				//report issue or FLAG fullName needed
				$output[] = __("Invalid name in entry $cnt of idMap.xml");
			}
			
			$post = get_post($pageId);
			if(!isset($post) || $exclude = 'true') {
				//report issue or remove node		
				$output[] = __("Expected post for page $pageId does not exist. Add page or delete entry in idMap.xml");		
			} else if(isset($post) && $exclude = 'true') {
				//report issue or remove PAGE
				$output[] = __("Page defined for excluded person (page $pageId).  Delete page to avoid security risk.");	
			}
			if($isFirst) {
				echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
					. __('Issues found with your rootsPersona setup.')."</p>";	
				$isFirst = false;			
			}
			foreach ($output as $line) {
				echo $line . "<br/>";
			}
			$cnt++;
		}
		if($isFirst) {
			echo "<p style='padding: .5em; background-color: green; color: white; font-weight: bold;'>"
				. __('Your rootsPersona setup is VALID.')."</p>";
		} else {
			echo  "<div style='text-align:center;margin-top:10px;'>"
				. "<span class='rp_linkbutton' style='border:2px outset orange;width:250px;'><a href=' " . site_url() . "?page_id=" . get_option('rootsUtilityPage') . "&utilityAction=repair'>" . __('Repair') . "</a></span>"
				. "<span>&#160;&#160;</span>"
				. "<span class='rp_linkbutton' style='border:2px outset orange;width:250px;'><a href=' " . admin_url() . "tools.php?page=rootsPersona'>" . __('Return') . "</a></span>"
				.  "</div>";
			
		}
	}
}
?>
