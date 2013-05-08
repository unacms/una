<?php 

/**
 * AUTO GENERATED code for PayPalAPIInterfaceService
 */
class PayPalAPIInterfaceServiceService extends PPBaseService {

	// Service Version
	private static $SERVICE_VERSION = "98.0";

	// Service Name
	private static $SERVICE_NAME = "PayPalAPIInterfaceService";

    // SDK Name
	protected static $SDK_NAME = "merchant-php-sdk";
	
	// SDK Version
	protected static $SDK_VERSION = "2.2.98";


	public function __construct() {
		parent::__construct(self::$SERVICE_NAME, 'SOAP', array('PPMerchantServiceHandler'));
        parent::$SDK_NAME    = self::$SDK_NAME ;
        parent::$SDK_VERSION = self::$SDK_VERSION;
	}

	private function setStandardParams(AbstractRequestType $request) {
		if ($request->Version == NULL) {
			$request->Version = self::$SERVICE_VERSION;
		}
	}

	/**
	 * Service Call: RefundTransaction
	 * @param RefundTransactionReq $refundTransactionReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return RefundTransactionResponseType
	 * @throws APIException
	 */
	public function RefundTransaction($refundTransactionReq, $apiCredential = NULL) {
		$this->setStandardParams($refundTransactionReq->RefundTransactionRequest);
		$ret = new RefundTransactionResponseType();
		$resp = $this->call('PayPalAPI', 'RefundTransaction', $refundTransactionReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: InitiateRecoup
	 * @param InitiateRecoupReq $initiateRecoupReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return InitiateRecoupResponseType
	 * @throws APIException
	 */
	public function InitiateRecoup($initiateRecoupReq, $apiCredential = NULL) {
		$this->setStandardParams($initiateRecoupReq->InitiateRecoupRequest);
		$ret = new InitiateRecoupResponseType();
		$resp = $this->call('PayPalAPI', 'InitiateRecoup', $initiateRecoupReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: CompleteRecoup
	 * @param CompleteRecoupReq $completeRecoupReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return CompleteRecoupResponseType
	 * @throws APIException
	 */
	public function CompleteRecoup($completeRecoupReq, $apiCredential = NULL) {
		$this->setStandardParams($completeRecoupReq->CompleteRecoupRequest);
		$ret = new CompleteRecoupResponseType();
		$resp = $this->call('PayPalAPI', 'CompleteRecoup', $completeRecoupReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: CancelRecoup
	 * @param CancelRecoupReq $cancelRecoupReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return CancelRecoupResponseType
	 * @throws APIException
	 */
	public function CancelRecoup($cancelRecoupReq, $apiCredential = NULL) {
		$this->setStandardParams($cancelRecoupReq->CancelRecoupRequest);
		$ret = new CancelRecoupResponseType();
		$resp = $this->call('PayPalAPI', 'CancelRecoup', $cancelRecoupReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetTransactionDetails
	 * @param GetTransactionDetailsReq $getTransactionDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetTransactionDetailsResponseType
	 * @throws APIException
	 */
	public function GetTransactionDetails($getTransactionDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getTransactionDetailsReq->GetTransactionDetailsRequest);
		$ret = new GetTransactionDetailsResponseType();
		$resp = $this->call('PayPalAPI', 'GetTransactionDetails', $getTransactionDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: BillUser
	 * @param BillUserReq $billUserReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return BillUserResponseType
	 * @throws APIException
	 */
	public function BillUser($billUserReq, $apiCredential = NULL) {
		$this->setStandardParams($billUserReq->BillUserRequest);
		$ret = new BillUserResponseType();
		$resp = $this->call('PayPalAPI', 'BillUser', $billUserReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: TransactionSearch
	 * @param TransactionSearchReq $transactionSearchReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return TransactionSearchResponseType
	 * @throws APIException
	 */
	public function TransactionSearch($transactionSearchReq, $apiCredential = NULL) {
		$this->setStandardParams($transactionSearchReq->TransactionSearchRequest);
		$ret = new TransactionSearchResponseType();
		$resp = $this->call('PayPalAPI', 'TransactionSearch', $transactionSearchReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: MassPay
	 * @param MassPayReq $massPayReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return MassPayResponseType
	 * @throws APIException
	 */
	public function MassPay($massPayReq, $apiCredential = NULL) {
		$this->setStandardParams($massPayReq->MassPayRequest);
		$ret = new MassPayResponseType();
		$resp = $this->call('PayPalAPI', 'MassPay', $massPayReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: BillAgreementUpdate
	 * @param BillAgreementUpdateReq $billAgreementUpdateReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return BAUpdateResponseType
	 * @throws APIException
	 */
	public function BillAgreementUpdate($billAgreementUpdateReq, $apiCredential = NULL) {
		$this->setStandardParams($billAgreementUpdateReq->BAUpdateRequest);
		$ret = new BAUpdateResponseType();
		$resp = $this->call('PayPalAPI', 'BillAgreementUpdate', $billAgreementUpdateReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: AddressVerify
	 * @param AddressVerifyReq $addressVerifyReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return AddressVerifyResponseType
	 * @throws APIException
	 */
	public function AddressVerify($addressVerifyReq, $apiCredential = NULL) {
		$this->setStandardParams($addressVerifyReq->AddressVerifyRequest);
		$ret = new AddressVerifyResponseType();
		$resp = $this->call('PayPalAPI', 'AddressVerify', $addressVerifyReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: EnterBoarding
	 * @param EnterBoardingReq $enterBoardingReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return EnterBoardingResponseType
	 * @throws APIException
	 */
	public function EnterBoarding($enterBoardingReq, $apiCredential = NULL) {
		$this->setStandardParams($enterBoardingReq->EnterBoardingRequest);
		$ret = new EnterBoardingResponseType();
		$resp = $this->call('PayPalAPI', 'EnterBoarding', $enterBoardingReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetBoardingDetails
	 * @param GetBoardingDetailsReq $getBoardingDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetBoardingDetailsResponseType
	 * @throws APIException
	 */
	public function GetBoardingDetails($getBoardingDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getBoardingDetailsReq->GetBoardingDetailsRequest);
		$ret = new GetBoardingDetailsResponseType();
		$resp = $this->call('PayPalAPI', 'GetBoardingDetails', $getBoardingDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: CreateMobilePayment
	 * @param CreateMobilePaymentReq $createMobilePaymentReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return CreateMobilePaymentResponseType
	 * @throws APIException
	 */
	public function CreateMobilePayment($createMobilePaymentReq, $apiCredential = NULL) {
		$this->setStandardParams($createMobilePaymentReq->CreateMobilePaymentRequest);
		$ret = new CreateMobilePaymentResponseType();
		$resp = $this->call('PayPalAPI', 'CreateMobilePayment', $createMobilePaymentReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetMobileStatus
	 * @param GetMobileStatusReq $getMobileStatusReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetMobileStatusResponseType
	 * @throws APIException
	 */
	public function GetMobileStatus($getMobileStatusReq, $apiCredential = NULL) {
		$this->setStandardParams($getMobileStatusReq->GetMobileStatusRequest);
		$ret = new GetMobileStatusResponseType();
		$resp = $this->call('PayPalAPI', 'GetMobileStatus', $getMobileStatusReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: SetMobileCheckout
	 * @param SetMobileCheckoutReq $setMobileCheckoutReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return SetMobileCheckoutResponseType
	 * @throws APIException
	 */
	public function SetMobileCheckout($setMobileCheckoutReq, $apiCredential = NULL) {
		$this->setStandardParams($setMobileCheckoutReq->SetMobileCheckoutRequest);
		$ret = new SetMobileCheckoutResponseType();
		$resp = $this->call('PayPalAPI', 'SetMobileCheckout', $setMobileCheckoutReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoMobileCheckoutPayment
	 * @param DoMobileCheckoutPaymentReq $doMobileCheckoutPaymentReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoMobileCheckoutPaymentResponseType
	 * @throws APIException
	 */
	public function DoMobileCheckoutPayment($doMobileCheckoutPaymentReq, $apiCredential = NULL) {
		$this->setStandardParams($doMobileCheckoutPaymentReq->DoMobileCheckoutPaymentRequest);
		$ret = new DoMobileCheckoutPaymentResponseType();
		$resp = $this->call('PayPalAPI', 'DoMobileCheckoutPayment', $doMobileCheckoutPaymentReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetBalance
	 * @param GetBalanceReq $getBalanceReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetBalanceResponseType
	 * @throws APIException
	 */
	public function GetBalance($getBalanceReq, $apiCredential = NULL) {
		$this->setStandardParams($getBalanceReq->GetBalanceRequest);
		$ret = new GetBalanceResponseType();
		$resp = $this->call('PayPalAPI', 'GetBalance', $getBalanceReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetPalDetails
	 * @param GetPalDetailsReq $getPalDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetPalDetailsResponseType
	 * @throws APIException
	 */
	public function GetPalDetails($getPalDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getPalDetailsReq->GetPalDetailsRequest);
		$ret = new GetPalDetailsResponseType();
		$resp = $this->call('PayPalAPI', 'GetPalDetails', $getPalDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoExpressCheckoutPayment
	 * @param DoExpressCheckoutPaymentReq $doExpressCheckoutPaymentReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoExpressCheckoutPaymentResponseType
	 * @throws APIException
	 */
	public function DoExpressCheckoutPayment($doExpressCheckoutPaymentReq, $apiCredential = NULL) {
		$this->setStandardParams($doExpressCheckoutPaymentReq->DoExpressCheckoutPaymentRequest);
		$ret = new DoExpressCheckoutPaymentResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoExpressCheckoutPayment', $doExpressCheckoutPaymentReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoUATPExpressCheckoutPayment
	 * @param DoUATPExpressCheckoutPaymentReq $doUATPExpressCheckoutPaymentReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoUATPExpressCheckoutPaymentResponseType
	 * @throws APIException
	 */
	public function DoUATPExpressCheckoutPayment($doUATPExpressCheckoutPaymentReq, $apiCredential = NULL) {
		$this->setStandardParams($doUATPExpressCheckoutPaymentReq->DoUATPExpressCheckoutPaymentRequest);
		$ret = new DoUATPExpressCheckoutPaymentResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoUATPExpressCheckoutPayment', $doUATPExpressCheckoutPaymentReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: SetAuthFlowParam
	 * @param SetAuthFlowParamReq $setAuthFlowParamReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return SetAuthFlowParamResponseType
	 * @throws APIException
	 */
	public function SetAuthFlowParam($setAuthFlowParamReq, $apiCredential = NULL) {
		$this->setStandardParams($setAuthFlowParamReq->SetAuthFlowParamRequest);
		$ret = new SetAuthFlowParamResponseType();
		$resp = $this->call('PayPalAPIAA', 'SetAuthFlowParam', $setAuthFlowParamReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetAuthDetails
	 * @param GetAuthDetailsReq $getAuthDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetAuthDetailsResponseType
	 * @throws APIException
	 */
	public function GetAuthDetails($getAuthDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getAuthDetailsReq->GetAuthDetailsRequest);
		$ret = new GetAuthDetailsResponseType();
		$resp = $this->call('PayPalAPIAA', 'GetAuthDetails', $getAuthDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: SetAccessPermissions
	 * @param SetAccessPermissionsReq $setAccessPermissionsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return SetAccessPermissionsResponseType
	 * @throws APIException
	 */
	public function SetAccessPermissions($setAccessPermissionsReq, $apiCredential = NULL) {
		$this->setStandardParams($setAccessPermissionsReq->SetAccessPermissionsRequest);
		$ret = new SetAccessPermissionsResponseType();
		$resp = $this->call('PayPalAPIAA', 'SetAccessPermissions', $setAccessPermissionsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: UpdateAccessPermissions
	 * @param UpdateAccessPermissionsReq $updateAccessPermissionsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return UpdateAccessPermissionsResponseType
	 * @throws APIException
	 */
	public function UpdateAccessPermissions($updateAccessPermissionsReq, $apiCredential = NULL) {
		$this->setStandardParams($updateAccessPermissionsReq->UpdateAccessPermissionsRequest);
		$ret = new UpdateAccessPermissionsResponseType();
		$resp = $this->call('PayPalAPIAA', 'UpdateAccessPermissions', $updateAccessPermissionsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetAccessPermissionDetails
	 * @param GetAccessPermissionDetailsReq $getAccessPermissionDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetAccessPermissionDetailsResponseType
	 * @throws APIException
	 */
	public function GetAccessPermissionDetails($getAccessPermissionDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getAccessPermissionDetailsReq->GetAccessPermissionDetailsRequest);
		$ret = new GetAccessPermissionDetailsResponseType();
		$resp = $this->call('PayPalAPIAA', 'GetAccessPermissionDetails', $getAccessPermissionDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetIncentiveEvaluation
	 * @param GetIncentiveEvaluationReq $getIncentiveEvaluationReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetIncentiveEvaluationResponseType
	 * @throws APIException
	 */
	public function GetIncentiveEvaluation($getIncentiveEvaluationReq, $apiCredential = NULL) {
		$this->setStandardParams($getIncentiveEvaluationReq->GetIncentiveEvaluationRequest);
		$ret = new GetIncentiveEvaluationResponseType();
		$resp = $this->call('PayPalAPIAA', 'GetIncentiveEvaluation', $getIncentiveEvaluationReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: SetExpressCheckout
	 * @param SetExpressCheckoutReq $setExpressCheckoutReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return SetExpressCheckoutResponseType
	 * @throws APIException
	 */
	public function SetExpressCheckout($setExpressCheckoutReq, $apiCredential = NULL) {
		$this->setStandardParams($setExpressCheckoutReq->SetExpressCheckoutRequest);
		$ret = new SetExpressCheckoutResponseType();
		$resp = $this->call('PayPalAPIAA', 'SetExpressCheckout', $setExpressCheckoutReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: ExecuteCheckoutOperations
	 * @param ExecuteCheckoutOperationsReq $executeCheckoutOperationsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return ExecuteCheckoutOperationsResponseType
	 * @throws APIException
	 */
	public function ExecuteCheckoutOperations($executeCheckoutOperationsReq, $apiCredential = NULL) {
		$this->setStandardParams($executeCheckoutOperationsReq->ExecuteCheckoutOperationsRequest);
		$ret = new ExecuteCheckoutOperationsResponseType();
		$resp = $this->call('PayPalAPIAA', 'ExecuteCheckoutOperations', $executeCheckoutOperationsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetExpressCheckoutDetails
	 * @param GetExpressCheckoutDetailsReq $getExpressCheckoutDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetExpressCheckoutDetailsResponseType
	 * @throws APIException
	 */
	public function GetExpressCheckoutDetails($getExpressCheckoutDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getExpressCheckoutDetailsReq->GetExpressCheckoutDetailsRequest);
		$ret = new GetExpressCheckoutDetailsResponseType();
		$resp = $this->call('PayPalAPIAA', 'GetExpressCheckoutDetails', $getExpressCheckoutDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoDirectPayment
	 * @param DoDirectPaymentReq $doDirectPaymentReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoDirectPaymentResponseType
	 * @throws APIException
	 */
	public function DoDirectPayment($doDirectPaymentReq, $apiCredential = NULL) {
		$this->setStandardParams($doDirectPaymentReq->DoDirectPaymentRequest);
		$ret = new DoDirectPaymentResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoDirectPayment', $doDirectPaymentReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: ManagePendingTransactionStatus
	 * @param ManagePendingTransactionStatusReq $managePendingTransactionStatusReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return ManagePendingTransactionStatusResponseType
	 * @throws APIException
	 */
	public function ManagePendingTransactionStatus($managePendingTransactionStatusReq, $apiCredential = NULL) {
		$this->setStandardParams($managePendingTransactionStatusReq->ManagePendingTransactionStatusRequest);
		$ret = new ManagePendingTransactionStatusResponseType();
		$resp = $this->call('PayPalAPIAA', 'ManagePendingTransactionStatus', $managePendingTransactionStatusReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoCancel
	 * @param DoCancelReq $doCancelReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoCancelResponseType
	 * @throws APIException
	 */
	public function DoCancel($doCancelReq, $apiCredential = NULL) {
		$this->setStandardParams($doCancelReq->DoCancelRequest);
		$ret = new DoCancelResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoCancel', $doCancelReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoCapture
	 * @param DoCaptureReq $doCaptureReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoCaptureResponseType
	 * @throws APIException
	 */
	public function DoCapture($doCaptureReq, $apiCredential = NULL) {
		$this->setStandardParams($doCaptureReq->DoCaptureRequest);
		$ret = new DoCaptureResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoCapture', $doCaptureReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoReauthorization
	 * @param DoReauthorizationReq $doReauthorizationReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoReauthorizationResponseType
	 * @throws APIException
	 */
	public function DoReauthorization($doReauthorizationReq, $apiCredential = NULL) {
		$this->setStandardParams($doReauthorizationReq->DoReauthorizationRequest);
		$ret = new DoReauthorizationResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoReauthorization', $doReauthorizationReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoVoid
	 * @param DoVoidReq $doVoidReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoVoidResponseType
	 * @throws APIException
	 */
	public function DoVoid($doVoidReq, $apiCredential = NULL) {
		$this->setStandardParams($doVoidReq->DoVoidRequest);
		$ret = new DoVoidResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoVoid', $doVoidReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoAuthorization
	 * @param DoAuthorizationReq $doAuthorizationReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoAuthorizationResponseType
	 * @throws APIException
	 */
	public function DoAuthorization($doAuthorizationReq, $apiCredential = NULL) {
		$this->setStandardParams($doAuthorizationReq->DoAuthorizationRequest);
		$ret = new DoAuthorizationResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoAuthorization', $doAuthorizationReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: SetCustomerBillingAgreement
	 * @param SetCustomerBillingAgreementReq $setCustomerBillingAgreementReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return SetCustomerBillingAgreementResponseType
	 * @throws APIException
	 */
	public function SetCustomerBillingAgreement($setCustomerBillingAgreementReq, $apiCredential = NULL) {
		$this->setStandardParams($setCustomerBillingAgreementReq->SetCustomerBillingAgreementRequest);
		$ret = new SetCustomerBillingAgreementResponseType();
		$resp = $this->call('PayPalAPIAA', 'SetCustomerBillingAgreement', $setCustomerBillingAgreementReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetBillingAgreementCustomerDetails
	 * @param GetBillingAgreementCustomerDetailsReq $getBillingAgreementCustomerDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetBillingAgreementCustomerDetailsResponseType
	 * @throws APIException
	 */
	public function GetBillingAgreementCustomerDetails($getBillingAgreementCustomerDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getBillingAgreementCustomerDetailsReq->GetBillingAgreementCustomerDetailsRequest);
		$ret = new GetBillingAgreementCustomerDetailsResponseType();
		$resp = $this->call('PayPalAPIAA', 'GetBillingAgreementCustomerDetails', $getBillingAgreementCustomerDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: CreateBillingAgreement
	 * @param CreateBillingAgreementReq $createBillingAgreementReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return CreateBillingAgreementResponseType
	 * @throws APIException
	 */
	public function CreateBillingAgreement($createBillingAgreementReq, $apiCredential = NULL) {
		$this->setStandardParams($createBillingAgreementReq->CreateBillingAgreementRequest);
		$ret = new CreateBillingAgreementResponseType();
		$resp = $this->call('PayPalAPIAA', 'CreateBillingAgreement', $createBillingAgreementReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoReferenceTransaction
	 * @param DoReferenceTransactionReq $doReferenceTransactionReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoReferenceTransactionResponseType
	 * @throws APIException
	 */
	public function DoReferenceTransaction($doReferenceTransactionReq, $apiCredential = NULL) {
		$this->setStandardParams($doReferenceTransactionReq->DoReferenceTransactionRequest);
		$ret = new DoReferenceTransactionResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoReferenceTransaction', $doReferenceTransactionReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoNonReferencedCredit
	 * @param DoNonReferencedCreditReq $doNonReferencedCreditReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoNonReferencedCreditResponseType
	 * @throws APIException
	 */
	public function DoNonReferencedCredit($doNonReferencedCreditReq, $apiCredential = NULL) {
		$this->setStandardParams($doNonReferencedCreditReq->DoNonReferencedCreditRequest);
		$ret = new DoNonReferencedCreditResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoNonReferencedCredit', $doNonReferencedCreditReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: DoUATPAuthorization
	 * @param DoUATPAuthorizationReq $doUATPAuthorizationReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return DoUATPAuthorizationResponseType
	 * @throws APIException
	 */
	public function DoUATPAuthorization($doUATPAuthorizationReq, $apiCredential = NULL) {
		$this->setStandardParams($doUATPAuthorizationReq->DoUATPAuthorizationRequest);
		$ret = new DoUATPAuthorizationResponseType();
		$resp = $this->call('PayPalAPIAA', 'DoUATPAuthorization', $doUATPAuthorizationReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: CreateRecurringPaymentsProfile
	 * @param CreateRecurringPaymentsProfileReq $createRecurringPaymentsProfileReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return CreateRecurringPaymentsProfileResponseType
	 * @throws APIException
	 */
	public function CreateRecurringPaymentsProfile($createRecurringPaymentsProfileReq, $apiCredential = NULL) {
		$this->setStandardParams($createRecurringPaymentsProfileReq->CreateRecurringPaymentsProfileRequest);
		$ret = new CreateRecurringPaymentsProfileResponseType();
		$resp = $this->call('PayPalAPIAA', 'CreateRecurringPaymentsProfile', $createRecurringPaymentsProfileReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: GetRecurringPaymentsProfileDetails
	 * @param GetRecurringPaymentsProfileDetailsReq $getRecurringPaymentsProfileDetailsReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return GetRecurringPaymentsProfileDetailsResponseType
	 * @throws APIException
	 */
	public function GetRecurringPaymentsProfileDetails($getRecurringPaymentsProfileDetailsReq, $apiCredential = NULL) {
		$this->setStandardParams($getRecurringPaymentsProfileDetailsReq->GetRecurringPaymentsProfileDetailsRequest);
		$ret = new GetRecurringPaymentsProfileDetailsResponseType();
		$resp = $this->call('PayPalAPIAA', 'GetRecurringPaymentsProfileDetails', $getRecurringPaymentsProfileDetailsReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: ManageRecurringPaymentsProfileStatus
	 * @param ManageRecurringPaymentsProfileStatusReq $manageRecurringPaymentsProfileStatusReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return ManageRecurringPaymentsProfileStatusResponseType
	 * @throws APIException
	 */
	public function ManageRecurringPaymentsProfileStatus($manageRecurringPaymentsProfileStatusReq, $apiCredential = NULL) {
		$this->setStandardParams($manageRecurringPaymentsProfileStatusReq->ManageRecurringPaymentsProfileStatusRequest);
		$ret = new ManageRecurringPaymentsProfileStatusResponseType();
		$resp = $this->call('PayPalAPIAA', 'ManageRecurringPaymentsProfileStatus', $manageRecurringPaymentsProfileStatusReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: BillOutstandingAmount
	 * @param BillOutstandingAmountReq $billOutstandingAmountReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return BillOutstandingAmountResponseType
	 * @throws APIException
	 */
	public function BillOutstandingAmount($billOutstandingAmountReq, $apiCredential = NULL) {
		$this->setStandardParams($billOutstandingAmountReq->BillOutstandingAmountRequest);
		$ret = new BillOutstandingAmountResponseType();
		$resp = $this->call('PayPalAPIAA', 'BillOutstandingAmount', $billOutstandingAmountReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: UpdateRecurringPaymentsProfile
	 * @param UpdateRecurringPaymentsProfileReq $updateRecurringPaymentsProfileReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return UpdateRecurringPaymentsProfileResponseType
	 * @throws APIException
	 */
	public function UpdateRecurringPaymentsProfile($updateRecurringPaymentsProfileReq, $apiCredential = NULL) {
		$this->setStandardParams($updateRecurringPaymentsProfileReq->UpdateRecurringPaymentsProfileRequest);
		$ret = new UpdateRecurringPaymentsProfileResponseType();
		$resp = $this->call('PayPalAPIAA', 'UpdateRecurringPaymentsProfile', $updateRecurringPaymentsProfileReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: ReverseTransaction
	 * @param ReverseTransactionReq $reverseTransactionReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return ReverseTransactionResponseType
	 * @throws APIException
	 */
	public function ReverseTransaction($reverseTransactionReq, $apiCredential = NULL) {
		$this->setStandardParams($reverseTransactionReq->ReverseTransactionRequest);
		$ret = new ReverseTransactionResponseType();
		$resp = $this->call('PayPalAPIAA', 'ReverseTransaction', $reverseTransactionReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 

	/**
	 * Service Call: ExternalRememberMeOptOut
	 * @param ExternalRememberMeOptOutReq $externalRememberMeOptOutReq
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 * @return ExternalRememberMeOptOutResponseType
	 * @throws APIException
	 */
	public function ExternalRememberMeOptOut($externalRememberMeOptOutReq, $apiCredential = NULL) {
		$this->setStandardParams($externalRememberMeOptOutReq->ExternalRememberMeOptOutRequest);
		$ret = new ExternalRememberMeOptOutResponseType();
		$resp = $this->call('PayPalAPIAA', 'ExternalRememberMeOptOut', $externalRememberMeOptOutReq, $apiCredential);
		$ret->init(PPUtils::xmlToArray($resp));
		return $ret;
	}
	 
}