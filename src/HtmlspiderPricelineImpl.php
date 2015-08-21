<?php namespace justinwang24\phphtmlspider;

/**
 * 针对Chemist Warehouse网站产品页面的抓取功能实现类
 * @author justinwang
 *
 */

use justinwang24\phphtmlspider\Htmlspider;
use PHPHtmlParser\Dom;

class HtmlspiderPricelineImpl implements Htmlspider{
	
	protected $url = NULL;
	protected $dom = NULL;
	protected $controler_obj = NULL;
	
	/**
	 * 构造函数,需要传入当前调用的控制器的实例来实现某些功能
	 * @param string $controler_obj
	 */
	public function __construct(){
		$this->dom = new Dom;
	}

	/*
		设置需要爬行的 url 地址
	*/
	public function setUrl($url){
		$this->url = $url;
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
	
		$this->dom->loadFromUrl($this->url);
		//检查是否可以找出制造商
		$productName = strtoupper( $this->parseProductName() );
	
		$manufacturerName = '';
		$default_manufacturers = $this->controler_obj->manufacturer_model->get_all();
	
		foreach ($default_manufacturers as $manufacturer) {
				
			if (strpos($productName, strtoupper($manufacturer->name) )  !== FALSE ) {
				$manufacturerName = $manufacturer->name;
				//去掉产品名字中得厂商名称
				$productName = trim( str_replace( strtoupper($manufacturerName), '', $productName) );
				break;
			}
		}
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
				'source'=>'Priceline',
				'RRP'=>$this->parseProductPrice(),
				'hasImage'=> ($origin_file_saved || $small_file_saved) ? 1 : 0,
				'image_file_name'=>$small_file_saved ? $real_name : '',
				'original_image_file_name'=>$origin_file_saved ? 'original_'.$real_name : '',
				'productName'=>urldecode( ucwords( strtolower($productName) ) ),
				'manufacturer'=>urldecode( ucwords(strtolower($manufacturerName) )),
				'product_id'=>random_string('numeric', 4).time(),   //产生一个产品代码
				'published'=>0,  //默认不展示
				'description'=>$this->parseProductDescription()
		);
	}

	/**
	 * 取得 html 中的产品描述
	 * @param string $tag
	 */
	public function parseProductDescription(){
		$productDesription = '';
		if ($this->dom) {
			return trim($this->dom->find('.short-description')->innerHtml);
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
				//取最后一个数组
				return ucwords( str_replace('-', ' ', $url_array[count($url_array)-1]) ) ;
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
			$price = trim($this->dom->find('.price')->innerHtml);
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
		if ($htmlElement && $attr_name) {
			$htmlElement = str_replace('"', '\'', $htmlElement);
			$htmlElement_array = explode($attr_name, $htmlElement);
			if (count($htmlElement_array)>0) {
				$result = substr($htmlElement_array[1], 2);
				$tmp_array = explode('\'',$result);
				$result = $tmp_array[0];
			}
		}
		return $result;
	}
	
	/**
	 * 取得产品的缩略图片 url 地址
	 * @param string $html
	 * @return string
	 */
	public function getSmallImageUrl(){
		$tag = '.product-image img';
		$html = trim($this->dom->find($tag)->outerHtml);
		return $this->getAttrInGivenElement($html,'src');
	}
	
	/**
	 * 取得产品的原始图片 url 地址
	 * @param string $html
	 * @return string
	 */
	public function getOriginalImageUrl(){
		$tag = '.product-image';
		$html = trim($this->dom->find($tag)->outerHtml);
		return $this->getAttrInGivenElement($html,'href');
	}
}