<?php
require_once 'GEDTransformer.php';

class PersonUtility {
	
	/**
	 * 
	 * edit person form
	 * 
	 * @param  $p
	 * @param  $imgBase
	 * @param  $msg
	 */
	public function showForm($p,$imgBase, $msg='') {
		$block = "<form  action='" . $p['action'] . "' method='POST'><div class='truncate'>";
		
		if(isset($p['gender']) && $p['gender'] == 'F') {
			$block = $block . "<img src='" . $imgBase. "/images/girl-silhouette.gif' class='headerBox' />";
		} else {
			$block = $block . "<img src='" . $imgBase. "/images/boy-silhouette.gif' class='headerBox' />";
		}
		
		$block = $block . "<div class='headerBox' style='padding-top: 5px;'>";

		if($p['isSystemOfRecord'] =="true") {
			$block = $block . "<div style='width:4em;float:left'>Id:</div><input value='". $p['personId'] ."' type='text' name='personId' size='6' style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='width:4em;float:left'>Name:</div><input value='". $p['personName'] ."' type='text' name='personName' size='35' style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div  style='width:4em;float:left'>Gender:</div>";
			$block = $block . "<div style='margin:0px 5px 5px 5px;'>";
			$block = $block . "<input type='radio' name='gender' value='M' " . ($p['gender'] == 'M'?"checked='checked'":"") ."/> Male";
			$block = $block . "<input type='radio' name='gender' value='F' " . ($p['gender'] == 'F'?"checked='checked'":"") ."/> Female";
			$block = $block . "<input type='radio' name='gender' value='U' " . ($p['gender'] == 'U'?"checked='checked'":"") ."/> Unknown";
			$block = $block . "</div>";
			$block = $block . "<div  style='width:4em;float:left'>Born:</div><input style='float:left' value='". $p['bDate'] ."' type='text' name='bDate'  style='margin:0px 5px 5px 5px;'>";
			$block = $block . "<div  style='width:3em;float:left;padding-left:5px;'>Place:</div><input value='". $p['bPlace'] ."' type='text' name='bPlace'  style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div  style='width:4em;float:left'>Died:</div><input style='float:left' value='". $p['dDate'] ."' type='text' name='dDate'  style='margin:0px 5px 5px 5px;'>";
			$block = $block . "<div  style='width:3em;float:left;padding-left:5px;'>Place:</div><input value='". $p['dPlace'] ."' type='text' name='dPlace'  style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='Submit'/>";
			$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='Reset'/></div>";
			$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>";
			$block = $block . "</div></div>";
			
			$block = $block . "<br/><div class='personBanner'>Facts</div><div class='truncate'>";
			$block = $block . "<div style='padding-top: 15px;'>";
			$block = $block . "<div  style='width:6em;float:left'>Marraige:</div><input style='float:left' value='". $p['mDate'] ."' type='text' name='mDate'  style='margin:0px 5px 5px 5px;'>";
			$block = $block . "<div  style='width:3.4em;float:left;padding-left:5px;'>Place:</div><input value='". $p['mPlace'] ."' type='text' name='mPlace'  style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='width:6em;float:left'>Partner's Id:</div><input style='float:left' value='". $p['pid'] ."' type='text' name='pid' size='6' style='margin:0px 5px 5px 5px;'>";
			$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='Submit'/>";
			$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='Reset'/></div></div>";
			$block = $block . "</div>";
					
			$block = $block . "<br/><div class='personBanner'>Ancestors</div><div class='truncate'>";
			$block = $block . "<div style='padding-top: 15px;'>";
			$block = $block . "<div style='width:8em;float:left'>Father's Id:</div><input value='". $p['fid'] ."' type='text' name='fid' size='6' style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='width:8em;float:left'>Mother's Id:</div><input value='". $p['mid'] ."' type='text' name='mid' size='6' style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='Submit'/>";
			$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='Reset'/></div></div>";
			$block = $block . "</div>";

			$block = $block . "<br/><div class='personBanner'>Family Group</div><div class='truncate'>";
			$block = $block . "<div style='padding-top: 15px;'>";
			$block = $block . "<div style='width:10em;float:left'>Group Id (as Parent):</div><input value='". $p['gpid'] ."' type='text' name='gpid' size='6' style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='width:10em;float:left'>Group Id (as Child):</div><input value='". $p['gcid'] ."' type='text' name='gcid' size='6' style='margin:0px 5px 5px 5px;'>";
			$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='Submit'/>";
			$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='Reset'/></div></div>";
			$block = $block . "</div>";
			
		} else {
			$block = $block . "<div style='width:4em;float:left'>Id:</div><input readonly value='". $p['personId'] ."' type='text' name='personId' size='6' style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='width:4em;float:left'>Name:</div><input readonly value='". $p['personName'] ."' type='text' name='personName' size='35' style='margin:0px 5px 5px 5px;'><br/>";
			$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>";
			$block = $block . "</div></div>";
		}
		
		$block = $block . "<br/><div class='personBanner'>Pictures</div><div class='truncate'>";
		$block = $block . "<div style='padding-top: 15px;'>";
		
		for ($i= 1;$i <= 7; $i++) {
			$pf = 'picFile' . $i;
			$pc = 'picCap' . $i;
			$block = $block . "<div><div style='width:2em;float:left;'>$i</div>";
			$block = $block . "<div style='width:5em;float:left;padding-left:5px;'>File:</div>"
				. "<input  style='float:left;' value='" 
				. (isset($p[$pf])?$p[$pf]:'')  
				. "' type='text' name='" 
				. $pf . "' id='" . $pf
				. "' size='40'></div>";
			$block = $block . "<div style='clear:both'><div style='width:2em;float:left;'>&nbsp;</div>";
			if($i == 1) {
				$block = $block . "<div style='width:6em;float:left;'>&nbsp;</div>";
				$block = $block . "<div style=''>(example: wp-content/uploads/father.jpg)</div></div><br/>";
			} else {
				$block = $block . "<div style='width:5em;float:left;padding-left:5px;'>Caption:</div>"
					. "<input value='" 
					. (isset($p[$pc])?$p[$pc]:'')  
					. "' type='text' name='"
					. $pc . "' id='" . $pc
					. "' size='40'></div><br/>";
			}
		}

		$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' id='submitPersonForm' value='Submit'/>";
		$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='Reset'/></div></div>";

		$block = $block . "</div>";
	
		$block = $block . "<br/><div class='personBanner'><br/></div>";
		$block = $block . "<input type='hidden' name='srcPage' id='srcPage' value='" . $p['srcPage'] ."'>";
		$block = $block . "</form>";	
		return $block;
	}

