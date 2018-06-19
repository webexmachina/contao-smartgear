<?php

/**
 * SMARTGEAR for Contao Open Source CMS
 *
 * Copyright (c) 2015-2018 Web ex Machina
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */

namespace WEM\SmartGear\Backend\Module;

use \Exception;
use Contao\Config;
use Contao\PageModel;
use Contao\ModuleModel;
use Contao\NewsArchiveModel;
use Contao\ArticleModel;
use Contao\ContentModel;
use WEM\SmartGear\Backend\Module;

/**
 * Back end module "smartgear".
 *
 * @author Web ex Machina <https://www.webexmachina.fr>
 */
class Blog extends Module
{
	public function install(){
		// Create the archive
		$objArchive = new NewsArchiveModel();
		$objArchive->tstamp = time();
		$objArchive->title = "Blog";
		$objArchive->save();

		// Create the list module
		$objListModule = new ModuleModel();
		$objListModule->tstamp = time();
		$objListModule->pid = Config::get("sgInstallTheme");
		$objListModule->name = "Blog - List";
		$objListModule->type = "newslist";
		$objListModule->news_archives = serialize([0=>$objArchive->id]);
		$objListModule->numberOfItems = 0;
		$objListModule->news_featured = "all_items";
		$objListModule->perPage = 15;
		$objListModule->news_template = 'news_latest';
		$objListModule->skipFirst = 0;
		$objListModule->news_metaFields = serialize([0=>'date']);
		$objListModule->news_order = 'order_date_desc';
		$objListModule->save();

		// Create the reader module
		$objReaderModule = new ModuleModel();
		$objReaderModule->tstamp = time();
		$objReaderModule->pid = Config::get("sgInstallTheme");
		$objReaderModule->name = "Blog - Reader";
		$objReaderModule->type = "newsreader";
		$objReaderModule->news_archives = serialize([0=>$objArchive->id]);
		$objReaderModule->news_metaFields = serialize([0=>'date']);
		$objReaderModule->news_template = 'news_full';
		$objReaderModule->save();

		// Create the list page
		$objListPage = new PageModel();
		$objListPage->tstamp = time();
		$objListPage->pid = Config::get("sgInstallRootPage");
		$objListPage->sorting = (PageModel::countBy("pid", Config::get("sgInstallRootPage")) + 1) * 128;
		$objListPage->title = "Blog - List";
		$objListPage->alias = \StringUtil::generateAlias($objListPage->title);
		$objListPage->type = "regular";
		$objListPage->pageTitle = "Blog";
		$objListPage->robots = "index,follow";
		$objListPage->sitemap = "map_default";
		$objListPage->published = 1;
		$objListPage->save();

		// Create the article
		$objArticle = new ArticleModel();
		$objArticle->tstamp = time();
		$objArticle->pid = $objListPage->id;
		$objArticle->sorting = 128;
		$objArticle->title = $objListPage->title;
		$objArticle->alias = $objListPage->alias;
		$objArticle->author = Config::get("sgInstallUser");
		$objArticle->inColumn = "main";
		$objArticle->published = 1;
		$objArticle->save();

		// Create the content
		$objContent = new ContentModel();
		$objContent->tstamp = time();
		$objContent->pid = $objArticle->id;
		$objContent->ptable = "tl_article";
		$objContent->sorting = 128;
		$objContent->type = "module";
		$objContent->module = $objListModule->id;
		$objContent->save();

		// Create the reader page
		$objReaderPage = new PageModel();
		$objReaderPage->tstamp = time();
		$objReaderPage->pid = $objListPage->id;
		$objReaderPage->sorting = 128;
		$objReaderPage->title = "Blog - Reader";
		$objReaderPage->alias = \StringUtil::generateAlias($objReaderPage->title);
		$objReaderPage->type = "regular";
		$objReaderPage->robots = "index,follow";
		$objReaderPage->sitemap = "map_never";
		$objReaderPage->hide = 1;
		$objReaderPage->published = 1;
		$objReaderPage->save();

		// Create the article
		$objArticle = new ArticleModel();
		$objArticle->tstamp = time();
		$objArticle->pid = $objReaderPage->id;
		$objArticle->sorting = 128;
		$objArticle->title = $objReaderPage->title;
		$objArticle->alias = $objReaderPage->alias;
		$objArticle->author = Config::get("sgInstallUser");
		$objArticle->inColumn = "main";
		$objArticle->published = 1;
		$objArticle->save();

		// Create the content
		$objContent = new ContentModel();
		$objContent->tstamp = time();
		$objContent->pid = $objArticle->id;
		$objContent->ptable = "tl_article";
		$objContent->sorting = 128;
		$objContent->type = "module";
		$objContent->module = $objReaderModule->id;
		$objContent->save();

		// Update the archive jumpTo
		$objArchive->jumpTo = $objReaderPage->id;
		$objArchive->save();
		
		// And save stuff in config
		$this->updateConfig([
			"sgBlogInstall"=>1
			,"sgBlogNewsArchive"=>$objArchive->id
			,"sgBlogModuleList"=>$objListModule->id
			,"sgBlogModuleReader"=>$objReaderModule->id
			,"sgBlogPageList"=>$objListPage->id
			,"sgBlogPageReader"=>$objReaderPage->id
		]);
	}

	public function reset(){
		$this->remove();
		$this->install();
	}

	public function remove(){
		$objArchive = NewsArchiveModel::findByPk(Config::get("sgBlogNewsArchive"));
		$objArchive->delete();
		$objModule = ModuleModel::findByPk(Config::get("sgBlogModuleList"));
		$objModule->delete();
		$objModule = ModuleModel::findByPk(Config::get("sgBlogModuleReader"));
		$objModule->delete();
		$objPage = PageModel::findByPk(Config::get("sgBlogPageList"));
		$objPage->delete();
		$objPage = PageModel::findByPk(Config::get("sgBlogPageReader"));
		$objPage->delete();

		$this->updateConfig([
			"sgBlogInstall"=>''
			,"sgBlogNewsArchive"=>''
			,"sgBlogModuleList"=>''
			,"sgBlogModuleReader"=>''
			,"sgBlogPageList"=>''
			,"sgBlogPageReader"=>''
		]);
	}
}