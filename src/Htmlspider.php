<?php namespace justinwang24\phphtmlspider;

/**
 * 解析 Html 页面内容的工具类接口
 */
interface Htmlspider{
	public function setUrl($url);
	/**
	 * 解析产品的信息并返回产品对象或者数组
	 */
	public function parseProduct();
	
	/**
	 * 取得 html 中的产品名称信息
	 * @param string $tag
	 */
	public function parseProductName();
	
	/**
	 * 取得 html 中的产品价格信息
	 * @param string $tag
	 */
	public function parseProductPrice();
	
	
	/**
	 * 取得产品的原始图片 url 地址
	 * @param string $html
	 * @return string
	 */
	public function getOriginalImageUrl($html=NULL);
	
	/**
	 * 取得产品的缩略图片 url 地址
	 * @param string $html
	 * @return string
	 */
	public function getSmallImageUrl($html=NULL);
	
	/**
	 * 取得给定的 HTML 标签代码中的某个属性的值
	 * @param string $element
	 * @param string $attrName
	 */
	public function getAttrInGivenElement($htmlElement,$attrName);
}