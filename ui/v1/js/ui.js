/*
*
*  florin JS engine
*  Version: 1.0.0
*  Updated: 20171123
*  URI: https://payapi.io
*  Author: florin
*. Author URI: https://www.josebamirena.com/
*  Copyright: PayApi.io
*  License: GPL2
*
*/

var payapi_js = {
	api: 'input.payapi.io',
	signed: false,
	settings: {
		debug: true
	},
	ed: null,
	button: null,
	label: {
		modal: "payapi_modal",
		notice: "form_top_notice",
		formPayment: "payapi_payment",
		formSignin: "payapi_signin",
		advanced: "advanced_settings",
		advancedFocus: "product_taxes",
		productFocus: "product_name",
		signingFocus: "payapi_public_id"
	},
	schema: {
		product: {
			"name": {
				"mandatory": true,
				"select": false
			},
			"price": {
				"mandatory": true,
				"select": false
			},
			"quantity": {
				"mandatory": true,
				"select": true
			},
			"currency": {
				"mandatory": true,
				"select": true
			},
			"taxes": {
				"mandatory": false,
				"select": false
			},
			"shipping": {
				"mandatory": false,
				"select": false
			},
			"image": {
				"mandatory": false,
				"select": false
			}
		},
		signin : {
			"public_id": {
				"mandatory": true,
				"select": false
			},
			"api_key": {
				"mandatory": true,
				"select": false
			},
			"mode": {
				"mandatory": true,
				"select": true
			},
			"token": {
				"mandatory": true,
				"select": false
			}
		}
	},
	getId: function _getId(id)
	{
		return document.getElementById(id);
	},
	display: function _display(id)
	{
		jQuery(this.getId(id)).fadeIn("slow").css("display","block");
	},
	hide: function _hide(id)
	{
		jQuery(this.getId(id)).fadeOut("slow", function () {
		    jQuery(payapi_js.getId(id)).css("display","none");
		});
	},
	close: function _close()
	{
		this.hide(this.label.modal);
	},
	visible: function _visible(id, status)
	{
		if (status === true) {
			jQuery(this.getId(id)).fadeIn("slow").css("visibility","visible");
		} else {
			jQuery(this.getId(id)).fadeOut().css("visibility","hidden");
		}
	},
	login: function _login()
	{
		this.reset();
		this.visible(this.label.formSignin);
		this.sleep(1000).then(function () {
			payapi_js.validateSignin();
		    payapi_js.visible(payapi_js.label.formSignin, true);
		});
	},
	curl: function _curl(url, data)
	{
		jQuery.post(url,
    	data,
	    function(data, status, xhr){
	    	return payapi_js.response(data, status, xhr);
	    });
	},
	response: function _response(response, status, xhr)
	{
		payapi_js.debug("response [" + status + "] " + JSON.stringify(response)); //  + ' : ' + JSON.stringify(xhr)
    	if (status === 'success' && response['code'] === 200) {
			this.signed = response.code;
			this.success('login success');
	        payapi_js.debug("response [" + response.code + "] " + this.signed);
	        setTimeout(location.reload.bind(location), 1000);
    	} else {
    		this.error('no valid signin data');
    	}
    	return status;
	},
	code: function _code()
	{
		this.hide(this.label.advanced);
		this.display(this.label.modal);
		this.getId(this.label.productFocus).focus();
	},
	validateProduct: function _validate_product_form()
	{
		var elements = this.elements(this.label.formPayment);
		this.debug('payment form: ' + JSON.stringify(elements));
		var error = false;
		var key = null;
		for (var entry in this.schema.product) {
			key = 'product_' + entry;
			if (error == false) {
				if (this.schema.product[entry]['mandatory'] === false || (elements.hasOwnProperty(key) && elements[key] != undefined && elements[key] != '')) {
					//->
					this.debug(key + ':' + elements[key] )
				} else {
					this.debug('validation error: ' + entry);
					error = true;
				}
			}
		}
		if (error === false) {
			return elements;
		} else {
			return false;
		}
	},
	validateSignin: function _validate_signin_form()
	{
		var public_id = jQuery('#payapi_public_id').val();
		var api_key = jQuery('#payapi_api_key').val();
		var mode = jQuery('#payapi_mode').val();
		var token = jQuery('#payapi_token').val();
		if (public_id != undefined && public_id != '' && api_key != undefined && api_key != '') {
			var data = {public_id:public_id, api_key:api_key, mode:mode, token:token};
			this.curl(this.getId(this.label.formSignin).action,data);
			//-debug->this.debug('public_id: ' + public_id + '; mode: ' + mode + '; token: ' + token);
			return true;
		}
		var msg = 'no valid signin data';
		this.debug('error: ' + msg);
		this.error(msg);
		return false;
	},
	elements: function _form_elements(id) //-> gets form elements
	{
		var elements = document.getElementById(id).elements;
		    	var obj = {};
		for(var i = 0 ; i < elements.length ; i++){
			var item = elements.item(i);
        	obj[item.name] = item.value;
		}
		return obj;

	},
	//-> gets button wp native ed
	handler: function _handler(ed)
	{
		this.ed = ed;
		this.code();
	},
	//-> validates product data and gets button pattern
	button: function _button()
	{
		var validated = this.validateProduct();
		if (validated != false) {
			this.debug('payment form: ' + JSON.stringify(validated));
			var button_pattern = '[' + '(payapi-button)' + '(name=' + validated.product_name + ',price=' + validated.product_price + ',quantity=' + validated.product_quantity + ',currency=' + validated.product_currency + ',taxes=' + validated.product_taxes + ',shipping=' + validated.product_shipping + ',image=' + validated.product_image + ')]';
			this.ed.execCommand("mceInsertContent", 0, button_pattern);
			this.close();			
		} else {
			this.error('no valid product data');
		}

	},
	//-> displays notice error
	error: function _error(msg)
	{
		this.debug('error: ' + msg);
		this.getId(this.label.notice).className = "form_top_notice fail";
		this.getId(this.label.notice + '_text').innerHTML = msg;
		this.display(this.label.notice);
	},
	//-> displays notice success
	success: function _success(msg)
	{
		this.reset();
		this.getId(this.label.notice).className = "form_top_notice success";
		this.getId(this.label.notice + '_text').innerHTML = msg;
		this.display(this.label.notice);
		this.debug('login success');
	},
	reset: function _reset()
	{
		this.hide(this.label.notice);
		this.getId(this.label.notice + '_text').innerHTML = '';
	},
	sleep: function _sleep(ms)
	{
		return new Promise(resolve => setTimeout(resolve, ms));
	},
	advanced: function _display_advanced_settings()
	{
		var advanced_id = this.getId(this.label.advanced);
		var status = advanced_id.style.display;
		if (status !== 'block') {
			this.display(this.label.advanced);
			this.getId(this.label.advancedFocus).focus();
		} else {
			this.hide(this.label.advanced);
			this.getId(this.label.productFocus).focus();
		}
	},
	//-> to unify
	signup: function _signup()
	{
		var domain = this.api; 
		var mode = jQuery('#payapi_mode option:selected').val();
		console.log('signin into ' + jQuery('#payapi_mode option:selected').text());
		if (mode != 1) {
			domain = 'staging-' + this.api;
		}
		this.getId(this.label.signingFocus).focus();
		window.open('https://' + domain + '/#!/signup');
		return false;
	},
	signin: function _signin()
	{
		var domain = this.api; 
		var mode = jQuery('#payapi_mode option:selected').val();
		console.log('signin into ' + jQuery('#payapi_mode option:selected').text());
		if (mode != 1) {
			domain = 'staging-' + this.api;
		}
		this.getId(this.label.signingFocus).focus();
		window.open('https://' + domain + '/#!/signin');
		return false;
	},
	//->
	setup: function _setup()
	{
		this.display(this.label.modal);
		this.getId(this.label.signingFocus).focus();
	},
	filterPrice: function _filterPrice(event)
	{
		var charCode = (event.which) ? event.which : event.keyCode;
		var caller = this.getId(event.target.id);
		var value = caller.value;
		var full = false;
		this.debug(charCode + ':' + event.target.id + ':' + value);
		if (value.match(/\./i) != null) {
			var found = value.match(/\./i);
			var splited = value.split('.');
			if (splited[1].length >= 2) {
				full = true;
			}
		}
		if (full === false && (charCode === 45 || charCode === 46 || (charCode >= 48 && charCode <= 57))) {
			if ((charCode >= 49 && charCode <= 57)) {
				return true;
			} else if (charCode == 48) {
				//->
				return true;
			}  else if (charCode === 45 && value == '') {
				return true;
			} else if (charCode === 46  && value != '' && value.match(/\./i) == null) {
				return true;
			}
		}
		return false;
	},
	isImage: function _isImage(imageUrl) {
		this.isImageCallback(imageUrl, function(exists) {
			return exists;
		});
	},
	isImageCallback: function _isImageCallback(imageUrl, callback) {
		var img = new Image();
		img.onload = function() {
			callback(true);
		};
		img.onerror = function() {
			callback(false);
		};
		img.src = imageUrl;
	},
	debug: function _debug(debug) {
		if (this.settings.debug === true) {
			return console.log(debug);
		}
		return true;
	}
}

document.addEventListener("DOMContentLoaded", function(event) {
	new WOW().init();
	console.log('WOW v1.1.3');
	console.log('PayApi JS');
});