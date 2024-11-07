<?php

namespace PluginizeLab\DokanCustomers;

/**
 * DokanCustomers class
 *
 * @class DokanCustomers The class that holds the entire DokanCustomers plugin
 */
final class DokanCustomers {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.2.0';

    /**
     * Instance of self
     *
     * @var DokanCustomers
     */
    private static $instance = null;

    /**
     * Holds various class instances
     *
     * @since 2.6.10
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor for the DokanCustomers class
     *
     * Sets up all the appropriate hooks and actions
     * within our plugin.
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( DOKAN_CUSTOMERS_FILE, [ $this, 'activate' ] );
        register_deactivation_hook( DOKAN_CUSTOMERS_FILE, [ $this, 'deactivate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
        add_action( 'woocommerce_flush_rewrite_rules', [ $this, 'flush_rewrite_rules' ] );
    }

    /**
     * Initializes the DokanCustomers() class
     *
     * Checks for an existing DokanCustomers instance
     * and if it doesn't find one, creates it.
     */
    public static function init() {
        if ( self::$instance === null ) {
			self::$instance = new self();
		}

        return self::$instance;
    }

    /**
     * Magic getter to bypass referencing objects
     *
     * @since 2.6.10
     *
     * @param string $prop
     *
     * @return Class Instance
     */
    public function __get( $prop ) {
		if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
		}
    }

    /**
     * Placeholder for activation function
     *
     * Nothing being called here yet.
     */
    public function activate() {
        // Check dependency plugins
        if ( ! $this->check_dependencies() ) {
            add_action( 'admin_notices', [ $this, 'admin_error_notice_for_dependency_missing' ] );
            return;
        }
    }

    /**
     * Flush rewrite rules after DOKAN_CUSTOMERS is activated or woocommerce is activated
     *
     * @since 3.2.8
     */
    public function flush_rewrite_rules() {
        // fix rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Placeholder for deactivation function
     *
     * Nothing being called here yet.
     */
    public function deactivate() {     }

    /**
     * Define all constants
     *
     * @return void
     */
    public function define_constants() {
        $this->define( 'DOKAN_CUSTOMERS_PLUGIN_VERSION', $this->version );
        $this->define( 'DOKAN_CUSTOMERS_DIR', dirname( DOKAN_CUSTOMERS_FILE ) );
        $this->define( 'DOKAN_CUSTOMERS_INC_DIR', DOKAN_CUSTOMERS_DIR . '/includes' );
        $this->define( 'DOKAN_CUSTOMERS_TEMPLATE_DIR', DOKAN_CUSTOMERS_DIR . '/templates' );
        $this->define( 'DOKAN_CUSTOMERS_PLUGIN_ASSET', plugins_url( 'assets', DOKAN_CUSTOMERS_FILE ) );

        // give a way to turn off loading styles and scripts from parent theme
        $this->define( 'DOKAN_CUSTOMERS_LOAD_STYLE', true );
        $this->define( 'DOKAN_CUSTOMERS_LOAD_SCRIPTS', true );
    }

    /**
     * Define constant if not already defined
     *
     * @param string      $name
     * @param string|bool $value
     *
     * @return void
     */
    private function define( $name, $value ) {
        if ( ! defined( $name ) ) {
            define( $name, $value );
		}
    }

    /**
     * Load the plugin after WP User Frontend is loaded
     *
     * @return void
     */
    public function init_plugin() {
        // Check dependency plugins
        if ( ! $this->check_dependencies() ) {
            add_action( 'admin_notices', [ $this, 'admin_error_notice_for_dependency_missing' ] );
            return;
        }

        $this->includes();
        $this->init_hooks();

        do_action( 'DOKAN_CUSTOMERS_loaded' );
    }

    /**
     * Initialize the actions
     *
     * @return void
     */
    public function init_hooks() {
        // initialize the classes
        add_action( 'init', [ $this, 'init_classes' ], 4 );
        add_action( 'plugins_loaded', [ $this, 'after_plugins_loaded' ] );
    }

    /**
     * Include all the required files
     *
     * @return void
     */
    public function includes() {
        // include_once STUB_PLUGIN_DIR . '/functions.php';
    }

    /**
     * Init all the classes
     *
     * @return void
     */
    public function init_classes() {
        $this->container['scripts'] = new Assets();
        new ManageCustomers();
    }

    /**
     * Executed after all plugins are loaded
     *
     * At this point DOKAN_CUSTOMERS Pro is loaded
     *
     * @since 2.8.7
     *
     * @return void
     */
    public function after_plugins_loaded() {
        // Initiate background processes and other tasks
    }

    /**
     * Check plugin dependencies
     *
     * @return boolean
     */
    public function check_dependencies() {
        if ( ! class_exists( 'WooCommerce' ) || ! class_exists( 'WeDevs_Dokan' ) ) {
            return false;
        }

        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) || ! is_plugin_active( 'dokan-lite/dokan.php' ) ) {
            return false;
        }

        return true;
    }

    /**
     * Dependency error message
     *
     * @return void
     */
    protected function get_dependency_message() {
        return __( 'Dokan customer plugin is enabled but not effective. It requires WooCommerce & Dokan Lite(Free) plugins to work.', 'dokan-customers' );
    }

    /**
     * Admin error notice for missing dependency plugins
     *
     * @return void
     */
    public function admin_error_notice_for_dependency_missing() {
        $class = 'notice notice-error';
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $this->get_dependency_message() ) );
    }
}
