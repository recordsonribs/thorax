<?php
use RecordsOnRibs\Thorax\Collection as Collection;

class TestCollection extends WP_UnitTestCase {

	public function setUp() {
        parent::setUp();
    }

	function testCollectionNameIsSet() {
		$collection = new Widget('some-thing');
		$this->assertEquals($collection->name, 'some-thing');
	}

	function testPluralisationWorks() {
		$collection = new Widget('artist');
		$this->assertEquals($collection->plural, 'Artists');
	}

	function testSingularWorks() {
		$collection = new Widget('artist');
		$this->assertEquals($collection->single, 'Artist');
	}

	function testInfersCustomPostTypeNameFromClassName() {
		$artists = new Artists();
		$this->assertEquals($artists->single, 'Artist');
		$this->assertEquals($artists->plural, 'Artists');
	}

	function testRegistersACustomPostType() {
		$this->markTestIncomplete();
	}

}

class Widget extends Collection { }

class Artists extends Collection { }