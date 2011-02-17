<?php 

function buildRootsToolsPage() {

$dataDir =  get_option('rootsDataDir');
$fullDataDir = ABSPATH .$dataDir;

echo "<div class='wrap'><h2>rootsPersona</h2>";
if(!is_dir($fullDataDir)) {
echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>Data Directory " 
	. $fullDataDir . " does not exist. Make sure wp-content is writable, then reactivate plugin.</p>";
} else if (!is_writable($fullDataDir)) {
	echo "<p style='padding: .5em; background-color: red; color: white; font-weight: bold;'>Data Directory " 
	. $fullDataDir . " is not writable. Update directory permissions, then reactivate plugin.</p>";
}
echo  "<table class='form-table'>";

echo  "<tr valign='top'>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsCreatePage') . "'>" . __('Add Uploaded Persons') . "</a></td></tr>";

echo  "<tr valign='top'>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsUploadGedcomPage') . "'>" . __('Upload GEDCOM') . "</a></td></tr>";

echo  "<tr valign='top'>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsIncludePage') . "'>" . __('Review Excluded Persons') . "</a></td></tr>";

echo  "<tr valign='top'>";
echo  "<td><a href=' " . site_url() . "?page_id=" . get_option('rootsPersonaIndexPage') . "'>" . __('Name Index') . "</a></td></tr>";

echo  "</table></div>";
}
?>