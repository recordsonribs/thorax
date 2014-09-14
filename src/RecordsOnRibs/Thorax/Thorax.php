<?php

namespace RecordsOnRibs\Thorax;

use RecordsOnRibs\Thorax\Collections\Artists as Artists;

class Thorax
{
    public function __construct()
    {
    	
   	}

   	public function initialise()
   	{
   		$this->artists = new Artists(['parent' => true]);
   	}
}