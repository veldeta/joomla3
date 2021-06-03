<?php 
/**
 * @package Huge IT Gallery
 * @author Huge-IT
 * @copyright (C) 2014 Huge IT. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @website		http://www.huge-it.com/
 **/
?>
<?php defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class GalleryViewGalleries extends JViewLegacy
{
	
	protected $items;
	protected $pagination;
        protected $gallery;
        protected $other;
       

	public function display($tpl = null)
	{
		try
		{
			
			$this->items = $this->get('Items');
                        $this ->gallery = $this->get('Gallery');
                        $this->other=$this->get('Other');
			$this->pagination = $this->get('Pagination');
                        JHtml::stylesheet(Juri::root() . 'media/com_gallery/style/gallery.style.css');
			$this->addToolBar();

			parent::display($tpl);
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

		protected function addToolBar()
	{

		JToolBarHelper::title(JText::_('COM_GALLERY_MANAGER_GALLERIES'),  JText::_('COM_GALLERY_MANAGER_GALLERIES'));
             	JToolBarHelper::addNew('galleries.add');
                JToolBarHelper::divider();
		JToolBarHelper::editList('gallery.edit');
		JToolBarHelper::divider();
		JToolBarHelper::deleteList('', 'galleries.delete');
	}
}
