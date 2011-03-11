<?php

/**
 * @author Tom Fuller
 * @copyright 2011
 * Footer Studydetails view
 * Since 7.0
 */

defined('_JEXEC') or die();

?>
<?php 
        switch ($this->params->get('show_passage_view', '0'))
        {
            case 0:
                break;
            
            case 1:
                ?>
                <strong><a class="heading" href="javascript:ReverseDisplay('scripture')">>>
                <?php echo JText::_('JBS_CMN_SHOW_HIDE_SCRIPTURE');?><<</a>
                <div id="scripture" style="display:none;"></strong>
                <?php 
                
                $passage_call = JView::loadHelper('passage');
                $response = getPassage($params, $row);
                echo $response;
                echo '</div>';
                break;
        
            case 2:
                echo '<div id="scripture">';
                $passage_call = JView::loadHelper('passage');
                $response = getPassage($params, $row);
                echo $response;
                echo '</div>';
                break;
        }
?>