<?php
require_once(WP_PLUGIN_DIR  . '/rootspersona/php/paramParser.php');
function showPage($srcPage) {
	$location = site_url() . '/?page_id=' . $srcPage;
	// The wp_redirect command uses a PHP redirect at its core,
	// therefore, it will not work either after header information
	// has been defined for a page.
	return '<script type="text/javascript">window.location="' . $location . '"; </script>';
}
function showEditForm($p,$imgBase, $msg='') {
	$block = "<form  action='" . $p['action'] . "' method='POST'><div class='truncate'>";

	if(isset($p['gender']) && $p['gender'] == 'F') {
		$block = $block . "<img src='" . $imgBase. "/images/girl-silhouette.gif' class='headerBox' />";
	} else {
		$block = $block . "<img src='" . $imgBase. "/images/boy-silhouette.gif' class='headerBox' />";
	}

	$block = $block . "<div class='headerBox' style='padding-top: 5px;'>";

	if($p['isSystemOfRecord'] =="true") {
		systemOfRecordForm($p);
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
		$block = $block . "<div style='width:5em;float:left;padding-left:5px;'>".('File:')."</div>"
		. "<input  style='float:left;' value='"
		. (isset($p[$pf])?$p[$pf]:'')
		. "' type='text' name='"
		. $pf . "' id='" . $pf
		. "' size='40'></div>";
		$block = $block . "<div style='clear:both'><div style='width:2em;float:left;'>&nbsp;</div>";
		if($i == 1) {
			$block = $block . "<div style='width:6em;float:left;'>&nbsp;</div>";
			$block = $block . "<div>".__('(example: wp-content/uploads/father.jpg)', 'rootspersona')."</div></div><br/>";
		} else {
			$block = $block . "<div style='width:5em;float:left;padding-left:5px;'>".__('Caption:', 'rootspersona')."</div>"
			. "<input value='"
			. (isset($p[$pc])?$p[$pc]:'')
			. "' type='text' name='"
			. $pc . "' id='" . $pc
			. "' size='40'></div><br/>";
		}
	}

	$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' id='submitPersonForm' value='".__('Submit', 'rootspersona')."'/>";
	$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='".__('Reset', 'rootspersona')."'/></div></div>";

	$block = $block . "</div>";

	$block = $block . "<br/><div class='personBanner'><br/></div>";
	$block = $block . "<input type='hidden' name='srcPage' id='srcPage' value='" . $p['srcPage'] ."'>";
	$block = $block . "</form>";
	return $block;
}

function systemOfRecordForm($p) {
	$block = "<div style='width:4em;float:left'>Id:</div><input value='". $p['personId'] ."' type='text' name='personId' size='6' style='margin:0px 5px 5px 5px;'><br/>";
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
	$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='".__('Submit', 'rootspersona')."'/>";
	$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='".__('Reset', 'rootspersona')."'/></div>";
	$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>";
	$block = $block . "</div></div>";

	$block = $block . "<br/><div class='personBanner'>Facts</div><div class='truncate'>";
	$block = $block . "<div style='padding-top: 15px;'>";
	$block = $block . "<div  style='width:6em;float:left'>Marraige:</div><input style='float:left' value='". $p['mDate'] ."' type='text' name='mDate'  style='margin:0px 5px 5px 5px;'>";
	$block = $block . "<div  style='width:3.4em;float:left;padding-left:5px;'>Place:</div><input value='". $p['mPlace'] ."' type='text' name='mPlace'  style='margin:0px 5px 5px 5px;'><br/>";
	$block = $block . "<div style='width:6em;float:left'>Partner's Id:</div><input style='float:left' value='". $p['pid'] ."' type='text' name='pid' size='6' style='margin:0px 5px 5px 5px;'>";
	$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='".__('Submit', 'rootspersona')."'/>";
	$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='".__('Reset', 'rootspersona')."'/></div></div>";
	$block = $block . "</div>";

	$block = $block . "<br/><div class='personBanner'>Ancestors</div><div class='truncate'>";
	$block = $block . "<div style='padding-top: 15px;'>";
	$block = $block . "<div style='width:8em;float:left'>Father's Id:</div><input value='". $p['fid'] ."' type='text' name='fid' size='6' style='margin:0px 5px 5px 5px;'><br/>";
	$block = $block . "<div style='width:8em;float:left'>Mother's Id:</div><input value='". $p['mid'] ."' type='text' name='mid' size='6' style='margin:0px 5px 5px 5px;'><br/>";
	$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='".__('Submit', 'rootspersona')."'/>";
	$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='".__('Reset', 'rootspersona')."'/></div></div>";
	$block = $block . "</div>";

	$block = $block . "<br/><div class='personBanner'>Family Group</div><div class='truncate'>";
	$block = $block . "<div style='padding-top: 15px;'>";
	$block = $block . "<div style='width:10em;float:left'>Group Id (as Parent):</div><input value='". $p['gpid'] ."' type='text' name='gpid' size='6' style='margin:0px 5px 5px 5px;'><br/>";
	$block = $block . "<div style='width:10em;float:left'>Group Id (as Child):</div><input value='". $p['gcid'] ."' type='text' name='gcid' size='6' style='margin:0px 5px 5px 5px;'>";
	$block = $block . "<div style='float:right;'><input type='submit' name='submitPersonForm' value='".__('Submit', 'rootspersona')."'/>";
	$block = $block . "&#160;&#160;&#160;<input type='reset' name='reset' value='".__('Reset', 'rootspersona')."'/></div></div>";
	$block = $block . "</div>";
}


function processEdit() {
	$p = paramsFromHTML($_POST);
	$isSystemOfRecord = get_option('rootsIsSystemOfRecord');
	$msg = '';
	if(strlen($p['personId']) < 1) $msg =  $msg .  "<br>" . __('Invalid Id.', 'rootspersona');
	if(strlen($p['personName']) < 1) $msg = $msg . "<br>" . __('Name required.', 'rootspersona');
	if($isSystemOfRecord == 'false') {
		$my_post = array();
		$my_post['ID'] = $p['srcPage'];
		$content = "[rootsPersona personId='" . $p['personId'] . "'";
		for ($i= 1;$i <= 7; $i++) {
			$pf = 'picFile' . $i;
			if(isset($p[$pf]) && !empty($p[$pf])) {
				$content = $content . ' ' . $pf . "='" . $p[$pf] . "'";
				$pc = 'picCap' . $i;
				if(isset($p[$pc]) && !empty($p[$pc])) {
					$content = $content . ' ' . $pc . "='" . $p[$pc] . "'";
				}
			}
		}
		$content = $content . "/]";
		$my_post['post_content'] = $content;
		wp_update_post( $my_post );
		return showPage($p['srcPage']);
	}
	$p['action'] =  site_url() . '/?page_id=' . $this->getPageId();
	$p['isSystemOfRecord'] = $isSystemOfRecord;
	return showEditForm($p, plugins_url() . "rootspersona/", "<div class='truncate'>" . $msg . "</div>");
}
?>