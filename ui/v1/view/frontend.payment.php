<form action="<?=$payapi['end_point']?>" id="payapi_payment_<?=$payapi['payment_id']?>" name="payapi_payment_<?=$payapi['payment_id']?>" method="post" target="_blank">
	<input type="hidden" name="public_id" value="<?=$payapi['public_id']?>">
	<input type="hidden" name="payload" value="<?=$payapi['payload']?>">
</form>