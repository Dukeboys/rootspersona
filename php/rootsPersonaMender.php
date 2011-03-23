<?php
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/personUtility.php');

class rootsPersonaMender {
	function validateMap ($dataDir, $isRepair=false) {
		if(!is_file($dataDir . "idMap.xml")) {
			echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>"
			. __('Missing idMap.xml in', 'rootspersona') ." ". $dataDir ."</p>";

			echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
			.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
			. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
			.  "</div>";
			return;
		} else if (!is_writable($dataDir . "idMap.xml")) {
			echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>"
			. __('idMap.xml is not writable in', 'rootspersona') ." ". $dataDir ."</p>";

			echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
			.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
			. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
			.  "</div>";
			return;
		}

		$utility = new personUtility();

		$dom = new DOMDocument();
		if($dom->load($dataDir . "/idMap.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/idMap.xml");
		}
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
		$nodeList = $xpath->query('/map:idMap/map:entry');
		$cnt = 1;
		$isFirst = true;
		$isEmpty = $nodeList->length<= 0;

		foreach($nodeList as $entryEl) {
			$output = array();
			while(sizeof($output) == 0) {
				$personId = $entryEl->getAttribute('personId');
				if(!isset($personId) || $personId == '') {
					if($isRepair) {
						$output[] = sprintf(__("Invalid %s, deleting element from idMap.xml", 'rootspersona'),"personId");
						$entryEl->parentNode->removeChild($entryEl);
						break;
					} else {
						$output[] = sprintf(__("Invalid %s in idMap.xml", 'rootspersona'),"personId");
					}
				} else {
					$fileName =  $dataDir . $personId . ".xml";
					if(!file_exists($fileName)) {
						if($isRepair) {
							$output[] = __("Missing file, deleting element from idMap.xml", 'rootspersona');
							$entryEl->parentNode->removeChild($entryEl);
							break;
						} else {
							$output[] = __("Missing file:", 'rootspersona') ." ". $fileName;
						}
					}
				}
					
				$pageId = $entryEl->getAttribute('pageId');
				$exclude = $entryEl->getAttribute('excludeLiving');
				if(!isset($pageId) || $pageId == '') {
					if(!isset($exclude) || $exclude != 'true') {
						if($isRepair) {
							$output[] = sprintf(__("Invalid %s, deleting element from idMap.xml", 'rootspersona'),"pageId");
							$entryEl->parentNode->removeChild($entryEl);
							break;
						} else {
							$output[] = sprintf(__("Invalid %s in idMap.xml", 'rootspersona'),"pageId");
						}
					}
				} elseif (isset($exclude) && $exclude == 'true') {
					if($isRepair) {
						$output[] = sprintf(__("%s defined for excluded person, deleting page and removing %s.", 'rootspersona'),"pageId","pageId");
						wp_delete_post($pageId);
						$entryEl->setAttribute('pageId', '');
					} else {
						$output[] = sprintf(__("%s defined in idMap.xml for excluded person.", 'rootspersona'),"pageId");
					}
				}

				$surName = $entryEl->getAttribute('surName');
				if(!isset($surName) || empty($surName)) {
					if($isRepair) {
						$surName = $utility->getSurname($personId . '.xml', $dataDir);
						if(empty($surName)) $surName = 'Unknown';
						$output[] = __("Missing surName: updating surName in idMap.xml to ", 'rootspersona') . $surName;
						$entryEl->setAttribute('surName', $surName);
					} else {
						$output[] = __("Missing surName in idMap.xml.", 'rootspersona');
					}
				}
					
				$fullName = $entryEl->nodeValue;
				if(!isset($fullName) || empty($fullName)) {
					if($isRepair) {
						$fullName = $utility->getName($personId . '.xml', $dataDir);
						$output[] = __("Invalid name: updating name in idMap.xml to ", 'rootspersona') . $fullName;
						$entryEl->nodeValue = $fullName;
					} else {
						$output[] = __("Invalid name in idMap.xml.", 'rootspersona');
					}
				} else if($fullName != $utility->getName($personId . '.xml', $dataDir)) {
					if($isRepair) {
						$fullName = $utility->getName($personId . '.xml', $dataDir);
						$output[] = __("Invalid name: updating name in idMap.xml to ", 'rootspersona') . $fullName;
						$entryEl->nodeValue = $fullName;
					} else {
						$output[] = __("Invalid name in idMap.xml.", 'rootspersona');
					}
				}

				if(isset($pageId) && !empty($pageId)) {
					$post = get_post($pageId);
					if(!isset($post) && $exclude != 'true') {
						if($isRepair) {
							$output[] = __("Page does not exist: deleting element from idMap.xml", 'rootspersona');
							$entryEl->parentNode->removeChild($entryEl);
							break;
						} else {
							$output[] = __("Expected post for page", 'rootspersona') ." (".$pageId.") ".__("does not exist.", 'rootspersona');
						}
					} else if(isset($post) && $exclude == 'true') {
						if($isRepair) {
							$output[] = __("Page defined for excluded person: deleting page.", 'rootspersona');
							wp_delete_post($pageId);
							break;
						} else {
							$output[] = __("Page defined in idMap.xml for excluded person. Delete page to avoid security risk.", 'rootspersona');
						}
					}

					if($post->post_title != $fullName) {
						if($isRepair) {
							$output[] = __("Page title out of synch: updating page title.", 'rootspersona');
							$my_post = array();
							$my_post['ID'] = $pageId;
							$my_post['post_title'] = $fullName;
							wp_update_post( $my_post );
						} else {
							$output[] = __("Page title", 'rootspersona') ." (".$post->post_title
							.") ".__("does not reflect the name in idMap.xml", 'rootspersona')." (".$fullName .")";
						}
					}

					$content = $post->post_content;
					if(!preg_match("/rootsPersona /", $content)) {
						if($isRepair) {
							$output[] = sprintf(__("Invalid %s page: deleting element from idMap.xml", 'rootspersona'),"persona");
							$entryEl->parentNode->removeChild($entryEl);
							break;
						} else {
							$output[] = sprintf(__("Invalid %s page for", 'rootspersona'),"persona")." ". $fullName
							." (".__("page", 'rootspersona')." ".$pageId .")";
						}
					}

					$pagePerson = @preg_replace(
           							'/.*?personId=[\'|"](.*)[\'|"].*?/US'
           							, '$1'
           							, $content);
           							if($pagePerson != $personId) {
           								if($isRepair) {
           									$output[] = sprintf(__("%s out of synch with %s on page: deleting element from idMap.xml", 'rootspersona'),"personId","personId");
           									$entryEl->parentNode->removeChild($entryEl);
           									break;
           								} else {
           									$output[] = sprintf(__("%s referenced in idMap.xml", 'rootspersona'),"personId")." (".$personId.") ".__("does not reference", 'rootspersona') . " ". $fullName
           									." (pageId ".$pageId . ", personId ".$pagePerson.")";
           								}
           							}
				}
				break;
			}

			foreach ($output as $line) {
				if($isFirst) {
					echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
					. sprintf(__('Issues found with your %s map file.', 'rootspersona'),"rootsPersona")."</p>";
					$isFirst = false;
				}
				echo __("Entry", 'rootspersona')." ". $cnt . ": " .$line . "<br/>";
			}
			$cnt++;
		}
		if(!$isFirst && $isRepair) {
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->save($dataDir . "/idMap.xml");
		}
		$footer =   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>";
		if($isEmpty) {
			$footer =  $footer . "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
			. __('idMap.xml is empty.', 'rootspersona')."</p>"
			. "<span>&#160;&#160;</span>";
		} else if($isValid) {
			$footer =$footer .  "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
			. sprintf(__('Your %s setup is VALID.', 'rootspersona'),"rootsPersona")."</p>"
			. "<span>&#160;&#160;</span>";
		} else if(!$isRepaired) {
			$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' "
			. site_url() . "?page_id=" . get_option('rootsUtilityPage')
			. "&utilityAction=repairPages'>" . __('Repair Inconsistencies?', 'rootspersona') . "</a></span>"
			. "<span>&#160;&#160;</span>";
		}
		$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
		. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
		.  "</div>";
		echo $footer;
	}

