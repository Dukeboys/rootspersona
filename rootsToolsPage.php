<?php 

function buildRootsToolsPage() {

$dataDir =  get_option('rootsDataDir');
$fullDataDir = strtr(ABSPATH,'\\','/') .$dataDir;

echo "<div class='wrap'><h2>rootsPersona</h2>";
if(!is_dir($fullDataDir)) {
echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>".__('Data Directory') 
	. $fullDataDir . __(' does not exist. Make sure wp-content is writable, then reactivate plugin.')."</p>";
} else if (!is_writable($fullDataDir)) {
	echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>".__('Data Directory ')
	. $fullDataDir . __(' is not writable. Update directory permissions, then reactivate plugin.')."</p>";
}
echo  "<table class='form-table'>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsCreatePage') . "'>" . __('Add Uploaded Persons') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Review the list of people you have uploaded but not created pages for.') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsUploadGedcomPage') . "'>" . __('Upload GEDCOM') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Upload (or re-upload) a gedcom file.') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsIncludePage') . "'>" . __('Review Excluded Persons') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Review people you have previous excluded, and include the ones you select.') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsPersonaIndexPage') . "'>" . __('Name Index') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('View a sortable index table of all persons that you have created pages for.') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsUtilityPage') . "&utilityAction=validate'>" . __('Validate idMap.xml') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Validate your rootsPersona setup. Confirms the mapping file used to track pages is not corrupt.') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsUtilityPage') . "&utilityAction=validatePages'>" . __('Validate persona Pages') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Identify orphaned persona pages. Includes all pages with [rootsPersona/] shortcode and no reference in idMap.xml.') . "</td></tr>";

echo  "<tr style='vertical-align: top'>";
echo  "<td style='width:200px;'><div class='rp_linkbutton'><a href=' " . site_url() . "?page_id=" . get_option('rootsUtilityPage') . "&utilityAction=delete'>" . __('Delete persona Pages') . "</a></div></td>";
echo  "<td style='vertical-align:middle'>" . __('Perform a bulk deletion of all persona pages.  Only deletes pages with the main [rootsPersona/] shortcode.') . "</td></tr>";

echo  "</table>";
echo  "</div>";
}
?>
