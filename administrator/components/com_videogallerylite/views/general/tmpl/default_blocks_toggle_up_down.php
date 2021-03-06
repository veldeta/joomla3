<?php
/**
 * @package Video Gallery Lite
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/ 

defined('_JEXEC') or die('Restircted access');
jimport('joomla.html.html.tabs');
?>
<div class="span10" style="width:100%"> 
    <div class="back">
        <fieldset class="form-horizontal">
            <legend class="legend" ><?php echo JText::_('Element Styles'); ?></legend>
            <?php foreach ($this->form->getFieldset('first') as $field): ?>
                <div class="control-group  options-block-title-wrapper first">
                    <?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset>
    </div>
    <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Element Title'); ?></legend>
            <?php foreach ($this->form->getFieldset('GalleryContentElementTitle') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
            <?php endforeach; ?>
        </fieldset></div>
    <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Element Link Button'); ?></legend>
            <?php foreach ($this->form->getFieldset('GalleryContentElementLinkButton') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
            <?php endforeach; ?>
        </fieldset></div>
    <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Popup Styles'); ?></legend>
                <?php foreach ($this->form->getFieldset('GalleryContentPopupStyles') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset>
    </div>

    <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Popup Title'); ?></legend>
            <?php foreach ($this->form->getFieldset('GalleryContentPopupTitle') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
            <?php endforeach; ?>
        </fieldset></div>
    <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Popup Description'); ?></legend>
                <?php foreach ($this->form->getFieldset('GalleryContentPopupDescription') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset></div>

    <div class="back"><fieldset class="form-horizontal">
            <legend class="legend"><?php echo JText::_('Popup Link Button'); ?></legend>
                <?php foreach ($this->form->getFieldset('GalleryContentPopupLinkButton') as $field): ?>
                <div class="control-group"><?php
                    echo "<div class='control-label'>" . $field->label . "</div>";
                    echo "<div class='controls'>" . $field->input . "</div>";
                    ?>
                </div>
<?php endforeach; ?>
        </fieldset></div>
</div>
