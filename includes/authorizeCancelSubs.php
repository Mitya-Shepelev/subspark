<?php
  require 'AuthorizeNet/vendor/autoload.php'; 
  use net\authorize\api\contract\v1 as AnetAPI;
  use net\authorize\api\controller as AnetController;
  
  define("AUTHORIZENET_LOG_FILE", "phplog");

function cancelSubscription($subscriptionId,$autName, $autKey)
{
    /* Create a merchantAuthenticationType object with authentication details
       retrieved from the constants file */
    $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
    $merchantAuthentication->setName($autName);   
	$merchantAuthentication->setTransactionKey($autKey);  
    
    // Set the transaction's refId
    $refId = 'ref' . time();

    $request = new AnetAPI\ARBCancelSubscriptionRequest();
    $request->setMerchantAuthentication($merchantAuthentication);
    $request->setRefId($refId);
    $request->setSubscriptionId($subscriptionId);

    $controller = new AnetController\ARBCancelSubscriptionController($request);

    $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

    if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
    {
        $successMessages = $response->getMessages()->getMessage();
        $status = '200';
        
     }
    else
    {
        $status =  '404';
        
    }

    return $response;

  } 
?>