	function validateEvidencePages ($dataDir, $isRepair=false) {
		if(!is_file($dataDir . "evidence.xml")) {
			echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>"
			. __('Missing evidence.xml in', 'rootspersona') ." ". $dataDir ."</p>";

			echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
			.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
			. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
			.  "</div>";
			return;
		} else if (!is_writable($dataDir . "evidence.xml")) {
			echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>"
			. __('evidence.xml is not writable in', 'rootspersona') ." ". $dataDir ."</p>";

			echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
			.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
			. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
			.  "</div>";
			return;
		}

		$dom = new DOMDocument();
		if($dom->load($dataDir . "/evidence.xml") === false) {
			throw new Exception("Unable to load " . $dataDir . "/evidence.xml");
		}
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('cite', 'http://ed4becky.net/evidence');
		$nodeList = $xpath->query('/cite:evidence/cite:source');
		$cnt = 1;
		$isFirst = true;
		$isEmpty = $nodeList->length<= 0;

		foreach($nodeList as $entryEl) {
			$output = array();
			while(sizeof($output) == 0) {
				$sourceId = $entryEl->getAttribute('sourceId');
				$pageId = $entryEl->getAttribute('pageId');
				if(!isset($pageId) || empty($pageId)) {
					if($isRepair) {
						$output[] = sprintf(__("Creating page for %s.", 'rootspersona'),$sourceId);
						$pageId = $this->createEvidencePage($sourceId, sprintf("[rootsEvidencePage sourceId='%s'/]", $sourceId));
						$entryEl->setAttribute('pageId',$pageId);
						break;
					} else {
						$output[] = sprintf(__("No page exists for %s.", 'rootspersona'),$sourceId);
					}
				}

				if(isset($pageId) && !empty($pageId)) {
					$post = get_post($pageId);
					if(!isset($post)) {
						if($isRepair) {
							$output[] = __("Page does not exist. ", 'rootspersona')
							. sprintf(__("Creating page for %s.", 'rootspersona'),$sourceId);
							$pageId = $this->createEvidencePage($sourceId, sprintf("[rootsEvidencePage sourceId='%s'/]", $sourceId));
							$entryEl->setAttribute('pageId',$pageId);
							break;
						} else {
							$output[] = sprintf(__("Page %s does not exist for %s.", 'rootspersona'), $pageId, $sourceId);
						}
					} else {

						$content = $post->post_content;
						if(!preg_match("/rootsEvidencePage/i", $content)) {
							if($isRepair) {
								$output[] = sprintf(__("Invalid shortcode for %s (page %s). ", 'rootspersona'), $sourceId, $pageId)
								. sprintf(__("Creating page for %s.", 'rootspersona'),$sourceId);
								$pageId = $this->createEvidencePage($sourceId, sprintf("[rootsEvidencePage sourceId='%s'/]", $sourceId));
								$entryEl->setAttribute('pageId',$pageId);
								break;
							} else {
								$output[] = sprintf(__("Invalid shortcode for %s (page %s). ", 'rootspersona'), $sourceId, $pageId);
							}
						}

						$pageSource = @preg_replace(
           							'/.*?sourceId=[\'|"](.*)[\'|"].*?/US'
           							, '$1'
           							, $content);
           							if($pageSource != $sourceId) {
           								if($isRepair) {
           									$output[] = sprintf(__("Invalid evidence page for %s (page %s). ", 'rootspersona'), $sourceId, $pageId)
           									. sprintf(__("Creating page for %s.", 'rootspersona'),$sourceId);
           									$pageId = $this->createEvidencePage($sourceId, sprintf("[rootsEvidencePage sourceId='%s'/]", $sourceId));
           									$entryEl->setAttribute('pageId',$pageId);
           									break;
           								} else {
           									$output[] = sprintf(__("Invalid evidence page for %s (page %s). ", 'rootspersona'), $sourceId, $pageId);
           								}
           							}

           							if($post->post_title != $sourceId) {
           								if($isRepair) {
           									$output[] = __("Page title out of synch: updating page title.", 'rootspersona');
           									$my_post = array();
           									$my_post['ID'] = $pageId;
           									$my_post['post_title'] = $sourceId;
           									wp_update_post( $my_post );
           								} else {
           									$output[] = __("Page title", 'rootspersona') ." (".$post->post_title
           									.") ".__("does not reflect the name in evidence.xml", 'rootspersona')." (".'???' .")";
           								}
           							}
					}
				}
				break;
			}

			foreach ($output as $line) {
				if($isFirst) {
					echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
					. __('Issues found with your evidence file.', 'rootspersona')."</p>";
					$isFirst = false;
				}
				echo __("Entry", 'rootspersona')." ". $cnt . ": " .$line . "<br/>";
			}
			$cnt++;
		}

		if(!$isFirst && $isRepair) {
			$dom->formatOutput = true;
			$dom->preserveWhiteSpace = false;
			$dom->save($dataDir . "/evidence.xml");
		}

		$args = array( 'numberposts' => -1, 'post_type'=>'page', 'post_status'=>'any');
		$pages = get_posts($args);
		$dom = new DOMDocument();
		$dom->load($dataDir . "evidence.xml");
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('cite', 'http://ed4becky.net/evidence');
		$parent = get_option('rootsEvidencePage');

		foreach($pages as $page) {
			$output = array();

			if(preg_match("/rootsEvidencePage *sourceId=.*/i", $page->post_content)) {
				$sid = @preg_replace( '/.*?sourceId=[\'|"](.*)[\'|"].*?/US'
				, '$1'
				, $page->post_content);
				$nodeList = $xpath->query('/cite:evidence/cite:source[@sourceId="' . $sid . '"]');
				if($nodeList->length <= 0) {
					if($isRepair) {
						$output[] = __("Deleted orphaned page with no reference in evidence.xml.", 'rootspersona');
						wp_delete_post($page->ID);
					} else {
						$output[] = __("No reference in evidence.xml.", 'rootspersona');
					}
				} else if($page->post_parent != $parent) {
					if($isRepair) {
						$output[] = sprintf(__("Updated parent page to %s.", 'rootspersona'),$parent);
						$my_post = array();
						$my_post['ID'] = $page->ID;
						$my_post['post_parent'] = $parent;
						wp_update_post( $my_post );
					} else {
						$output[] = sprintf(__("Parent page out of synch %s", 'rootspersona')," (" . $parent . ").");
					}
				}

			}
			foreach ($output as $line) {
				if($isFirst) {
					echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
					. sprintf(__('Issues found with your %s pages.', 'rootspersona'),"rootsPersona")."</p>";
					$isFirst = false;
				}
				echo __("Page", 'rootspersona')." ". $page->ID . ": " .$line . "<br/>";
			}
		}
		$footer =   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>";
		if($isEmpty) {
			$footer =  $footer . "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
			. __('evidence.xml is empty.', 'rootspersona')."</p>"
			. "<span>&#160;&#160;</span>";
		}
		else if($isFirst) {
			$footer =$footer .  "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
			. sprintf(__('Your %s setup is VALID.', 'rootspersona'),"rootsPersona")
			."</p><span>&#160;&#160;</span>";
		} else if(!$isRepair) {
			$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' "
			. site_url() . "?page_id="
			. get_option('rootsUtilityPage')
			. "&utilityAction=repairEvidencePages'>" . __('Repair Inconsistencies?', 'rootspersona')
			. "</a></span><span>&#160;&#160;</span>";
		}

		echo $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
		. admin_url() . "tools.php?page=rootsPersona'>"
		. __('Return', 'rootspersona') . "</a></span>"
		.  "</div>";


	}

