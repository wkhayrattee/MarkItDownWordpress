<?php
/**
 * Class MarkItDownOptions
 *
 * This is class serves as a replacement to class.jetpack-options.php in order to remove dependecies to the latter.
 * The reason being is that if we want to make more standalone plugins like this one, we can it.
 */
class MarkItDownOptions
{
    /**
     * Returns the requested option, and ensures it's autoloaded in the future.
     * This does _not_ adjust the prefix in any way (does not prefix jetpack_%)
     *
     * @param string $name Option name
     * @param mixed $default (optional)
     *
     * @return mixed
     */
    public static function get_option_and_ensure_autoload( $name, $default )
    {
        // In this function the name is not adjusted by prefixing jetpack_
        // so if it has already prefixed, we'll replace it and then
        // check if the option name is a network option or not
        $jetpack_name = preg_replace( '/^jetpack_/', '', $name, 1 );
        $is_network_option = self::is_network_option( $jetpack_name );
        $value = $is_network_option ? get_site_option( $name ) : get_option( $name );

        if ( $value === false && $default !== false ) {
            if ( $is_network_option ) {
                update_site_option( $name, $default );
            } else {
                update_option( $name, $default );
            }
            $value = $default;
        }

        return $value;
    }

    /**
     * Checks if an option must be saved for the whole network in WP Multisite
     *
     * @param string $option_name Option name. It must come _without_ `jetpack_%` prefix. The method will prefix the option name.
     *
     * @return bool
     */
    public static function is_network_option( $option_name )
    {
        if ( ! is_multisite() ) {
            return false;
        }
        return in_array( $option_name, self::get_option_names( 'network' ) );
    }

    /**
     * Returns an array of option names for a given type.
     *
     * @param string $type The type of option to return. Defaults to 'compact'.
     *
     * @return array
     */
    public static function get_option_names( $type = 'compact' ) {
        switch ( $type ) {
            case 'non-compact' :
            case 'non_compact' :
                return array(
                    'activated',
                    'active_modules',
                    'available_modules',
                    'do_activate',
                    'log',
                    'publicize',
                    'slideshow_background_color',
                    'widget_twitter',
                    'wpcc_options',
                    'relatedposts',
                    'file_data',
                    'autoupdate_plugins',          // (array)  An array of plugin ids ( eg. jetpack/jetpack ) that should be autoupdated
                    'autoupdate_plugins_translations', // (array)  An array of plugin ids ( eg. jetpack/jetpack ) that should be autoupdated translation files.
                    'autoupdate_themes',           // (array)  An array of theme ids ( eg. twentyfourteen ) that should be autoupdated
                    'autoupdate_themes_translations', // (array)  An array of theme ids ( eg. twentyfourteen ) that should autoupdated translation files.
                    'autoupdate_core',             // (bool)   Whether or not to autoupdate core
                    'autoupdate_translations',     // (bool)   Whether or not to autoupdate all translations
                    'json_api_full_management',    // (bool)   Allow full management (eg. Activate, Upgrade plugins) of the site via the JSON API.
                    'sync_non_public_post_stati',  // (bool)   Allow synchronisation of posts and pages with non-public status.
                    'site_icon_url',               // (string) url to the full site icon
                    'site_icon_id',                // (int)    Attachment id of the site icon file
                    'dismissed_manage_banner',     // (bool) Dismiss Jetpack manage banner allows the user to dismiss the banner permanently
                    'restapi_stats_cache',         // (array) Stats Cache data.
                    'unique_connection',           // (array)  A flag to determine a unique connection to wordpress.com two values "connected" and "disconnected" with values for how many times each has occured
                    'protect_whitelist',           // (array) IP Address for the Protect module to ignore
                    'sync_error_idc',              // (bool|array) false or array containing the site's home and siteurl at time of IDC error
                    'safe_mode_confirmed',         // (bool) True if someone confirms that this site was correctly put into safe mode automatically after an identity crisis is discovered.
                    'migrate_for_idc',             // (bool) True if someone confirms that this site should migrate stats and subscribers from its previous URL
                );

            case 'private' :
                return array(
                    'register',
                    'authorize',
                    'blog_token',                  // (string) The Client Secret/Blog Token of this site.
                    'user_token',                  // (string) The User Token of this site. (deprecated)
                    'user_tokens'                  // (array)  User Tokens for each user of this site who has connected to jetpack.wordpress.com.
                );

            case 'network' :
                return array(
                    'file_data'                     // (array) List of absolute paths to all Jetpack modules
                );
        }

        return array(
            'id',                           // (int)    The Client ID/WP.com Blog ID of this site.
            'publicize_connections',        // (array)  An array of Publicize connections from WordPress.com
            'master_user',                  // (int)    The local User ID of the user who connected this site to jetpack.wordpress.com.
            'version',                      // (string) Used during upgrade procedure to auto-activate new modules. version:time
            'old_version',                  // (string) Used to determine which modules are the most recently added. previous_version:time
            'fallback_no_verify_ssl_certs', // (int)    Flag for determining if this host must skip SSL Certificate verification due to misconfigured SSL.
            'time_diff',                    // (int)    Offset between Jetpack server's clocks and this server's clocks. Jetpack Server Time = time() + (int) Jetpack_Options::get_option( 'time_diff' )
            'public',                       // (int|bool) If we think this site is public or not (1, 0), false if we haven't yet tried to figure it out.
            'videopress',                   // (array)  VideoPress options array.
            'is_network_site',              // (int|bool) If we think this site is a network or a single blog (1, 0), false if we haven't yet tried to figue it out.
            'social_links',                 // (array)  The specified links for each social networking site.
            'identity_crisis_whitelist',    // (array)  An array of options, each having an array of the values whitelisted for it.
            'gplus_authors',                // (array)  The Google+ authorship information for connected users.
            'last_heartbeat',               // (int)    The timestamp of the last heartbeat that fired.
            'jumpstart',                    // (string) A flag for whether or not to show the Jump Start.  Accepts: new_connection, jumpstart_activated, jetpack_action_taken, jumpstart_dismissed.
            'hide_jitm',                    // (array)  A list of just in time messages that we should not show because they have been dismissed by the user
            'custom_css_4.7_migration',     // (bool)   Whether Custom CSS has scanned for and migrated any legacy CSS CPT entries to the new Core format.
        );
    }
}