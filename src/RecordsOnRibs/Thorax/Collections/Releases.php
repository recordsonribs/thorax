<?php

namespace RecordsOnRibs\Thorax\Collections;

use RecordsOnRibs\Thorax\Collection as Collection;

class Releases extends Collection {
	function __construct() {
		$this->post_type = 'release';

		$overwrites = [
			'title_prompt' => 'Enter release title here',
			'meta_box_titles' => [
				'Excerpt' => 'Short Description'
			]
		];

		$metaboxes = [];

		$prefix = '_' . $this->post_type . 'release_';

		$tracks = [
			'id'          => $prefix . 'track_listing',
			'type'        => 'group',
			'options'     => [
			    'group_title'   => 'Track {#}',
			    'add_button'    => 'Add Another Track',
			    'remove_button' => 'Remove Track',
			    'sortable'      => true
			],
			'fields'      => [
			    [
			        'name' => 'Track Title',
			        'id'   => 'title',
			        'type' => 'text'
			    ],
				[
				    'name' => 'Time',
				    'id' => 'time',
				    'type' => 'text_time'
				]
			],
		];

		$reviews = [
			'id'          => $prefix . 'reviews',
			'type'        => 'group',
			'options'     => [
			    'group_title'   => 'Review {#}',
			    'add_button'    => 'Add A Review',
			    'remove_button' => 'Remove Review',
			    'sortable'      => true
			],
			'fields'      => [
			    [
			        'name' => 'Review Text',
			        'id'   => 'text',
			        'type' => 'textarea'
			    ],
				[
				    'name' => 'Reviewer',
				    'id' => 'reviewer',
				    'type' => 'text'
				],
				[
				    'name' => 'Publication',
				    'id' => 'publication',
				    'type' => 'text'
				],
				[
						'name' => 'URL',
						'id' => 'url',
						'type' => 'text_url'
				]
			],
		];

		array_push($metaboxes, [
			'id'            => $prefix . 'tracks',
			'title'         => 'Tracks',
			'object_types'  => [ $this->post_type ],
			'context'       => 'normal',
			'priority' => 'low',
			'fields' => [
				$tracks
			]
		]);

		array_push($metaboxes, [
			'id'            => $prefix . 'reviews',
			'title'         => 'Reviews',
			'object_types'  => [ $this->post_type ],
			'context'       => 'normal',
			'priority' => 'low',
			'fields' => [
				$reviews
			]
		]);

		array_push($metaboxes, [
			'id'            => $prefix . 'artist',
			'title'         => 'Artist',
			'object_types'  => [ $this->post_type ],
			'context'       => 'side',
			'priority' => 'high',
			'fields' => [
				[
	        		'desc'    => __( 'Artist releasing this' ),
	        		'id'      => $prefix . 'artist',
	        		'type'    => 'select',
	        		'options' => $this->get_releasers()
				]
			]
		]);

		$this->hook_save_releaser();

		parent::__construct( [ 'parent' => true, 'metaboxes' => $metaboxes, 'overwrite' => $overwrites ] );
	}

	function hook_save_releaser(){
		add_action( 'save_post', [ $this, 'save_releaser' ] );
	}

	function unhook_save_releaser(){
		remove_action( 'save_post', [ $this, 'save_releaser' ] );
	}

	function get_releaser_meta( $post_id ){
		return get_post_meta( $post_id, '_releaserelease_artist', true );
	}

	function save_releaser( $post_id ){
		if ( $parent_id = wp_is_post_revision( $post_id ) ) {
			$post_id = $parent_id;
		}

		$artist = $this->get_releaser_meta( $post_id );

		$this->unhook_save_releaser();
		wp_update_post( [ 'ID' => $post_id, 'post_parent' => $artist ] );
		$this->hook_save_releaser();
	}

	function get_releasers(){
		$posts = get_posts( [ 'post_type' => 'artist', 'numberposts' => -1 ] );

		$post_options = [];

		if ( $posts ) {
		    foreach ( $posts as $post ) {
		      $post_options[ $post->ID ] = $post->post_title;
		    }
		}

		return $post_options;
	}

}