	function createEvidencePage($title, $contents, $page='') {
		// Create post object
		$my_post = array();
		$my_post['post_title'] = $title;
		$my_post['post_content'] = $contents;
		$my_post['post_status'] = 'publish';
		$my_post['post_author'] = 0;
		$my_post['post_type'] = 'page';
		$my_post['ping_status'] = 'closed';
		$my_post['comment_status'] = 'closed';
		$my_post['post_parent'] = get_option('rootsEvidencePage');

		$pageID = '';
		if(empty($page)) {
			$pageID = wp_insert_post( $my_post );
		} else {
			$my_post['ID'] = $page;
			wp_update_post( $my_post );
			$pageID = $page;
		}
		return $pageID;
	}

	function validatePages ($dataDir, $isRepair=false) {
		$args = array( 'numberposts' => -1, 'post_type'=>'page', 'post_status'=>'any');
		$pages = get_posts($args);
		$cnt = 0;
		$isFirst = true;
		$dom = new DOMDocument();
		$dom->load($dataDir . "idMap.xml");
		$xpath = new DOMXPath($dom);
		$xpath->registerNamespace('map', 'http://ed4becky.net/idMap');
		$parent = get_option('rootsPersonaParentPage');

		foreach($pages as $page) {
			$output = array();

			if(preg_match("/rootsPersona /i", $page->post_content)) {
				$pid = @preg_replace( '/.*?personId=[\'|"](.*)[\'|"].*?/US'
				, '$1'
				, $page->post_content);
				$nodeList = $xpath->query('/map:idMap/map:entry[@personId="' . $pid . '"]');
				if($nodeList->length <= 0) {
					if($isRepair) {
						$output[] = __("Deleted orphaned page with no reference in idMap.xml.", 'rootspersona');
						wp_delete_post($page->ID);
					} else {
						$output[] = __("No reference in idMap.xml.", 'rootspersona');
					}
				} else if($page->post_parent != $parent) {
					if($isRepair) {
						$output[] = sprintf(__("Updated parent page to %s.", 'rootspersona'),$parent);
						$my_post = array();
						$my_post['ID'] = $page->ID;
						$my_post['post_parent'] = $parent;
						wp_update_post( $my_post );
					} else {
						$output[] = sprintf(__("Parent page out of synch %s", 'rootspersona')," (" . $parent . ").");
					}
				}
			} else if(preg_match("/rootsPersonaIndexPage/i", $page->post_content)) {
				$pageId = get_option('rootsPersonaIndexPage');
				if($pageId != $page->ID) {
					if($isRepair) {
						$output[] = sprintf(__("Deleted orphaned %s page.", 'rootspersona'),"rootsPersonaIndexPage");
						wp_delete_post($page->ID);
					} else {
						$output[] = __("Orphaned", 'rootspersona') . " rootsPersonaIndexPage.";
					}
				}
			} else if(preg_match("/rootsEditPersonaForm/i", $page->post_content)) {
				$pageId = get_option('rootsEditPage');
				if($pageId != $page->ID) {
					if($isRepair) {
						$output[] = sprintf(__("Deleted orphaned %s page.", 'rootspersona'),"rootsEditPersonaForm");
						wp_delete_post($page->ID);
					} else {
						$output[] = __("Orphaned", 'rootspersona')." rootsEditPersonaForm.";
					}
				}
			} else if(preg_match("/rootsAddPageForm/i", $page->post_content)) {
				$pageId = get_option('rootsCreatePage');
				if($pageId != $page->ID) {
					if($isRepair) {
						$output[] = sprintf(__("Deleted orphaned %s page.", 'rootspersona'),"rootsAddPageForm");
						wp_delete_post($page->ID);
					} else {
						$output[] = __("Orphaned", 'rootspersona') ." rootsAddPageForm.";
					}
				}
			} else if(preg_match("/rootsUploadGedcomForm/i", $page->post_content)) {
				$pageId = get_option('rootsUploadGedcomPage');
				if($pageId != $page->ID) {
					if($isRepair) {
						$output[] = sprintf(__("Deleted orphaned %s page.", 'rootspersona'),"rootsUploadGedcomForm");
						wp_delete_post($page->ID);
					} else {
						$output[] = __("Orphaned.", 'rootspersona') . " rootsUploadGedcomForm.";
					}
				}
			} else if(preg_match("/rootsIncludePageForm/i", $page->post_content)) {
				$pageId = get_option('rootsIncludePage');
				if($pageId != $page->ID) {
					if($isRepair) {
						$output[] = sprintf(__("Deleted orphaned %s page.", 'rootspersona'),"rootsIncludePage");
						wp_delete_post($page->ID);
					} else {
						$output[] = __("Orphaned", 'rootspersona') ." rootsIncludePage.";
					}
				}
			} else if(preg_match("/rootsUtilityPage/i", $page->post_content)) {
				$pageId = get_option('rootsUtilityPage');
				if($pageId != $page->ID) {
					if($isRepair) {
						$output[] = sprintf(__("Deleted orphaned %s page.", 'rootspersona'),"rootsUtilityPage");
						wp_delete_post($page->ID);
					} else {
						$output[] = __("Orphaned", 'rootspersona')." rootsUtilityPage.";
					}
				}
			}

			foreach ($output as $line) {
				if($isFirst) {
					echo "<p style='padding: .5em; background-color: yellow; color: black; font-weight: bold;'>"
					. sprintf(__('Issues found with your %s pages.', 'rootspersona'),"rootsPersona")."</p>";
					$isFirst = false;
				}
				echo __("Page", 'rootspersona')." ". $page->ID . ": " .$line . "<br/>";
			}
			$cnt++;
		}
		$footer =   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>";
		if($isEmpty) {
			$footer =  $footer . "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
			. __('idMap.xml is empty.', 'rootspersona')."</p>"
			. "<span>&#160;&#160;</span>";
		} else if($isValid) {
			$footer =$footer .  "<p style='padding: .5em;margin-top:.5em; background-color: green; color: white; font-weight: bold;'>"
			. sprintf(__('Your %s setup is VALID.', 'rootspersona'),"rootsPersona")."</p>"
			. "<span>&#160;&#160;</span>";
		} else if(!$isRepaired) {
			$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' "
			. site_url() . "?page_id=" . get_option('rootsUtilityPage')
			. "&utilityAction=repair'>" . __('Repair Inconsistencies?', 'rootspersona') . "</a></span>"
			. "<span>&#160;&#160;</span>";
		}
		$footer = $footer . "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
		. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
		.  "</div>";
		echo $footer;
	}

