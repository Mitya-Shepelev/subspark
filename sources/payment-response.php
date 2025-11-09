<?php
require_once 'includes/inc.php';
require_once 'includes/payment/vendor/autoload.php';
if (!defined('INORA_METHODS_CONFIG')) {
	define('INORA_METHODS_CONFIG', realpath('includes/payment/paymentConfig.php'));
}
$payment_time = time();
use App\Components\Payment\BitPayResponse;
use App\Components\Payment\IyzicoResponse;
use App\Components\Payment\PaypalIpnResponse;
use App\Components\Payment\PaytmResponse;
use App\Components\Payment\StripeResponse;
use App\Components\Payment\MercadopagoResponse;

// Get Config Data
$configData = configItem();
// Get Request Data when payment success or failed
$requestData = $_REQUEST;

// Check payment Method is paytm
if ($requestData['paymentOption'] == 'paytm') {
	// Get Payment Response instance
	$paytmResponse = new PaytmResponse();

	// Fetch payment data using payment response instance
	$paytmData = $paytmResponse->getPaytmPaymentData($requestData);

	// Check if payment status is success
	if ($paytmData['STATUS'] == 'TXN_SUCCESS') {

		// Create payment success response data.
		$paymentResponseData = [
			'status' => true,
			'rawData' => $paytmData,
			'data' => preparePaymentData($paytmData['ORDERID'], $paytmData['TXNAMOUNT'], $paytmData['TXNID'], 'paytm'),
		];
		// Send data to payment response.
		paymentResponse($paymentResponseData);
	} else {
		// Create payment failed response data.
		$paymentResponseData = [
			'status' => false,
			'rawData' => $paytmData,
			'data' => preparePaymentData($paytmData['ORDERID'], $paytmData['TXNAMOUNT'], $paytmData['TXNID'], 'paytm'),
		];
		// Send data to payment response function
		paymentResponse($paymentResponseData);
	}
// Check payment method is instamojo
} else if ($requestData['paymentOption'] == 'iyzico') {

	// Check if payment status is success for iyzico.
	if ($_REQUEST['status'] == 'success') {
		// Get iyzico response.
		$iyzicoResponse = new IyzicoResponse();

		// fetch payment data using iyzico response instance.
		$iyzicoData = $iyzicoResponse->getIyzicoPaymentData($requestData);
		$rawResult = json_decode($iyzicoData->getRawResult(), true);

		// Check if iyzico payment data is success
		// Then create a array for success data
		if ($iyzicoData->getStatus() == 'success') {
			$paymentResponseData = [
				'status' => true,
				'rawData' => (array) $iyzicoData,
				'data' => preparePaymentData($requestData['orderId'], $rawResult['price'], $rawResult['conversationId'], 'iyzico'),
			];
			$pData = DB::one("SELECT * FROM i_user_payments WHERE order_key = ?", [$requestData['orderId']]);
			$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
			$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
			$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
			if(!empty($userPayedPlanID)){
				$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
				$planAmount = $pAData['plan_amount'];
				DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
				DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE order_key = ?", [$requestData['orderId']]);
            }else if(!empty($productID)){
				$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
				$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
				$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
				$adminEarning = ($adminFee * $productPrice) / 100;
				$userEarning = $productPrice - $adminEarning;
				DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND order_key = ?",
				    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID, $requestData['orderId']]
				);
				DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
			}
			// Send data to payment response
			paymentResponse($paymentResponseData);
			// If payment failed then create data for failed
		} else {
			DB::exec("DELETE FROM i_user_payments WHERE order_key = ?", [$requestData['orderId']]);
			// Prepare failed payment data
			$paymentResponseData = [
				'status' => false,
				'rawData' => (array) $iyzicoData,
				'data' => preparePaymentData($requestData['orderId'], $rawResult['price'], $rawResult['conversationId'], 'iyzico'),
			];
			// Send data to payment response
			paymentResponse($paymentResponseData);
		}
		// Check before 3d payment process payment failed
	} else {
		// Prepare failed payment data
		$paymentResponseData = [
			'status' => false,
			'rawData' => $requestData,
			'data' => preparePaymentData($requestData['orderId'], $rawResult['price'], null, 'iyzico'),
		];
		// Send data to process response
		paymentResponse($paymentResponseData);
	}

