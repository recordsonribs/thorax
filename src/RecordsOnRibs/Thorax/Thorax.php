<?php

namespace RecordsOnRibs\Thorax;

use RecordsOnRibs\Thorax\Collections\Artists as Artists;
use RecordsOnRibs\Thorax\Collections\Releases as Releases;

class Thorax
{
    public function __construct()
    {
    	
   	}

   	public function initialise()
   	{
   		$this->artists = new Artists( array( 'parent' => true ) );
   		$this->artists->has_many( 'releases' );

   		$this->releases = new Releases( array( 'parent' => true ) );
   	}
}