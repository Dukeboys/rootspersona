<?php 

function buildRootsOptionsPage() {

$dataDir =  get_option('rootsDataDir');
$fullDataDir = strtr(ABSPATH,'\\','/') .$dataDir;

echo "<div class='wrap'><h2>rootsPersona</h2>";
if(!is_dir($fullDataDir)) {
echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>Data Directory " 
	. $fullDataDir . __(' does not exist. Make sure wp-content is writable, then reactivate plugin.')."</p>";
} else if (!is_writable($fullDataDir)) {
	echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>Data Directory " 
	. $fullDataDir . __(' is not writable. Update directory permissions, then reactivate plugin.')."</p>";
}
echo  "<form method='post' action='options.php'>";
echo  "<table class='form-table'>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaVersion'> " .  __('rootsPersona Version') . "</label></th>";
echo  "<td><label class='regular-text' id='rootsPersonaVersion'>" . get_option('rootsPersonaVersion') . " </label></td>";
echo  "<td></td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaParentPage'>" .  __('rootsPersona Parent Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsPersonaParentPage' id='rootsPersonaParentPage'";
echo  " value=' " . get_option('rootsPersonaParentPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsPersonaParentPage') . "'>" . __('Page') . "</a> " . __('you want persona pages to be organized under in a menu structure.  0 indicates no parent page.'). "</td></tr>";			

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsDataDir'>" .  __('rootsPersona Data Directory') . "</label></th>";
echo  "<td><input type='text' size='35' name='rootsDataDir' id='rootsDataDir'";
echo  " value=' " . $dataDir . " '/></td>";
echo  "<td>" . __('Directory where data files are stored. There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsEditPage'>" .  __('Edit Person Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsEditPage' id='rootsEditPage'";
echo  " value=' " . get_option('rootsEditPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsEditPage') . "'>" . __('Page') . "</a> " . __('with the  Edit Page shortcode.  There is usually no need to change this.'). "</td></tr>";			

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsCreatePage'>" .  __('Add Person Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsCreatePage' id='rootsCreatePage'";
echo  " value=' " . get_option('rootsCreatePage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsCreatePage') . "'>" . __('Page') . "</a> " . __('with the  Add Page shortcode.  There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsUploadGedcomPage'>" .  __('Upload Gedcom Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsUploadGedcomPage' id='rootsUploadGedcomPage'";
echo  " value=' " . get_option('rootsUploadGedcomPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsUploadGedcomPage') . "'>" . __('Page') . "</a> " . __('with the  Upload GEDCOM page shortcode.  There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIncludePage'>" .  __('Include Person Page') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsIncludePage' id='rootsIncludePage'";
echo  " value=' " . get_option('rootsIncludePage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsIncludePage') . "'>" . __('Page') . "</a> " . __('with the  Include Person page shortcode.  There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIndexPage'>" .  __('Persona Index Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsPersonaIndexPage' id='rootsPersonaIndexPage'";
echo  " value=' " . get_option('rootsPersonaIndexPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsPersonaIndexPage') . "'>" . __('Page') . "</a> " . __('with the  Name Index shortcode.  There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideHeader'>" .  __('Hide Header?') . "</label></th>";
$yes = get_option('rootsHideHeader');
if(isset($yes) && $yes == '1') {
	$yes = 'checked';
	$no = '';
} else {
	$yes = '';
	$no = 'checked';
}
echo  "<td><input type='radio' name='rootsHideHeader' value='1' $yes>Yes ";
echo "<input type='radio' name='rootsHideHeader' value='0' $no>No </td>";

echo  "<td>" .  __('Skip the Header Panel on persona pages.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideFacts'>" .  __('Hide Facts?') . "</label></th>";
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

echo  "<td>" .  __('Skip the Facts panel on persona pages.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideAncestors'>" .  __('Hide Ancestors?') . "</label></th>";
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

echo  "<td>" .  __('Skip the Ancestors panel on person pages.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideFamilyC'>" .  __('Hide Child Family Group?') . "</label></th>";
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

echo  "<td>" .  __('Skip the Family Group panel where the person is a Child on persona pages.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideFamilyS'>" .  __('Hide Spousal Family Groups?') . "</label></th>";
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

echo  "<td>" .  __('Skip the Family Group panels where the person is a Spouse on persona pages.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHidePictures'>" .  __('Hide Picture?') . "</label></th>";
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

echo  "<td>" .  __('Skip the pictures panel on perosna pages. Still displays the image in the Header panel.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideEvidence'>" .  __('Hide Evidence?') . "</label></th>";
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

echo  "<td>" .  __('Skip the evidence panel in persona pages.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaHideDates'>" .  __('Hide Dates?') . "</label></th>";
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

echo  "<td>" .  __('Some people may want to hide dates for privacy purposes.  This is a global flag (impacts all persona pages).'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsPersonaHidePlaces'>" .  __('Hide Locations?') . "</label></th>";
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

echo  "<td>" .  __('Some people may want to hide locations for privacy purposes.  This is a global flag (impacts all persona pages).'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsHideEditLinks'>" .  __('Hide Edit Links?') . "</label></th>";
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

echo  "<td>" .  __('Some people may want to hide the edit links at the bottom of the persona page.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIsSystemOfRecord'>" .  __('Is this the System Of Record?') . "</label></th>";
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

echo  "<td>" .  __('Only No is supported at this time (meaning some external program is the system of record).'). "</td></tr>";


echo  "</table><p class='submit'>";
echo  "<input type='submit' name='Submit' value=' " . __('Save Changes') . " '/>";
echo  settings_fields('rootsPersonaOptions');
echo  "</p></form></div>";
}
?>