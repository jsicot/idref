<?php
add_plugin_hook('admin_theme_header', 'IdrefPlugin::adminThemeHeader');
add_plugin_hook('install', 'idref_install');
add_plugin_hook('uninstall', 'idref_uninstall');
add_plugin_hook('config_form', 'idref_config_form');
add_plugin_hook('config', 'idref_config');
add_filter(array('Form', 'Item', 'Dublin Core', 'Creator'), 'IdrefPlugin::filterDcCreator');
add_filter(array('Form', 'Item', 'Dublin Core', 'Contributor'), 'IdrefPlugin::filterDcContributor');
add_filter(array('Form', 'Item', 'Dublin Core', 'Subject'), 'IdrefPlugin::filterDcSubject');
add_filter(array('Form', 'Item', 'Dublin Core', 'Spatial Coverage'), 'IdrefPlugin::filterDcSpatialCoverage');

define('IDREF_PLUGIN_VERSION', get_plugin_ini('Idref', 'version'));
define('IDREF_PROXY_HOST', get_option('idref_proxy_host'));
define('IDREF_PROXY_PORT', get_option('idref_proxy_port'));

//installation du plugin dans omeka
function idref_install()
{
	set_option('idref_plugin_version', IDREF_PLUGIN_VERSION);
	set_option('idref_proxy_host', $idref_proxy_host);
	set_option('idref_proxy_port', $idref_proxy_port);                                                                                                                      
}

//désinstallation du plugin
function idref_uninstall()
{
	delete_option('idref_plugin_version');
	delete_option('idref_proxy_host');
	delete_option('idref_proxy_port');
	
}


/**
* Processes the configuration form.
*/
function idref_config()
{
	set_option('idref_proxy_host', $_POST['idref_proxy_host']);
	set_option('idref_proxy_port', $_POST['idref_proxy_port']);
}

/**
* Shows the configuration form.
*/
function idref_config_form()
{
	$idref_proxy_host = get_option('idref_proxy_host');
	$idref_proxy_port = get_option('idref_proxy_port');

	include 'config_form.php';
}

function regAccents($chaine) {
	$accent = array('à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ð','ò','ó','ô','õ','ö','ù','ú','û','ü','ý','ý','ÿ');
	$inter = array('%01','%02','%03','%04','%05','%06','%07','%08','%09','%10','%11','%12','%13','%14','%15','%16','%17','%18','%19','%20','%21','%22','%23','%24','%25','%26','%27','%28','%29','%30','%31','%32','%33','%34','%35');
	$regex = array('a','a','a','a','a','a',
		'c',
		'e','e','e','e',
		'i','i','i','i',   'o','o','o','o','o','o','u','u','u','u',
		'y','y','y');
	$chaine = str_ireplace($accent, $inter, $chaine);
	$chaine = str_replace($inter, $regex, $chaine);      
	return $chaine;
};

class IdrefPlugin
{
    public static function adminThemeHeader()
    {
        $request = Zend_Controller_Front::getInstance()->getRequest();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        // Only add the JS and style to item form pages.
        if (!($controller == 'items' && ($action == 'edit' || $action == 'add'))) {
            return;
        }

        $db = get_db();
        $dcCreator = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Creator');
		$dcContributor = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Contributor');
		$dcSubject = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Subject');
		$dcSpatialCoverage = $db->getTable('Element')->findByElementSetNameAndElementName('Dublin Core', 'Spatial Coverage');
?>
<style type="text/css">
.ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
}
</style>
<?php __v()->headScript()->captureStart(); ?>
// Add an autocompleter to all Dublin Core:Creator form inputs.
jQuery(document).bind('omeka:elementformload', function(event) {
    jQuery('#element-<?php echo $dcCreator->id; ?> input[type="text"]').autocomplete({
        minLength: 3,
        source: <?php echo js_escape(uri('idref/index/auteur-proxy/')); ?>
    });
	// Add an autocompleter to all Dublin Core:Contributor form inputs.
	jQuery('#element-<?php echo $dcContributor->id; ?> input[type="text"]').autocomplete({
        minLength: 3,
        source: <?php echo js_escape(uri('idref/index/sujet-proxy/')); ?>
    });
	// Add an autocompleter to all Dublin Core:Subject form inputs.
	jQuery('#element-<?php echo $dcSubject->id; ?> input[type="text"]').autocomplete({
        minLength: 3,
        source: <?php echo js_escape(uri('idref/index/sujet-proxy/')); ?>
    });
	// Add an autocompleter to all Dublin Core:Spatial Coverage form inputs.
	jQuery('#element-<?php echo $dcSpatialCoverage->id; ?> input[type="text"]').autocomplete({
        minLength: 3,
        source: <?php echo js_escape(uri('idref/index/geo-proxy/')); ?>
    });
});
<?php __v()->headScript()->captureEnd(); ?>
<?php
    }

    /**
     * Replaces an input with a simple text input.
     *
     * Used to replace Dublin Core Creator textareas with single-line inputs.
     */
    public static function filterDcCreator($html, $inputNameStem, $value, 
                                           $options, $record, $element)
    {
        return __v()->formText($inputNameStem . '[text]',
                               $value, 
                               array('size' => '50', 'class' => 'textinput'));
    }
	
	public static function filterDcContributor($html, $inputNameStem, $value, 
                                           $options, $record, $element)
    {
        return __v()->formText($inputNameStem . '[text]',
                               $value, 
                               array('size' => '50', 'class' => 'textinput'));
    }
	
	public static function filterDcSubject($html, $inputNameStem, $value, 
                                           $options, $record, $element)
    {
        return __v()->formText($inputNameStem . '[text]',
                               $value, 
                               array('size' => '50', 'class' => 'textinput'));
    }

	public static function filterDcSpatialCoverage($html, $inputNameStem, $value, 
                                           $options, $record, $element)
    {
        return __v()->formText($inputNameStem . '[text]',
                               $value, 
                               array('size' => '50', 'class' => 'textinput'));
    }
}
