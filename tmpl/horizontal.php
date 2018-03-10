<?php
/**
 * @package      Userideas
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */
 
// no direct access
defined('_JEXEC') or die; ?>
<div class="ui-modsearch<?php echo $moduleclassSfx; ?>">
    <form action="<?php echo $url;?>" method="get">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="filter_search" value="<?php echo $filterSearch;?>" placeholder="<?php echo JText::_('MOD_USERIDEASSEARCH_SEARCH_');?>" class="form-control" />
            </div>

            <?php if ($params->get('display_categories', 1) or $params->get('display_statuses', 1)) { ?>
                <?php if ($params->get('display_categories', 0)) {
                    $elementId = Prism\Utilities\StringHelper::generateRandomString();
                    ?>
                    <div class="col-md-3">
                        <label for="<?php echo $elementId;?>" class="hidden"><?php echo JText::_('MOD_USERIDEASSEARCH_CATEGORY'); ?></label>
                        <select name="filter_category" class="js-userideas-modsearch-filter" id="<?php echo $elementId;?>">
                            <?php echo JHtml::_('select.options', $categories, 'value', 'text', $filterCategory);?>
                        </select>
                    </div>
                <?php }?>
                <?php if ($params->get('display_statuses', 0)) {
                    $elementId = Prism\Utilities\StringHelper::generateRandomString();
                    ?>
                    <div class="col-md-3">
                        <label for="<?php echo $elementId;?>" class="hidden"><?php echo JText::_('MOD_USERIDEASSEARCH_STATUS'); ?></label>
                        <select name="filter_status" class="js-userideas-modsearch-filter" id="<?php echo $elementId;?>">
                            <?php echo JHtml::_('select.options', $statuses, 'value', 'text', $filterStatus);?>
                        </select>
                    </div>
                <?php }?>
            <?php } ?>

            <div class="col-md-2">
                <?php if ($params->get('display_button', 1)) { ?>
                <button class="btn btn-default" type="submit">
                    <span class="fa fa-search"></span>
                    <?php echo JText::_('MOD_USERIDEASSEARCH_SEARCH');?>
                </button>
                <?php } ?>
            </div>
        </div>

    </form>

    <?php if ($displaySortingFilters) {?>
    <nav class="navbar navbar-default ui-modsearch-sorting mt-20">
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript: void(0);"><?php echo JText::_('MOD_USERIDEASSEARCH_SORT_BY'); ?>:</a>
        </div>
        <ul class="nav navbar-nav">
            <?php
            if ($params->get('display_sort_title', 1)) {
                echo UserideasHelper::sortByLink(JText::_('MOD_USERIDEASSEARCH_TITLE'), 'alpha', $orderOptions, 'ui-order-alphabet');
            }
            if ($params->get('display_sort_mostvoted', 1)) {
                echo UserideasHelper::sortByLink(JText::_('MOD_USERIDEASSEARCH_VOTES'), 'votes', $orderOptions, 'ui-order-funding');
            }
            if ($params->get('display_sort_latest', 1)) {
                echo UserideasHelper::sortByLink(JText::_('MOD_USERIDEASSEARCH_RECENT'), 'date', $orderOptions, 'ui-order-latest');
            }
            if ($params->get('display_sort_popular', 1)) {
                echo UserideasHelper::sortByLink(JText::_('MOD_USERIDEASSEARCH_POPULAR'), 'hits', $orderOptions, 'ui-order-popular');
            }
            ?>
        </ul>
    </nav>
    <?php } ?>
</div>