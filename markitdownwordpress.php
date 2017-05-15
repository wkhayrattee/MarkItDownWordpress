<?php
/*
 * Plugin Name: Mark It Down Wordpress!
 * Plugin URI: http://7php.com/
 * Description: Write posts or pages in plain-text Markdown syntax. This plugin will also switch-off the Visual Editor.
 * Author: 7PHP
 * Version: 1.0.0
 * Text Domain: jetpack
 * Domain Path: /languages/
 * License: GPL2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

$lib_files_list['gfm']   = plugin_dir_path( __FILE__ ) . 'markdown/lib/gfm.php';
$lib_files_list['extra'] = plugin_dir_path( __FILE__ ) . 'markdown/lib/extra.php';
foreach ($lib_files_list as $file) {
    if (! file_exists($file)) {
        trigger_error("Cannot find the file at: " . $file, E_USER_ERROR);
    }
}

if (! class_exists('MarkdownExtra_Parser')) {
    require_once $lib_files_list['extra'];
}

require_once $lib_files_list['gfm'];
require_once plugin_dir_path( __FILE__ ) . 'markdown/class.jetpack-options.php';
require_once plugin_dir_path( __FILE__ ) . 'markdown/easy-markdown.php';

/*
 * Disable the wordpress visual editor, in Markdown writing we don't need it
 */
add_filter('user_can_richedit' , create_function('' , 'return false;') , 50);

/*
 * Leaving this function here to prevent any changes form happening inside the core file easy-mardown.php
 * since we are already including this file inside markitdownwordpress.php
 */
function jetpack_require_lib($slug) {}