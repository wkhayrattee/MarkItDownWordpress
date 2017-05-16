<?php
/*
 * Plugin Name: Mark It Down Wordpress!
 * Plugin URI: http://7php.com/
 * Description: Helping you Write posts or pages in plain-text Markdown syntax. It will also switch-off the Visual Editor. This plugin cleverly sits on a giant's shoulder, namely Jetpack.
 * Author: 7PHP
 * Version: 1.0.1
 * Text Domain: jetpack
 * Domain Path: /languages/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */


if (! class_exists('Jetpack')) {
    $lib_files_list['gfm'] = plugin_dir_path(__FILE__) . 'markdown/lib/gfm.php';
    $lib_files_list['extra'] = plugin_dir_path(__FILE__) . 'markdown/lib/extra.php';

    if (! class_exists('Markdown_Parser')) {
        include $lib_files_list['extra'];
    }
    if (! class_exists('WPCom_GHF_Markdown_Parser')) {
        include $lib_files_list['gfm'];
    }

    require_once plugin_dir_path(__FILE__) . 'markdown/class.jetpack-options.php';
    require_once plugin_dir_path(__FILE__) . 'markdown/easy-markdown.php';

    /*
     * Disable the wordpress visual editor, in Markdown writing we don't need it
     */
    add_filter('user_can_richedit', create_function('', 'return false;'), 50);

    /*
     * Though we are using this function, leaving it here to prevent any changes from happening
     * inside the core file easy-mardown.php and hence ease updates process when we have to
     */
    function jetpack_require_lib($slug) { }

} else {
    trigger_error("It seems you are already running the Jetpack plugin. If YES, you don't need this plugin as Jetpack already has this module bundled.", E_USER_ERROR);
}