// Check Paypal payment process
} else if ($requestData['paymentOption'] == 'paypal') {
	// Get instance of paypal
	$paypalIpnResponse = new PaypalIpnResponse();

	// fetch paypal payment data
	$paypalIpnData = $paypalIpnResponse->getPaypalPaymentData();
	$rawData = json_decode($paypalIpnData, true);
	// Note : IPN and redirects will come here
	// Check if payment status exist and it is success
	if (isset($requestData['PayerID'])) {

		// Then create a data for success paypal data
		$paymentResponseData = [
			'status' => true,
			'rawData' => (array) $paypalIpnData,
			'data' => preparePaymentData($rawData['invoice'], $rawData['payment_gross'], $rawData['txn_id'], 'paypal'),
		];
		// Send data to payment response function for further process
		paymentResponse($paymentResponseData);
		$pData = DB::one("SELECT * FROM i_user_payments WHERE payment_type IN('point','product') AND payment_status = 'pending' AND payment_option = 'paypal' AND payer_iuid_fk = ?", [$userID]);
		$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
		$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
		$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
		if(!empty($userPayedPlanID)){
			$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
			$planAmount = $pAData['plan_amount'];
			DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $userID]);
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE payer_iuid_fk = ? AND payment_type = 'point' AND payment_option = 'paypal'", [$userID]);
		}else if(!empty($productID)){
			$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
			$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
			$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
			$adminEarning = ($adminFee * $productPrice) / 100;
			$userEarning = $productPrice - $adminEarning;
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND payment_type = 'product' AND payment_status = 'pending' AND payment_option = 'paypal'",
			    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID]
			);
			DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
		}
		// Check if payment not successfull
	} else {
		DB::exec("DELETE FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_option = 'paypal' AND payment_type IN('point','product') AND payment_status = 'pending'", [$userID]);
		// Prepare payment failed data
		$paymentResponseData = [
			'status' => false,
			'rawData' => [],
			'data' => preparePaymentData($rawData['invoice'], $rawData['payment_gross'], null, 'paypal'),
		];
		// Send data to payment response function for further process
		paymentResponse($paymentResponseData);
	}

// Check Paystack payment process
} else if ($requestData['paymentOption'] == 'paystack') {

	$requestData = json_decode($requestData['response'], true);

	// Check if status key exists and payment is successfully completed
	if (isset($requestData['status']) and $requestData['status'] == "success") {
		// Create data for payment success
		$paymentResponseData = [
			'status' => true,
			'rawData' => $requestData,
			'data' => preparePaymentData($requestData['data']['reference'], $requestData['data']['amount'], $requestData['data']['reference'], 'paystack'),
		];
		$pData = DB::one("SELECT * FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_status = 'pending' AND payment_option = 'paystack' AND payment_type IN('point','product')", [$userID]);
		$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
		$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
		$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
		if(!empty($userPayedPlanID)){
			$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
			$planAmount = $pAData['plan_amount'];
			DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE payer_iuid_fk = ? AND payment_option = 'paystack' AND payment_type = 'point'", [$userID]);
		}else if(!empty($productID)){
			$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
			$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
			$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
			$adminEarning = ($adminFee * $productPrice) / 100;
            $userEarning = $productPrice - $adminEarning;
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND payment_status = 'pending' AND payment_option = 'paystack' AND payment_type = 'point'",
			    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID]
			);
			DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
		}
		// Send data to payment response for further process
		paymentResponse($paymentResponseData);
		// If paystack payment is failed
	} else {
		// Prepare data for failed payment
		$paymentResponseData = [
			'status' => false,
			'rawData' => $requestData,
			'data' => preparePaymentData($requestData['data']['reference'], $requestData['data']['amount'], $requestData['data']['reference'], 'paystack'),
		];
		DB::exec("DELETE FROM i_user_payments WHERE payer_iuid_fk = ? AND payment_option = 'paystack' AND payment_type IN('point','product') AND payment_status = 'pending'", [$userID]);
		// Send data to payment response to further process
		paymentResponse($paymentResponseData);
	}

