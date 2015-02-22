<?php

namespace RecordsOnRibs\Thorax;

use RecordsOnRibs\Thorax\Collections\Artists as Artists;
use RecordsOnRibs\Thorax\Collections\Releases as Releases;

class Thorax
{
		public function __construct()
		{
			$this->initialise();	
		}

		public function initialise()
		{
			$artist_overwrites = [
				'title_prompt' => 'Enter artist name here',
				'meta_box_titles' => [
					'Excerpt' => 'Short Description'
				]
			];

			$this->artists = new Artists( [ 'parent' => true, 'overwrite' =>  $artist_overwrites ] );
			$this->artists->has_many( 'releases' );

			$this->releases = new Releases( [ 'parent' => true ] );
		}
}