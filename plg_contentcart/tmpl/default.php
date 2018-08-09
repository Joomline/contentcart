<?php
/**
 * Content Cart
 *
 * @version 	@version@
 * @author		Joomline
 * @copyright	(C) 2018 Efanych (efanych@gmail.com), Joomline. All rights reserved.
 * @license 	GNU General Public License version 2 or later; see	LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('jquery.framework');
$doc = JFactory::getDocument();

if($this->params->get('mymenuitem')){
	$cart_url = JRoute::_("index.php?Itemid=".$this->params->get('mymenuitem'));
} else {
	$content_order = $session->get('content_order');
	$cart_url = $content_order[0]['link'].'?cart=1';
}

if ($params->get('enable_css', 1)) {
	$doc->addStyleSheet(JUri::root().'plugins/content/contentcart/assets/css/jlcontentcart.css', array('version' => 'auto'));
}

if((!$session->get('content_order') or  array_search($row->id, array_column($session->get('content_order', array()), 'article_id'))=== false) && $link != $cart_url) {
?>
<div class="jlcontentcart">
<form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
<input type="hidden" name="add" value="1" />
<input type="hidden" name="article_id" value="<?php echo $row->id ?>" />
<input type="hidden" name="title" value="<?php echo $row->title ?>" />
<input type="hidden" name="link" value="<?php echo $link ?>" />
<?php if ($this->params->get('using_price') == '1') { ?>
<input type="hidden" name="price" value="<?php echo $row->jcfields[$this->params->get('price_id')]->value ?>" />
<?php } ?>
<input type="submit" class="jlcc-button jlcc-primary" value="<?php echo JText::_('CONTENTCART_ADD_TO_CART')?>" />
<input type="number" name="count" max="999" min="1" value="1" class="jlcc-input jlcc-count">
</form>
</div>
<?php } 

elseif (!JFactory::getApplication()->input->getInt('cart')  && $link != $cart_url) { ?>
	<div class="to-cart"><a class="jlcc-button jlcc-success" href="<?php echo $cart_url;?>"><?php echo JText::_('CONTENTCART_GO_TO_CART')?></a></div>
<?php } ?>

