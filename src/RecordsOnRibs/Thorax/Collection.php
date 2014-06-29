<?php
namespace RecordsOnRibs\Thorax;

abstract class Collection {
	public $name   = '';

	public $single = '';

	public $plural = '';

	// Overwrite the defaults for the custom post type.
	private $overwrite = array();

	// Set to the parent custom post type - for ease of testing custom post types.
	public $parent = false;

	public static function add( $add = [] ) {
		$q = array(
			'post_type' => self::customPostTypeNameFromClassName(get_called_class()),
			'post_status' => 'publish'
		);

		wp_insert_post( array_merge( $add, $q ) );
	}

	public static function get() {
		$q = array(
			'post_type' => self::customPostTypeNameFromClassName(get_called_class())
		);

		return get_posts($q);
	}

	public static function customPostTypeNameFromClassName($t) {
		$bits = explode('\\', $t);
		return end($bits);
	}

	function __construct( $name = false, $single = false, $plural = false, $overwrite = false, $parent = false ) {
		if ( ! $name ) {
			$name = self::customPostTypeNameFromClassName(get_class($this));
		}

		// Fake named parameters - PHP Y U NO RUBY?
		if ( is_array( $name ) ) 
			extract( $name, EXTR_IF_EXISTS );

		if ( substr( $name, -1 ) == 's' ) {
			$this->name = rtrim( $name, 's' );
		} else {
			$this->name = $name;
		}

		if ( $single ) {
			$this->single = ucfirst( strtolower( $single ) );
		} else {
			$this->single = ucfirst( strtolower( $this->name ) );
		}

		if ( $plural ) {
			$this->plural = $plural;
		} else {
			$this->plural = $this->single . 's';
		}

		if ( $overwrite ) {
			$this->overwrite = $overwrite;
		}

		// Make sure that we over-write the 'hiearchical' variable for custom post types with parents.
		if ( $parent ) {
			$overwrite['hierarchical'] = true;
		}

		add_action( 'init', array( $this, 'init' ) );

		// Overwrite the 'Enter title here' for the post type
		if ( isset( $this->overwrite['title_prompt'] ) ) {
			add_filter( 'enter_title_here', array( $this, 'enter_title_here' ) );
		}

		// Overwrite the little instruction underneith the Featured Image metabox
		if ( isset( $this->overwrite['featured_image_instruction'] ) ) {
			add_filter( 'admin_post_thumbnail_html', array( $this, 'admin_post_thumbnail_html' ) );
		}

		// Overwrite metabox title text!
		if ( isset( $this->overwrite['meta_box_titles'] ) ) {
			add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
		}
	}

	public function init() {
		$args = array(
			'labels'               => $this->create_labels(),
			'description'          => '',
			'public'               => true,
			'publicly_queryable'   => true,
			'show_ui'              => true,
			'capability_type'      => 'post',
			'has_archive'          => true,
			'hierarchical'         => false,
			'show_in_menu'         => true,
			'menu_position'        => 50,
			'supports'             => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' ),
			'register_meta_box_cb' => array( $this, 'metaboxes' ),
			'rewrite'              => array(
										'slug' => strtolower( $this->single ),
										'with_front' => false,
										'feeds' => true,
										'pages' => true,
			),
			'can_export'           => true,
			'show_in_nav_menus'    => true,
		);

		$args = array_merge( $args, $this->overwrite );

		register_post_type( $this->name, $args );

		add_filter( 'post_updated_messages', array( $this, 'post_updated_messages' ) );
	}

	public function post_updated_messages( $messages ) {
		global $post;

		$this->messages = array(
			0  => '', // Unused in WordPress messages
			1  => sprintf( "$this->single updated. <a href='%s'>View $this->single</a>", esc_url( get_permalink( $post->ID ) ) ),
			2  => __( 'Custom field updated.' ),
			3  => __( 'Custom field updated.' ),
			4  => __( "$this->single updated." ),
			5  => isset( $_GET['revision'] ) ? sprintf( "$this->single restored to revision from %s", wp_post_revision_title( (int) $_GET['revision'] ), false ) : false,
			6  => sprintf( "$this->single published. <a href='%s'>View $this->single</a>", esc_url( get_permalink( $post->ID ) ) ),
			7  => __( "$this->single saved." ),
			8  => sprintf( "$this->single submitted. <a target='_blank' href='%s'>Preview $this->single</a>", esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
			9  => sprintf( 'Page scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview page</a>', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink( $post->ID ) ) ),
			10 => sprintf( "$this->single draft updated. <a target=\"_blank\" href=\"%s\">Preview $this->single</a>", esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) ),
		);

		$messages[$this->name] = $this->messages;

		return $messages;
	}

	public function create_labels() {
		return array(
			'name'               => $this->plural,
			'singular_name'      => $this->single,
			'add_new'            => __( "Add New $this->single" ),
			'all_items'          => "All $this->plural",
			'add_new_item'       => "Add New $this->single",
			'edit_item'          => "Edit $this->single",
			'new_item'           => "New $this->single",
			'view_item'          => "View $this->single",
			'search_items'       => "View $this->plural",
			'not_found'          => "No $this->plural found",
			'not_found_in_trash' => "No $this->plural found in trash",
			'menu_name' => $this->plural,
		);
	}

	public function enter_title_here( $content ) {
		global $post;

		if ( $post->post_type != $this->name ) {
			return $content;
		}

		return $this->overwrite['enter_title_here'];
	}

	public function admin_post_thumbnail_html( $content ) {
		global $post;

		if ( $post->post_type != $this->name ) {
			return $content;
		}

		return $content .= '<p>' . $this->overwrite['featured_image_instruction'] . '</p>';
	}

	public function add_meta_boxes( $post_type, $post ) {
		global $wp_meta_boxes;

		// Lets smoosh through these and make changes as we see fit!
		foreach ( array( 'side', 'normal' ) as $column ) {
			foreach ( array( 'core', 'low' ) as $placing ) {
				foreach ( $wp_meta_boxes[$this->name][$column][$placing] as $meta_box_name => $meta_box ) {
					foreach ( $this->overwrite['meta_box_titles'] as $overwrite => $with ) {
						if ( $meta_box['title'] == $overwrite ) {
							$wp_meta_boxes[$this->name][$column][$placing][$meta_box_name]['title'] = $with;
						}
					}
				}
			}
		}
	}
}