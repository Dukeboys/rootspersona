<?php

function showUploadGedcomForm($action,$dataDir,$stageDir, $msg='') {

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
?>