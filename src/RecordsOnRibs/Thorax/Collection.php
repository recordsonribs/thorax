<?php
namespace RecordsOnRibs\Thorax;

abstract class Collection {
	public $name   = '';

	public $post_type = '';

	public $single = '';

	public $plural = '';

	// Overwrite the defaults for the custom post type.
	private $overwrite = [];

	// Set to the parent custom post type - for ease of testing custom post types.
	public $parent = false;

	public static function add( array $add = [] ) {
		$q = [
			'post_type' => self::customPostTypeNameFromClassName(get_called_class()),
			'post_status' => 'publish'
		];

		wp_insert_post( array_merge( $add, $q ) );
	}

	public static function get( ) {
		$q = [
			'post_type' => self::customPostTypeNameFromClassName(get_called_class())
		];

		return get_posts($q);
	}

	public static function customPostTypeNameFromClassName($t) {
		$bits = explode('\\', $t);
		return end($bits);
	}

	function __construct( $name = false, $single = false, $plural = false, $overwrite = false, $parent = false ) {
		// Fake named parameters.
		if ( is_array( $name ) )  {
			extract( $name, EXTR_IF_EXISTS );
		}

		// If we are passing fake named parameters we also need to infer the name.
		if ( ! $name || is_array( $name ) ) {
			$name = self::customPostTypeNameFromClassName(get_class($this));
		}

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

		$this->post_type = strtolower( $this->single );

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
			$this->overwrite['hierarchical'] = true;
		}

		add_action( 'init', [ $this, 'init' ] );

		// Overwrite the 'Enter title here' for the post type
		if ( isset( $this->overwrite['title_prompt'] ) ) {
			add_filter( 'enter_title_here', [ $this, 'enter_title_here' ] );
		}

		// Overwrite the little instruction underneith the Featured Image metabox
		if ( isset( $this->overwrite['featured_image_instruction'] ) ) {
			add_filter( 'admin_post_thumbnail_html', [ $this, 'admin_post_thumbnail_html' ] );
		}

		// Overwrite metabox title text!
		if ( isset( $this->overwrite['meta_box_titles'] ) ) {
			add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 10, 2 );
		}
	}

	public function init() {
		$supports = [ 'title', 'editor', 'author', 'thumbnail', 'excerpt' ];

		if ( isset( $this->overwrite['hierarchical'] ) && $this->overwrite['hierarchical'] == true ) {
			array_push( $supports, 'page-attributes' );
		}

		$args = [
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
			'supports'             => $supports,
			'rewrite'              => array(
										'slug' => strtolower( $this->plural ),
										'with_front' => false,
										'feeds' => true,
										'pages' => true,
			),
			'can_export'           => true,
			'show_in_nav_menus'    => true,
		];

		$args = array_merge( $args, $this->overwrite );

		register_post_type( $this->post_type, $args );

		// Use CMB to register custom metaboxes.
		add_action( 'cmb2_meta_boxes', [ $this, 'metaboxes'] );

		add_filter( 'post_updated_messages', [ $this, 'post_updated_messages' ] );
	}

	public function metaboxes( array $metaboxes ) {
		return $metaboxes;
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

		$messages[$this->post_type] = $this->messages;

		return $messages;
	}

	public function add_rewrite_rule () {
		$query_param = rtrim( $this->has_many, 's' );
		add_rewrite_rule( strtolower( $this->plural ) . '/([^/]+)/([^/]+)', 'index.php?' . $query_param . '=$matches[2]', 'top' );
	}

	public function has_many( $of ) {
		$this->has_many = $of;

		add_action( 'init', [ $this, 'add_rewrite_rule' ] );
	}

	public function create_labels() {
		return [
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
		];
	}

	private function are_we_on_the_post_type(){
		global $post;

		return ( $post->post_type == $this->post_type );
	}

	public function enter_title_here( $content ) {
		global $post;

		if ( $post->post_type != $this->post_type ) {
			return $content;
		}

		return $this->overwrite['title_prompt'];
	}

	public function admin_post_thumbnail_html( $content ) {
		global $post;

		if ( $post->post_type != $this->post_type ) {
			return $content;
		}

		return $content .= '<p>' . $this->overwrite['featured_image_instruction'] . '</p>';
	}

	public function add_meta_boxes( $post_type, $post ) {
		if (! $this->are_we_on_the_post_type()){
			return;
		}

		global $wp_meta_boxes;
		global $post;

		// Filter boxes.
		foreach ( [ 'side', 'normal' ] as $column ) {
			if (! $wp_meta_boxes[$this->post_type][$column]) {
				return;
			}

			foreach( $wp_meta_boxes[$this->post_type][$column] as &$metabox_list ) {
				foreach($metabox_list as &$metabox) {
					if ( isset( $this->overwrite['meta_box_titles'][$metabox['title']] ) ) {
						$metabox['title'] = $this->overwrite['meta_box_titles'][$metabox['title']];
					}
				}
			}
		}

		return;
	}
}