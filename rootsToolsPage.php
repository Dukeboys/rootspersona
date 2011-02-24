<?php 

function buildRootsToolsPage() {

$dataDir =  get_option('rootsDataDir');
$fullDataDir = strtr(ABSPATH,'\\','/') .$dataDir;

echo "<div class='wrap'><h2>rootsPersona</h2>";
if(!is_dir($fullDataDir)) {
echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>" .__("Data Directory", 'rootspersona') . " "
	. $fullDataDir . " " . printf(__(' does not exist. Make sure %s is writable, then reactivate plugin.', 'rootspersona'),"wp-content")."</p>";
} else if (!is_writable($fullDataDir)) {
	echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>" .__("Data Directory", 'rootspersona') . " "
	. $fullDataDir .  " " .__(' is not writable. Update directory permissions, then reactivate plugin.', 'rootspersona')."</p>";
}
echo  "<table class='form-table'>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsCreatePage') . "'>" . __('Add Uploaded Persons', 'rootspersona') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Review the list of people you have uploaded but not created pages for.', 'rootspersona') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsUploadGedcomPage') . "'>" . __('Upload GEDCOM', 'rootspersona') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Upload (or re-upload) a GEDCOM file.', 'rootspersona') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsIncludePage') . "'>" . __('Review Excluded Persons', 'rootspersona') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Review people you have previous excluded, and include the ones you select.', 'rootspersona') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsPersonaIndexPage') . "'>" . __('Name Index', 'rootspersona') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('View a sortable index table of all persons that you have created pages for.', 'rootspersona') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsUtilityPage') . "&utilityAction=validate'>" . __('Validate idMap.xml', 'rootspersona') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . printf(__('Validate your %s setup. Confirms the mapping file used to track pages is not corrupt.', 'rootspersona'),"rootsPersona") . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsUtilityPage') . "&utilityAction=validatePages'>" . __('Validate persona Pages', 'rootspersona') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . printf(__('Identify orphaned %s pages. Includes all pages with %s shortcode and no reference in idMap.xml.', 'rootspersona'),"persona","[rootsPersona/]") . "</td></tr>";

$win1 = __('All persona pages will be deleted.  Does not include utilities.  Proceed?', 'rootspersona');
echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'>";
echo "<a href='#' onClick='javascript:rootsConfirm(\"" . $win1 . "\",\"" 
	. site_url() . "?page_id=" . get_option('rootsUtilityPage') 
	. "&utilityAction=delete\");return false;'>" . __('Delete persona Pages', 'rootspersona') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . printf(__('Perform a bulk deletion of all %s pages.  Only deletes pages with the main %s shortcode.', 'rootspersona'),"persona","[rootsPersona/]") . "</td></tr>";

echo  "</table>";
echo  "</div>";
}
?>
