<?php
/*
===========================
  [[[ Custom Post Types ]]]
===========================
*/

// Clear Rewrite Rules for CPT's
function ex_theme_terlet() {
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'ex_theme_terlet');

// CPT: Service
function cpt_service() {

	$labels = array(
		'name'                  => _x( 'Services', 'Post Type General Name', 'exonym' ),
		'singular_name'         => _x( 'Service', 'Post Type Singular Name', 'exonym' ),
		'menu_name'             => __( 'Services', 'exonym' ),
		'name_admin_bar'        => __( 'Service', 'exonym' ),
		'archives'              => __( 'Service Archives', 'exonym' ),
		'attributes'            => __( 'Service Attributes', 'exonym' ),
		'parent_item_colon'     => __( 'Parent Service:', 'exonym' ),
		'all_items'             => __( 'All Services', 'exonym' ),
		'add_new_item'          => __( 'Add New Service', 'exonym' ),
		'add_new'               => __( 'Add New', 'exonym' ),
		'new_item'              => __( 'New Service', 'exonym' ),
		'edit_item'             => __( 'Edit Service', 'exonym' ),
		'update_item'           => __( 'Update Service', 'exonym' ),
		'view_item'             => __( 'View Services', 'exonym' ),
		'view_items'            => __( 'View Services', 'exonym' ),
		'search_items'          => __( 'Search Service', 'exonym' ),
		'not_found'             => __( 'Not found', 'exonym' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'exonym' ),
		'featured_image'        => __( 'Featured Image', 'exonym' ),
		'set_featured_image'    => __( 'Set featured image', 'exonym' ),
		'remove_featured_image' => __( 'Remove featured image', 'exonym' ),
		'use_featured_image'    => __( 'Use as featured image', 'exonym' ),
		'insert_into_item'      => __( 'Insert into Service', 'exonym' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Service', 'exonym' ),
		'items_list'            => __( 'Services list', 'exonym' ),
		'items_list_navigation' => __( 'Services list navigation', 'exonym' ),
		'filter_items_list'     => __( 'Filter Services list', 'exonym' ),
	);
	$rewrite = array(
		'slug'                  => 'services',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Service', 'exonym' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		'taxonomies'            => array( 'service_groups' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-plugins-checked',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'service', $args );
}
add_action( 'init', 'cpt_service', 0 );

// CTAX: Service Groups
function ctax_serviceCats() {

	$labels = array(
		'name'                       => _x( 'Service Groups', 'Taxonomy General Name', 'exonym' ),
		'singular_name'              => _x( 'Service Group', 'Taxonomy Singular Name', 'exonym' ),
		'menu_name'                  => __( 'Service Groups', 'exonym' ),
		'all_items'                  => __( 'All Service Groups', 'exonym' ),
		'parent_item'                => __( 'Parent Service Group', 'exonym' ),
		'parent_item_colon'          => __( 'Parent Service Group:', 'exonym' ),
		'new_item_name'              => __( 'New Service Group', 'exonym' ),
		'add_new_item'               => __( 'Add Service Group', 'exonym' ),
		'edit_item'                  => __( 'Edit Service Group', 'exonym' ),
		'update_item'                => __( 'Update Service Group', 'exonym' ),
		'view_item'                  => __( 'View Service Group', 'exonym' ),
		'separate_items_with_commas' => __( 'Separate groups with commas', 'exonym' ),
		'add_or_remove_items'        => __( 'Add or remove groups', 'exonym' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'exonym' ),
		'popular_items'              => __( 'Popular Service Groups', 'exonym' ),
		'search_items'               => __( 'Search Service Groups', 'exonym' ),
		'not_found'                  => __( 'Not Found', 'exonym' ),
		'no_terms'                   => __( 'No groups', 'exonym' ),
		'items_list'                 => __( 'Service Groups list', 'exonym' ),
		'items_list_navigation'      => __( 'Service Groups list navigation', 'exonym' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => false,
	);
	register_taxonomy( 'service_groups', array( 'service', 'resource' ), $args );
}
add_action( 'init', 'ctax_serviceCats', 0 );

// CPT: Resource
function cpt_resource() {
	$labels = array(
		'name'                  => _x( 'Resources', 'Post Type General Name', 'exonym' ),
		'singular_name'         => _x( 'Resource', 'Post Type Singular Name', 'exonym' ),
		'menu_name'             => __( 'Resources', 'exonym' ),
		'name_admin_bar'        => __( 'Resource', 'exonym' ),
		'archives'              => __( 'Resource Archives', 'exonym' ),
		'attributes'            => __( 'Resource Attributes', 'exonym' ),
		'parent_item_colon'     => __( 'Parent Resource:', 'exonym' ),
		'all_items'             => __( 'All Resources', 'exonym' ),
		'add_new_item'          => __( 'Add New Resource', 'exonym' ),
		'add_new'               => __( 'Add New', 'exonym' ),
		'new_item'              => __( 'New Resource', 'exonym' ),
		'edit_item'             => __( 'Edit Resource', 'exonym' ),
		'update_item'           => __( 'Update Resource', 'exonym' ),
		'view_item'             => __( 'View Resource', 'exonym' ),
		'view_items'            => __( 'View Resources', 'exonym' ),
		'search_items'          => __( 'Search Resource', 'exonym' ),
		'not_found'             => __( 'Not found', 'exonym' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'exonym' ),
		'featured_image'        => __( 'Featured Image', 'exonym' ),
		'set_featured_image'    => __( 'Set featured image', 'exonym' ),
		'remove_featured_image' => __( 'Remove featured image', 'exonym' ),
		'use_featured_image'    => __( 'Use as featured image', 'exonym' ),
		'insert_into_item'      => __( 'Insert into Resource', 'exonym' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Resource', 'exonym' ),
		'items_list'            => __( 'Resources list', 'exonym' ),
		'items_list_navigation' => __( 'Resources list navigation', 'exonym' ),
		'filter_items_list'     => __( 'Filter Resources list', 'exonym' ),
	);
	$args = array(
		'label'                 => __( 'Resource', 'exonym' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		'taxonomies'            => array( 'service_groups' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-portfolio',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'resources',
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);
	register_post_type( 'resource', $args );
}
add_action( 'init', 'cpt_resource', 0 );

// CPT: Staff
function cpt_staff() {
	$labels = array(
		'name'                  => _x( 'Staff', 'Post Type General Name', 'exonym' ),
		'singular_name'         => _x( 'Staff Member', 'Post Type Singular Name', 'exonym' ),
		'menu_name'             => __( 'Staff', 'exonym' ),
		'name_admin_bar'        => __( 'Staff', 'exonym' ),
		'archives'              => __( 'Staff Archives', 'exonym' ),
		'attributes'            => __( 'Staff Attributes', 'exonym' ),
		'parent_item_colon'     => __( 'Parent Staff Member:', 'exonym' ),
		'all_items'             => __( 'All Staff Members', 'exonym' ),
		'add_new_item'          => __( 'Add New Staff Member', 'exonym' ),
		'add_new'               => __( 'Add New', 'exonym' ),
		'new_item'              => __( 'New Staff Member', 'exonym' ),
		'edit_item'             => __( 'Edit Staff Member', 'exonym' ),
		'update_item'           => __( 'Update Staff Member', 'exonym' ),
		'view_item'             => __( 'View Staff Member', 'exonym' ),
		'view_items'            => __( 'View Staff Members', 'exonym' ),
		'search_items'          => __( 'Search Staff Member', 'exonym' ),
		'not_found'             => __( 'Not found', 'exonym' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'exonym' ),
		'featured_image'        => __( 'Photo', 'exonym' ),
		'set_featured_image'    => __( 'Set staff photo', 'exonym' ),
		'remove_featured_image' => __( 'Remove staff photo', 'exonym' ),
		'use_featured_image'    => __( 'Use as staff photo', 'exonym' ),
		'insert_into_item'      => __( 'Insert into staff member', 'exonym' ),
		'uploaded_to_this_item' => __( 'Uploaded to this staff member', 'exonym' ),
		'items_list'            => __( 'Staff Members list', 'exonym' ),
		'items_list_navigation' => __( 'Staff Members list navigation', 'exonym' ),
		'filter_items_list'     => __( 'Filter Staff Members list', 'exonym' ),
	);
	$args = array(
		'label'                 => __( 'Staff Member', 'exonym' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-id',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => false,
		'capability_type'       => 'page',
	);
	register_post_type( 'staff', $args );
}
add_action( 'init', 'cpt_staff', 0 );
