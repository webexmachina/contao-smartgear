<?php

/**
 * SMARTGEAR for Contao Open Source CMS
 *
 * Copyright (c) 2015-2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\SmartGear\Backend;

use Exception;

/**
 * Back end module "smartgear".
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */
class Util
{
	/**
	 * Store the path to the config file
	 * @var String
	 */
	protected static $strConfigPath = "system/modules/wem-contao-smartgear/assets/config/smartgear.json";

	/**
	 * Find and Create an Object, depending on type and module
	 * @param  [String] $strType   [Type / Folder]
	 * @param  [String] $strModule [Class / File]
	 * @return [Object]            [Object of the class]
	 */
	public static function findAndCreateObject($strType, $strModule = ''){
		try{
			// If module is missing, try to explode strType
			if('' === $strModule && false != strpos($strType, '_')){
				$arrObject = explode('_', $strType);
				$strType = $arrObject[0];
				$strModule = $arrObject[1];
			}

			// Parse the classname
			$strClass = sprintf("WEM\SmartGear\Backend\%s\%s", ucfirst($strType), ucfirst($strModule));

			// Throw error if class doesn't exists
			if(!class_exists($strClass))
				throw new Exception(sprintf("Unknown class %s", $strClass));

			// Create the object
			$objModule = new $strClass;

			// And return
			return $objModule;
		}
		catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * Get Smartgear Config
	 * @param  [String] $strKey [Config key wanted]
	 * @return [Mixed] 			[Config value]
	 */
	public static function loadSmartgearConfig(){
		try{
			$objFiles = \Files::getInstance();
			$objFile = $objFiles->fopen(static::$strConfigPath, "a");
			$arrConfig = [];

			// Get the config file
			if($strConfig = file_get_contents(static::$strConfigPath))
				$arrConfig = (array)json_decode($strConfig);

			// And return the entire config, updated
			return $arrConfig;
		}
		catch(Exception $e){
			throw $e;
		}
	}
	
	/**
	 * Update Contao Config
	 * @param  [Array] $arrVars [Key/Value Array]
	 */
	public static function updateConfig($arrVars){
		try{
			$objFiles = \Files::getInstance();
			$strConfig = file_get_contents(static::$strConfigPath);
			$arrConfig = [];

			// Decode the config
			if($strConfig)
				$arrConfig = (array)json_decode($strConfig);
			
			// Update the config
			foreach($arrVars as $strKey => $varValue)
				$arrConfig[$strKey] = $varValue;

			// Open and update the config file
			$objFile = $objFiles->fopen(static::$strConfigPath, "w");
			$objFiles->fputs($objFile, json_encode($arrConfig, JSON_PRETTY_PRINT));
			
			// And return the entire config, updated
			return $arrConfig;
		}
		catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * Shortcut for page creation
	 */
	public static function createPage($strTitle, $intPid = 0){
		$arrConfig = static::loadSmartgearConfig();
		if(0 === $intPid)
			$intPid = $arrConfig["sgInstallRootPage"];

		// Create the page
		$objPage = new \PageModel();
		$objPage->tstamp = time();
		$objPage->pid = $intPid;
		$objPage->sorting = (\PageModel::countBy("pid", $intPid) + 1) * 128;
		$objPage->title = $strTitle;
		$objPage->alias = \StringUtil::generateAlias($objPage->title);
		$objPage->type = "regular";
		$objPage->pageTitle = $strTitle;
		$objPage->robots = "index,follow";
		$objPage->sitemap = "map_default";
		$objPage->published = 1;
		$objPage->save();

		// Return the page ID
		return $objPage;
	}

	/**
	 * Shortcut for article creation
	 */
	public static function createArticle($objPage){
		// Create the article
		$objArticle = new \ArticleModel();
		$objArticle->tstamp = time();
		$objArticle->pid = $objPage->id;
		$objArticle->sorting = (\ArticleModel::countBy("pid", $objPage->id) + 1) * 128;;
		$objArticle->title = $objPage->title;
		$objArticle->alias = $objPage->alias;
		$objArticle->author = $arrConfig["sgInstallUser"];
		$objArticle->inColumn = "main";
		$objArticle->published = 1;
		$objArticle->save();

		// Return the page ID
		return $objArticle;
	}

	/**
	 * Shortcut for page w/ modules creations
	 */
	public static function createPageWithModules($strTitle, $arrModules, $intPid = 0){
		$arrConfig = static::loadSmartgearConfig();
		if(0 === $intPid)
			$intPid = $arrConfig["sgInstallRootPage"];
		
		// Create the page
		$objPage = static::createPage($strTitle, $intPid);

		// Create the article
		$objArticle = static::createArticle($objPage);

		// Create the content
		$i = 0;
		foreach($arrModules as $intModule){
			$i += 128;
			$objContent = new \ContentModel();
			$objContent->tstamp = time();
			$objContent->pid = $objArticle->id;
			$objContent->ptable = "tl_article";
			$objContent->sorting = $i;
			$objContent->type = "module";
			$objContent->module = $intModule;
			$objContent->save();
		}

		// Return the page ID
		return $objPage->id;
	}

	/**
	 * Shortcut for page w/ texts creations
	 */
	public static function createPageWithText($strTitle, $strText, $intPid = 0, $arrHl = null){
		$arrConfig = static::loadSmartgearConfig();
		if(0 === $intPid)
			$intPid = $arrConfig["sgInstallRootPage"];
		
		// Create the page
		$objPage = static::createPage($strTitle, $intPid);

		// Create the article
		$objArticle = static::createArticle($objPage);

		// Create the content
		$objContent = new \ContentModel();
		$objContent->tstamp = time();
		$objContent->pid = $objArticle->id;
		$objContent->ptable = "tl_article";
		$objContent->sorting = 128;
		$objContent->type = "text";
		$objContent->text = $strText;

		if($arrHl)
			$objContent->headline = serialize($arrHl);

		$objContent->save();

		// Return the page ID
		return $objPage->id;
	}
}