// Check Stripe payment process
} else if ($requestData['paymentOption'] == 'stripe') {

	$stripeResponse = new StripeResponse();

	$stripeData = $stripeResponse->retrieveStripePaymentData($requestData['stripe_session_id']);

	// Check if payment charge status key exist in stripe data and it success
	if (isset($stripeData['status']) and $stripeData['status'] == "succeeded") {
		// Prepare data for success
		$paymentResponseData = [
			'status' => true,
			'rawData' => $stripeData,
			'data' => preparePaymentData($stripeData->charges->data[0]['balance_transaction'], $stripeData->amount, $stripeData->charges->data[0]['balance_transaction'], 'stripe'),
		];
		$pData = DB::one("SELECT * FROM i_user_payments WHERE order_key = ?", [$requestData['orderId']]);
		$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
		$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
		$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
		if(!empty($userPayedPlanID)){
			$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
			$planAmount = $pAData['plan_amount'];
			DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE payer_iuid_fk = ? AND order_key = ?", [$payerUserID, $requestData['orderId']]);
		}else if(!empty($productID)){
			$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
			$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
			$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
			$adminEarning = ($adminFee * $productPrice) / 100;
            $userEarning = $productPrice - $adminEarning;
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND order_key = ?",
			    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID, $requestData['orderId']]
			);
			DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
		}

		// Check if stripe data is failed
	} else {
		// Prepare failed payment data
		$paymentResponseData = [
			'status' => false,
			'rawData' => $stripeData,
			'data' => preparePaymentData($requestData['orderId'], $stripeData->amount, null, 'stripe'),
		];
		DB::exec("DELETE FROM i_user_payments WHERE order_key = ?", [$requestData['orderId']]);
	}
	// Send data to payment response for further process
	paymentResponse($paymentResponseData);

