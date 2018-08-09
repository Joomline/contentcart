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

header('Content-Type: text/html; charset=utf-8');
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
$session = JFactory::getSession();
$plugin = JPluginHelper::getPlugin('content','contentcart');
$pluginParams = new JRegistry($plugin->params);
$content_order = $session->get('content_order');
if (!empty($_REQUEST['mail']) && empty($_REQUEST['nosend'])){
include JPluginHelper::getLayoutPath('content', 'contentcart', 'mail');
}
if(!empty($_REQUEST['nosend'])) {	
	for($i=0;$i<count($content_order);$i++){
		$content_order[$i]['count'] = $_REQUEST['count'.$i];
	}
$session->set('content_order',  $content_order);

}


?>
<div class="jlcontentcart">
<h1 class="title"><?php echo JText::_('CONTENTCART_SHOPPING_CART')?></h1>
<form name="cart" class="order" method="post" action="<?php $_SERVER['REQUEST_URI'] ?>">
<table style="width:100%;">
<thead>
<th>№</th>
<th><?php echo JText::_('CONTENTCART_PRODUCT_TITLE')?></th>
<th><?php echo JText::_('CONTENTCART_PRODUCT_COUNT')?></th>
<?php if ($pluginParams->get('using_price')=='1') { ?>
<th><?php echo JText::_('CONTENTCART_PRODUCT_PRICE')?></th>
<th><?php echo JText::_('CONTENTCART_PRODUCT_SUMM')?></th>
<?php } ?>
<th></th>
</thead>
<tbody>
<?php $i = 0; $total=0; foreach($content_order as $order_item){ ?>
	<tr class="order_item">
		<td><?php echo $i+1 ?></td>
		<td><a class="order_item_name" href="<?php echo $order_item['link'] ?>"><?php echo $order_item['title'] ?></a></td>
		<td><input class="jlcc-input jlcc-count" type="number" name="count<?php echo $i ?>" max="999" min="1"  value="<?php echo $order_item['count'] ?>" onchange="update()" /></td>
		<?php if ($pluginParams->get('using_price')=='1') { ?>
			<td name="price"><?php echo $order_item['price'].' '.$pluginParams->get('currency') ?></td>
			<td><?php echo $order_item['price']*$order_item['count'].' '.$pluginParams->get('currency') ?></td>
		<?php } ?>
		<td><a href="<?php echo JURI::current().'?delete='.$i ?>"><?php echo JText::_('CONTENTCART_PRODUCT_DELETE')?></a></td>
	</tr>	
<?php $i++; $total=$total+($order_item['price']*$order_item['count']);} ?>
<?php if ($pluginParams->get('using_price')=='1') { ?>
	<tr class="order_total">
		<td colspan="4" style="text-align:right;"><b><?php echo JText::_('CONTENTCART_PRODUCT_TOTAL')?>:&nbsp;</b></td>
		<td> <?php echo $total.' '.$pluginParams->get('currency') ?></td>
		<td></td>
	</tr>
<?php } ?>
</tbody>
</table>
<?php
if(!JFactory::getUser()->guest) {
	$userid = JFactory::getUser()->id;
	$useremail = JFactory::getUser()->email;
	$username = JFactory::getUser()->name;
} else {
	$userid = 1;
	$useremail = '';
	$username = '';
}
?>
	<h3 class="jlcc-title-data"><?php echo JText::_('CONTENTCART_CLIENT_DATA')?></h3>
	<div class="jlcc-block-data">
	<input class="jlcc-input" type="hidden" name="mail" value="1" />
<?php if ($pluginParams->get('client_name')!='0') { ?>
	<div>
    <input class="jlcc-input" type="text" name="client_name" value="<?php echo $username ?>"  size="25" 
	<?php if ($pluginParams->get('client_name')=='2') { ?>
		required="required" aria-required="true" 
	<?php } ?>
	autofocus="" aria-invalid="false" placeholder="<?php echo JText::_('CONTENTCART_ENTER_NAME')?>" />
    </div>
<?php } ?>

<?php if ($pluginParams->get('client_email')!='0') { ?>
	<div>
    <input class="jlcc-input" type="email" name="client_email" value="<?php echo $useremail ?>"  size="25" 
	<?php if ($pluginParams->get('client_email')=='2') { ?>
		required="required" aria-required="true" 
	<?php } ?>
	autofocus="" aria-invalid="false" placeholder="<?php echo JText::_('CONTENTCART_ENTER_EMAIL')?>" validate="email" />
    </div>
<?php } ?>

<?php if ($pluginParams->get('client_phone')!='0') { ?>
	<div>
    <input class="jlcc-input" type="tel" name="client_phone" value=""  size="25" 
	<?php if ($pluginParams->get('client_phone')=='2') { ?>
		required="required" aria-required="true" 
	<?php } ?>
	autofocus="" aria-invalid="false" placeholder="<?php echo JText::_('CONTENTCART_ENTER_PHONE')?>"  />
    </div>
<?php } ?>

<?php if ($pluginParams->get('client_note')!='0') { ?>
	<div><textarea class="jlcc-textarea" name="client_note" value=""
	<?php if ($pluginParams->get('client_note')=='2') { ?>
		required="required" aria-required="true" 
	<?php } ?>
	autofocus="" aria-invalid="false" placeholder="<?php if ($pluginParams->get('title_note')) {echo $pluginParams->get('title_note');} else {echo JText::_('CONTENTCART_CLIENT_NOTE');} ?>"></textarea></div>
<?php } ?>
<div><input type="submit" class="validate jlcc-button jlcc-primary" value="<?php echo JText::_('CONTENTCART_TO_ORDER')?>" /></div>
</div> 
</form>
<script>
function update() {
	var x = document.createElement("INPUT");
    x.setAttribute("type", "hidden");
	x.setAttribute("name", "nosend");
    x.setAttribute("value", "yes");
    document.cart.appendChild(x);
	document.cart.submit();
}
</script>
</div>