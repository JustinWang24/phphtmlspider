<?php namespace justinwang24\phphtmlspider;

/**
 * 针对 aminoz.com.au 网站产品页面的抓取功能实现类
 * @author justinwang
 *
 */

use justinwang24\phphtmlspider\Htmlspider;
use Sunra\PhpSimple\HtmlDomParser;

class HtmlspiderAmcalImpl implements Htmlspider{
	
	protected $url = NULL;
	protected $dom = NULL;
	protected $controler_obj = NULL;
	
	/**
	 * 构造函数,需要传入当前调用的控制器的实例来实现某些功能
	 * @param string $controler_obj
	 */
	public function __construct(){
		//$this->dom = new Dom;
	}

	/**
	 * 取得搜索 url 返回结果页面中的产品链接页面
	 * @param string $tag
	 */
	public function parseProductLinkFromSearchUrl($url,$barcode=null){
		if (strlen($url)>0 && $barcode) {
			$temp_result = file_get_contents($url);
			$temp_arr = explode($barcode,$temp_result);
			if (isset($temp_arr[2])) {
				//由于返回的是一个中间页面,然后通过 js 跳转,所以只能如此解析
				return substr( $temp_arr[2],3 ) . $barcode ;
			}
		}
		return null;
	}

	/*
		设置需要爬行的 url 地址
	*/
	public function setUrl($url){
		$this->url = $url;
		$this->dom = HtmlDomParser::file_get_html( $url );
	}

	/*
		设置需要使用的 mvc 框架控制器类
	*/
	public function setCiController($controller){
		$this->controler_obj = $controller;
	}
	
	/**
	 * 真正抓取和保存图片的方法
	 */
	public function parseProduct(){
		if (is_null($this->url) || strlen($this->url)==0 || !$this->url) {
			return FALSE;
		}
	
		//$this->dom->loadFromUrl($this->url);

		//检查是否可以找出制造商
		$productName = strtoupper( $this->parseProductName() );
	
		$manufacturerName = $this->parseProductManufacturer();

		$productName = trim( str_replace( strtoupper($manufacturerName), '', $productName) );

		//检查制造商完毕
	
		//取得图片的 url
		$real_name = random_string('alpha', 10).time().'.png';
		$origin_file_saved = FALSE;
		$small_file_saved = FALSE;

		$small_image_url = $this->getSmallImageUrl();
		$origin_image_url = $this->getOriginalImageUrl();

		if ( strlen( $origin_image_url )>0 ) {
			$origin_file_saved = $this->controler_obj->_save_remote_image( 'original_'.$real_name, $origin_image_url);
		}
	
		if ( strlen( $small_image_url )>0 ) {
			$small_file_saved = $this->controler_obj->_save_remote_image( $real_name , $small_image_url );
		}
		//保存图片完成
	
		$product = array(
				'product_page_url'=>$this->url,
				'source'=>'Amcal',
				'RRP'=>$this->parseProductPrice(),
				'plainPrice'=>$this->parseProductPrice(),
				'hasImage'=> ($origin_file_saved || $small_file_saved) ? 1 : 0,
				'image_file_name'=>$small_file_saved ? $real_name : '',
				'original_image_file_name'=>$origin_file_saved ? 'original_'.$real_name : '',
				'productName'=>urldecode( ucwords( strtolower($productName) ) ),
				'manufacturer'=>$this->parseProductManufacturer(),
				'product_id'=>random_string('numeric', 4).time(),   //产生一个产品代码
				'published'=>0,  //默认不展示
				'description'=>$this->parseProductDescription()
		);

		//有的页面可能没有包含 barcode 的信息,所以要检查一下
		$barcode = $this->parseProductBarcode();
		if (strlen($barcode)>0) {
			$product['barcode'] = $barcode;
		}

		return $product;
	}

	/*
		尝试取得页面中产品的 生产商 信息
	*/
	public function parseProductManufacturer(){
		$manufacturer = '';
		if ($this->dom) {
			# code...
		}
		return $manufacturer;
	}

	/*
		尝试取得页面中产品的 barcode 信息
	*/
	public function parseProductBarcode(){
		$barcode = '';
		if ($this->dom) {
			# code...
		}
		return $barcode;
	}

	/**
	 * 取得 html 中的产品描述
	 * @param string $tag
	 */
	public function parseProductDescription(){
		$productDesription = '';
		if ($this->dom) {
			$productDesription = $this->dom->find('#tab1_content p',0)->innertext;
			return trim( str_replace('<br />', '', $productDesription) );
		};
		return $productDesription;
	}
	
	/**
	 * 取得 html 中的产品名称信息
	 * @param string $tag
	 */
	public function parseProductName()
	{
		$productName = '';
		if ($this->dom) {
			//这个产品名称从url中直接可以解析出来
			if ($this->url) {
				//product-name
				$posible_product_name = $this->dom->find('.main_header',0)->innertext;
				//取最后一个数组
				return ucwords( trim($posible_product_name) ) ;
			}
		};
		return $productName;
	}
	
	/**
	 * 取得产品的价格, 只是数字,不需要签名的$符号
	 * @param string $tag
	 * @return string
	 */
	public function parseProductPrice() 
	{
		$productPrice = '';
		if ($this->dom) {
			$priceDollars = trim($this->dom->find('.price .priceDollars',0)->plaintext);
			$priceCents = trim($this->dom->find('.price .priceCents',0)->plaintext);
			$productPrice = $priceDollars.'.'.$priceCents;
		};
		return $productPrice;
	}
	
	/**
	 * 取得给定的 HTML 标签代码中的某个属性的值
	 * @param string $element
	 * @param string $attrName
	 */
	public function getAttrInGivenElement($htmlElement,$attr_name){
		$result = '';
		return $result;
	}
	
	/**
	 * 取得产品的缩略图片 url 地址
	 * @param string $html
	 * @return string
	 */
	public function getSmallImageUrl(){
		$tag = '#productMainImage';
		return 'http://s.squixa.net/www.amcal.com.au/635660319075400001'.trim($this->dom->find($tag,0)->getAttribute('src'));
	}
	
	/**
	 * 取得产品的原始图片 url 地址
	 * @param string $html
	 * @return string
	 */
	public function getOriginalImageUrl(){
		return $this->getSmallImageUrl();
	}
}