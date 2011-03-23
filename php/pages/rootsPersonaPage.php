<?php
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

?>