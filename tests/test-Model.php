<?php
use RecordsOnRibs\Thorax\Model as Model;

class TestModel extends WP_UnitTestCase {

	public function setUp() {
        parent::setUp();
    }

	function testModelNameIsSet() {
		$model = new Widget('some-thing');
		$this->assertEquals($model->name, 'some-thing');
	}

	function testPluralisationWorks() {
		$model = new Widget('artist');
		$this->assertEquals($model->plural, 'Artists');
	}

	function testSingularWorks() {
		$model = new Widget('artist');
		$this->assertEquals($model->single, 'Artist');
	}

	function testInfersNameFromClassName() {
		$artists = new Artists();
		$this->assertEquals($artists->single, 'Artist');
		$this->assertEquals($artists->plural, 'Artists');
	}

	function testRegistersACustomPostType() {
		$this->markTestIncomplete();
	}

}

class Widget extends Model { }

class Artists extends Model { }