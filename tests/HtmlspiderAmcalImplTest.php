<?php
use justinwang24\phphtmlspider\HtmlspiderAmcalImpl;

class HtmlspiderAmcalImplTest extends PHPUnit_Framework_TestCase{
	public function testParseProductLink()
	{
	    $spider = new HtmlspiderAmcalImpl;
	    $url = 'http://www.amcal.com.au/webapp/wcs/stores/servlet/SearchDisplay?searchTerm=9311770588263&categoryId=&x=8&y=15&storeId=10154&catalogId=10051&langId=-1&pageSize=20&beginIndex=0&sType=SimpleSearch&resultCatEntryType=2&showResultsPage=true&searchSource=Q&pageView=';
		$productLink = $spider->parseProductLinkFromSearchUrl($url,'9311770588263');

		$this->assertEquals('http://www.amcal.com.au/swisse-ultiboost-high-strength-vitamin-c---60-tablets-p-9311770588263', $productLink);
	}

	public function testParseProductName()
	{
	    $spider = new HtmlspiderAmcalImpl;
	    $url = 'http://www.amcal.com.au/swisse-ultiboost-high-strength-vitamin-c---60-tablets-p-9311770588263';
		$spider->setUrl($url);

		$this->assertEquals('Swisse Ultiboost High Strength Vitamin C - 60 Tablets', $spider->parseProductName());
	}

	public function testParseProductPrice()
	{
	    $spider = new HtmlspiderAmcalImpl;
	    $url = 'http://www.amcal.com.au/swisse-ultiboost-high-strength-vitamin-c---60-tablets-p-9311770588263';
		$spider->setUrl($url);

		$this->assertEquals('15.95', $spider->parseProductPrice());
	}

	public function testParseSmallImageUrl()
	{
	    $spider = new HtmlspiderAmcalImpl;
	    $url = 'http://www.amcal.com.au/swisse-ultiboost-high-strength-vitamin-c---60-tablets-p-9311770588263';
		$spider->setUrl($url);

		$this->assertEquals('http://s.squixa.net/www.amcal.com.au/635660319075400001/wcsstore/ExtendedSitesCatalogAssetStore/images/products/x9311770588263_LL_1.jpg.pagespeed.ic.J_ynz4kV0D.jpg', $spider->getSmallImageUrl());
	}
	public function testParseOriginalImageUrl()
	{
	    $spider = new HtmlspiderAmcalImpl;
	    $url = 'http://www.amcal.com.au/swisse-ultiboost-high-strength-vitamin-c---60-tablets-p-9311770588263';
		$spider->setUrl($url);

		$this->assertEquals('http://www.amcal.com.au/wcsstore/ExtendedSitesCatalogAssetStore/images/products/9311770588263_LL_1.jpg', $spider->getOriginalImageUrl());
	}
}