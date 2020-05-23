<?php
// No direct access
defined( '_JEXEC' ) or die;

/**
 * Content Cart
 *
 * @version 	@version@
 * @author		Joomline
 * @copyright	(C) 2018 Efanych (efanych@gmail.com), Joomline. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 */

class plgContentcontentcart extends JPlugin
{
	/**
	 * Class Constructor
	 * @param object $subject
	 * @param array $config
	 */
	public function __construct( & $subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->loadLanguage();
	}

	public function onContentAfterDisplay($context, &$row, &$params, $page = 0){
	$session = JFactory::getSession();
	if (JFactory::getApplication()->input->getInt('delete') !== NULL) {
			$content_order = $session->get('content_order');
			unset($content_order[JFactory::getApplication()->input->getInt('delete')]);
			sort($content_order);
			$session->set('content_order',  $content_order);
			JFactory::getApplication()->redirect($_SERVER['HTTP_REFERER'], 301);
		}
	if (!$row->text) {return;}
	if (($this->params->get('category_filtering_type')=='0' && in_array($row->catid,$this->params->get('catid'))) or ($this->params->get('category_filtering_type')=='1' && !in_array($row->catid,$this->params->get('catid')))) {return;}
		if (!in_array($context,$this->params->get('application_area'))) {return;}
		$link = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid, $row->language));		
		if (!empty($_REQUEST['add'])) {
			$msg = '';
			if($session->get('content_order')) {
				$content_order=$session->get('content_order');
			}
			if($_REQUEST['article_id'] == $row->id && (!$session->get('content_order') or array_search($row->id, array_column($session->get('content_order'), 'article_id'))=== false)) {
				$content_order[]=array('article_id'=>$_REQUEST['article_id'], 'title'=>$_REQUEST['title'], 'link'=>$_REQUEST['link'], 'count'=>$_REQUEST['count'], 'price'=>($_REQUEST['price']));
				$msg = JText::_('CONTENTCART_ADDED');
				$session->set('content_order',  $content_order);
			}
			$application = JFactory::getApplication();
			$application->enqueueMessage($msg, 'message');
		}
			ob_start();
			include JPluginHelper::getLayoutPath('content', 'contentcart', 'default');
			$html = ob_get_clean(); 
			return $html;
	}
	
    public function onContentPrepare($context, $article, $params, $page = 0)
    {
        if ($context != 'com_content.article') {
            return;
        }
        $app  = JFactory::getApplication();
        $session = JFactory::getSession();
        $cart_url = JRoute::_("index.php?Itemid=" . $this->params->get('mymenuitem'));
        $link = JRoute::_(ContentHelperRoute::getArticleRoute($article->slug, $article->catid, $article->language));
        if ($app->input->getInt('cart', 0) == 0 && $link != $cart_url) {
            return;
        }

        if ($session->get('content_order'))
        {
            $template = $app->getTemplate();
            $view = JControllerLegacy::getInstance('Content')->getView('article', JFactory::getDocument()->getType());

            $basePath = JPATH_ROOT . '/plugins/content/contentcart/tmpl/';
            if(is_file(JPATH_ROOT.'/templates/'.$template.'/html/plg_content_contentcart/cart.php')){
                $basePath = JPATH_ROOT.'/templates/'.$template.'/html/plg_content_contentcart/';
            }

            $view->addTemplatePath($basePath);
            $view->setLayout('cart');
            if (!$this->params->get('mymenuitem')) {
                $doc = JFactory::getDocument();
                $doc->setTitle(JText::_('CONTENTCART_SHOPPING_CART'));
            }
        }
        elseif ($link != $cart_url)
        {
            JFactory::getApplication()->redirect($link, 301);
        }
    }
	
}
