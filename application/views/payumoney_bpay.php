<?php


/* $merchant_key  = "gtKFFx"; // test 
	$salt          = "eCwWELxi"; //test
	$payu_base_url = "https://test.payu.in"; // For Test environment */
	
$merchant_key=$payumoneydetail["MERCHANT_KEY"];
$salt=$payumoneydetail["SALT"];
$MERCHANT_ID=$payumoneydetail["MERCHANT_ID"];
$payu_base_url=$payumoneydetail["URL"];

$action        = '';
	
	$posted = array();
	if(!empty($_POST)) {
	  foreach($_POST as $key => $value) {    
	    $posted[$key] = $value; 
	  }
	}

	$formError = 0;
	if(empty($posted['txnid'])) {
	  $txnid = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
	} else {
	  $txnid = $posted['txnid'];
	}

	$hash= '';
	$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

	if(empty($posted['hash']) && sizeof($posted) > 0) {
	  if(
          empty($posted['key'])
          || empty($posted['txnid'])
          || empty($posted['amount'])
          || empty($posted['firstname'])
          || empty($posted['email'])
          || empty($posted['phone'])
          || empty($posted['productinfo'])
		  		|| empty($posted['udf1'])
          || empty($posted['surl'])
          || empty($posted['furl'])
		  || empty($posted['service_provider'])
	  ){
	    $formError = 1;

	  } else {
	   	$hashVarsSeq = explode('|', $hashSequence);
	    $hash_string = '';	
		foreach($hashVarsSeq as $hash_var) {
	      $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
	      $hash_string .= '|';
	    }
	    $hash_string .= $salt;
	    $hash = strtolower(hash('sha512', $hash_string));
	    $action = $payu_base_url . '/_payment';
	  }
	} elseif(!empty($posted['hash'])) {
	  $hash = $posted['hash'];
	  $action = $payu_base_url . '/_payment';
	}
	
$finalInputArr=array("bid" => $bid,"month"=>$month);
$Prod_info = json_encode($finalInputArr);
?> 

  <script>
    var hash = '<?php echo $hash; ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
<section class="padding-top-30 padding-btm-30 dis-t">

<div class="container">

<div> 
    <form action="<?php echo $action; ?>" method="post" name="payuForm" id="payuForm"/>
		<input type="hidden" name="key" value="<?php echo $merchant_key ?>" />
		<input type="hidden" name="hash" value="<?php echo $hash ?>"/>
		<input type="hidden" name="txnid" value="<?php echo $txnid ?>" />
		<input type="hidden" name="amount" value="<?= $payamount ?>" />
		<input type="hidden" name="udf1" value="11" />
		<input type="hidden" name="udf2" value="12" />
		<input type="hidden" name="surl" value="<?= base_url()."Airmed_tech_report/payumoney_suceess?bid=".$bid."&month=".$month; ?>" />
		<input type="hidden" name="furl" value="<?= base_url()."Airmed_tech_report/index?bid=".$bid; ?>" />
		<input type="hidden" name="productinfo" value="Airmed Tech payment" />
		<input type="hidden" name="firstname" value="<?= $user->name ?>" />
		<input type="hidden" name="email" value="payment@airmedlabs.com" />
		<input type="hidden" name="phone" value="<?= $user->phone; ?>" />
		 <input type="hidden" name="service_provider" value="payu_paisa" />
		<input type="hidden" name="address1" value="payu_paisa">
		<center><span>This window will redirect in 5 seconds automatically</span></center>
		
		<center><input class="pr-btn form-control" type="submit" id="final_checkout" value="PROCEED TO CHECKOUT" /></center>
    </form>
</div>

</div>

</section>
<script>
window.onload=function(){ 
	
    window.setTimeout(function() { document.payuForm.submit(); }, 5000);
};
</script>
	 <script>
    var hash = '<?php echo $hash; ?>';
	var action = '<?php echo $action; ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
	if(hash != "" && action !=""){
		var payuForm = document.forms.payuForm;
      payuForm.submit();
	}
	
  </script>
