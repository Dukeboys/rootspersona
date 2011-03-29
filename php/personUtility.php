<?php
require_once 'GEDTransformer.php';


class PersonUtility {

	function createDataDir($pluginDir, $rootsDataDir) {
		if(!is_dir($rootsDataDir)) {
			$utility = new personUtility();
			$utility->recurse_copy($pluginDir . "rootsData/", $rootsDataDir);
		} else {
			copy($pluginDir . "rootsData/p000.xml", $rootsDataDir ."p000.xml");
			copy($pluginDir . "rootsData/f000.xml", $rootsDataDir ."f000.xml");
			copy($pluginDir . "rootsData/templatePerson.xml", $rootsDataDir ."templatePerson.xml");
			copy($pluginDir . "rootsData/README.txt", $rootsDataDir ."README.txt");
			if(!is_file($rootsDataDir . "idMap.xml"))
			copy($pluginDir . "rootsData/idMap.xml", $rootsDataDir ."idMap.xml");
			if(!is_file($rootsDataDir . "evidence.xml"))
			copy($pluginDir . "rootsData/evidence.xml", $rootsDataDir ."evidence.xml");
		}
	}

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
				//$pid = substr($filename,0,-4);
				//if(!$this->isExcluded($pid, $dataDir))
				$files[] = $filename;
			}
		}
		return $files;
	}

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
		if($nodeList->length > 0) {
			$nodeList->item(0)->setAttribute('excludeLiving',$value);
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->save($dataDir . "/idMap.xml");
		}
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

	public function isExcluded($pid, $dataDir ) {
		$dom = new DOMDocument();
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
		$q = "/map:idMap/map:entry[@personId=$pid and @excludeLiving='true']";
		$nodeList = $xpath->query($q);
		return $nodeList->length > 0;;
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

	function processGedcomForm($fileName, $stageDir, $dataDir) {
		$transformer = new GEDTransformer();
		$transformer->transformToXML($fileName, $stageDir, $dataDir);
			
		// open this directory
		$myDirectory = opendir($stageDir);
			
		// get each entry
		while($entryName = readdir($myDirectory)) {
			if (strpos($entryName, "xml") > 0) {
				// update map entry with possible name change, if map entry exists
				$pid = str_replace('.xml', '', $entryName);
				$name = $this->getName($entryName, $stageDir);
				if(!empty($name)) {
					$surname = $this->getSurname($entryName, $stageDir);
					if(empty($surname)) {
						$surname = 'Unknown';
					}
					$this->updateNames($pid, $name, $surname, $dataDir );
				}
				rename($stageDir . $entryName, $dataDir . $entryName);
				set_time_limit(60);
			}
		}

		// close directory
		closedir($myDirectory);

		return true;
	}

	function returnDefaultEmpty($input, $mysite, $pluginUrl) {
		$block = "<div class='truncate'><img src='" . $pluginUrl ."rootspersona/images/boy-silhouette.gif' class='headerBox' />";
		$block = $block . "<div class='headerBox'><span class='headerBox'>" . $input . "</span></div></div>";
		$block = $block . "<br/><div class='personBanner'><br/></div>";
		return $block;
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
