<?php
//require_once ('temp.inc.php');
require_once ('include.inc.php');

class GEDTransformer {

	/**
	 * Parse a GEDCOM file into individual XML files
	 *
	 * @param $gedcomFile
	 * @param $stageDir
	 */
	public function transformToXML($gedcomFile, $stageDir, $dataDir) {
		$g = new GedcomManager();
		$g->parse($gedcomFile);

		if($g->getNumberOfSources() > 0) {
			$dom = new DOMDocument();
			if($dom->load($dataDir . "evidence.xml") === false)
			{
				throw new FileException('Unable to load ' . $dataDir . "evidence.xml");
			}
			$xpath = new DOMXPath($dom);
			$xpath->registerNamespace('cite', "http://ed4becky.net/evidence");

			foreach ($g->gedcomObjects['SrcRecs'] as $obj) {
				$nodeList = $xpath->query("/cite:evidence/cite:source[@sourceId='"
				. $obj->Id . "']");
				$this->createXMLEvidence($obj, $dom, $nodeList);
			}

			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->save("$dataDir/evidence.xml");
		}

		foreach ($g->gedcomObjects['IndiRecs'] as $obj) {
			$this->createXMLPerson($obj, $g, $stageDir, $dataDir);
		}

		foreach ($g->gedcomObjects['FamRecs'] as $obj) {
			$this->createXMLFamily($obj, $g, $stageDir, $dataDir);
		}
		print_r(memory_get_peak_usage (true));
	}


	public function createXMLPerson($person, $ged, $stageDir, $dataDir) {
		$sources = array();
		$dom = new DomDocument('1.0', 'UTF-8');
		$personEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
		$personEl->setAttributeNS  ( 'http://www.w3.org/2001/XMLSchema-instance'  , 'xsi:schemaLocation', 'http://ed4becky.net/rootsPersona ../schema/person.xsd');
		$personEl->setAttribute('id',strtolower($person->Id));
		$dom->appendChild($personEl);

		// characteristics
		$charsEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:characteristics');
		$charEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:characteristic');
		$charEl->setAttribute('type','name');
		$charEl->appendChild($dom->createTextNode(str_replace('/','',$person->getFullName())));
		$charsEl->appendChild($charEl);

		$charEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:characteristic');
		$charEl->setAttribute('type','surname');
		$charEl->appendChild($dom->createTextNode(str_replace('/','',$person->getSurname())));
		$charsEl->appendChild($charEl);

		$charEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:characteristic');
		$charEl->setAttribute('type','gender');
		$charEl->appendChild($dom->createTextNode($person->Gender));
		$charsEl->appendChild($charEl);

		$personEl->appendChild($charsEl);

		$events = $person->Events;
		if($events != null) {
			$eventsEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:events');
			foreach($events as $event) {
				$tag = $event->Tag;
				if($tag === 'EVEN')
				$tag = $event->Type;
				$eventEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:event');
				$type = isset($event->_TYPES[$tag])?$event->_TYPES[$tag]:$tag;
				$eventEl->setAttribute('type',$type);
				if($event->Date != null) {
					$dateEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:date');
					$dateEl->appendChild($dom->createTextNode($event->Date));
					$eventEl->appendChild($dateEl);
				}
				if($event->Place != null) {
					$placeEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:place');
					$placeEl->appendChild($dom->createTextNode($event->Place->Name));
					$eventEl->appendChild($placeEl);
				}

				$eventsEl->appendChild($eventEl);
				$this->addCitations($person, $event, $eventEl, $dom, $sources, $dataDir);
			}
		}

		$spouses = $person->SpouseFamilyLinks;
		$cnt =  count($spouses);
		for($i = 0; $i <$cnt; $i++) {

			$spouse = $ged->getFamily($spouses[$i]->FamilyId);
			if($spouse != null) {
				if($person->Gender == 'M')
				$sid = $spouse->Wife;
				else
				$sid = $spouse->Husband;
				$sid = $sid==''?"p000":strtolower($sid);
				//print_r($spouse . ' ' . $spouses[$i]->FamilyId);
				$ev = $spouse->getEvent('MARR');
				if($ev != null) {
					$date = $ev->Date;
					$place = $ev->Place->Name;
					$eventEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:event');
					$eventEl->setAttribute('type','Marriage');
					$dateEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:date');
					$dateEl->appendChild($dom->createTextNode($date));
					$eventEl->appendChild($dateEl);
					$placeEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:place');
					$placeEl->appendChild($dom->createTextNode($place));
					$eventEl->appendChild($placeEl);
					$p = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
					$p->setAttribute('id',strtolower($sid));
					$eventEl->appendChild($p);
					if(!isset($eventsEl))
					$eventsEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:events');
					$eventsEl->appendChild($eventEl);
					$this->addCitations($person, $ev,$eventEl, $dom, $sources, $dataDir);
				}
			} else {
				//print($person->Id . "-" . $spouses[$i]->FamilyId ."\n");
			}
		}
		if(isset($eventsEl)) $personEl->appendChild($eventsEl);

		// references
		$referencesEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:references');
		$parents = null;
		$familiesEl = null;
		if (count($person->ChildFamilyLinks) > 0) {
			$parents = $ged->getFamily($person->ChildFamilyLinks[0]->FamilyId);
			$familyEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:familyGroup');
			$familyEl->setAttribute('selfType','child');
			if(!isset($parents->Id) || $parents->Id == "")
			$fid = 'f000';
			else $fid = $parents->Id;
			$familyEl->setAttribute('refId',strtolower($fid));
			$familiesEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:familyGroups');
			$familiesEl->appendChild($familyEl);
		}
		$fg = $person->SpouseFamilyLinks;
		$cnt = count($fg);
		for($i=0; $i<$cnt;$i++)	{
			$familyEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:familyGroup');
			$familyEl->setAttribute('selfType','parent');
			if(!isset($fg[$i]) || $fg[$i] == "")
			$fid = 'f000';
			else $fid = $fg[$i]->FamilyId;
			$familyEl->setAttribute('refId',strtolower($fid));
			if($familiesEl == null)
			$familiesEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:familyGroups');
			$familiesEl->appendChild($familyEl);
		}

		if($familiesEl != null) {
			$referencesEl->appendChild($familiesEl);
			$personEl->appendChild($referencesEl);
		}

		//relations
		$relationsEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:relations');
		if($parents) {
			$father = $parents->Husband;
			if($father == "") $father = 'p000';
			$mother = $parents->Wife;
			if($mother == "") $mother = 'p000';
		} else {
			$father = 'p000';
			$mother = 'p000';
		}

		$relationEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:relation');
		$relationEl->setAttribute('type','father');
		$p = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
		$p->setAttribute('id',strtolower($father));
		$relationEl->appendChild($p);
		$relationsEl->appendChild($relationEl);

		$relationEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:relation');
		$relationEl->setAttribute('type','mother');
		$p = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
		$p->setAttribute('id',strtolower($mother));
		$relationEl->appendChild($p);
		$relationsEl->appendChild($relationEl);

		$personEl->appendChild($relationsEl);

		$this->addCitations($person, $person,$personEl, $dom, $sources, $dataDir);

		if(count($sources) > 0)
		$this->addEvidence($sources,$personEl, $dom, $ged);

		$fileName = $stageDir . strtolower($person->Id) . '.xml';
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($fileName);
	}

