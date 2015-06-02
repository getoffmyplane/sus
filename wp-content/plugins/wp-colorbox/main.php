<?php
/*
Plugin Name: WP Colorbox
Version: 1.0.5
Plugin URI: http://noorsplugin.com/2014/01/11/wordpress-colorbox-plugin/
Author: naa986
Author URI: http://noorsplugin.com/
Description: Colorbox Lightbox to pop up media files from your WordPress site
*/

if(!defined('ABSPATH')) exit;
if(!class_exists('WP_COLORBOX'))
{
    class WP_COLORBOX
    {
        var $plugin_version = '1.0.5';
        var $plugin_url;
        var $plugin_path;
        function __construct()
        {
            define('WP_COLORBOX_VERSION', $this->plugin_version);
            define('WP_COLORBOX_SITE_URL',site_url());
            define('WP_COLORBOX_URL', $this->plugin_url());
            define('WP_COLORBOX_PATH', $this->plugin_path());
            define('WP_COLORBOX_LIBRARY_VERSION', '1.4.33');
            $this->plugin_includes();
            add_action( 'admin_enqueue_scripts', array( $this, 'plugin_scripts' ), 0 );
        }
        function plugin_includes()
        {
            if(is_admin( ) )
            {
                //add_filter('plugin_action_links', array(&$this,'add_plugin_action_links'), 10, 2 );
            }
            //add_action('admin_menu', array( &$this, 'add_options_menu' ));
            //add_action('wp_ajax_open_link_in_lightbox', 'action_to_short_code_for_wp_colorbox_media_handler');
            add_shortcode('wp_colorbox_media','wp_colorbox_media_handler');
            //allows shortcode execution in the widget, excerpt and content
            add_filter('widget_text', 'do_shortcode');
            add_filter('the_excerpt', 'do_shortcode', 11);
            add_filter('the_content', 'do_shortcode', 11);
        }

        public function plugin_scripts()
        {
            /*if (!is_admin())
            {*/
                wp_enqueue_script('jquery');
                wp_register_script('colorbox', WP_COLORBOX_URL.'/jquery.colorbox.js', array('jquery'), WP_COLORBOX_VERSION);
                wp_enqueue_script('colorbox');
                wp_register_script('wp-colorbox', WP_COLORBOX_URL.'/wp-colorbox.js', array('colorbox'), WP_COLORBOX_VERSION);
                wp_enqueue_script('wp-colorbox');
                wp_register_style('colorbox', WP_COLORBOX_URL.'/example5/colorbox.css');
                wp_enqueue_style('colorbox');
            //}
        }
        function plugin_url()
        {
            if($this->plugin_url) return $this->plugin_url;
            return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
        }
        function plugin_path(){ 	
            if ( $this->plugin_path ) return $this->plugin_path;		
            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }
        function add_plugin_action_links($links, $file)
        {
            if ( $file == plugin_basename( dirname( __FILE__ ) . '/main.php' ) )
            {
                $links[] = '<a href="options-general.php?page=wp-colorbox-settings">Settings</a>';
            }
            return $links;
        }

        function add_options_menu()
        {
            if(is_admin())
            {
                add_options_page('WP Colorbox Settings', 'WP Colorbox', 'manage_options', 'wp-colorbox-settings', array(&$this, 'display_options_page'));
            }
        }
    }
    $GLOBALS['wp_colorbox'] = new WP_COLORBOX();
}

function action_to_short_code_for_wp_colorbox_media_handler()
{
    WP_COLORBOX;

    $url = $_POST['url'];
    $title = $_POST['title'];
    $type = $_POST['type'];
    $hyperlink = $_POST['hyperlink'];

    echo do_shortcode('[wp_colorbox_media url='.$url.' type='.$type.' hyperlink='.$hyperlink.']');
    //wp_die();
}

function wp_colorbox_media_handler($atts)
{
    extract(shortcode_atts(array(
        'url' => '',
        'title' => '',
        'type' => '',
        'hyperlink' => 'Click Here',
    ), $atts));
    if(empty($url)){
        return "Please specify the URL of your media file that you wish to pop up in lightbox";
    }
    if(empty($type)){
        return "Please specify the type of media file you wish to pop up in lightbox";
    }
    if (strpos($hyperlink, 'http') !== false)
    {
        $hyperlink = '<img src="'.$hyperlink.'">';
    }
    $class = "";
    if($type=="image"){
        $class = "wp-colorbox-image";
    }
    if($type=="youtube"){
        $class = "wp-colorbox-youtube";
    }
    if($type=="vimeo"){
        $class = "wp-colorbox-vimeo";
    }
    if($type=="iframe"){
        $class = "wp-colorbox-iframe";
    }
    if($type=="inline"){
        $class = "wp-colorbox-inline";
    }
    if(!empty($title)){
        $title = ' title="'.$title.'"';
    }
    $output = <<<EOT
    <a class="$class" href="$url"{$title}>$hyperlink</a>
EOT;
    return $output;
}
