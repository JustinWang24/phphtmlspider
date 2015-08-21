<?php namespace justinwang24\phphtmlspider;

/**
 * 解析 Html 页面内容的工具类接口
 */
interface Htmlspider{
	/*
		设置需要爬行的 url 地址
	*/
	public function setUrl($url);

	/*
		设置需要使用的 mvc 框架控制器类
	*/
	public function setCiController($controller);

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
	 * 取得 html 中的产品描述
	 * @param string $tag
	 */
	public function parseProductDescription();
	
	
	/**
	 * 取得产品的原始图片 url 地址
	 * @return string
	 */
	public function getOriginalImageUrl();
	
	/**
	 * 取得产品的缩略图片 url 地址
	 * @return string
	 */
	public function getSmallImageUrl();
	
	/**
	 * 取得给定的 HTML 标签代码中的某个属性的值
	 * @param string $element
	 * @param string $attrName
	 */
	public function getAttrInGivenElement($htmlElement,$attrName);
}