	function deletePages ($pluginDir, $rootsDataDir) {
		$args = array( 'numberposts' => -1, 'post_type'=>'page','post_status'=>'any');
		$pages = get_posts($args);
		$cnt = 0;
		foreach($pages as $page) {
			if(preg_match("/rootsPersona |rootsEvidencePage /", $page->post_content)) {
				wp_delete_post($page->ID);
				$cnt++;
			}
		}
		// since we know we just deleted everyting,
		//	just reseed the idMap.xml and evidence.xml templates.
		copy($pluginDir . "rootsData/idMap.xml", $rootsDataDir ."idMap.xml");
		copy($pluginDir . "rootsData/evidence.xml", $rootsDataDir ."evidence.xml");
		echo $cnt  ." ". __('pages deleted.', 'rootspersona')."<br/>";
		echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
		. "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px'><a href=' "
		. site_url() . "?page_id=" . get_option('rootsUtilityPage')
		. "&utilityAction=deleteFiles'>" . sprintf(__('Delete %s files as well?', 'rootspersona'),"persona") . "</a></span>"
		. "<span>&#160;&#160;</span>"
		.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
		. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
		.  "</div>";
	}

	function deleteFiles ($pluginDir, $rootsDataDir) {
		unlink($rootsDataDir);
		$utility = new PersonUtility();
		$utility->createDataDir($pluginDir, WP_CONTENT_DIR . get_option('rootsDataDir'));

		copy($pluginDir . "rootsData/idMap.xml", $rootsDataDir ."idMap.xml");
		copy($pluginDir . "rootsData/evidence.xml", $rootsDataDir . "evidence.xml");
		copy($pluginDir . "rootsData/p000.xml", $rootsDataDir . "p000.xml");
		copy($pluginDir . "rootsData/f000.xml", $rootsDataDir . "f000.xml");
		copy($pluginDir . "rootsData/templatePerson.xml", $rootsDataDir . "templatePerson.xml");
		copy($pluginDir . "rootsData/README.txt", $rootsDataDir . "README.txt");

		echo __('Files deleted.<br/>', 'rootspersona');
		echo   "<div style='text-align:center;padding:.5em;margin-top:.5em;'>"
		.  "<span class='rp_linkbutton' style='border:2px outset orange;padding:5px;'><a href=' "
		. admin_url() . "tools.php?page=rootsPersona'>" . __('Return', 'rootspersona') . "</a></span>"
		.  "</div>";
	}
}
?>