	public function createXMLFamily($family, $ged, $stageDir, $dataDir) {
		$sources = array();
		$dom = new DomDocument('1.0', 'UTF-8');
		$rootEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:familyGroup');
		$rootEl->setAttributeNS  ( 'http://www.w3.org/2001/XMLSchema-instance'  , 'xsi:schemaLocation', 'http://ed4becky.net/rootsPersona ../schema/person.xsd');
		$rootEl->setAttribute('id',strtolower($family->Id));
		$dom->appendChild($rootEl);
		$parents = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:parents');
		$rootEl->appendChild($parents);
		$parent = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:relation');
		$parent->setAttribute('type','father');
		$person = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
		$id = $family->Husband == ''?"p000":$family->Husband;
		$person->setAttribute('id',strtolower($id));
		$parent->appendChild($person);
		$parents->appendChild($parent);

		$parent = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:relation');
		$parent->setAttribute('type','mother');
		$person = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
		$id = $family->Wife == ''?"p000":$family->Wife;
		$person->setAttribute('id',strtolower($id));
		$parent->appendChild($person);
		$parents->appendChild($parent);

		$children = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:children');
		$rootEl->appendChild($children);
		$cnt = count($family->Children);
		for($i=0; $i<$cnt;$i++) {
			$child = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:relation');
			$child->setAttribute('type','child');
			$children->appendChild($child);
			$person = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
			$id = $family->Children[$i] == ''?"p000":$family->Children[$i];
			$person->setAttribute('id',strtolower($id));
			$child->appendChild($person);
		}
		//		$this->addCitations($family,$rootEl, $dom, $sources);
		//
		//		if(count($sources) > 0)
		//			$this->addEvidence($sources,$rootEl, $dom, $ged);
			
		$fileName = $stageDir . strtolower($family->Id) . '.xml';
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($fileName);
	}


	public function createXMLEvidence($source, $dom, $nodes) {
		if($nodes->length > 0) {
			$entryEl = $nodes->item(0);
			$aNodes = $entryEl->getElementsByTagName ('abbr');
			$abbrEl = $aNodes->item(0);
			$abbrEl->nodeValue = htmlentities ($source->AbbreviatedTitle);
			$tNodes = $entryEl->getElementsByTagName ('title');
			$titleEl = $tNodes->item(0);
			$titleEl->nodeValue = htmlentities ($source->Title);
		} else {
			$rootNode = $dom->documentElement;
			$entryEl = $dom->createElementNS('http://ed4becky.net/evidence', 'cite:source');
			$entryEl->setAttribute('sourceId',strtolower($source->Id));
			//$entryEl->setAttribute('pageId','');
			$rootNode->appendChild($entryEl);
			$abbrEl = $dom->createElementNS('http://ed4becky.net/evidence', 'cite:abbr');
			$abbrEl->nodeValue = htmlentities ($source->AbbreviatedTitle);
			$entryEl->appendChild($abbrEl);
			$titleEl = $dom->createElementNS('http://ed4becky.net/evidence', 'cite:title');
			$titleEl->nodeValue = htmlentities ($source->Title);
			$entryEl->appendChild($titleEl);
		}
	}

