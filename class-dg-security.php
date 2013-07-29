<?php
/**
 * DG Security.
 *
 * @package   Dg_Security
 * @author    Ross Tweedie <ross.tweedie@dachisgroup.com>
 * @license   GPL-2.0+
 * @link      http://labs.dachisgroup.com
 * @copyright 2013 Dachis Group
 */

/**
 * DG Security.
 *
 *
 * @package Dg_Security
 */
class Dg_Security {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'dg-security';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

    protected static $_capabilities_to_check = array( 'publish_posts','upload_files','edit_published_posts');

    protected static $_options;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		// add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );



        // Let's replace the core WordPress strength indicator
        add_action('login_form_resetpass', array(&$this, 'replace_core_strength'));

        add_action('personal_options', array(&$this, 'replace_core_strength'));
        add_action('user_new_form_tag', array(&$this, 'replace_core_strength'));

        // Hook onto profile update to check user profile update and throw an error if the password isn't strong
        add_action( 'user_profile_update_errors', array( $this, 'validate_profile_update' ), 0, 3 );


        // Hook onto password reset screen
        add_action( 'validate_password_reset', array( $this, 'validate_strong_password' ), 10, 2 );

	}


    /**
     * Function to translate the validate profile update errors with the validate password reset action.
     *
     * @param $errors
     * @param $update
     * @param $user_data
     *
     * @return validate_strong_password
     */
    function validate_profile_update( $errors, $update, $user_data ) {
    	return $this->validate_strong_password( $errors, $user_data );
    }


    /**
     * Replace the core strength JavaScript
     *
     * @param void
     *
     * @return void
     */
    function replace_core_strength( )
    {
        wp_deregister_script('password-strength-meter'); // Remove the core

        wp_register_script( 'jquery.complexify.banlist', plugins_url( 'vendor/jquery.complexify/jquery.complexify.banlist.js', __FILE__ ), array( 'jquery' ), $this->version );

        wp_register_script( 'jquery.complexify', plugins_url( 'vendor/jquery.complexify/jquery.complexify.js', __FILE__ ), array( 'jquery', 'jquery.complexify.banlist' ), $this->version );

        wp_enqueue_script( 'jquery.complexify.banlist' );

        // Add the updated version
        wp_enqueue_script( 'password-strength-meter', plugins_url( 'js/password-strength-meter.js', __FILE__ ), array( 'jquery', 'jquery.complexify.banlist' ), $this->version );

        // Localise the new script.
        $translation_array = array(
                                'empty' => __('Strength indicator'),
                                'short' => __('Very weak'),
                                'bad' => __('Weak'),
                                /* translators: password strength */
                                'good' => _x('Medium', 'password strength'),
                                'strong' => __('Strong'),
                                'mismatch' => __('Mismatch')
                            );
        did_action( 'init' ) && wp_localize_script( 'password-strength-meter', 'pwsL10n', $translation_array );

    }


	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public static function activate( $network_wide ) {
        // Stub
	}


	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {
        // Stub
	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	}


	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		}

	}


	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {

		if ( ! isset( $this->plugin_screen_hook_suffix ) ) {
			return;
		}

		$screen = get_current_screen();
		if ( $screen->id == $this->plugin_screen_hook_suffix ) {
			wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		}
	}


	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		//wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
	}


	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

        // We need to replace the core password strength JavaScript with the updated version.
        $this->replace_core_strength();

		// wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		/*
        $this->plugin_screen_hook_suffix = add_plugins_page(
			__( 'Page Title', $this->plugin_slug ),
			__( 'Menu Text', $this->plugin_slug ),
			'read',
			$this->plugin_slug,
			array( $this, 'display_plugin_admin_page' )
		);
		*/

	}


	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_admin_page() {
		// Stub
	}


    /**
    * Check if we should be ensuring a strong password for the current user.
    *
    * Tests on basic capabilities that can compromise a site. Doesn't check on higher capabilities.
    * It's assumed the someone who can't publish_posts won't be able to update_core!
    *
    * @since	1.0.0
    * @param integer $user_id
    * @return boolean true || false
    */
    function enforce_for_user( $user_id ) {
    	$enforce = true;
    	$capabilities_check = Dg_Security::$_capabilities_to_check;
    	$capabilities_check = apply_filters( 'dg_security_capability_check', $capabilities_check );
    	$capabilities_check = (array) $capabilities_check;

        if ( ! empty( $check_caps ) ) {
    		$enforce = false; // Now we won't enforce unless the user has one of the caps specified
    		foreach ( $capabilities_check as $capability ) {
    			if ( user_can( $user_id, $capability ) ) {
    				$enforce = true;
    				break;
    			}
    		}
    	}
    	return $enforce;
    }


    /**
     * Add an additional score to the complexity for different character sets.
     *
     * @param string $string the password
     * @param array $charset the charset to check
     *
     * @return integer
     */
    function add_additional_complexity_for_charset( $string, $charset) {
        $start = $end = $score = $matches = null;

        if ( ! is_array( $charset ) ){
            return 0;
        }

        // Now check if the charset is being used.
        $start  = substr( $charset[0], -4 );
        $end    = substr( $charset[1], -4 );

        $pattern = "/[\x{" . $start . "}-\x{" . $end . "}\x]/u";

        @preg_match( $pattern, $string, $matches ).'<br />';

        if ( is_array( $matches ) && empty( $matches ) ){
            return 0;
        }

        // It would appear that the charset is being used, so let's set and resturn a score.
        $score = hexdec( $charset[1] ) - hexdec( $charset[0] ) + 1;

        return $score;
    }


    /**
     * Check if the string exists in the ban list
     *
     * @param string $string the password
     * @param array $options || null
     *
     * @return boolean TRUE || FALSE
     */
    function in_ban_list( $string, $options = null ) {
        if ( ! $options ){
            $options = self::get_options();
        }

        $passwords = $options['banned_passwords'];
        $banned_passwords = explode( '|', $passwords );

        if ( in_array( strtolower( trim( $string ) ), $banned_passwords )){
            return true;
        }

        return false;
    }


    /**
    * Check the password strength
    *
    * @since	1.0.0
    * @param string $password
    * @param string $username
    * @return   integer	1 = very weak; 2 = weak; 3 = medium; 4 = strong
    */
    function password_strength( $password, $username ) {

        $short_pass     = 1;
        $bad_pass       = 2;
        $good_pass      = 3;
        $strong_pass    = 4;
        $mismatch       = 5;
        $symbol_size    = 0;
        $natLog         = $score = null;
        $complexity     = 0;
        $valid          = false;
        $options        = array();

        $options        = $this->get_options();

        if ( strlen( $password ) < 4 ){
            return $short_pass;
        }

        if ( strtolower( $password ) == strtolower( $username ) ){
            return $bad_ass;
        }

        // Collapse repetition.
        $password = preg_replace('/(([^\d])\2\2)\2+/', '$1', $password);

        // Reset complexity to 0 when banned password is found
        if (! $this->in_ban_list( $password ) ) {

            // Get the charsets to check.
            require_once( plugin_dir_path( __FILE__ ) . 'config/charsets.php' );

            if ( isset( $charsets ) ){
                $i = 0;
                foreach( $charsets AS $key => $charset ){
                    $complexity += $this->add_additional_complexity_for_charset( $password, $charset );
                    $i++;
                }
            }
        }else{
            $complexity = 1;
        }

        // Use natural log to produce linear scale
        $complexity = log( pow($complexity, strlen( $password ) ) ) * ( 1 / $options['strength_scale_factor'] );

        //$valid = ( $complexity > $options['min_complexity'] && strlen( $password ) >= $options['minimum_chars'] );

        // Scale to percentage, so it can be used for a progress bar
        $complexity = ( $complexity / $options['max_complexity'] ) * 100;
        $complexity = ( $complexity > 100) ? 100 : $complexity;

        if ( $complexity < $options['min_complexity'] ){
            return $bad_pass;
        }

    	if ( $complexity < $options['med_complexity'] ){
    		return $good_pass;
        }

        return $strong_pass;
    }


    /**
     * Validate the strong password
     *
     * This will check if the user requires a strong password and enforces it.
     *
     * @param $errors
     * @param $user_data
     *
     * @return array
     */
    function validate_strong_password( $errors, $user_data ) {

        $password = $role = $user_id= $username = false;

        $password = ( isset( $_POST[ 'pass1' ] ) && trim( $_POST[ 'pass1' ] ) ) ? $_POST[ 'pass1' ] : false;
        $role = isset( $_POST[ 'role' ] ) ? $_POST[ 'role' ] : false;
        $user_id = isset( $user_data->ID ) ? $user_data->ID : false;
        $username = isset( $_POST["user_login"] ) ? $_POST["user_login"] : $user_data->user_login;

        // No password set?
        if ( false === $password ){
            return $errors;
        }

        // Already got a password error?
        if ( $errors->get_error_data("pass") ){
            return $errors;
        }

        // Should a strong password be enforced for this user?
        $enforce = true;
        if ( $user_id ) {
            // User ID specified
            $enforce = $this->enforce_for_user( $user_id );
        } else {
            // No ID yet, adding new user - omit check for "weaker" roles
            if ( $role && in_array( $role, apply_filters( 'dg_security_weak_roles', array( 'subscriber', 'contributor' ) ) ) ){
                $enforce = false;
            }
        }

        $password_strength = $this->password_strength( $password, $username );

        // If enforcing and the strength check fails, add error
        if ( $enforce && $password_strength != 4 ){
            $errors->add( 'pass', apply_filters( 'dg_security_error_message', __( '<strong>ERROR</strong>: Your password needs to be a stronger one.', $this->plugin_slug ) ) );
        }

        return $errors;
    }


    /**
     * Get the options
     *
     * @param void
     *
     * @return array
     */
    protected static function get_options()
    {
        if ( isset( self::$_options ) ){
            return self::$_options;
        }

        require_once( plugin_dir_path( __FILE__ ) . 'config/options.php' );
        self::$_options = $options;

        return $options;
    }

}
