<?php
/**
 * Main view
 *
 * @package     BibleStudy
 * @subpackage  Model.BibleStudy
 * @copyright   (C) 2007 - 2011 Joomla Bible Study Team All rights reserved
 * @license     http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link        http://www.JoomlaBibleStudy.org
 * */
defined('_JEXEC') or die;

$show_link = $params->get('show_link', 1);

JLoader::register('JBSMHelper', BIBLESTUDY_PATH_ADMIN_HELPERS . 'helper.php');
JLoader::register('JBSMListing', BIBLESTUDY_PATH_LIB . '/biblestudy.listing.class.php');
$JBSMListing = new JBSMListing;
?>
<div class="container-fluid">
    <?php if (($params->get('pageheader'))){ ?>
    <div class="row-fluid">
        <div class="span12">
            <?php echo JHtml::_('content.prepare',$params->get('pageheader'),'','com_biblestudy.module');?>
        </div>
    </div>
  <?php } ?>
    <div class="row-fluid">
        <div class="span12">
            <?php
            $list = $JBSMListing->getFluidListing($items, $params, $admin_params, $template, $type="sermons");
            echo $list;
            ?>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span12">
            <?php
            if ($params->get('show_link') > 0)
            {
                echo $link;
            }
            ?>
        </div>
    </div><!--end of footer div-->
</div> <!--end container -->