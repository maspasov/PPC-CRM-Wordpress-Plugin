<?php
/**
* Plugin Name: #FOS Functions
* Version:     1.2   
* Description: This plugin add important functions for PPC Phone numbers, CRM, Chat
* Author:      FOS WP Team
* Author URI:  vortex.1stonlinesolutions.com/dev/WPU/
**/

if (basename($_SERVER['PHP_SELF']) == basename(__FILE__)) {
        die('Sorry, but you cannot access this page directly.');
}

if (!defined('ABSPATH')) {
        exit;
}

// Define current version constant
define('FOS_VERSION', '1.2');

//Define Plugin Name
define('FOS_PLUGIN_NAME', 'special-files-plugin');

//Define Plugin URL
define('FOS_PLUGIN_URL', plugin_dir_url(__FILE__));


//Special Functions
require_once 'includes/ppc_and_crm_functions.php';

//Gravity Form Special Functions
require_once 'includes/gravity_form_functions.php';

//Plugin Update Checker
require 'lib/plugin-updates/plugin-update-checker.php';

//Plugin Dependency
require 'lib/plugin-dependency/class-tgm-plugin-activation.php';


/**
* Auto Plugin Install List 
* Include plugin zip file in /lib/plugins-install/
**/
global $plugins;    
$plugins = array(

    // This is an example of how to include a plugin pre-packaged with a theme.
    array(
        'name'               => 'Exploit Scanner', // The plugin name.
        'slug'               => 'exploit-scanner', // The plugin slug (typically the folder name).
        'zip_name'           => 'exploit-scanner.zip', // The plugin slug (typically the folder name).
        'source'             => plugin_dir_url(__FILE__) . 'lib/plugins-install/exploit-scanner.zip', // The plugin source.
        'required'           => true, // If false, the plugin is only 'recommended' instead of required.
        // 'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
        'force_activation'   => true, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
        'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
        // 'external_url'       => '', // If set, overrides default API URL and points to an external URL.
    ),  

);


add_action( 'tgmpa_register', 'register_required_plugins' );
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function register_required_plugins() {

    global $plugins;
    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        #'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $plugins, $config );

}


//Activate Plugins List
function extract_plugin_list() {

    require_once(ABSPATH . 'wp-admin/includes/file.php');
    WP_Filesystem(); 
    
    global $plugins;       
        
    foreach ($plugins as $activate_plugin) {

        $file =  plugin_dir_path(__FILE__) . 'lib/plugins-install/'.$activate_plugin['zip_name'];
        $to = substr(plugin_dir_path( __FILE__ ),0,(strlen(FOS_PLUGIN_NAME)+1)*-1);
        
        $unzipfile = unzip_file( $file, $to);
            
           if ( $unzipfile ) {
               //Successfully unzipped the file!
               return true;
           } else {              
               return false;
           }
    }       
   
}


function fos_options_style() {
        wp_register_style('fos_options_css', plugin_dir_url(__FILE__) . 'css/styles.css', false, '1.0.0');
        wp_enqueue_style('fos_options_css');
}
add_action('admin_enqueue_scripts', 'fos_options_style');


function theme_scripts() {    
    wp_enqueue_script( 'special-files-script', plugin_dir_url(__FILE__) . 'js/app.js', array(), '1.0.1', true );
}
add_action( 'wp_enqueue_scripts', 'theme_scripts' );

//Return GenText Src
function get_gentext_url( $url, $language, $address = NULL ) {

    $arr_params = array( 'siteuri' => $url, 'lang' => $language, 'address' => $address );
    return add_query_arg( $arr_params,  plugins_url('includes/gentext.php', __FILE__) );
}


class MyFiles {

    private $pdf_url = "";

    function __construct() {        
        //Get from Theme Options URL path
        $this->pdf_url = get_option('pdf_url');

    }  

