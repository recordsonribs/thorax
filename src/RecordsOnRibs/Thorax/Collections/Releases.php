<?php

namespace RecordsOnRibs\Thorax\Collections;

use RecordsOnRibs\Thorax\Collection as Collection;

class Releases extends Collection { 
	function __construct() {
		parent::__construct( [ 'parent' => true ] );
	}
}