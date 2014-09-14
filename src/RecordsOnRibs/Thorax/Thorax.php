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
   		$this->artists = new Artists( [ 'parent' => true] );
   		$this->releases = new Releases( [ 'parent' => true ] );
   	}
}