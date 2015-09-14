<?php
use justinwang24\phphtmlspider\HtmlspiderMatsukiyoImpl;

class HtmlspiderMatsukiyoImplTest extends PHPUnit_Framework_TestCase{
	/*
		创建基境 Fixture
	*/
	protected $spider;
	protected $url;
	protected $productUrl;

	/*
		测试环境的初始化
	*/
	protected function setUp(){
		$this->spider = new HtmlspiderMatsukiyoImpl;
		$this->url = 'http://www.matsukiyo.co.jp/store/online/search/detail/?text=4986803803696';
		$this->productUrl = 
					'http://www.matsukiyo.co.jp/store/%E5%8C%96%E7%B2%A7%E5%93%81/%E3%82%A4%E3%83%B3%E3%83%8A%E3%83%BC%E3%82%A6%E3%82%A7%E3%82%A2/%E6%A9%9F%E8%83%BD%E6%80%A7%E3%82%BD%E3%83%83%E3%82%AF%E3%82%B9/%E6%A9%9F%E8%83%BD%E6%80%A7%E3%82%BD%E3%83%83%E3%82%AF%E3%82%B9%E3%80%80%E5%B1%8B%E5%86%85%E4%BD%BF%E7%94%A8/%E3%83%89%E3%82%AF%E3%82%BF%E3%83%BC%E3%83%BB%E3%82%B7%E3%83%A7%E3%83%BC%E3%83%AB-%E3%81%8A%E3%81%86%E3%81%A1%E3%81%A7%E3%83%A1%E3%83%87%E3%82%A3%E3%82%AD%E3%83%A5%E3%83%83%E3%83%88-%E3%83%AD%E3%83%B3%E3%82%B0-%EF%BC%AD/p/4986803803696';

	}

	/*
		清理工作
	*/
	protected function tearDown(){
		$this->spider = null;
	}

	// public function testParseProductLink()
	// {
	// 	$productLink = $this->spider->parseProductLinkFromSearchUrl($this->url);

	// 	$this->assertEquals(
	// 		'/store/化粧品/インナーウェア/機能性ソックス/機能性ソックス%E3%80%80屋内使用/ドクター・ショール-おうちでメディキュット-ロング-Ｍ/p/4986803803696',
	// 		$productLink
	// 	);
	// }

	public function testParseProductPrice()
	{
		$spider = new HtmlspiderMatsukiyoImpl;
		$url = 'http://www.matsukiyo.co.jp/store/online/search/detail/?text=4986803803696';
		$purl = $spider->parseProductLinkFromSearchUrl($url); 
		//echo $purl.'.................. \r\n'; 

		$spider->setUrl( $purl );
		$price = $spider->parseProductPrice();
		$this->assertEquals(
			'2,457',
			$price
		);
	}
}