<div id="payapi_modal" class="payapi_modal">
	<div class="wrapper wow bounceInDown" data-wow-delay="10ms" data-wow-iteration="1" data-wow-duration="0.15s">
		<row>
			<a href="<?=$payapi['brand']['partnerWebUrl']?>" target="_blank"><img src="<?=$payapi['media']?>img/payapi-logo_transparent.png" height="32" width="auto" class="pa_logo wow pulse" data-wow-delay="10.15ms" data-wow-iteration="3" data-wow-duration="0.15s" alt="<?=$payapi['brand']['partnerName']?>"></a>
			<span class="close pull-right"><a href="javascript:payapi_js.close()"><span class="dashicons dashicons-dismiss pa-dashicon"></span></a></span>
		</row>
		<br class="clear">
		<form action="" class="payapi_form" id="payapi_payment" name="payapi_payment" method="post">
			<div id="form_top_notice" class="form_top_notice fail"><span class="dashicons dashicons-info"></span> <span id="form_top_notice_text"></span><span class="pull-right dashicons dashicons-dismiss pointing" onclick="javascript:payapi_js.hide('form_top_notice')"></span></div>
			<p><label>Name</label>
			<input type="text" id="product_name" name="product_name" value="" placeholder="Payment title"></p>
			<div class="block-3">
				<p><label>Price</label>
				<input type="text" id="product_price" name="product_price" value="" placeholder="100.10" onkeypress="return payapi_js.filterPrice(event)"></p>
			</div>
			<div class="block-3">
				<p><label>Quantity</label>
					<select id="product_quantity" name="product_quantity">
						<option value="1" selected="selected">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
					</select>
				</p>
			</div>
			<div class="block-3 last">
				<p><label>Currency</label>
					<select id="product_currency" name="product_currency">
						<option value="EUR" selected="selected">EUR</option>
						<option value="USD">USD</option>
						<option value="GBP">GBP</option>
					</select>
				</p>
			</div>
			<div id="advanced_settings" class="advanced_settings wow pulse" data-wow-delay="50ms" data-wow-iteration="1" data-wow-duration="0.40s">
				<div class="block-2">
					<p><label>Taxes</label>
					<input type="text" id="product_taxes" name="product_taxes" value="" placeholder="20.02" onkeypress="return payapi_js.filterPrice(event)"></p>
				</div>
				<div class="block-2 last">
					<p><label>Shipping</label>
					<input type="text" id="product_shipping" name="product_shipping" value="" placeholder="9" onkeypress="return payapi_js.filterPrice(event)"></p>
				</div>
				<div class="block-1">
					<p><label>Image url</label>
					<input type="text" id="product_image" name="product_image" value="" placeholder="https://url.to/image.jpg"></p>
				</div>
			</div>
			<br class="clear">
			<br class="clear buttoms">
			<button type="button" class="btn success" onclick="payapi_js.advanced()">advanced</button>
			<button type="button" onclick="payapi_js.button()" class="btn">add</button>
			<br class="clear">
		</form>
	</div>
</div>
<script type="text/javascript">
	payapi_js.getId('product_name').focus();
</script>