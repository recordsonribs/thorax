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
			$this->artists = new Artists();
			$this->releases = new Releases();
		}
}