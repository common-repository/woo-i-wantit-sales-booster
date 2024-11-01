<?php
/**
 * Plugin Name:     WooCommerce I-wantit sales booster
 * Plugin URI:      http://wordpress.org/extend/plugins/woo-i-wantit-sales-booster/
 * Description:     Integration between I-wantit and WooCommerce.
 * Author:          i-wantit
 * Author URI:      https://i-wantit.com
 * License:         GNU General Public License 2.0 (GPL) http://www.gnu.org/licenses/gpl-2.0.html
 * Version:         1.0.3
 *
 * @package         IWANTIT_Sales_Booster
 */

if ( ! defined( 'ABSPATH' ) ) exit;


class IWantitAddBuySettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
        wp_enqueue_style( 'iwantit-admin_settings_css', plugin_dir_url( __FILE__ ).'assets/css/admin_settings.css' );
    }


    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'I-Wantit Settings', 
            'manage_options', 
            'iwantit-setting-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'iwantit_option_settings' );
        ?>
        <div class="wrap">
            <h1>I-wantit Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'iwantit_option_group' );
                do_settings_sections( 'iwantit-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'iwantit_option_group', // Option group
            'iwantit_option_settings', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Iwantit Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'iwantit-setting-admin' // Page
        );  

        add_settings_field(
            'partner_id', // ID
            __("Partner ID"), // Title 
            array( $this, 'partner_id_callback' ), // Callback
            'iwantit-setting-admin', // Page
            'setting_section_id' // Section           
        );

        add_settings_field(
            'btn_image_width', // ID
            __("Button image width (300px or 150px recommanded)"), // Title 
            array( $this, 'btn_image_width_callback' ), // Callback
            'iwantit-setting-admin', // Page
            'setting_section_id' // Section           
        );

        add_settings_field(
            'add_btn_img', // ID
            __("Button image"), // Button add 
            array( $this, 'add_btn_img_callback' ), // Callback
            'iwantit-setting-admin', // Page
            'setting_section_id' // Section           
        );

        add_settings_field(
            'add_btn_position', // ID
            __("Button position"), // Button add 
            array( $this, 'add_btn_position_callback' ), // Callback
            'iwantit-setting-admin', // Page
            'setting_section_id' // Section           
        );


    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['partner_id'] ) )
            $new_input['partner_id'] = ( $input['partner_id'] );

        if( isset( $input['btn_image_width'] ) )
            $new_input['btn_image_width'] = (int)( $input['btn_image_width'] );

        if( isset( $input['add_btn_img'] ) )
            $new_input['add_btn_img'] = ( $input['add_btn_img'] );

        if( isset( $input['add_btn_position'] ) )
            $new_input['add_btn_position'] = ( $input['add_btn_position'] );

        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your configuration :';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function partner_id_callback()
    {
        printf(
            '<input type="text" id="partner_id" name="iwantit_option_settings[partner_id]" value="%s" />',
            isset( $this->options['partner_id'] ) ? esc_attr( $this->options['partner_id']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function btn_image_width_callback()
    {
        if(!isset($this->options['btn_image_width']) OR !$this->options['btn_image_width']){
            $this->options['btn_image_width'] = 300;
        }
        printf(
            '<input type="number" id="btn_image_width" name="iwantit_option_settings[btn_image_width]" value="%s" />',
            isset( $this->options['btn_image_width'] ) ? esc_attr( $this->options['btn_image_width']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function add_btn_img_callback()
    {
        if(!isset($this->options['add_btn_img']) OR !$this->options['add_btn_img']){
            $this->options['add_btn_img'] = 1;
        }
        /*
        echo '<select id="add_btn_img_id" name="iwantit_option_settings[add_btn_img]" >';
        for($i=1; $i<=49; $i++){
            printf(
                '<option value="%s" %s >%s</option>',
                $i,
                (isset( $this->options['add_btn_img'] ) AND $this->options['add_btn_img']==$i) ? 'selected="selected"' : '',
                sprintf(__("Button %s"), $i)
            );
        }
        echo '</select>';
        echo '<div id="button-iwantit-demo" ></div>';
        */


        echo '<div class="form-group wrap-btn-image-selector" >';
        for($i=1; $i<=52; $i++){
            printf(
                '<label for="%s" ><input type="radio" name="iwantit_option_settings[add_btn_img]" value="%s" id="%s" %s ><span><img src="%s" /></span></label>',
                'add_btn_img_'.$i,
                $i,
                'add_btn_img_'.$i,
                (isset( $this->options['add_btn_img'] ) AND $this->options['add_btn_img']==$i) ? 'checked="checked"' : '',
                plugin_dir_url( __FILE__ ).'/assets/img/buttons/IWI_BOUTON_SOURCE-'.$i.'.svg'
            );
        }
        echo '</div>';

        
        
        wp_enqueue_script( 'iw2_admin_js', plugin_dir_url( __FILE__ ).'assets/js/iwi2_admin.js', array('jquery') );
        wp_localize_script('iw2_admin_js', 'iw2_admin_js', array('plugin_url' => plugin_dir_url( __FILE__ )) );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function add_btn_position_callback()
    {
        if(!isset($this->options['add_btn_position']) OR !$this->options['add_btn_position']){
            $this->options['add_btn_position'] = 'woocommerce_after_single_product_summary';
        }
        $positions = array(
            'woocommerce_single_product_summary' => __('In product summary'),
            'woocommerce_before_add_to_cart_form' => __('Before add to cart single form'),
            'woocommerce_after_add_to_cart_form' => __('After add to cart single form'),
            'woocommerce_product_meta_start' => __('Before meta product'),
            'woocommerce_product_meta_end' => __('After meta product (Before product share)'),
            'woocommerce_share' => __('After product share'),
        );
        echo '<select name="iwantit_option_settings[add_btn_position]" >';
        foreach($positions as $position_key => $position_name){
            printf(
                '<option value="%s" %s >%s</option>',
                $position_key,
                (isset( $this->options['add_btn_position'] ) AND $this->options['add_btn_position']==$position_key) ? 'selected="selected"' : '',
                sprintf(__("%s"), $position_name)
            );
        }
        echo '</select>';
    }

}


function iwantit_get_partner_id()
{
    $options = get_option( 'iwantit_option_settings' );
    if(isset($options['partner_id'])) return $options['partner_id'];
    return null;
}

function iwantit_get_btn_image_width()
{
    $options = get_option( 'iwantit_option_settings' );
    if(isset($options['btn_image_width'])) return $options['btn_image_width'];
    return 300;
}

function iwantit_get_add_btn_img()
{
    $options = get_option( 'iwantit_option_settings' );
    if(isset($options['add_btn_img'])) return $options['add_btn_img'];
    return 1;
}

function iwantit_get_add_btn_position()
{
    $options = get_option( 'iwantit_option_settings' );
    if(isset($options['add_btn_position'])) return $options['add_btn_position'];
    return 'woocommerce_after_single_product_summary';
}



function iwi2_init()
{
    if( is_admin() )
    {
        $iwantit_settings_page = new IWantitAddBuySettingsPage();
    }


    wp_register_script( 'iwi2_js', 'https://www.i-wantit.com/js/iwi2.js');
    wp_register_script( 'iwi2_add_btn_js', plugin_dir_url( __FILE__ ).'assets/js/iwi2_add_btn.js', array('iwi2_js') );
    wp_localize_script('iwi2_add_btn_js', 'iwi2_add_btn', array('domainKey' => iwantit_get_partner_id()) );

    $position = iwantit_get_add_btn_position();
    switch ($position) {
        case 'woocommerce_before_single_product_summary':
        case 'woocommerce_after_single_product_summary':
        case 'woocommerce_before_single_product':
        case 'woocommerce_after_single_product':
        case 'woocommerce_single_product_summary':
        case 'woocommerce_before_add_to_cart_form':
        case 'woocommerce_after_add_to_cart_form':
        case 'woocommerce_product_meta_start':
        case 'woocommerce_product_meta_end':
        case 'woocommerce_share':
            add_action($position, 'iwantit_woocommerce_product_meta_start_load_script', 999);
            break;
        case 'woocommerce_before_short_description':
            add_filter( 'woocommerce_short_description', 'iwantit_woocommerce_before_content_load_script_filter', 999, 2);
            break;
        case 'woocommerce_after_short_description':
            add_filter( 'woocommerce_short_description', 'iwantit_woocommerce_after_content_load_script_filter', 999, 2);
            break;
        case 'woocommerce_before_the_title':
            add_filter( 'the_title', 'iwantit_woocommerce_before_content_load_script_filter', 999, 2);
            break;
        case 'woocommerce_after_the_title':
            add_filter( 'the_title', 'iwantit_woocommerce_after_content_load_script_filter', 999, 2);
            break;
        case 'woocommerce_before_the_content':
            add_filter( 'the_content', 'iwantit_woocommerce_before_content_load_script_filter', 999, 2);
            break;
        case 'woocommerce_after_the_content':
            add_filter( 'the_content', 'iwantit_woocommerce_after_content_load_script_filter', 999, 2);
            break;
        default:
            # code...
            break;
    }

    add_action('woocommerce_before_shop_loop_item', 'iwantit_woocommerce_disable_filters', 999);

}
add_action( 'init', 'iwi2_init');


function iwantit_woocommerce_disable_filters()
{
    remove_filter( 'woocommerce_short_description', 'iwantit_woocommerce_before_content_load_script_filter', 999);
    remove_filter( 'woocommerce_short_description', 'iwantit_woocommerce_after_content_load_script_filter', 999);
    remove_filter('the_title', 'iwantit_woocommerce_before_content_load_script_filter', 999);
    remove_filter('the_title', 'iwantit_woocommerce_after_content_load_script_filter', 999);
    remove_filter('the_content', 'iwantit_woocommerce_before_content_load_script_filter', 999);
    remove_filter('the_content', 'iwantit_woocommerce_after_content_load_script_filter', 999);
}


function iwantit_woocommerce_before_content_load_script_filter($content, $post_id){
    if( get_post_type($post_id)!='product' AND !is_single() ){
        return $content;
    }
    ob_start();
    iwantit_woocommerce_product_meta_start_load_script();
    $output = ob_get_contents();
    ob_end_clean();
    return $output.$content;
}

function iwantit_woocommerce_after_content_load_script_filter($content, $post_id){
    if( get_post_type($post_id)!='product' AND !is_single() ){
        return $content;
    }
    ob_start();
    iwantit_woocommerce_product_meta_start_load_script();
    $output = ob_get_contents();
    ob_end_clean();
    return $content.$output;
}


function iwantit_woocommerce_session_start()
{
   if(isset($_GET['iwi_ref']) AND isset($_GET['iwi_add_date']) AND isset($_GET['iwi_data']))
    {
        if(!session_id())
        {
            session_start();
        }
        $_SESSION['iwi_request_product'] = array(
            'iwi_ref' => sanitize_text_field($_GET['iwi_ref']),
            'iwi_add_date' => sanitize_text_field($_GET['iwi_add_date']),
            'iwi_data' => sanitize_text_field($_GET['iwi_data']),
        );
    }
}
add_action( 'init', 'iwantit_woocommerce_session_start' );


function iwantit_woocommerce_product_meta_start_load_script()
{  
    global $woocommerce;
    global $product;
    $product_id = $product->id;
    wp_enqueue_script( 'iwi2_add_btn_js' );
    echo '<div class="iwi_button" id="iwi_button" data-preload="0" data-item-title="'.esc_attr('Partager sur Iwantit', 'iwi2').'" data-item-token="'.$product_id.'" />
        <img src="'.plugin_dir_url( __FILE__ ).'assets/img/buttons/IWI_BOUTON_SOURCE-'.iwantit_get_add_btn_img().'.svg" style="max-width: '.iwantit_get_btn_image_width().'px;margin: 15px 0;" />
    </div>';
}


add_action('woocommerce_order_details_after_order_table', 'iwantit_woocommerce_payment_successful_result_load_script');
function iwantit_woocommerce_payment_successful_result_load_script($order_details)
{
    if(!session_id())
    {
        session_start();
    }
    if(isset($_SESSION['iwi_request_product']) AND !empty($_SESSION['iwi_request_product']) AND $iwi_request_product = $_SESSION['iwi_request_product'])
    {

        $partenaire_id = iwantit_get_partner_id();
        $iwi_ref = $iwi_request_product['iwi_ref'];
        $iwi_add_date = $iwi_request_product['iwi_add_date'];
        $iwi_data = $iwi_request_product['iwi_data'];
        $order_id = $order_details->get_id();
        $iwi_price = $order_details->get_total();
        $url_ = 'https://www.i-wantit.com/addBuy?iwi_data='.$iwi_data.'&iwi_orderId='.$order_id.'&iwi_price='.$iwi_price.'&iwi_partner='.$partenaire_id;
        /*
            No CURL support
            wp_remote_get($url_);
        */
        echo '<img src="'.$url_.'" style="width: 1px; height: 1px;" />';
    }
    return;
}