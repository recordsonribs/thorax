<?php
/*
Plugin Name: thorax
Version: 0.1-alpha
Description: The new version of our record label management software.
Author: Alex Andrews <alex@recordsonribs.com>
Author URI: http://recordsonribs.com
Plugin URI: http://recordsonribs.com/thorax
Text Domain: thorax
Domain Path: /languages
*/

require_once dirname(__FILE__) . '/vendor/autoload.php';

$plugin = new RecordsOnRibs\Thorax\Thorax;

$plugin->intialize();

