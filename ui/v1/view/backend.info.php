<?php if($payapi['public_id'] !== false) { ?>
<div class="pa_notice_top wow bounceInDown" data-wow-delay="50ms" data-wow-iteration="1" data-wow-duration="0.15s" style="background-image: url(<?=$payapi['media']?>img/payapi-banner-1920x120.jpg);">
	<form name="payapi_activate" action="" method="POST">
		<div class="payapi_activate">
			<div class="pull-right">
				<button type="button" class="btn btn-default" onclick="payapi_js.setup()"><?php esc_attr_e( 'settings', 'payapi' ); ?></button>
				<?php 
				if ($active !== false){ ?>
				<button type="button" class="btn btn-success" onclick="payapi_js.signin()"><?php esc_attr_e( 'dashboard', 'payapi' ); ?></button>
				<?php 
				}?>
			</div>
			<div class="description">
				<span class="pull-left smaller">
					<a href="<?=$payapi['brand']['partnerWebUrl']?>" target="_blank"><img src="<?=$payapi['media']?>img/payapi-logo_transparent.png" height="32" width="auto" class="wow pulse" data-wow-delay="10.15ms" data-wow-iteration="3" data-wow-duration="0.15s" alt="<?=$payapi['brand']['partnerName']?>"></a>
					<br>
					<?php _e($payapi['brand']['partnerSlogan'], 'payapi');?>
				</span>
			</div>
		</div>
	</form>
</div>
<?php }?>
<div class="pa_wrapper">
	<h1><?=$payapi['brand']['partnerName']?><span class="smaller">&trade;</span> Payments <?=$payapi['version']?> for WordPress<span class="smaller">&trade;</span><span class="pull-right smaller"><span class="pa_labeled <?=$payapi['status']['style']?>">status</span></span></h1>
	<p>The official PayApi extension for WordPress.</p>
	<p>PayApi is a modern online PCI level 1 certified payments provider offering merchants a secure and scalable payments routing platform for online and mobile consumers. The smart payments routing is done between several payment gateways depending on multiple factors and with pre-processing through advanced anti-fraud system.</p>
	<p><span class="dashicons <?=$payapi['status']['icon']?> pa_<?=$payapi['status']['style']?> pa-bigger pull-left"></span> <?=$payapi['status']['text']?></p>
	<h1>Configuration</h1>
	<p>- On top of this page you can set up or update you account, settings link in the plugins listing can also be used.
		<br>- After clicking on 'settings', please do not use your user information, fill in the form with your <strong>API information</strong>.
		<br>- Account information is properly encoded and saved, you do not need to update it in the meanwhile you do not update your API settings.
		<br>- Once succesfully sign in, a new button is available in your editor, this is 'Payment Button'.
		<br><img src="<?=$payapi['pa_sample']?>" alt="Payment Button">
		<br>- Note if you are using any cache plugin you should need to flush cached files after activating/deactivating payments.
		<br>- 'Payment Button' offers you a new feature to include payment buttons into your WordPress.
	</p>
	<h1>Payment buttons</h1>
	<p>- Note your account needs a payment gateway configured for payments being proccessed properly.
		<br>- You will get an email notification in every payment status update.
		<br>- Payments information is always available in your Payments Dashboard.
	</p>
	<code>
		# payment button code sample<br>
		[(payapi-button)(name=donate,price=1000,quantity=1,currency=EUR,taxes=200,shipping=10,image=https://payapi.io/wp-content/uploads/2015/02/logo_transparent.png)]<br>
		/*<br>
		* @param name (string) title urlencoded<br>
		* @param price (integer) price in cents<br>
		* @param quantity (integer) quantity<br>
		* @param currency (string) [EUR,USD,GBP]<br>
		* @param taxes (integer) taxes in cents<br>
		* @param shipping (integer) shipping &amp; handling price in cents<br>
		* @param image (string) image secure url<br>
		*/
	</code>
	<h1>Legal Disclaimer</h1>
	<p>PayApi seeks to ensure the highest level of quality and security in all its services, but given the multitude of factors and their technical complexity, the services are provided 'as is', without any responsibility of whatsoever for any kind of loss or damage, of any character, from the use of the service provided herein.</p>
	<h1>Support</h1>
	<p><?=$payapi['brand']['partnerSupportInfoL1']?></p>
	<?php 
	if (isset($payapi['brand']['partnerSupportInfoL2']) === true && $payapi['brand']['partnerSupportInfoL2'] != null) {?>
	<p><?=$payapi['brand']['partnerSupportInfoL2']?></p>
	<?php 
	}?>
	<p class="pa_prefooter">&copy;<?=date("Y")?> <a href="<?=$payapi['brand']['partnerWebUrl']?>" target="_blank"><?=$payapi['brand']['partnerName']?></a>, <?=$payapi['brand']['partnerSlogan']?></p>
</div>