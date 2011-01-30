<?php 

function buildRootsOptionsPage() {

echo "<div class='wrap'><h2>rootsPersona</h2>";
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
echo  "<td><a href=' " . site_url() . " ?page_id= " . get_option('rootsPersonaParentPage') . "'>" . __('Page') . "</a> " . __('you want persona pages to be organized under in a menu structure.  0 indicates no parent page.'). "</td></tr>";			

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsDataDir'>" .  __('rootsPersona Data Directory') . "</label></th>";
echo  "<td><input type='text' size='35' name='rootsDataDir' id='rootsDataDir'";
echo  " value=' " . get_option('rootsDataDir'). " '/></td>";
echo  "<td>" . __('Directory where data files are stored. There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsEditPage'>" .  __('Edit Person Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsEditPage' id='rootsEditPage'";
echo  " value=' " . get_option('rootsEditPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . " ?page_id= " . get_option('rootsEditPage') . "'>" . __('Page') . "</a> " . __('with the  Edit Page shortcode.  There is usually no need to change this.'). "</td></tr>";			

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsCreatePage'>" .  __('Add Person Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsCreatePage' id='rootsCreatePage'";
echo  " value=' " . get_option('rootsCreatePage'). " '/></td>";
echo  "<td><a href=' " . site_url() . " ?page_id= " . get_option('rootsCreatePage') . "'>" . __('Page') . "</a> " . __('with the  Add Page shortcode.  There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsUploadGedcomPage'>" .  __('Upload Gedcom Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsUploadGedcomPage' id='rootsUploadGedcomPage'";
echo  " value=' " . get_option('rootsUploadGedcomPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . " ?page_id= " . get_option('rootsUploadGedcomPage') . "'>" . __('Page') . "</a> " . __('with the  Upload GEDCOM page shortcode.  There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIndexPage'>" .  __('Persona Index Page Id') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsPersonaIndexPage' id='rootsPersonaIndexPage'";
echo  " value=' " . get_option('rootsPersonaIndexPage'). " '/></td>";
echo  "<td><a href=' " . site_url() . " ?page_id= " . get_option('rootsPersonaIndexPage') . "'>" . __('Page') . "</a> " . __('with the  Name Index shortcode.  There is usually no need to change this.'). "</td></tr>";

echo  "<tr valign='top'>";
echo  "<th scope='row'><label for='rootsIsSystemOfRecord'>" .  __('Is this the System Of Record?') . "</label></th>";
echo  "<td><input type='text' size='5' name='rootsIsSystemOfRecord' id='rootsIsSystemOfRecord'";
echo  " value=' " . get_option('rootsIsSystemOfRecord'). " '/></td>";
echo  "<td>" .  __('true|false. Only false is supported at this time (meaning some external program is the system of record).'). "</td></tr>";

echo  "</table><p class='submit'>";
echo  "<input type='submit' name='Submit' value=' " . __('Save Changes') . " '/>";
echo  settings_fields('rootsPersonaOptions');
echo  "</p></form></div>";
}
?>