<?php

/**
 * Trigger this file on plugin uninstall
 * 
 * @package  WordPress_Careers 
 */

if(!defined('WP_UNINSTALL_PLUGIN')){
    die;
}

// Clear database stored data

$careers = get_posts( array( 'post_type' => 'careers', 'posts_per_page' => -1 ) );

foreach($careers as $career){
    wp_delete_posts($career->ID, true);
}

// Access database via SQL
// global $wpdb;
// $wpdb->query("DELETE FROM wp_posts WHERE post_type = 'careers'");
// $wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts) ");
// $wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts) ");

// Delete the taxonomy and its associated terms
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->prefix}terms WHERE term_id IN (SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'location')");
$wpdb->query("DELETE FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'location'");
$wpdb->query("DELETE FROM {$wpdb->prefix}term_relationships WHERE object_id NOT IN (SELECT id FROM {$wpdb->prefix}posts)");