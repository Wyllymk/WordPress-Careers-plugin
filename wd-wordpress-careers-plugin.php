<?php
/** 
 * 
 * WordPress_Careers 
 * 
 * @package           WordPress_Careers 
 * @author            Wilson Devops 
 * @copyright         2024 Wilson Devops
 * @license           GPL-2.0-or-later
 * @link              https://github.com/Wyllymk/wd-wordpress-careers-plugin
 * 
 * @wordpress-plugin
 * 
 * Plugin Name:       Wordpress Careers Plugin 
 * Plugin URI:        https://github.com/Wyllymk/wd-wordpress-careers-plugin 
 * Description:       Wordpress Careers Plugin. 
 * Version:           1.0.0 
 * Requires at least: 6.0
 * Requires PHP:      7.2 
 * Author:            Wilson Devops 
 * Author URI:        https://wilsondevops.com
 * Text Domain:       textdomain 
 * License:           GPL v2 or later 
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt 
 * Update URI:        https://github.com/Wyllymk/wd-wordpress-careers-plugin 
 * Requires Plugins:  
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}



if(!(class_exists('WdWordpressCareers'))){
    class WdWordpressCareers{

        function __construct(){
            // Register CPT
            add_action('init', array($this, 'custom_post_type'));
            // Register shortcode
            add_shortcode('custom_careers_loop', array($this, 'custom_careers_loop_shortcode'));
            //Enqueue scripts
            add_action( 'wp_enqueue_scripts', array($this, 'register_wprs') );
            // Register taxonomy
            add_action( 'init', array($this, 'wporg_register_taxonomy') );


        }
        
        /**
         * The code that runs during plugin activation.
         */
        function activate() {
           $this->custom_post_type();
           // Clear the permalinks to remove our post type's rules from the database.
           flush_rewrite_rules();
        }


        /**
         * The code that runs during plugin deactivation.
         */
        function deactivate() {
            // Unregister the post type, so the rules are no longer in memory.
            unregister_post_type( 'careers' );
            unregister_taxonomy( 'location' );
            // Clear the permalinks to remove our post type's rules from the database.
            flush_rewrite_rules();
        }

        function register_wprs(){
            wp_register_style( 'cta_stylesheet', plugins_url( '/assets/careers.css', __FILE__ ) );
            wp_enqueue_style( 'cta_stylesheet' );
        }
        
        /**
         * Register the "Careers" custom post type
         */
        function custom_post_type() {
            $labels = array(
                'name'                  => _x( 'Careers', 'Post type general name', 'career' ),
                'singular_name'         => _x( 'Career', 'Post type singular name', 'career' ),
                'menu_name'             => _x( 'Careers', 'Admin Menu text', 'career' ),
                'name_admin_bar'        => _x( 'Career', 'Add New on Toolbar', 'career' ),
                'add_new'               => __( 'Add New', 'career' ),
                'add_new_item'          => __( 'Add New Career', 'career' ),
                'new_item'              => __( 'New career', 'career' ),
                'edit_item'             => __( 'Edit career', 'career' ),
                'view_item'             => __( 'View career', 'career' ),
                'all_items'             => __( 'All careers', 'career' ),
                'search_items'          => __( 'Search careers', 'career' ),
                'parent_item_colon'     => __( 'Parent careers:', 'career' ),
                'not_found'             => __( 'No careers found.', 'career' ),
                'not_found_in_trash'    => __( 'No careers found in Trash.', 'career' ),
                'featured_image'        => _x( 'Career Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'career' ),
                'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'career' ),
                'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'career' ),
                'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'career' ),
                'archives'              => _x( 'Career archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'career' ),
                'insert_into_item'      => _x( 'Insert into career', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'career' ),
                'uploaded_to_this_item' => _x( 'Uploaded to this career', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'career' ),
                'filter_items_list'     => _x( 'Filter careers list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'career' ),
                'items_list_navigation' => _x( 'Careers list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'career' ),
                'items_list'            => _x( 'Careers list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'career' ),
            );     
            $args = array(
                'labels'             => $labels,
                'description'        => 'career custom post type.',
                'public'             => true,
                'publicly_queryable' => true,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'query_var'          => true,
                'rewrite'            => array( 'slug' => 'careers' ),
                'capability_type'    => 'post',
                'has_archive'        => true,
                'hierarchical'       => false,
                'menu_position'      => 20,
                'menu_icon'          => 'dashicons-chart-area',
                'supports'           => array( 'title', 'editor', 'author', 'thumbnail' ),
                'taxonomies'         => array( 'location' ),
                'show_in_rest'       => true
            );
             
            register_post_type( 'Careers', $args );
        }


        function wporg_register_taxonomy() {
            $labels = array(
                'name'              => _x( 'Location', 'taxonomy general name' ),
                'singular_name'     => _x( 'Location', 'taxonomy singular name' ),
                'search_items'      => __( 'Search Locations' ),
                'all_items'         => __( 'All Locations' ),
                'parent_item'       => __( 'Parent Location' ),
                'parent_item_colon' => __( 'Parent Location:' ),
                'edit_item'         => __( 'Edit Location' ),
                'update_item'       => __( 'Update Location' ),
                'add_new_item'      => __( 'Add New Location' ),
                'new_item_name'     => __( 'New Location Name' ),
                'menu_name'         => __( 'Location' ),
            );
            $args   = array(
                'hierarchical'      => true, // make it hierarchical (like categories)
                'labels'            => $labels,
                'show_ui'           => true,
                'show_admin_column' => true,
                'query_var'         => true,
                'rewrite'           => [ 'slug' => 'location' ],
            );
            register_taxonomy( 'location', [ 'careers' ], $args );
        }

        // Define shortcode function
        function custom_careers_loop_shortcode() {
            // WP_Query arguments
            $args = array(
                'post_type'      => 'careers',
                'posts_per_page' => 10,
            );
            
            // The Query
            $loop = new WP_Query($args);
        
            // The Loop
            $output = '';
            if ($loop->have_posts()) {
                $output .= '<div class="container">';
                while ($loop->have_posts()) {
                    $loop->the_post();

                    // Get the terms for the "location" taxonomy
                    $locations = wp_get_post_terms(get_the_ID(), 'location');
                    $location_text = !empty($locations) ? $locations[0]->name : 'On-Site'; // Default to "On-Site" if no location term is found

                    $output .= '<div class="careers-div red-border-cont">';
                    $output .= '<div class="career-cell d-flex flex-sm-row flex-column justify-content-between align-items-center">';
                    $output .= '<p class="techn">' . get_the_title() . '</p>';
                    $output .= '<p class="techn-2" style="">'. $location_text .'</p>';
                    $output .= '<button class="btn careers-btn collapsed" type="button" data-toggle="collapse" data-target="#collapseExample' . get_the_ID() . '" aria-expanded="false" aria-controls="collapseExample' . get_the_ID() . '">Details</button>';
                    $output .= '</div>';
                    $output .= '<div class="collapse" id="collapseExample' . get_the_ID() . '" style="margin-top: 30px">';
                    $output .= '<div class="card card-body row c-content" style="border: none">';
                    $output .= '<div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">' . get_the_content() . '</div>';
                    // Similar blocks for other sections (Key Competencies / Skills, Required Qualifications & Experience)
                    $output .= '</div>'; // card-body
                    $output .= '</div>'; // collapse
                    $output .= '</div>'; // careers-div
                    
                }
                $output .= '</div>'; // container
                // Restore original Post Data
                wp_reset_postdata();
            } else {
                $output = '<p>No Career Opportunities Available.</p>';
            }
        
            return $output;
        }        
        

    }
}

if (class_exists('WdWordpressCareers')){
    $wdWordpressCareers = new WdWordpressCareers;
}
    
register_activation_hook( __FILE__, array($wdWordpressCareers, 'activate' ));

register_deactivation_hook( __FILE__, array($wdWordpressCareers, 'deactivate' )); 