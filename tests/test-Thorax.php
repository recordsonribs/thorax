<?php

class TestIntialise extends WP_UnitTestCase {

	public function setUp() {
        parent::setUp();

		$this->thorax = new RecordsOnRibs\Thorax\Thorax;
    }

	function testcheckPluginIsIntialized() {
		$this->assertTrue( isset($this->thorax) );
	}

	function testThoraxHasProperNamespace() {
		$this->assertEquals( get_class($this->thorax), 'RecordsOnRibs\Thorax\Thorax');
	}

	function testThoraxPluginIsLoaded() {
		$this->markTestSkipped();
		$this->assertTrue( is_plugin_active('thorax') );
	}

}
