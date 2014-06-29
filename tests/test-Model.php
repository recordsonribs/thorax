<?php
use RecordsOnRibs\Thorax\Model as Model;

class TestModel extends WP_UnitTestCase {

	public function setUp() {
        parent::setUp();
    }

	function testModelHasProperNamespace() {
		$model = new RecordsOnRibs\Thorax\Model('test-model');

		$this->assertEquals(get_class($model), 'RecordsOnRibs\Thorax\Model');
	}

	function testModelNameIsSet() {
		$model = new Model('some-thing');
		$this->assertEquals($model->name, 'some-thing');
	}

	function testPluralisationWorks() {
		$model = new Model('artist');
		$this->assertEquals($model->plural, 'Artists');
	}

	function testSingularWorks() {
		$model = new Model('artist');
		$this->assertEquals($model->single, 'Artist');
	}

	function testNoDefaultInfersName() {
		$model = new Model();
		$this->assertEquals($model->single, 'Model');
	}

	function testInfersNameFromClassName() {
		$artists = new Artists();
		$this->assertEquals($artists->single, 'Artist');
		$this->assertEquals($artists->plural, 'Artists');
	}

}

class Artists extends Model { }