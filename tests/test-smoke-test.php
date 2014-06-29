<?php

class TestSmokeTest extends WP_UnitTestCase {

	public function setUp() {
        parent::setUp();

		$this->thorax = new RecordsOnRibs\Thorax\Thorax;
    }

	function testTests() {
		$this->assertTrue( true );
	}

	function testcheckPluginIsIntialized() {
		$this->assertTrue( isset($this->thorax) );
	}

	function testThoraxHasProperNamesapce() {
		$this->assertEquals( get_class($this->thorax), 'RecordsOnRibs\Thorax\Thorax');
	}


}

