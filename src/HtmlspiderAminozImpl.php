<?php namespace justinwang24\phphtmlspider;

/**
 * 针对 aminoz.com.au 网站产品页面的抓取功能实现类
 * @author justinwang
 *
 */

use justinwang24\phphtmlspider\Htmlspider;
use Sunra\PhpSimple\HtmlDomParser;

class HtmlspiderAminozImpl implements Htmlspider{
	
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
	
		return $product = array(
				'product_page_url'=>$this->url,
				'source'=>'Aminoz',
				'RRP'=>$this->parseProductPrice(),
				'hasImage'=> ($origin_file_saved || $small_file_saved) ? 1 : 0,
				'image_file_name'=>$small_file_saved ? $real_name : '',
				'original_image_file_name'=>$origin_file_saved ? 'original_'.$real_name : '',
				'productName'=>urldecode( ucwords( strtolower($productName) ) ),
				'manufacturer'=>$this->parseProductManufacturer(),
				'product_id'=>random_string('numeric', 4).time(),   //产生一个产品代码
				'published'=>0,  //默认不展示
				'description'=>$this->parseProductDescription(),
				'barcode'=>$this->parseProductBarcode()
		);
	}

	/*
		尝试取得页面中产品的 生产商 信息
	*/
	public function parseProductManufacturer(){
		$manufacturer = '';
		if ($this->dom) {
			# code...
			$element = $this->dom->find('table#product-attribute-specs-table tr',0)->innertext;
			$temp_arr = explode(' ', $element);
			$temp_arr[0] = '';
			$manufacturer = implode(' ', $temp_arr);
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
			$element = $this->dom->find('table#product-attribute-specs-table tr',1)->innertext;
			$temp_arr = explode(' ', $element);
			$barcode = $temp_arr[1];
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
			$productDesription = $this->dom->find('.box-description div',0)->innertext;
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
				$url_array = explode('/', $this->url);
				//这个网站的产品 url 最后都带了一个 url, 可能是 magento 的
				$posible_product_name = str_replace('.html', '', $url_array[count($url_array)-1]);
				//取最后一个数组
				return ucwords( str_replace('-', ' ', $posible_product_name) ) ;
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
			$price = trim($this->dom->find('.regular-price .price',0)->innertext);
			if (strlen($price)==0) {
				# 尝试去抓打折的价格
				$price = trim($this->dom->find('.special-price .price',0)->innertext);
			}
			return str_replace('$', '', $price);
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
		$tag = 'img.product-retina';
		return trim($this->dom->find($tag,0)->src);
	}
	
	/**
	 * 取得产品的原始图片 url 地址
	 * @param string $html
	 * @return string
	 */
	public function getOriginalImageUrl(){
		$tag = 'img.product-retina';
		return trim($this->dom->find($tag,1)->src);
	}
}