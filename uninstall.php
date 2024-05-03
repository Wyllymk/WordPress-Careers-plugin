<?php

/**
 * Trigger this file on plugin uninstall
 * 
 * @package  WordPress_Careers 
 */

if(!defined('WP_UNINSTALL_PLUGIN')){
    exit;
}

if (!get_option('plugin_do_uninstall', false)) exit;

// Clear database stored data
// delete custom post type posts

$careers = get_posts( array( 'post_type' => 'careers', 'posts_per_page' => -1 ) );

foreach($careers as $career){
    wp_delete_post($career->ID, true);
}

// Access database via SQL
// global $wpdb;
// $wpdb->query("DELETE FROM wp_posts WHERE post_type = 'careers'");
// $wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts) ");
// $wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts) ");