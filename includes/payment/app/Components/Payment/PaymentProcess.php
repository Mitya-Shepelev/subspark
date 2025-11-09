<?php 
namespace App\Components\Payment;

class PaymentProcess
{
    /**
     * @var paytmService - Paytm Service
     */
    protected $paytmService;
 
    /**
     * @var iyzicoService - Iyzico Service
     */
    protected $iyzicoService;

    /**
     * @var paypalService - Paypal Service
     */
    protected $paypalService;

    /**
     * @var paystackService - Paystack Service
     */
    protected $paystackService;

    /**
     * @var razorpayService - Razorpay Service
     */
    protected $razorpayService;

    /**
     * @var stripeService - Stripe Service
     */
    protected $stripeService;

    /**
     * @var authorizeNetService - Authorize.Net Service
     */
    protected $authorizeNetService;

    /**
     * @var bitPayService - BitPay Service
     */
    protected $bitPayService;

    /**
     * @var mercadopagoService - Mercadopago Service
     */
    protected $mercadopagoService;
 

    public function __construct($paytmService, $iyzicoService, $paypalService, $paystackService, $razorpayService, $stripeService, $authorizeNetService, $bitPayService, $mercadopagoService)
    {  
        $this->paytmService          = $paytmService; 
        $this->iyzicoService         = $iyzicoService;
        $this->paypalService         = $paypalService;
        $this->paystackService       = $paystackService;
        $this->razorpayService       = $razorpayService;
        $this->stripeService         = $stripeService;
        $this->authorizeNetService   = $authorizeNetService;
        $this->bitPayService         = $bitPayService;
        $this->mercadopagoService    = $mercadopagoService; 
    }

    public function getPaymentData($request)
    {
        $processResponse = [];
        if ($request['paymentOption'] == 'instamojo') {
            //get instamojo request data
            $processResponse = $this->instamojoService->processInstamojoRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'iyzico') {
            //get iyzico request data
            $processResponse = $this->iyzicoService->processIyzicoRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'paypal') {
            //get paypal request data
            $processResponse = $this->paypalService->processPaypalRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'stripe') {
            // Get Stripe request Data
            $processResponse = $this->stripeService->processStripeRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'paystack') {
            // Get Stripe request Data
            $processResponse = $this->paystackService->processPaystackRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'razorpay') {
            // Get Stripe request Data
            $processResponse = $this->razorpayService->processRazorpayRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'authorize-net') {
            $processResponse = $this->authorizeNetService->processAuthorizeNetRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'bitpay') {
            $processResponse = $this->bitPayService->processBitPayRequest($request);
            return $processResponse;
        } elseif ($request['paymentOption'] == 'mercadopago') {
            $processResponse = $this->mercadopagoService->processMercadopagoRequest($request);
            return $processResponse;
        } 
    }
}