    function activate() {
        global $wp_rewrite;       

        // update stored plugin version for next time
        update_option('FOS_PLUGIN_VERSION', FOS_VERSION);

        //Unzip and Activate plugin
        extract_plugin_list();

        $this->flush_rewrite_rules();
    }


    function update() {

        $prev_version = get_option( 'FOS_PLUGIN_VERSION' );
        $current_version = FOS_VERSION;

        // special case: we've never set the version before; not all plugins will need to upgrade in that case
        if(empty($prev_version) || version_compare($prev_version, $current_version) < 0) {

            // update stored plugin version for next time
            update_option('FOS_PLUGIN_VERSION', FOS_VERSION);

            //Unzip and Activate plugin
            extract_plugin_list();           
        }


    }

    // Took out the $wp_rewrite->rules replacement so the rewrite rules filter could handle this.
    function create_rewrite_rules($rules) {
        global $wp_rewrite;

        $newRule = array(            
            '^local.php$' => 'index.php?customfiles=local',
            '^local/$' => 'index.php?customfiles=local',
            '^local$' => 'index.php?customfiles=local',
            '^images/newsletter/(.*)$' => 'index.php?customfiles='.$wp_rewrite->preg_index(1),
            '^images/coupons/(.*)$' => 'index.php?customfiles='.$wp_rewrite->preg_index(1),
            '^newsletter/images/(.*)$' => 'index.php?customfiles='.$wp_rewrite->preg_index(1),
            '^download/(.*)$' => 'index.php?customfiles='.$wp_rewrite->preg_index(1),
        );        

        $newRules = $newRule + $rules;
        return $newRules;
    }


    function add_query_vars($qvars) {
        $qvars[] = 'customfiles';
        return $qvars;
    }

    function flush_rewrite_rules() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    function template_redirect_intercept() {
        global $wp_query;
        if ($wp_query->get('customfiles')) {
            $this->pushoutput($wp_query->get('customfiles'));
            exit;
        }
    }

    function pushoutput($message) {
        $this->output($message);
    }

    function output( $output ) {     
        $images_extensions = array("jpg", "png", "gif", "jpeg");
        $file_extension_path = pathinfo($output);
        $file_extension = $file_extension_path['extension'];    
        
        //Google local script                
        if ($output == 'local') {

                $url = plugins_url('includes/local.php', __FILE__);
        
        //For terms-and-conditions PDF documents           
        } elseif ($file_extension == 'pdf' &&  !empty($this->pdf_url)) {

                $file = plugins_url('includes/pdf_files.php', __FILE__);
                $url = $file . '?file_name=' . $output;
        //For Coupons Newsletter images    
        } elseif (in_array( $file_extension, $images_extensions)) {

                $file = plugins_url('includes/newsletter_images.php', __FILE__);
                $url = $file . '?image_name=' . $output;
        }
        
        wp_safe_redirect( $url, 301);
        exit;     
    }
}

//Update Plugin Checker
$MyUpdateChecker = PucFactory::buildUpdateChecker(
                'http://vortex.1stonlinesolutions.com/dev/WPU/?action=get_metadata&slug=' . FOS_PLUGIN_NAME, //Metadata URL.
                __FILE__, //Full path to the main plugin file.
                FOS_PLUGIN_NAME //Plugin slug. Usually it's the same as the name of the directory.
);

$MyPluginCode = new MyFiles();
register_activation_hook( __file__, array($MyPluginCode, 'activate') );

// Using a filter instead of an action to create the rewrite rules.
// Write rules -> Add query vars -> Recalculate rewrite rules
add_filter('rewrite_rules_array', array($MyPluginCode, 'create_rewrite_rules'));
add_filter('query_vars',array($MyPluginCode, 'add_query_vars'));

// Recalculates rewrite rules during admin init to save resourcees.
// Could probably run it once as long as it isn't going to change or check the
// $wp_rewrite rules to see if it's active.
add_filter('admin_init', array($MyPluginCode, 'update'));
add_action( 'template_redirect', array($MyPluginCode, 'template_redirect_intercept') );