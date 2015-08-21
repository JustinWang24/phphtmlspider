<?php
use justinwang24\phphtmlspider\HtmlspiderChemistWarehouseImpl;

class HtmlspiderChemistWarehouseImplTest extends PHPUnit_Framework_TestCase{
	public function testNachHasCheese()
	{
	    $spider = new HtmlspiderChemistWarehouseImpl;
	    $this->assertTrue($spider->getTrue());
	}
}