<?php
require_once 'GEDTransformer.php';

class PersonUtility {

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
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}

		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', "http://ed4becky.net/idMap");
		$nodes = $xpath->query("/map:idMap/map:entry");
		$ids = array();
		$cnt = $nodes->length;
		for ($i=0; $i < $cnt; $i++)  {
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
		if($dom->load($dataDir . "/" . $fileName) === false) {
			throw new Exception("Unable to load " . $dataDir . "/" . $fileName);
		}
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");
		$nodeList = $xpath->query('/persona:person/persona:characteristics/persona:characteristic[@type="name"]');
		$name = $nodeList->length>0?$nodeList->item(0)->nodeValue:'';
		return $name;
	}	
	
	/**
	 * 
	 * Enter description here ...
	 * @param  $fileName
	 * @param  $dataDir
	 */
	public function getSurname($fileName, $dataDir) {
		$dom = new DOMDocument();
		if($dom->load($dataDir . "/" . $fileName) === false) {
			throw new Exception("Unable to load " . $dataDir . "/" . $fileName);
		}
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('persona', "http://ed4becky.net/rootsPersona");
		$nodeList = $xpath->query('/persona:person/persona:characteristics/persona:characteristic[@type="surname"]');
		$surname = $nodeList->length>0?$nodeList->item(0)->nodeValue:'';
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
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}
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
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}
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
		}
	}
	
	public function updateExcluded($pid, $value, $dataDir ) {
		// add to idMap.xml
  		$dom = new DOMDocument();
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}
		$xpath = new DOMXPath($dom);
        $xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
        $nodeList = $xpath->query('/map:idMap/map:entry[@personId="' . $pid . '"]');
		foreach($nodeList as $entryEl) {
			$entryEl->setAttribute('excludeLiving',$value);
		}
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($dataDir . "/idMap.xml");
	}

	public function getExcluded($dataDir ) {
  		$dom = new DOMDocument();
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}
		$xpath = new DOMXPath($dom);
        $xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
        $nodeList = $xpath->query('/map:idMap/map:entry[@excludeLiving="true"]');
        $persons = null;
		$cnt = 0;
		foreach($nodeList as $entryEl) {
			$persons[$cnt]['name'] = $entryEl->nodeValue;
			$persons[$cnt]['id'] = $entryEl->getAttribute('personId');
			$cnt++;
		}
		return $persons;
	}
	
	function deleteIdMapNode($pid, $dataDir) {
  		$dom = new DOMDocument();
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}
		$xpath = new DOMXPath($dom);
        $xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
        $nodeList = $xpath->query('/map:idMap/map:entry[@personId="' . $pid . '"]');
        $root = $dom->documentElement;
		foreach($nodeList as $entryEl) {
			$root->removeChild($entryEl);
		}
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($dataDir . "/idMap.xml");		
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
			$block = $block . "<br/><div style='text-align:center;color:red;font-weight:bold'>".__('All available files have been added.', 'rootspersona')."</div>";
		} else {
			$block = $block . "<form  action='".$action."' method='POST'>";
			$block = $block . "<br/><select multiple name='fileNames[]' size='16'>";
			$cnt = count($files);
			for($i = 0; $i < $cnt; $i++) {
				$name = $this->getName($files[$i], $dataDir);
  				$block = $block . "<option value='".$files[$i]."'>".$name."</option>";
			}
			$block = $block . "</select><br/>";	
			$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>".$msg."</div>";
		
			$block = $block . "<br/><input type='submit' name='submitAddPageForm' value='" .  __('Submit', 'rootspersona') . "'/>";
			$block = $block . "&#160;&#160;<input type='reset' name='reset' value='" . __('Reset', 'rootspersona') ."'/>";

			$block = $block . "<br/><br/><div class='personBanner'><br/></div>";
			$block = $block . "</form>";
		}
		return $block;
	}

	public function showIncludePageForm($action,$persons, $msg='') {
		$block = "<br/><div class='personBanner'><br/></div>";
		if(count($persons) == 0) {
			$block = $block . "<br/><div style='text-align:center;color:red;font-weight:bold'>"
				.  sprintf(__('All %s have been included.', 'rootspersona'),"personas") ."</div>";
		} else {
			$block = $block . "<form  action='".$action."' method='POST'>";
			$block = $block . "<br/><select multiple name='persons[]' size='16'>";
			$cnt = count($persons);
			for($i = 0; $i < $cnt; $i++) {
				$name = $persons[$i]['name'];
  				$block = $block . "<option value='" . $persons[$i]['id'] . "'>$name</option>";
			}
			$block = $block . "</select><br/>";	
			$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>";
		
			$block = $block . "<br/><input type='submit' name='submitIncludePageForm' value='" . __('Include', 'rootspersona') ."'/>";
			$block = $block . "&#160;&#160;<input type='reset' name='reset' value='" . __('Reset', 'rootspersona') ."'/>";

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
	public function showUploadGedcomForm($action,$dataDir,$stageDir, $msg='') {
		
		$block = "<br/><div class='personBanner'><br/></div><form enctype='multipart/form-data' action='$action' method='POST'>";
		if(!is_dir($dataDir)) {
			$block = $block .  "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>".__('Data Directory', 'rootspersona') ." "
				. $dataDir  ." ". sprintf(__('does not exist. Make sure %s is writable, then reactivate plugin.', 'rootspersona'),"wp-content") ."</p>";
		} else if (!is_writable($dataDir)) {
			$block = $block .  "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>".__('Data Directory', 'rootspersona')." "
			. $dataDir ." ".__('is not writable. Update directory permissions, then reactivate plugin.', 'rootspersona')."</p>";
		} else if (!is_writable($stageDir)) {
			$block = $block .  "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>".__('Data Directory', 'rootspersona')." "
			. $stageDir . " ".__('is not writable. Update directory permissions.', 'rootspersona')."</p>";			
		}
		$block = $block . "<br/>&#160;&#160;<input type='file' name='gedcomFile' size='70'/>";
		$block = $block . "<br/>&#160;&#160;<input type='submit' class='button' name='submitUploadGedcomForm' value='".__('Upload', 'rootspersona')."'/>";
		$block = $block . "&#160;&#160;<input type='reset' name='reset' value='".__('Reset', 'rootspersona')."'/>";
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
			$transformer->transformToXML($fileName, $stageDir, $dataDir);
			
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
        function buildPersonaPage($atts,  $callback, $mysite, $dataDir, $pluginDir, $pageId) {
        	$rootsPersonId = $atts["personid"];
            $block = "";
            $fileName =  $dataDir . $rootsPersonId . ".xml";
            if(file_exists($fileName)) {
                $xp = new XsltProcessor();
                // create a DOM document and load the XSL stylesheet
                $xsl = new DomDocument;
                if(isset($atts["xsl"]))
                	$xslFile = $atts["xsl"];
                if(!isset($xslFile) || $xslFile == '')
                	$xslFile = $pluginDir . 'xsl/transformPerson2Page.xsl';
            	if($xsl->load($xslFile) === false) {
					throw new Exception("Unable to load " . $xslFile);
				}
				
                // import the XSL stylesheet into the XSLT process
                $xp->importStylesheet($xsl);
                $xp->setParameter('','site_url',$mysite);
                $xp->setParameter('','data_dir',$dataDir);
                $callback = strtolower ($callback);
                if($callback == 'rootspersona') {
                	$xp->setParameter('','hideHdr',get_option('rootsHideHeader'));
    				$xp->setParameter('','hideFac',get_option('rootsHideFacts'));
   					$xp->setParameter('','hideAnc',get_option('rootsHideAncestors'));
    				$xp->setParameter('','hideFamC',get_option('rootsHideFamilyC'));
    				$xp->setParameter('','hideFamS',get_option('rootsHideFamilyS'));
    				$xp->setParameter('','hidePic',get_option('rootsHidePictures'));
    				$xp->setParameter('','hideEvi',get_option('rootsHideEvidence'));
    				$xp->setParameter('','hideBanner',0);
                } else {
                	$xp->setParameter('','hideHdr',1);
    				$xp->setParameter('','hideFac',1);
   					$xp->setParameter('','hideAnc',1);
    				$xp->setParameter('','hideFamC',1);
    				$xp->setParameter('','hideFamS',1);
    				$xp->setParameter('','hidePic',1);
    				$xp->setParameter('','hideEvi',1);
               		$xp->setParameter('','hideBanner',1);
    				if($callback == 'rootspersonaheader') {
                		$xp->setParameter('','hideHdr',0);
                	} else if($callback == 'rootspersonafacts') {
    					$xp->setParameter('','hideFac',0);              	
                	} else if($callback == 'rootspersonaancestors') {
   						$xp->setParameter('','hideAnc',0);               	
                	} else if($callback == 'rootspersonafamilyc') {
    					$xp->setParameter('','hideFamC',0);  
    				} else if($callback == 'rootspersonafamilys') {
    					$xp->setParameter('','hideFamS',0);              	
  	              	} else if($callback == 'rootspersonapictures') {
    					$xp->setParameter('','hidePic',0);                	
					} else if($callback == 'rootspersonaevidence') {
    					$xp->setParameter('','hideEvi',0);                	
                	}
                }
                $xp->setParameter('','hideDates',get_option('rootsPersonaHideDates'));
                $xp->setParameter('','hidePlaces',get_option('rootsPersonaHidePlaces'));
                
                if(isset($atts['picfile1'])) {
                    $xp->setParameter('','pic0',$atts['picfile1']);
                } else {
                    $xp->setParameter('','pic0',$mysite . '/wp-content/plugins/rootspersona/images/boy-silhouette.gif');
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
                	if($xml_doc->load($fileName) === false)
					{
						throw new Exception('Unable to load ' . $fileName);  
					}

                    // transform the XML into HTML using the XSL file
                    if ((($html = $xp->transformToXML($xml_doc)) !== false)
                    	 || empty($html)) {
                        $block = $html;
                        if((get_post_type($pageId) != 'post') 
                        	&& (current_user_can("edit_pages"))
                        	&& get_option('rootsHideEditLinks') != 1)
                        {
                        	
							
                        	$win1 = __('Page will be removed but supporting data will not be deleted.  Proceed?', 'rootspersona');
                        	$win2 = __('Page will be removed and supporting data will be deleted.  Proceed?', 'rootspersona');
                        	$win3 = __('Page will be viewable by logged in members only.  Proceed?', 'rootspersona');
                        	$win4 = __('Page will be viewable by anyone.  Proceed?', 'rootspersona');
                        	$editPage = $mysite . '/?page_id=' . get_option("rootsEditPage")
                        	            . "&personId=" . $rootsPersonId
                            			. "&srcPage=" . $pageId . "&action=";
                            			
                            $block = $block . "<div style='margin-top:10px;text-align: center ;'><a href='".$editPage
                            . "edit'>".__('Edit Person', 'rootspersona')."</a>"
                            . "&#160;&#160;<a href='#'"
                            . " onClick='javascript:rootsConfirm(\"" . $win1 . "\",\"" 
                            . $editPage . "exclude\");return false;'>".__('Exclude Person', 'rootspersona')."</a>"
                            . "&#160;&#160;<a href='#'"
                            . " onClick='javascript:rootsConfirm(\"" . $win2 . "\",\"" 
                            . $editPage . "delete\");return false;'>".__('Delete Person', 'rootspersona')."</a>"                            
                            . "&#160;&#160;<a href='#'";
                            
                            $perms = get_post_meta($pageId, 'permissions', true);
                            if ( empty($perms) || $perms == 'false') {
                            	$block = $block .  " onClick='javascript:rootsConfirm(\"" . $win3 . "\",\"" 
                           		. $editPage . "makePrivate\");return false;'>".__('Make Person Private', 'rootspersona')."</a>";     
                            }  else {                      
                            	$block = $block .  " onClick='javascript:rootsConfirm(\"" . $win4 . "\",\"" 
                            	. $editPage . "makePublic\");return false;'>".__('Make Person Public', 'rootspersona')."</a>" ; 
                            }                                                     
                            $block = $block .  "</div>";
                        }
                    } else {
                        $block = $this->returnDefaultEmpty(__('XSL transformation failed.', 'rootspersona'),$mysite,$pluginDir);
                    } // if

                } catch (Exception $e) {
                    $block = $this->returnDefaultEmpty(__('No Information available.', 'rootspersona'),$mysite,$pluginDir);
                }
            } else {
                $block = $this->returnDefaultEmpty(__('No Information available.', 'rootspersona'),$mysite,$pluginDir);
            }
            return $block;
        }
        
		/**
         * Return an empty person page
         *
         * @param message to be displayed
         *
         * @return string HTML content
         */
        function returnDefaultEmpty($input, $mysite, $pluginUrl) {
            $block = "<div class='truncate'><img src='" . $pluginUrl ."rootspersona/images/boy-silhouette.gif' class='headerBox' />";
            $block = $block . "<div class='headerBox'><span class='headerBox'>" . $input . "</span></div></div>";
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
                if(isset($atts["xsl"]))
                	$xslFile = $atts["xsl"];
                if(!isset($xslFile) || $xslFile == '')
                	$xslFile = $pluginDir . 'xsl/personaIndex.xsl';     
               	if($dom->load($xslFile) === false)
				{
					throw new Exception('Unable to load ' . $xslFile);  
				}	           
                // import the XSL stylesheet into the XSLT process
                $xp->importStylesheet($xsl);
                $xp->setParameter('','site_url',$mysite);
                $xp->setParameter('','data_dir', $dataDir);

                
                // create a DOM document and load the XML data
                $xml_doc = new DomDocument;
                try {
                    if($dom->load($fileName) === false)
					{
						throw new Exception('Unable to load ' . $fileName);  
					}                    

                    // transform the XML into HTML using the XSL file
                    if (($html = $xp->transformToXML($xml_doc)) !== false) {
                        $block = $html;
                    } else {
                        $block = __('XSL transformation failed.', 'rootspersona');
                    } // if

                } catch (Exception $e) {
                    $block = __('No Information available.', 'rootspersona');
                }
            } else {
                $block = __('No Information available.', 'rootspersona');
            }

            return $block;
        }
}