	/**
	 * 
	 * prepopulate params from XML for add person form
	 * 
	 * @param unknown_type $xml_doc
	 */
	public function paramsFromXML($xml_doc) {
		$xpath = new DOMXPath($xml_doc);
		$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");

		$rootPersonNodeList = $xpath->query('/persona:person');
		$rootPersonNode = $rootPersonNodeList->item(0);
		$p['personId'] = $rootPersonNode->getAttribute('id');

		$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="name"]',$rootPersonNode);
		$p['personName'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

		$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="gender"]',$rootPersonNode);
		$p['gender'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

		$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:date',$rootPersonNode);
		$p['bDate'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

		$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:place',$rootPersonNode);
		$p['bPlace'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';

		$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:date',$rootPersonNode);
		$p['dDate'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';
				
		$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:place',$rootPersonNode);
		$p['dPlace'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';
		
		$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:date',$rootPersonNode);
		$p['mDate'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';
				
		$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:place',$rootPersonNode);
		$p['mPlace'] = $nodeList->length?$nodeList->item(0)->nodeValue:'';
		
		$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:person',$rootPersonNode);
		$partnerNode = $nodeList->item(0);
		$p['pid'] = isset($partnerNode)?$partnerNode->getAttribute('id'):'';
		
		$nodeList = $xpath->query('persona:relations/persona:relation[@type="father"]/persona:person',$rootPersonNode);
		$fatherNode = $nodeList->item(0);
		$p['fid'] = $fatherNode->getAttribute('id');

		$nodeList = $xpath->query('persona:relations/persona:relation[@type="mother"]/persona:person',$rootPersonNode);
		$motherNode = $nodeList->item(0);
		$p['mid'] = $motherNode->getAttribute('id');
	
		$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="parent"]',$rootPersonNode);
		$node = $nodeList->item(0);
		$p['gpid'] = isset($node)?$node->getAttribute('refId'):'';
		
		$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="child"]',$rootPersonNode);
		$node = $nodeList->item(0);
		$p['gcid'] = isset($node)?$node->getAttribute('refId'):'';
		
//		$picList = $xpath->query('persona:references/persona:media/persona:picture',$rootPersonNode);
//		for ($i=0; $i < $picList->length; $i++)  {
//			$picNode = $picList->item($i);
//			$p['picFile' . ($i+1)] = $picNode->getAttribute('src');		
//			$nodeList = $xpath->query('persona:caption',$picNode);
//			$p['picCap' . ($i+1)] = $nodeList->length?$nodeList->item(0)->nodeValue:'';
//		}
				
		return $p;
	}

	/**
	 * 
	 * get params from doc into XML file
	 * 
	 * @param  $xml_doc
	 * @param  $p
	 */
	public function paramsToXML($xml_doc, $p) {
		$xpath = new DOMXPath($xml_doc);
		$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");

		$rootPersonNodeList = $xpath->query('/persona:person');
		$rootPersonNode = $rootPersonNodeList->item(0);
		$rootPersonNode->setAttribute('id',$p['personId']);

		$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="name"]',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['personName'];

		$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="gender"]',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['gender'];

		$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:date',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['bDate'];

		$nodeList = $xpath->query('persona:events/persona:event[@type="birth"]/persona:place',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['bPlace'];

		$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:date',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['dDate'];

		$nodeList = $xpath->query('persona:events/persona:event[@type="death"]/persona:place',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['dPlace'];

		$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:date',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['mDate'];
				
		$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:place',$rootPersonNode);
		$nodeList->item(0)->nodeValue = $p['mPlace'];
		
		$nodeList = $xpath->query('persona:events/persona:event[@type="marriage"]/persona:person',$rootPersonNode);
		$partnerNode = $nodeList->item(0);
		$partnerNode->setAttribute('id', $p['pid']);
		
		$nodeList = $xpath->query('persona:characteristics/persona:characteristic[@type="name"]',$partnerNode);
		$nodeList->item(0)->nodeValue = $p['pname'];
		
		$nodeList = $xpath->query('persona:relations/persona:relation[@type="father"]/persona:person',$rootPersonNode);
		$fatherNode = $nodeList->item(0);
		$fatherNode->setAttribute('id',$p['fid']);

		$nodeList = $xpath->query('persona:relations/persona:relation[@type="mother"]/persona:person',$rootPersonNode);
		$motherNode = $nodeList->item(0);
		$motherNode->setAttribute('id',$p['mid']);

		$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="parent"]',$rootPersonNode);
		$node = $nodeList->item(0);
		$node->setAttribute('refId',$p['gpid']);
		
		$nodeList = $xpath->query('persona:references/persona:familyGroups/persona:familyGroup[@selfType="child"]',$rootPersonNode);
		$node = $nodeList->item(0);
		$node->setAttribute('refId',$p['gcid']);
		
//		$picList = $xpath->query('persona:references/persona:media/persona:picture',$rootPersonNode);
//		for ($i=1; $i < 8; $i++)  {
//			$picNode = $picList->item($i-1);
//			$picNode->setAttribute('seq', $i);
//			$picNode->setAttribute('src',$p['picFile' . $i]);		
//			$nodeList = $xpath->query('persona:caption',$picNode);
//			$nodeList->item(0)->nodeValue = $p['picCap' . $i]; 
//		}
		return $xml_doc;
	}

	/**
	 * 
	 * get params from posted form
	 * 
	 * @param unknown_type $params
	 */
	public function paramsFromHTML($params) {
		$p['personId']  = isset($params['personId'])  ? trim(esc_attr($params['personId']))  : '';
		$p['personName']  = isset($params['personName'])  ? trim(esc_attr($params['personName']))  : '';
		$p['gender']  = isset($params['gender'])  ? trim(esc_attr($params['gender']))  : '';
		$p['bDate']  = isset($params['bDate'])  ? trim(esc_attr($params['bDate']))  : '';
		$p['bPlace']  = isset($params['bPlace'])  ? trim(esc_attr($params['bPlace']))  : '';
		$p['dDate']  = isset($params['dDate'])  ? trim(esc_attr($params['dDate']))  : '';
		$p['dPlace']  = isset($params['dPlace'])  ? trim(esc_attr($params['dPlace']))  : '';

		$p['mDate']  = isset($params['mDate'])  ? trim(esc_attr($params['mDate']))  : '';
		$p['mPlace']  = isset($params['mPlace'])  ? trim(esc_attr($params['mPlace']))  : '';
		$p['pid']  = isset($params['pid'])  ? trim(esc_attr($params['pid']))  : '';
		
		$p['fid']  = isset($params['fid'])  ? trim(esc_attr($params['fid']))  : '';
		$p['mid']  = isset($params['mid'])  ? trim(esc_attr($params['mid']))  : '';
		
		$p['gpid']  = isset($params['gpid'])  ? trim(esc_attr($params['gpid']))  : '';
		$p['gcid']  = isset($params['gcid'])  ? trim(esc_attr($params['gcid']))  : '';
		$p['srcPage']  = isset($params['srcPage'])  ? trim(esc_attr($params['srcPage']))  : '';
		
		for ($i=1; $i < 8; $i++)  {
			$p['picFile' . $i]  = isset($params['picFile' . $i])  ? trim(esc_attr($params['picFile' . $i]))  : '';
			$p['picCap' . $i]  = isset($params['picCap' . $i])  ? trim(esc_attr($params['picCap' . $i]))  : '';
		}
		
		return $p;
	}
	
	/**
	 * 
	 * used by getMissing
	 * 
	 * @param unknown_type $dataDir
	 */
	public function getMapPersonIds($dataDir) {
		$dom = new DOMDocument();
		$dom->load($dataDir . "/idMap.xml");

		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', "http://ed4becky.net/idMap");
		$nodes = $xpath->query("/map:idMap/map:entry");
		$ids = array();
		for ($i=0; $i < $nodes->length; $i++)  {
			$node = $nodes->item($i);
			$ids[$i] = $node->getAttribute('personId');
		}
		return $ids;
	}
	
	/**
	 * 
	 * get list of files without entires in idMap
	 * 
	 * @param  $dataDir
	 */
	public function getMissing($dataDir) {
		$ids = $this->getMapPersonIds($dataDir);
		$dh  = opendir($dataDir);
		$files = null;
		while (false !== ($filename = readdir($dh))) {
			if(in_array(substr($filename,0,-4), $ids)) continue;
			if(strpos($filename,"xml") <= 0
			    || $filename == "p000.xml"
				|| $filename == "templatePerson.xml"
				|| $filename == "f000.xml"
				|| $filename == "idMap.xml") continue;
			$dom = DOMDocument::load($dataDir . "/" . $filename);
			$root = $dom->documentElement;;
			if(isset($root) && $root->tagName == "persona:person") {
    			$files[] = $filename;
			}
		}
		return $files;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param  $fileName
	 * @param  $dataDir
	 */
	public function getName($fileName, $dataDir) {
		$dom = new DOMDocument();
		$dom->load($dataDir . "/" . $fileName);
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");
		$nodeList = $xpath->query('/persona:person/persona:characteristics/persona:characteristic[@type="name"]');
		$name = $nodeList->length?$nodeList->item(0)->nodeValue:'';
		return $name;
	}	/**
	 * 
	 * Enter description here ...
	 * @param  $fileName
	 * @param  $dataDir
	 */
	public function getSurname($fileName, $dataDir) {
		$dom = new DOMDocument();
		$dom->load($dataDir . "/" . $fileName);
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");
		$nodeList = $xpath->query('/persona:person/persona:characteristics/persona:characteristic[@type="surname"]');
		$surname = $nodeList->length?$nodeList->item(0)->nodeValue:'';
		return $surname;
	}
	
	/**
	 * 
	 * add entry to idMap
	 * 
	 * @param  $pid
	 * @param  $page
	 * @param  $name
	 * @param  $dataDir
	 */
	public function createMapEntry($pid, $page, $name, $surname, $dataDir ) {
		// add to idMap.xml
  		$dom = new DOMDocument();
		$dom->load("$dataDir/idMap.xml");
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', "http://ed4becky.net/idMap");

		$nodes = $xpath->query("/map:idMap");
		$rootNode = $nodes->item(0);
		$entryEl = $dom->createElementNS('http://ed4becky.net/idMap', 'map:entry');
		$entryEl->setAttribute('personId',$pid);
		$entryEl->setAttribute('pageId',$page);
		$entryEl->setAttribute('surName',$surname);
		$entryEl->appendChild($dom->createTextNode($name));
		$rootNode->appendChild($entryEl);
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save("$dataDir/idMap.xml");
	}
	
	public function updateNames($pid, $name, $surname, $dataDir ) {
		// add to idMap.xml
  		$dom = new DOMDocument();
		$dom->load($dataDir . "idMap.xml");
		$xpath = new DOMXPath($dom);
        $xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
        $nodeList = $xpath->query('/map:idMap/map:entry[@personId="' . $pid . '"]');
		foreach($nodeList as $entryEl) {
			$entryEl->setAttribute('surName',$surname);
			$entryEl->nodeValue = $name;
			$page = $entryEl->getAttribute('pageId');
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->save($dataDir . "/idMap.xml");
			
			//update post title, assuming name change
			if(isset($page) && !empty($page)) {
				$my_post = array();
  				$my_post['ID'] = $page;
				$my_post['post_title'] = $name;
				wp_update_post( $my_post );
			}
			break; //only want first one
		}
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param  $action
	 * @param  $files
	 * @param  $dataDir
	 * @param  $msg
	 */
	public function showAddPageForm($action,$files,$dataDir, $msg='') {
		$block = "<br/><div class='personBanner'><br/></div>";
		if(count($files) == 0) {
			$block = $block . "<br/><div style='text-align:center;color:red;font-weight:bold'>All available files have been added.</div>";
		} else {
			$block = $block . "<form  action='$action' method='POST'>";
			$block = $block . "<br/><select multiple name='fileNames[]' size='16'>";
			for($i = 0; $i < count($files); $i++) {
				$name = $this->getName($files[$i], $dataDir);
  				$block = $block . "<option value='$files[$i]'>$name</option>";
			}
			$block = $block . "</select><br/>";	
			$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>";
		
			$block = $block . "<br/><input type='submit' name='submitAddPageForm' value='Submit'/>";
			$block = $block . "&#160;&#160;<input type='reset' name='reset' value='Reset'/>";

			$block = $block . "<br/><br/><div class='personBanner'><br/></div>";
			$block = $block . "</form>";
		}
		return $block;
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param  $action
	 * @param  $files
	 * @param  $msg
	 */
	public function showUploadGedcomForm($action,$msg='') {
		$block = "<br/><div class='personBanner'><br/></div><form enctype='multipart/form-data' action='$action' method='POST'>";
		$block = $block . "<br/>&#160;&#160;<input type='file' name='gedcomFile' size='70'/>";
		$block = $block . "<br/>&#160;&#160;<input type='submit' class='button' name='submitUploadGedcomForm' value='Upload'/>";
		$block = $block . "&#160;&#160;<input type='reset' name='reset' value='Reset'/>";
		$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>";
		$block = $block . "<br/><div class='personBanner'><br/></div>";
		$block = $block . "</form>";
		return $block;
	}
	
        /**
         * Create a page in wordpress
         *
         * @param $fileName
         *
         * @return string $pageID
         */
        function addPage($fileName, $dataDir) {
            $name = $this->getName($fileName, $dataDir);
            $pid = substr($fileName,0,-4);
            // Create post object
            $my_post = array();
            $my_post['post_title'] = $name;
            $my_post['post_content'] = "[rootsPersona personId='$pid'/]";
            $my_post['post_status'] = 'publish';
            $my_post['post_author'] = 0;
            $my_post['post_type'] = 'page';
            $my_post['ping_status'] = get_option('default_ping_status');
            $my_post['post_parent'] = get_option('rootsPersonaParentPage');

            // Insert the post into the database
            $pageID = wp_insert_post( $my_post );
            if($pageID != false)
            {
            	$surname = $this->getSurname($fileName, $dataDir);
                $this->createMapEntry($pid,$pageID, $name, $surname, $dataDir);
            }
            return $pageID;
        }
        
        /**
         * 
         * Enter description here ...
         * 
         * @param  $fileName
         * @param  $destDir
         */
		function processGedcomForm($fileName, $stageDir, $dataDir) {
			$transformer = new GEDTransformer();
			$transformer->transformToXML($fileName, $stageDir);
			
			// open this directory 
			$myDirectory = opendir($stageDir);
			
			// get each entry
			while($entryName = readdir($myDirectory)) {
				if (strpos($entryName, "xml") > 0) {
					// update map entry with possible name chnage, if map entry exists
					$pid = str_replace('.xml', '', $entryName);
					$name = $this->getName($entryName, $stageDir);
					if(!empty($name)) {
						$surname = $this->getSurname($entryName, $stageDir);
						$this->updateNames($pid, $name, $surname, $dataDir );
					}
					rename($stageDir . $entryName, $dataDir . $entryName);
				}	
			}

			// close directory
			closedir($myDirectory);
	
			return true;
		}
		
        /**
         *
         * @param $rootsPersonId
         *
         * @return string HTML content
         */
        function buildPersonaPage($atts,  $mysite, $dataDir, $pluginDir,$pageId) {
        	$rootsPersonId = $atts["personid"];
            $block = "buildPersonaPage:$rootsPersonId";
            $fileName =  $dataDir . $rootsPersonId . ".xml";
            if(file_exists($fileName)) {
                $xp = new XsltProcessor();
                // create a DOM document and load the XSL stylesheet
                $xsl = new DomDocument;
                $xsl->load($pluginDir . 'xsl/transformPerson2Page.xsl');

                // import the XSL stylesheet into the XSLT process
                $xp->importStylesheet($xsl);
                $xp->setParameter('','site_url',$mysite);
                $xp->setParameter('','data_dir','../../../../' . $dataDir);

                if(isset($atts['picfile1'])) {
                    $xp->setParameter('','pic0',$atts['picfile1']);
                } else {
                    $xp->setParameter('','pic0',$pluginDir . 'images/boy-silhouette.gif');
                }
                
                for ($idx=1; $idx<7;$idx++) {
                	$pic = 'picfile' . ($idx+1);
                	if(isset($atts[$pic])) {
                		$xp->setParameter('','pic'.$idx,$atts[$pic]);
                		$cap = 'piccap' . ($idx+1);
                	    if(isset($atts[$cap])) {
                			$xp->setParameter('','cap'.$idx,$atts[$cap]);
                	    }
                	}
                }
                
                // create a DOM document and load the XML data
                $xml_doc = new DomDocument;
                try {
                    $xml_doc->load($fileName);

                    // transform the XML into HTML using the XSL file
                    if (($html = $xp->transformToXML($xml_doc)) !== false) {
                        $block = $html;
                        if(current_user_can("edit_pages"))
                        {
                            $block = $block . "<div style='margin-top:10px'><a href='"
                            . get_option('siteurl') . '/?page_id=' . get_option("rootsEditPage")
                            . "&personId=" . $rootsPersonId
                            . "&srcPage=" . $pageId
                            . "'>Edit Person</a></div>";
                        }
                    } else {
                        $block = $this->returnDefaultEmpty('XSL transformation failed.',$pluginDir);
                    } // if

                } catch (Exception $e) {
                    $block = $this->returnDefaultEmpty('No Information available.',$pluginDir);
                }
            } else {
                $block = $this->returnDefaultEmpty('No Information available.',$pluginDir);
            }

            return $block;
        }
        
		/**
         * Return an empty person page
         *
         * @param $input message to be displayed
         *
         * @return string HTML content
         */
        function returnDefaultEmpty($input, $pluginDir) {
            $block = "<div class='truncate'><img src='" . $pluginDir . "images/boy-silhouette.gif' class='headerBox' />";
            $block = $block . "<div class='headerBox'><span class='headerBox'>" . $input . "</span></div></div>";
            $block = $block . "<br/><div class='personBanner'>Facts</div>";
            $block = $block . "<br/><div class='personBanner'>Ancestors</div>";
            $block = $block . "<br/><div class='personBanner'>Family Group</div>";
            $block = $block . "<br/><div class='personBanner'>Pictures</div>";
            $block = $block . "<br/><div class='personBanner'><br/></div>";
            return $block;
        }
        
        function buildPersonaIndexPage($atts,  $mysite, $dataDir, $pluginDir) {
            $block = "";
            $fileName =  $dataDir . "idMap.xml";
            if(file_exists($fileName)) {
                $xp = new XsltProcessor();
                // create a DOM document and load the XSL stylesheet
                $xsl = new DomDocument;
                $xsl->load($pluginDir . 'xsl/personaIndex.xsl');

                // import the XSL stylesheet into the XSLT process
                $xp->importStylesheet($xsl);
                $xp->setParameter('','site_url',$mysite);
                $xp->setParameter('','data_dir','../../../../' . $dataDir);
                
                // create a DOM document and load the XML data
                $xml_doc = new DomDocument;
                try {
                    $xml_doc->load($fileName);

                    // transform the XML into HTML using the XSL file
                    if (($html = $xp->transformToXML($xml_doc)) !== false) {
                        $block = $html;
                    } else {
                        $block = 'XSL transformation failed.';
                    } // if

                } catch (Exception $e) {
                    $block = 'No Information available.';
                }
            } else {
                $block = 'No Information available.';
            }

            return $block;
        }
}