// Check Razorpay payment process
} else if ($requestData['paymentOption'] == 'razorpay') {
	$orderId = $requestData['orderId'];

	$requestData = json_decode($requestData['response'], true);

	// Check if razorpay status exist and status is success
	if (isset($requestData['status']) and $requestData['status'] == 'captured') {
		// prepare payment data
		$paymentResponseData = [
			'status' => true,
			'rawData' => $requestData,
			'data' => preparePaymentData($orderId, $requestData['amount'], $requestData['id'], 'razorpay'),
		];
		$pData = DB::one("SELECT * FROM i_user_payments WHERE order_key = ?", [$orderId]);
		$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
		$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
		$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
		if(!empty($userPayedPlanID)){
			$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
			$planAmount = $pAData['plan_amount'];
			DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE payer_iuid_fk = ? AND order_key = ?", [$payerUserID, $orderId]);
		}else if(!empty($productID)){
		$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
		$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
		$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
		$adminEarning = ($adminFee * $productPrice) / 100;
		$userEarning = $productPrice - $adminEarning;
		DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND order_key = ?",
		    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID, $orderId]
		);
		DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
		}
		// send data to payment response
		paymentResponse($paymentResponseData);
		// razorpay status is failed
	} else {
		// prepare payment data for failed payment
		$paymentResponseData = [
			'status' => false,
			'rawData' => $requestData,
			'data' => preparePaymentData($orderId, $requestData['amount'], $requestData['id'], 'razorpay'),
		];
		DB::exec("DELETE FROM i_user_payments WHERE order_key = ?", [$orderId]);
		// send data to payment response
		paymentResponse($paymentResponseData);
	}
} else if ($requestData['paymentOption'] == 'authorize-net') {
	$orderId = $requestData['order_id'];

	$requestData = json_decode($requestData['response'], true);

	// Check if razorpay status exist and status is success
	if (isset($requestData['status']) and $requestData['status'] == 'success') {
		// prepare payment data
		$paymentResponseData = [
			'status' => true,
			'rawData' => $requestData,
			'data' => preparePaymentData($orderId, $requestData['amount'], $requestData['transaction_id'], 'authorize-net'),
		];
		$pData = DB::one("SELECT * FROM i_user_payments WHERE order_key = ?", [$requestData['order_id']]);
		$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
		$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
		$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
		if(!empty($userPayedPlanID)){
			$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
			$planAmount = $pAData['plan_amount'];
			DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE order_key = ?", [$requestData['order_id']]);
        }else if(!empty($productID)){
			$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
			$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
			$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
			$adminEarning = ($adminFee * $productPrice) / 100;
			$userEarning = $productPrice - $adminEarning;
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND order_key = ?",
			    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID, $requestData['order_id']]
			);
			DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
		}
		// send data to payment response
		paymentResponse($paymentResponseData);
		// razorpay status is failed
	} else {
		// prepare payment data for failed payment
		$paymentResponseData = [
			'status' => false,
			'rawData' => $requestData,
			'data' => preparePaymentData($orderId, $requestData['amount'], $requestData['transaction_id'], 'authorize-net'),
		];
		DB::exec("DELETE FROM i_user_payments WHERE order_key = ?", [$requestData['order_id']]);
		// send data to payment response
		paymentResponse($paymentResponseData);
	}
}else if ($requestData['paymentOption'] == 'mercadopago') {
    if ($requestData['collection_status'] == 'approved') {
        $paymentResponseData = [
            'status'   => true,
            'rawData'   => $requestData,
            'data'     => preparePaymentData($requestData['order_id'], $requestData['amount'], $requestData['collection_id'], 'mercadopago')
        ];
		$pData = DB::one("SELECT * FROM i_user_payments WHERE order_key = ?", [$requestData['order_id']]);
		$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
		$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
		$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
		if(!empty($userPayedPlanID)){
			$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
			$planAmount = $pAData['plan_amount'];
			DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE order_key = ?", [$requestData['order_id']]);
        }else if(!empty($productID)){
			$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
			$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
			$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
			$adminEarning = ($adminFee * $productPrice) / 100;
            $userEarning = $productPrice - $adminEarning;
			DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND order_key = ?",
			    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID, $requestData['order_id']]
			);
			DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
		}
    } elseif ($requestData['collection_status'] == 'pending') {
        $paymentResponseData = [
            'status'   => 'pending',
            'rawData'   => $requestData,
            'data'     => preparePaymentData($requestData['order_id'], $requestData['amount'], $requestData['collection_id'], 'mercadopago')
        ];
		DB::exec("DELETE FROM i_user_payments WHERE order_key = ?", [$requestData['order_id']]);
    } else {
        $paymentResponseData = [
            'status'   => false,
            'rawData'   => $requestData,
            'data'     => preparePaymentData($requestData['order_id'], $requestData['amount'], $requestData['collection_id'], 'mercadopago')
        ];
		DB::exec("DELETE FROM i_user_payments WHERE order_key = ?", [$requestData['order_id']]);
    }
    paymentResponse($paymentResponseData);
} else if ($requestData['paymentOption'] == 'mercadopago-ipn') {
    $mercadopagoResponse = new MercadopagoResponse;
    $mercadopagoIpnData = $mercadopagoResponse->getMercadopagoPaymentData($requestData);

    $rawPostData = json_decode(file_get_contents('php://input'), true);

	if(isset($rawPostData["topic"])){
		if($rawPostData["topic"] == "merchant_order"){

			$call_merchant_order_id = $rawPostData["resource"];

			$token_mp = DB::one("SELECT * FROM i_payment_methods WHERE payment_method_id = 1");


			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, $call_merchant_order_id);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$headers = array();
			$headers[] = 'Authorization: Bearer '.$token_mp["mercadopago_live_access_id"]; //
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
            if (curl_errno($ch)) {
                    echo str_replace('{error}', curl_error($ch), $LANG['generic_error_prefixed']);
            }
			curl_close($ch);

			$data = json_decode($result, true);


			if(isset($data["order_status"])){
				if($data["order_status"] == "paid"){
					$paymentResponseData = [
							'status'   => true,
							'rawData'   => $requestData,
							'data'     => preparePaymentData($requestData['order_id'], $requestData['amount'], $requestData['id'], 'mercadopago')
					];
					$pData = DB::one("SELECT * FROM i_user_payments WHERE order_key = ?", [$requestData['order_id']]);
					$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
					$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
					$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
					if(!empty($pData)){
						if(!empty($userPayedPlanID)){
							$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
							$planAmount = $pAData['plan_amount'];
							DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
							DB::exec("UPDATE i_user_payments SET payment_status = 'paid' WHERE order_key = ?", [$requestData['order_id']]);

						}else if(!empty($productID)){

							$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
							$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
							$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
							$adminEarning = ($adminFee * $productPrice) / 100;
										$userEarning = $productPrice - $adminEarning;
							DB::exec("UPDATE i_user_payments SET payment_status = 'paid' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND order_key = ?",
							    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID, $requestData['order_id']]
							);
							DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
						}

					}

				}
				paymentResponse($paymentResponseData);
			}

		}
	}
} else if ($requestData['paymentOption'] == 'bitpay') {
	// prepare payment data
	$paymentResponseData = [
		'status' => true,
		'rawData' => $requestData,
		'data' => preparePaymentData($requestData['orderId'], $requestData['amount'], $requestData['orderId'], 'bitpay'),
	];
	$pData = DB::one("SELECT * FROM i_user_payments WHERE order_key = ?", [$requestData['orderId']]);
	$userPayedPlanID = isset($pData['credit_plan_id']) ? $pData['credit_plan_id'] : NULL;
	$payerUserID = isset($pData['payer_iuid_fk']) ? $pData['payer_iuid_fk'] : NULL;
	$productID = isset($pData['paymet_product_id']) ? $pData['paymet_product_id'] : NULL;
	if(!empty($userPayedPlanID)){
		$pAData = DB::one("SELECT * FROM i_premium_plans WHERE plan_id = ?", [$userPayedPlanID]);
		$planAmount = $pAData['plan_amount'];
		DB::exec("UPDATE i_users SET wallet_points = wallet_points + ? WHERE iuid = ?", [$planAmount, $payerUserID]);
		DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE order_key = ?", [$requestData['orderId']]);
	}else if(!empty($productID)){
		$productData = DB::one("SELECT * FROM i_user_product_posts WHERE pr_id = ?", [$productID]);
		$productPrice = isset($productData['pr_price']) ? $productData['pr_price'] : NULL;
		$productOwnerID = isset($productData['iuid_fk']) ? $productData['iuid_fk'] : NULL;
		$adminEarning = ($adminFee * $productPrice) / 100;
		$userEarning = $productPrice - $adminEarning;
		DB::exec("UPDATE i_user_payments SET payment_status = 'pending' , payed_iuid_fk = ?, amount = ?, fee = ?, admin_earning = ?, user_earning = ? WHERE payer_iuid_fk = ? AND order_key = ?",
		    [$productOwnerID, $productPrice, $adminFee, $adminEarning, $userEarning, $payerUserID, $requestData['orderId']]
		);
		DB::exec("UPDATE i_users SET wallet_money = wallet_money + ? WHERE iuid = ?", [$userEarning, $productOwnerID]);
	}
	// send data to payment response
	paymentResponse($paymentResponseData);
} else if ($requestData['paymentOption'] == 'bitpay-ipn') {
	$bitpayResponse = new BitPayResponse;
	$rawPostData = file_get_contents('php://input');
	$ipnData = $bitpayResponse->getBitPayPaymentData($rawPostData);
	if ($ipnData['status'] == 'success') {
		// code here
		DB::exec("UPDATE i_user_payments SET payment_status = 'pending' WHERE order_key = ?", [$requestData['orderId']]);
	} else {
		// code here
		DB::exec("DELETE FROM i_user_payments WHERE order_key = ?", [$requestData['orderId']]);
	}
}

/*
 * This payment used for get Success / Failed data for any payment method.
 *
 * @param array $paymentResponseData - contains : status and rawData
 *
 */
function paymentResponse($paymentResponseData) {
	// payment status success
	if ($paymentResponseData['status']) {
		// Show payment success page or do whatever you want, like send email, notify to user etc
		header('Location: ' . getAppUrl('payment-success.php'));
		//  var_dump($paymentResponseData);
	} else {
		// Show payment error page or do whatever you want, like send email, notify to user etc
		header('Location: ' . getAppUrl('payment-failed.php'));
	}
}

/*
 * Prepare Payment Data.
 *
 * @param array $paymentData
 *
 */
function preparePaymentData($orderId, $amount, $txnId, $paymentGateway) {
	return [
		'order_id' => $orderId,
		'amount' => $amount,
		'payment_reference_id' => $txnId,
		'payment_gatway' => $paymentGateway,
	];
}