	function addCitations($persona, $rec, $node, $dom, &$sources, $dataDir) {
		if(count($rec->Citations) > 0) {
			$dom = new DOMDocument();
			if($dom->load($dataDir . "/evidence.xml") === false) {
				throw new Exception("Unable to load " . $dataDir . "/evidence.xml");
			}
			$xpath = new DOMXPath($dom);
			$xpath->registerNamespace('cite', "http://ed4becky.net/evidence");
			foreach($rec->Citations as $citation) {
				$nodeList = $xpath->query("/cite:evidence/cite:source[@sourceId='"
					. strtolower($citation->SourceId) . "']");
				if($nodeList->length > 0) {
					$page = ((!isset($citation->Page)||empty($citation->Page))?'Unspecified':$citation->Page);
					$citeList = $xpath->query("./cite:citation/cite:detail[text()='".$page."']/.."
					,$nodeList->item(0));
					if($citeList->length > 0) {
						$personNodes = $xpath->query("./cite:person[@id='".strtolower($persona->Id)."']"
						,$citeList->item(0));
						if($personNodes->length <= 0) {
							$person = $dom->createElementNS('http://ed4becky.net/evidence', 'cite:person');
							$person->setAttribute('id', strtolower($persona->Id));
							$citeList->item(0)->appendChild($person);
						} else {
							// person already exists, move on
						}
					} else {
						// no citation yet
						$cite = $dom->createElementNS('http://ed4becky.net/evidence', 'cite:citation');
						$cite->setAttribute('sourceId',strtolower($citation->SourceId));
						$nodeList->item(0)->appendChild($cite);
						$detail = $dom->createElementNS('http://ed4becky.net/evidence', 'cite:detail');
						$detail->nodeValue = $page;
						$cite->appendChild($detail);
						$person = $dom->createElementNS('http://ed4becky.net/evidence', 'cite:person');
						$person->setAttribute('id', strtolower($persona->Id));
						$cite->appendChild($person);
					}
				} else {
					//no source node
				}
			}
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->save("$dataDir/evidence.xml");
		}
	}

	function addEvidence($sources, $rootEl, $dom, $ged) {
		$evidence = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:evidence');
		$rootEl->appendChild($evidence);
		$rows = array_unique($sources);
		foreach($rows as $row) {
			$tokStr = explode(":::", $row);
			$source = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:source');
			$evidence->appendChild($source);
			$src = $ged->getSource($tokStr[0]);
			if(isset($src)) {
				$page = (count($tokStr)>1)?$tokStr[1]:'';
				if(isset($page) && !empty($page)) $page = ';' . $page;
				$source->appendChild($dom->createTextNode($src->Title . $page));
				$source->setAttribute('id',str_replace('@', '', $tokStr[0]));
			}
		}
	}

	/** Prettifies an XML string into a human-readable and indented work of art
	 *  @param string $xml The XML as a string
	 *  @param boolean $html_output True if the output should be escaped (for use in HTML)
	 */
	function xmlpp($xml, $html_output=false) {
		$xml_obj = new SimpleXMLElement($xml);
		$level = 5;
		$indent = 0; // current indentation level
		$pretty = array();

		// get an array containing each XML element
		$xml = explode("\n", preg_replace('/>\s*</', ">\n<", $xml_obj->asXML()));

		// shift off opening XML tag if present
		if (count($xml) && preg_match('/^<\?\s*xml/', $xml[0])) {
			$pretty[] = array_shift($xml);
		}

		foreach ($xml as $el) {
			if (preg_match('/^<([\w])+[^>\/]*>$/U', $el)) {
				// opening tag, increase indent
				$pretty[] = str_repeat(' ', $indent) . $el;
				$indent += $level;
			} else {
				if (preg_match('/^<\/.+>$/', $el)) {
					$indent -= $level;  // closing tag, decrease indent
				}
				if ($indent < 0) {
					$indent += $level;
				}
				$pretty[] = str_repeat(' ', $indent) . $el;
			}
		}
		$xml = implode("\n", $pretty);
		return ($html_output) ? htmlentities($xml) : $xml;
	}
}

//$tr = new GEDTransformer();
//$tr->transformToXML("C:\\Users\\ed\\Desktop\\20110208.ged", "C:\\development\\xampp\\htdocs\\wpuser1\\wp-content\\plugins\\rootspersona\\php\\out\\","C:\\development\\xampp\\htdocs\\wpuser1\\wp-content\\plugins\\rootspersona\\rootsData\\") 

?>
