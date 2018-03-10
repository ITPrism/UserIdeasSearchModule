<?php
/**
 * @package      Userideas
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

/** @var $app JApplicationSite */

jimport('Prism.init');
jimport('Userideas.init');

JLoader::register('UserideasSearchModuleHelper', JPATH_ROOT . '/modules/mod_userideassearch/helper.php');

$moduleclassSfx = htmlspecialchars($params->get('moduleclass_sfx'));

// Initialise Chosen
if ($params->get('enable_chosen', 0)) {
    JHtml::_('behavior.framework', true);
    JHtml::_('formbehavior.chosen', 'select.js-userideas-modsearch-filter');
}

$app   = JFactory::getApplication();

$filterSearch = $app->input->get('filter_search', '', 'string');
$layoutType   = $params->get('layout', 'default');

$view   = $app->input->getCmd('view');
$option = $app->input->getCmd('option');
if (strcmp('com_userideas', $option) === 0 and strcmp('category', $view) === 0) {
    $categoryId = $app->input->getInt('id');
    $url = JRoute::_(UserideasHelperRoute::getCategoryRoute($categoryId));
} else {
    $url = JRoute::_(UserideasHelperRoute::getItemsRoute());
}

// Prepare caching.
$cache = null;
if ($app->get('caching', 0)) {
    $cache = JFactory::getCache('com_userideas', '');
    $cache->setLifeTime(Prism\Constants::TIME_SECONDS_24H);
}

if ($params->get('display_statuses', 0)) {
    if ($cache !== null) { // Get the statuses from the cache.
        $statuses = Userideas\Helper\CacheHelper::getStatusOptions($cache);
    } else { // Get the statuses from the container.
        $container       = Prism\Container::getContainer();
        /** @var  $container Joomla\DI\Container */

        $containerHelper = new Userideas\Container\Helper();
        $statuses = $containerHelper->fetchStatuses($container);
        $statuses = $statuses->getStatusOptions();
    }

    $filterStatus    = $app->input->get('filter_status', '', 'int');

    $option          = JHtml::_('select.option', '', JText::_('MOD_USERIDEASSEARCH_SELECT_STATUS'), 'value', 'text');
    $option          = array($option);
    
    $statuses        = array_merge($option, $statuses);
}

if ($params->get('display_categories', 0)) {
    $categories      = Userideas\Helper\CacheHelper::getCategoryOptions($cache);
    $filterCategory  = $app->getUserStateFromRequest('mod_userideassearch.filter_category', 'filter_category');

    $option     = JHtml::_('select.option', 0, JText::_('MOD_USERIDEASSEARCH_SELECT_CATEGORY'));
    $option     = array($option);
    
    $categories = array_merge($option, $categories);
}

// START Sorting filters
$displaySortingFilters= false;
if ($params->get('display_sort_alphabet', Prism\Constants::DISPLAY) or $params->get('display_sort_mostvoted', Prism\Constants::DISPLAY) or
    $params->get('display_sort_latest', Prism\Constants::DISPLAY) or $params->get('display_sort_popular', Prism\Constants::DISPLAY)) {
    $displaySortingFilters = true;

    // Get current ordering column.
    $pageParams   = $app->getParams();
    $container    = Prism\Container::getContainer();
    if ($container->exists(Userideas\Constants::CONTAINER_FILTER_ORDER_CONTEXT)) {
        $orderContext = $container->get(Userideas\Constants::CONTAINER_FILTER_ORDER_CONTEXT);
        $orderBy      = $app->getUserStateFromRequest($orderContext, 'filter_order', $pageParams->get('orderby_sec', 'rdate'), 'cmd');
    } else {
        $orderBy = $app->input->get('filter_order', $pageParams->get('orderby_sec', 'rdate'));
    }

    $orderOptions = array(
        'ordered_by'    => $orderBy,
        'url'           => $url,
        'item_class'    => $params->get('sort_item_class')
    );
}

require JModuleHelper::getLayoutPath('mod_userideassearch', $params->get('layout', $layoutType));
