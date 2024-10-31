<?php if($active === false) { 
if(isset($payapi['public_id']) !== true) {
	$payapi['public_id'] = null;
}?>
<div class="pa_notice_top wow bounceInDown" data-wow-delay="50ms" data-wow-iteration="1" data-wow-duration="0.15s" style="background-image: url(<?=$payapi['media']?>img/payapi-banner-1920x120.jpg);">
	<form name="payapi_activate" action="" method="POST">
		<div class="payapi_activate">
			<div class="pull-right">
				<button type="button" class="btn btn-success" onclick="payapi_js.setup()"><?php esc_attr_e( 'set up account', 'payapi' ); ?></button>
			</div>
			<div class="description"><?php _e('<strong>Almost done!</strong> <br>Configure PayApi Payments and boost your business!', 'payapi');?></div>
		</div>
	</form>
</div>
<?php 
}?>
<div id="payapi_modal" class="payapi_modal payapi_modal_signin">
	<div class="wrapper wow bounceInDown" data-wow-delay="10ms" data-wow-iteration="1" data-wow-duration="0.15s">
		<row>
			<a href="<?=$payapi['brand']['partnerWebUrl']?>" target="_blank"><img src="<?=$payapi['media']?>img/payapi-logo_transparent.png" height="32" width="auto" class="pa_logo wow pulse" data-wow-delay="10.15ms" data-wow-iteration="3" data-wow-duration="0.15s" alt="<?=$payapi['brand']['partnerName']?>"></a>
			<span class="close pull-right"><a href="javascript:payapi_js.close()"><span class="dashicons dashicons-dismiss pa-dashicon"></span></a></span>
		</row>
		<form action="/?rest_route=/payapi/v1/signin" id="payapi_signin" name="payapi_signin" class="payapi_form" method="post">
			<div id="form_top_notice" class="form_top_notice fail"><span class="dashicons dashicons-info"></span> <span id="form_top_notice_text"></span><span class="pull-right dashicons dashicons-dismiss pointing" onclick="javascript:payapi_js.hide('form_top_notice')"></span></div>
			<p><label>Public ID</label>
			<input type="text" id="payapi_public_id" name="payapi_public_id" value="<?=$payapi['public_id']?>" placeholder="PayApi public ID"></p>
			<p><label>API key</label>
			<input type="password" id="payapi_api_key" name="payapi_api_key" value="" placeholder="PayApi API key"></p>
			<p><label>Mode</label>
			<select id="payapi_mode" name="payapi_mode">
				<option value="1"<?php if(isset($payapi['mode']) !== true || $payapi['mode'] != 0) { echo $payapi['option_selected'];} ?>><span class="option">production</span></option>
				<option value="0"<?php if(isset($payapi['mode']) === true && $payapi['mode'] === 0) { echo $payapi['option_selected'];} ?>><span class="option">staging</span></option>
			</select></p>
			<input type="hidden" id="payapi_token" name="payapi_token" value="<?=$payapi['token']?>">
			<br class="clear buttoms">
			<button class="btn" type="button" onclick="payapi_js.login()">sign in</button>
			<?php 
			if ($active === false){ ?>
			<button type="button" class="btn success" onclick="payapi_js.signup()">get a FREE account</button>
			<?php 
			}?>
			<br class="clear">
		</form>
		</optgroup>
	</div>
</div>