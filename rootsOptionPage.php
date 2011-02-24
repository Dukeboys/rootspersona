<?php 

function buildRootsOptionsPage() {

$dataDir =  get_option('rootsDataDir');
$fullDataDir = strtr(ABSPATH,'\\','/') .$dataDir;

echo "<div class='wrap'><h2>rootsPersona</h2>";
if(!is_dir($fullDataDir)) {
echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>" .__("Data Directory", 'rootspersona') . " " 
	. $fullDataDir . " " . sprintf(__('does not exist. Make sure %s is writable, then reactivate plugin.', 'rootspersona'),"wp-content")."</p>";
} else if (!is_writable($fullDataDir)) {
	echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>" .__("Data Directory", 'rootspersona') . " "
	. $fullDataDir . " " . __('is not writable. Update directory permissions, then reactivate plugin.', 'rootspersona')."</p>";
}
echo  "<form method='post' action='options.php'>";
echo  "<table class='form-table'>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaVersion'>rootsPersona " .  __('Version', 'rootspersona') . "</label></th>";
echo  "<td><label class='regular-text' id='rootsPersonaVersion'>" . get_option('rootsPersonaVersion') . " </label></td>";
echo  "<td></td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaParentPage'>rootsPersona " .  __('Parent Page Id', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsPersonaParentPage' id='rootsPersonaParentPage'";
echo  " value=' " . get_option('rootsPersonaParentPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsPersonaParentPage') . "'>" . __('Page', 'rootspersona') 
		. "</a> " . sprintf(__('you want %s pages to be organized under in a menu structure.  0 indicates no parent page.', 'rootspersona'),"persona")
		. "</td></tr>";			

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsDataDir'>rootsPersona " .  __('Data Directory', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='35' name='rootsDataDir' id='rootsDataDir'";
echo  " value=' " . $dataDir . " '/></td>";
echo  "<td>" . __('Directory where data files are stored. There is usually no need to change this.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsEditPage'>" .  __('Edit Person Page Id', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsEditPage' id='rootsEditPage'";
echo  " value=' " . get_option('rootsEditPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsEditPage') . "'>" 
		. __('Page', 'rootspersona') . "</a> " 
		. __('with the Edit Page shortcode.  There is usually no need to change this.', 'rootspersona'). "</td></tr>";			

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsCreatePage'>" .  __('Add Person Page Id', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsCreatePage' id='rootsCreatePage'";
echo  " value=' " . get_option('rootsCreatePage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsCreatePage') . "'>" . __('Page', 'rootspersona') . "</a> " 
	. __('with the Add Page shortcode.  There is usually no need to change this.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsUploadGedcomPage'>" .  __('Upload Gedcom Page Id', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsUploadGedcomPage' id='rootsUploadGedcomPage'";
echo  " value=' " . get_option('rootsUploadGedcomPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsUploadGedcomPage') . "'>" 
	. __('Page', 'rootspersona') . "</a> " 
	. __('with the Upload GEDCOM page shortcode.  There is usually no need to change this.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIncludePage'>" .  __('Include Person Page Id', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsIncludePage' id='rootsIncludePage'";
echo  " value=' " . get_option('rootsIncludePage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsIncludePage') . "'>" . __('Page', 'rootspersona') . "</a> " 
	. __('with the Include Person page shortcode.  There is usually no need to change this.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIndexPage'>" .  __('Persona Index Page Id', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsPersonaIndexPage' id='rootsPersonaIndexPage'";
echo  " value=' " . get_option('rootsPersonaIndexPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsPersonaIndexPage') . "'>" . __('Page', 'rootspersona') . "</a> " 
	. __('with the Name Index shortcode.  There is usually no need to change this.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsUtilityPage'>" .  __('Persona Utility Page Id', 'rootspersona') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsUtilityPage' id='rootsUtilityPage'";
echo  " value=' " . get_option('rootsUtilityPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsUtilityPage') . "'>" . __('Page', 'rootspersona') . "</a> " 
	. __('with the Utility page shortcode.  For internal use only (used to display output from various utility functions).', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top' style='border-top: solid black 1px;'>";
echo  "<th scope='row'><label for='rootsHideHeader'>" .  __('Hide Header?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHideHeader');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td style='border-top: solid black 1px;'><input type='radio' name='rootsHideHeader' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideHeader' value='0' $no>No </td>";

echo  "<td>" .  __('Skip the Header Panel on persona pages.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideFacts'>" .  __('Hide Facts?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHideFacts');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHideFacts' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideFacts' value='0' $no>No </td>";

echo  "<td>" .  __('Skip the Facts panel on persona pages.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideAncestors'>" .  __('Hide Ancestors?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHideAncestors');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHideAncestors' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideAncestors' value='0' $no>No </td>";

echo  "<td>" .  __('Skip the Ancestors panel on person pages.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideFamilyC'>" .  __('Hide Child Family Group?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHideFamilyC');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHideFamilyC' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideFamilyC' value='0' $no>No </td>";

echo  "<td>" .  __('Skip the Family Group panel where the person is a Child on persona pages.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideFamilyS'>" .  __('Hide Spousal Family Groups?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHideFamilyS');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHideFamilyS' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideFamilyS' value='0' $no>No </td>";

echo  "<td>" .  __('Skip the Family Group panels where the person is a Spouse on persona pages.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHidePictures'>" .  __('Hide Picture?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHidePictures');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHidePictures' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHidePictures' value='0' $no>No </td>";

echo  "<td>" .  sprintf(__('Skip the pictures panel on %s pages. Still displays the image in the Header panel.', 'rootspersona'),"persona"). "</td></tr>";

echo  "<tr valign='top' style='border-bottom: solid black 1px;'>";
echo  "<th scope='row'><label for='rootsHideEvidence'>" .  __('Hide Evidence?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHideEvidence');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHideEvidence' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideEvidence' value='0' $no>No </td>";

echo  "<td>" .  __('Skip the evidence panel in persona pages.', 'rootspersona'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaHideDates'>" .  __('Hide Dates?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsPersonaHideDates');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsPersonaHideDates' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsPersonaHideDates' value='0' $no>No </td>";

echo  "<td>" .  sprintf(__('Some people may want to hide dates for privacy purposes.  This is a global flag (impacts all %s pages).', 'rootspersona'),"persona"). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaHidePlaces'>" .  __('Hide Locations?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsPersonaHidePlaces');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsPersonaHidePlaces' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsPersonaHidePlaces' value='0' $no>No </td>";

echo  "<td>" .  sprintf(__('Some people may want to hide locations for privacy purposes.  This is a global flag (impacts all %s pages).', 'rootspersona'),"persona"). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideEditLinks'>" .  __('Hide Edit Links?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsHideEditLinks');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHideEditLinks' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideEditLinks' value='0' $no>No </td>";

echo  "<td>" .  sprintf(__('Some people may want to hide the edit links at the bottom of the %s page.', 'rootspersona'),"persona"). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIsSystemOfRecord'>" .  __('Is this the System Of Record?', 'rootspersona') . "</label></th>";
$yes = get_option('rootsIsSystemOfRecord');
if(isset($yes) && $yes == 'true') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsIsSystemOfRecord' value='true' $yes disabled>Yes ";
echo "<input type='radio' name='rootsIsSystemOfRecord' value='false' $no>No </td>";

echo  "<td>" .  __('Only No is supported at this time (meaning some external program is the system of record).', 'rootspersona'). "</td></tr>";


echo  "</table><p class='submit'>";
echo  "<input type='submit' name='Submit' value=' " . __('Save Changes', 'rootspersona') . " '/>";
echo  settings_fields('rootsPersonaOptions');
echo  "</p></form></div>";
}
?>
