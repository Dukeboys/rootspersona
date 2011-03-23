<?php

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
?>