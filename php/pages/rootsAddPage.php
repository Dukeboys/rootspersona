<?php
/**
 *
 * Enter description here ...
 * @param  $action
 * @param  $files
 * @param  $dataDir
 * @param  $msg
 */
function showAddPageForm($action,$files,$dataDir, $msg='') {
	$block = "<br/><div class='personBanner'><br/></div>";
	if(count($files) == 0) {
		$block = $block . "<br/><div style='text-align:center;color:red;font-weight:bold'>"
			.__('All available files have been added.', 'rootspersona')
			."</div>";
	} else {
		$block = $block . "<form  action='".$action."' method='POST'>";
		$block = $block . "<br/><select multiple name='fileNames[]' size='16'>";
		$cnt = count($files);
		for($i = 0; $i < $cnt; $i++) {
			$utility = new PersonUtility();
			$name = $utility->getName($files[$i], $dataDir);
			$block = $block . "<option value='".$files[$i]."'>".$name."</option>";
		}
		$block = $block . "</select><br/>";
		$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>".$msg."</div>";

		$block = $block . "<br/><input type='submit' name='submitAddPageForm' value='" .  __('Add', 'rootspersona') . "'/>";
		//$block = $block . "&#160;&#160;<input type='submit' name='submitExcPageForm' value='" .  __('Exclude', 'rootspersona') . "'/>";
		$block = $block . "&#160;&#160;<input type='reset' name='reset' value='" . __('Reset', 'rootspersona') ."'/>";

		$block = $block . "<br/><br/><div class='personBanner'><br/></div>";
		$block = $block . "</form>";
	}
	return $block;
}
?>