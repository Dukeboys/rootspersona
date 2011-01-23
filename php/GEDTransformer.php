<?php
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
		for($i=0; $i<count($family->Children);$i++) {
			$child = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:relation');
			$child->setAttribute('type','child');
			$children->appendChild($child);
			$person = $dom->createElementNS('http://ed4becky.net/rootsPersona', 'persona:person');
			$id = $family->Children[$i] == ''?"p000":$family->Children[$i];
			$person->setAttribute('id',strtolower($id));
			$child->appendChild($person);
		}

		$fileName = $dataDir . strtolower($family->Id) . '.xml';
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($fileName);
	}

	public function createXMLPerson($person, $ged, $dataDir) {
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
		}

		$spouses = $person->SpouseFamilyLinks;
		for($i = 0; $i < count($spouses); $i++) {
			$spouse = $ged->getFamily($spouses[$i]->FamilyId);
			if($person->Gender == 'M')
			$sid = $spouse->Wife;
			else
			$sid = $spouse->Husband;
			$sid = $sid==''?"p000":strtolower($sid);
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
			}
		}
		if(isset($eventsEl))
		$personEl->appendChild($eventsEl);

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
		for($i=0; $i<count($fg);$i++)	{
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

		$fileName = $dataDir . strtolower($person->Id) . '.xml';
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$dom->save($fileName);
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
?>