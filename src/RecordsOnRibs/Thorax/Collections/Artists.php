<?php

namespace RecordsOnRibs\Thorax\Collections;

use RecordsOnRibs\Thorax\Collection as Collection;

class Artists extends Collection { 
	function __construct() {
		$artist_overwrites = [
				'title_prompt' => 'Enter artist name here',
				'meta_box_titles' => [
					'Excerpt' => 'Short Description'
				]
			];

		$prefix = '_artists_';

		$this->post_type = 'artist';

		$metaboxes = [
			[
				'id'            => $prefix . 'urls',
				'title'         => 'Elsewhere',
				'object_types'  => [ $this->post_type ],
				'context'       => 'side',
				'priority' => 'low',
				'fields' => [
					[
						'name'       => __( 'Website' ),
						'id'         => $prefix . 'website',
						'type'       => 'text_url',
						'show_names' => false
					],
					[
						'name'       => __( 'Twitter' ),
						'id'         => $prefix . 'twitter',
						'type'       => 'text_url',
						'show_names' => false
					],
					[
						'name'       => __( 'Facebook' ),
						'id'         => $prefix . 'facebook',
						'type'       => 'text_url',
						'show_names' => false
					],
					[
						'name'       => __( 'SoundCloud' ),
						'id'         => $prefix . 'soundcloud',
						'type'       => 'text_url',
						'show_names' => false
					],
					[
						'name'       => __( 'MusicBrainz' ),
						'id'         => $prefix . 'musicbrainz',
						'type'       => 'text_url',
						'show_names' => false
					],
					[
						'name'       => __( 'Last.FM' ),
						'id'         => $prefix . 'lastfm',
						'type'       => 'text_url',
						'show_names' => false
					],
					[
						'name'       => __( 'BandCamp' ),
						'id'         => $prefix . 'bandcamp',
						'type'       => 'text_url',
						'show_names' => false
					]
				]
			],
			[
				'id'            => $prefix . 'press',
				'title'         => 'Press Information',
				'object_types'  => [ $this->post_type, ],
				'context'       => 'normal',
				'priority' => 'high',
				'fields' => [
					[
					    'name' => 'Press Photo',
					    'desc' => 'A high-quality image, of sufficient quality for print publication',
					    'id' => $prefix . 'press_image',
					    'type' => 'file',
					    'options' => array(
							'add_upload_file_text' => 'Upload JPEG',
						),
					    'allow' => [ 'attachment' ]
					],
					[
					    'name' => 'Press One Sheet',
					    'desc' => 'A PDF containing information about the artist',
					    'id' => $prefix . 'press_image',
					    'type' => 'file',
					   	'options' => [
							'add_upload_file_text' => 'Upload PDF',
						],
					    'allow' => [ 'attachment' ]
					]
				]
			]
		];

		parent::__construct( [ 'parent' => true, 'overwrite' =>  $artist_overwrites, 'metaboxes' => $metaboxes ] );

		$this->has_many( 'releases' );
	}
}