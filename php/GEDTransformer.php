<?php
//require_once ('temp.inc.php');
require_once ('include.inc.php');

class GEDTransformer {

	/**
	 * Parse a GEDCOM file into individual XML files
	 *
	 * @param $gedcomFile
	 * @param $destDir
	 */
	public function transformToXML($gedcomFile, $destDir) {
		$g = new GedcomManager();
		$g->parse($gedcomFile);

		foreach ($g->gedcomObjects['IndiRecs'] as $obj) {
			$this->createXMLPerson($obj, $g, $destDir);
		}

		foreach ($g->gedcomObjects['FamRecs'] as $obj) {
			$this->createXMLFamily($obj, $g, $destDir);
		}
	}

	public function createXMLFamily($family, $ged, $dataDir) {
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
			
		$fileName = $dataDir . strtolower($family->Id) . '.xml';
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($fileName);
	}

	public function createXMLPerson($person, $ged, $dataDir) {
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

		//events
		$eventEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:event');
		$eventEl->setAttribute('type','birth');
		$dateEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:date');
		$ev = $person->getEvent('BIRT');
		if($ev != null) {
			$dateEl->appendChild($dom->createTextNode($ev->Date));
			$eventEl->appendChild($dateEl);
			if($ev->Place != null) {
				
				$placeEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:place');
				$placeEl->appendChild($dom->createTextNode($ev->Place->Name));
				$eventEl->appendChild($placeEl);
			}
			if(!isset($eventsEl))
			$eventsEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:events');
			$eventsEl->appendChild($eventEl);
			$this->addCitations($ev,$eventEl, $dom, $sources);
		}

		$ev = $person->getEvent('DEAT');
		if ($ev != null) {
			$eventEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:event');
			$eventEl->setAttribute('type','death');
			$dateEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:date');
			$dateEl->appendChild($dom->createTextNode($ev->Date));
			$eventEl->appendChild($dateEl);
			if($ev->Place != null) {
				$placeEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:place');
				$placeEl->appendChild($dom->createTextNode($ev->Place->Name));
				$eventEl->appendChild($placeEl);
			}
			if(!isset($eventsEl))
			$eventsEl = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:events');
			$eventsEl->appendChild($eventEl);
			$this->addCitations($ev,$eventEl, $dom, $sources);
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
					$eventEl->setAttribute('type','marriage');
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
					$this->addCitations($ev,$eventEl, $dom, $sources);
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

		$this->addCitations($person,$personEl, $dom, $sources);
		
		if(count($sources) > 0)
			$this->addEvidence($sources,$personEl, $dom, $ged);

		$fileName = $dataDir . strtolower($person->Id) . '.xml';
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($fileName);
	}

	function addCitations($rec, $node, $dom, &$sources) {
		if(count($rec->Citations) > 0) {
			$cites = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:citations');
			$node->appendChild($cites);
			$cnt = count($rec->Citations);
			for($i=0; $i<$cnt;$i++) {
				$citation = $rec->Citations[$i];
				$cite = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:citation');
				if(isset($citation->Page)) {
					$cite->setAttribute('page',$citation->Page);
				}
				if(isset($citation->SourceId)) {
					$cite->setAttribute('srcId',str_replace('@', '', $citation->SourceId));
					$sources[] = $citation->SourceId . ":::" .  $citation->Page;
				}
				$cites->appendChild($cite);
			}
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
				$source->appendChild($dom->createTextNode($src->Title . '; ' . $page));
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
//$tr->transformToXML("C:\\Users\\ed\\Downloads\\The Chron_Cron Family Tree.ged", "C:\\Users\\ed\\Workspaces\\Eclipse 3.6 PDT\\rootspersona\\php\\out\\") 

?>
