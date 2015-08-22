<?php
use justinwang24\phphtmlspider\HtmlspiderAminozImpl;

class HtmlspiderAminozImplTest extends PHPUnit_Framework_TestCase{
	public function testNachHasCheese()
	{
	    $spider = new HtmlspiderAminozImpl;
	    $spider->setUrl('http://www.aminoz.com.au/products/health-wellbeing/bone-joint/herbs-of-gold-pain-ease-60-tablets.html');
	    $spider->parseProductManufacturer();
	}
}