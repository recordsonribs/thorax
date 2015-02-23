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

		parent::__construct( [ 'parent' => true, 'overwrite' =>  $artist_overwrites ] );

		$this->has_many( 'releases' );
	}
}