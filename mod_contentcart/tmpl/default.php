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
$plugin = JPluginHelper::getPlugin('content','contentcart');
$pluginParams = new JRegistry($plugin->params);
$session = JFactory::getSession();
if($pluginParams->get('mymenuitem')){
	$cart_url = JRoute::_("index.php?Itemid=".$pluginParams->get('mymenuitem'));
} else {
	$order = $session->get('content_order');
	$cart_url = $order[0]['link'].'?cart=1';
}
if ($session->get('content_order')) {
?>
<div class="content_cart" style="border:1px solid #CCC;padding:10px;"> 
<div class="content_cart_info" style="display:none;"> 
<?php $i = 0; $total=0; foreach($session->get('content_order') as $order_item){ 
	$order_item['title'];/*Название товара*/
	$order_item['link'];/*Урл товара*/
	$order_item['price'];/*Цена товара, если есть*/
	$order_item['count'];/*количество товара*/
 $i++; $total=$total+($order_item['price']*$order_item['count']);} ?>
</div>
<p class="count"><span><?php echo JText::_('CONTENTCART_PRODUCTS_COUNT')?>: </span><span><?php echo ' '.count($session->get('content_order')); ?> </span></p>
<p class="total"><span><?php echo JText::_('CONTENTCART_PRODUCT_TOTAL')?>: </span><span><?php echo ' '.$total.' '.$pluginParams->get('currency'); ?> </span></p>
<a style="margin:0 auto;display:block;" class="btn btn-success" title="" href="<?php echo $cart_url ?>"><?php echo JText::_('CONTENTCART_GO_TO_CART')?></a>
</div>
<?php } else { ?>
<div class="content_cart" style="border:1px solid #CCC;padding:10px;"> 
<p class="count"><span><?php echo JText::_('CONTENTCART_PRODUCTS_COUNT')?>: </span><span><?php echo ' 0 ' ?> </span></p>
<p class="total"><span><?php echo JText::_('CONTENTCART_PRODUCT_TOTAL')?>: </span><span><?php echo ' 0 '.$pluginParams->get('currency'); ?> </span></p>
<a style="margin:0 auto;display:block;" onclick="return false" class="btn btn-primary" title="" href="<?php echo $cart_url ?>"><?php echo JText::_('CONTENTCART_EMPTY_CART')?></a>
</div>
<?php } ?>