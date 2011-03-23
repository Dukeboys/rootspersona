<?php
function showIncludePageForm($action,$persons, $msg='') {
	$block = "<br/><div class='personBanner'><br/></div>";
	if(count($persons) == 0) {
		$block = $block . "<br/><div style='text-align:center;color:red;font-weight:bold'>"
		.  sprintf(__('All %s have been included.', 'rootspersona'),"personas") ."</div>";
	} else {
		$block = $block . "<form  action='".$action."' method='POST'>";
		$block = $block . "<br/><select multiple name='persons[]' size='16'>";
		$cnt = count($persons);
		for($i = 0; $i < $cnt; $i++) {
			$name = $persons[$i]['name'];
			$block = $block . "<option value='" . $persons[$i]['id'] . "'>$name</option>";
		}
		$block = $block . "</select><br/>";
		$block = $block . "<div style='text-align:center;color:red;font-weight:bold'>$msg</div>";

		$block = $block . "<br/><input type='submit' name='submitIncludePageForm' value='" . __('Include', 'rootspersona') ."'/>";
		$block = $block . "&#160;&#160;<input type='reset' name='reset' value='" . __('Reset', 'rootspersona') ."'/>";

		$block = $block . "<br/><br/><div class='personBanner'><br/></div>";
		$block = $block . "</form>";
	}
	return $block;
}
?>