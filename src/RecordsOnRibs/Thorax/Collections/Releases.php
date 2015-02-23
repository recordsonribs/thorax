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
			    'sortable'      => true, 
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
				    'type' => 'text_small'
				]
			],
		];

		array_push($metaboxes, [
			'id'            => $prefix . 'urls',
			'title'         => 'Tracks',
			'object_types'  => [ $this->post_type ],
			'context'       => 'normal',
			'priority' => 'low',
			'fields' => [
				$tracks
			]
		]);

		parent::__construct( [ 'parent' => true, 'metaboxes' => $metaboxes, 'overwrite' => $overwrites ] );
	}
}