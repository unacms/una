# xero-php-oauth2

## Accounting API Documentation

Please follow the [README instructions](https://github.com/XeroAPI/xero-php-oauth2/blob/master/README.md) and then run the following:

```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

  // Init your oAuth2 provider
  $provider = new \League\OAuth2\Client\Provider\GenericProvider([
    'clientId'                => '__YOUR_CLIENT_ID__',   
    'clientSecret'            => '__YOUR_CLIENT_SECRET__',
    'redirectUri'             => '__YOUR_REDIRECT_URI__',  //same as at developer.xero.com/myapps
    'urlAuthorize'            => 'https://login.xero.com/identity/connect/authorize',
    'urlAccessToken'          => 'https://identity.xero.com/connect/token'
  ]);


  // Configure OAuth2 access token for authorization: OAuth2
  $config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');     

  $accountingApi = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
  );

  $xeroTenantId = 'xero_tenant_id_example'; // string | Xero identifier for Tenant

  // \XeroAPI\XeroPHP\Models\Accounting\Account | Request of type Account
  $account = new XeroAPI\XeroPHP\Models\Accounting\Account;
  $account->setCode($this->getRandNum());
  $account->setName("Foo" . $this->getRandNum());
  $account->setType("EXPENSE");
  $account->setDescription("Hello World");  

  try {
      $result = $accountingApi->createAccount($xero_tenant_id, $account);
      print_r($result);
  } catch (Exception $e) {
      echo 'Exception when calling accountingApi->createAccount: ', $e->getMessage(), PHP_EOL;
  }

?>
```

## Documentation for API Endpoints

All URIs are relative to *https://api.xero.com/api.xro/2.0*

Class | Method | HTTP request | Description
------------ | ------------- | ------------- | -------------
*AccountingApi* | [**createAccount**](Api/AccountingApi.md#createaccount) | **PUT** /Accounts | Allows you to create a new chart of accounts
*AccountingApi* | [**createAccountAttachmentByFileName**](Api/AccountingApi.md#createaccountattachmentbyfilename) | **PUT** /Accounts/{AccountID}/Attachments/{FileName} | Allows you to create Attachment on Account
*AccountingApi* | [**createBankTransactionAttachmentByFileName**](Api/AccountingApi.md#createbanktransactionattachmentbyfilename) | **PUT** /BankTransactions/{BankTransactionID}/Attachments/{FileName} | Allows you to create an Attachment on BankTransaction by Filename
*AccountingApi* | [**createBankTransactionHistoryRecord**](Api/AccountingApi.md#createbanktransactionhistoryrecord) | **PUT** /BankTransactions/{BankTransactionID}/History | Allows you to create history record for a bank transactions
*AccountingApi* | [**createBankTransactions**](Api/AccountingApi.md#createbanktransactions) | **PUT** /BankTransactions | Allows you to create one or more spend or receive money transaction
*AccountingApi* | [**createBankTransfer**](Api/AccountingApi.md#createbanktransfer) | **PUT** /BankTransfers | Allows you to create a bank transfers
*AccountingApi* | [**createBankTransferAttachmentByFileName**](Api/AccountingApi.md#createbanktransferattachmentbyfilename) | **PUT** /BankTransfers/{BankTransferID}/Attachments/{FileName} | 
*AccountingApi* | [**createBankTransferHistoryRecord**](Api/AccountingApi.md#createbanktransferhistoryrecord) | **PUT** /BankTransfers/{BankTransferID}/History | 
*AccountingApi* | [**createBatchPayment**](Api/AccountingApi.md#createbatchpayment) | **PUT** /BatchPayments | Create one or many BatchPayments for invoices
*AccountingApi* | [**createBatchPaymentHistoryRecord**](Api/AccountingApi.md#createbatchpaymenthistoryrecord) | **PUT** /BatchPayments/{BatchPaymentID}/History | Allows you to create a history record for a Batch Payment
*AccountingApi* | [**createBrandingThemePaymentServices**](Api/AccountingApi.md#createbrandingthemepaymentservices) | **POST** /BrandingThemes/{BrandingThemeID}/PaymentServices | Allow for the creation of new custom payment service for specified Branding Theme
*AccountingApi* | [**createContactAttachmentByFileName**](Api/AccountingApi.md#createcontactattachmentbyfilename) | **PUT** /Contacts/{ContactID}/Attachments/{FileName} | 
*AccountingApi* | [**createContactGroup**](Api/AccountingApi.md#createcontactgroup) | **PUT** /ContactGroups | Allows you to create a contact group
*AccountingApi* | [**createContactGroupContacts**](Api/AccountingApi.md#createcontactgroupcontacts) | **PUT** /ContactGroups/{ContactGroupID}/Contacts | Allows you to add Contacts to a Contact Group
*AccountingApi* | [**createContactHistory**](Api/AccountingApi.md#createcontacthistory) | **PUT** /Contacts/{ContactID}/History | Allows you to retrieve a history records of an Contact
*AccountingApi* | [**createContacts**](Api/AccountingApi.md#createcontacts) | **PUT** /Contacts | Allows you to create a multiple contacts (bulk) in a Xero organisation
*AccountingApi* | [**createCreditNoteAllocation**](Api/AccountingApi.md#createcreditnoteallocation) | **PUT** /CreditNotes/{CreditNoteID}/Allocations | Allows you to create Allocation on CreditNote
*AccountingApi* | [**createCreditNoteAttachmentByFileName**](Api/AccountingApi.md#createcreditnoteattachmentbyfilename) | **PUT** /CreditNotes/{CreditNoteID}/Attachments/{FileName} | Allows you to create Attachments on CreditNote by file name
*AccountingApi* | [**createCreditNoteHistory**](Api/AccountingApi.md#createcreditnotehistory) | **PUT** /CreditNotes/{CreditNoteID}/History | Allows you to retrieve a history records of an CreditNote
*AccountingApi* | [**createCreditNotes**](Api/AccountingApi.md#createcreditnotes) | **PUT** /CreditNotes | Allows you to create a credit note
*AccountingApi* | [**createCurrency**](Api/AccountingApi.md#createcurrency) | **PUT** /Currencies | 
*AccountingApi* | [**createEmployees**](Api/AccountingApi.md#createemployees) | **PUT** /Employees | Allows you to create new employees used in Xero payrun
*AccountingApi* | [**createExpenseClaimHistory**](Api/AccountingApi.md#createexpenseclaimhistory) | **PUT** /ExpenseClaims/{ExpenseClaimID}/History | Allows you to create a history records of an ExpenseClaim
*AccountingApi* | [**createExpenseClaims**](Api/AccountingApi.md#createexpenseclaims) | **PUT** /ExpenseClaims | Allows you to retrieve expense claims
*AccountingApi* | [**createInvoiceAttachmentByFileName**](Api/AccountingApi.md#createinvoiceattachmentbyfilename) | **PUT** /Invoices/{InvoiceID}/Attachments/{FileName} | Allows you to create an Attachment on invoices or purchase bills by it&#39;s filename
*AccountingApi* | [**createInvoiceHistory**](Api/AccountingApi.md#createinvoicehistory) | **PUT** /Invoices/{InvoiceID}/History | Allows you to retrieve a history records of an invoice
*AccountingApi* | [**createInvoices**](Api/AccountingApi.md#createinvoices) | **PUT** /Invoices | Allows you to create one or more sales invoices or purchase bills
*AccountingApi* | [**createItemHistory**](Api/AccountingApi.md#createitemhistory) | **PUT** /Items/{ItemID}/History | Allows you to create a history record for items
*AccountingApi* | [**createItems**](Api/AccountingApi.md#createitems) | **PUT** /Items | Allows you to create one or more items
*AccountingApi* | [**createLinkedTransaction**](Api/AccountingApi.md#createlinkedtransaction) | **PUT** /LinkedTransactions | Allows you to create linked transactions (billable expenses)
*AccountingApi* | [**createManualJournalAttachmentByFileName**](Api/AccountingApi.md#createmanualjournalattachmentbyfilename) | **PUT** /ManualJournals/{ManualJournalID}/Attachments/{FileName} | Allows you to create a specified Attachment on ManualJournal by file name
*AccountingApi* | [**createManualJournalHistoryRecord**](Api/AccountingApi.md#createmanualjournalhistoryrecord) | **PUT** /ManualJournals/{ManualJournalID}/History | Allows you to create history record for a manual journal
*AccountingApi* | [**createManualJournals**](Api/AccountingApi.md#createmanualjournals) | **PUT** /ManualJournals | Allows you to create one or more manual journals
*AccountingApi* | [**createOverpaymentAllocations**](Api/AccountingApi.md#createoverpaymentallocations) | **PUT** /Overpayments/{OverpaymentID}/Allocations | Allows you to create a single allocation for an overpayment
*AccountingApi* | [**createOverpaymentHistory**](Api/AccountingApi.md#createoverpaymenthistory) | **PUT** /Overpayments/{OverpaymentID}/History | Allows you to create history records of an Overpayment
*AccountingApi* | [**createPayment**](Api/AccountingApi.md#createpayment) | **POST** /Payments | Allows you to create a single payment for invoices or credit notes
*AccountingApi* | [**createPaymentHistory**](Api/AccountingApi.md#createpaymenthistory) | **PUT** /Payments/{PaymentID}/History | Allows you to create a history record for a payment
*AccountingApi* | [**createPaymentService**](Api/AccountingApi.md#createpaymentservice) | **PUT** /PaymentServices | Allows you to create payment services
*AccountingApi* | [**createPayments**](Api/AccountingApi.md#createpayments) | **PUT** /Payments | Allows you to create multiple payments for invoices or credit notes
*AccountingApi* | [**createPrepaymentAllocations**](Api/AccountingApi.md#createprepaymentallocations) | **PUT** /Prepayments/{PrepaymentID}/Allocations | Allows you to create an Allocation for prepayments
*AccountingApi* | [**createPrepaymentHistory**](Api/AccountingApi.md#createprepaymenthistory) | **PUT** /Prepayments/{PrepaymentID}/History | Allows you to create a history record for an Prepayment
*AccountingApi* | [**createPurchaseOrderAttachmentByFileName**](Api/AccountingApi.md#createpurchaseorderattachmentbyfilename) | **PUT** /PurchaseOrders/{PurchaseOrderID}/Attachments/{FileName} | Allows you to create Attachment on Purchase Order
*AccountingApi* | [**createPurchaseOrderHistory**](Api/AccountingApi.md#createpurchaseorderhistory) | **PUT** /PurchaseOrders/{PurchaseOrderID}/History | Allows you to create HistoryRecord for purchase orders
*AccountingApi* | [**createPurchaseOrders**](Api/AccountingApi.md#createpurchaseorders) | **PUT** /PurchaseOrders | Allows you to create one or more purchase orders
*AccountingApi* | [**createQuoteAttachmentByFileName**](Api/AccountingApi.md#createquoteattachmentbyfilename) | **PUT** /Quotes/{QuoteID}/Attachments/{FileName} | Allows you to create Attachment on Quote
*AccountingApi* | [**createQuoteHistory**](Api/AccountingApi.md#createquotehistory) | **PUT** /Quotes/{QuoteID}/History | Allows you to retrieve a history records of an quote
*AccountingApi* | [**createQuotes**](Api/AccountingApi.md#createquotes) | **PUT** /Quotes | Allows you to create one or more quotes
*AccountingApi* | [**createReceipt**](Api/AccountingApi.md#createreceipt) | **PUT** /Receipts | Allows you to create draft expense claim receipts for any user
*AccountingApi* | [**createReceiptAttachmentByFileName**](Api/AccountingApi.md#createreceiptattachmentbyfilename) | **PUT** /Receipts/{ReceiptID}/Attachments/{FileName} | Allows you to create Attachment on expense claim receipts by file name
*AccountingApi* | [**createReceiptHistory**](Api/AccountingApi.md#createreceipthistory) | **PUT** /Receipts/{ReceiptID}/History | Allows you to retrieve a history records of an Receipt
*AccountingApi* | [**createRepeatingInvoiceAttachmentByFileName**](Api/AccountingApi.md#createrepeatinginvoiceattachmentbyfilename) | **PUT** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{FileName} | Allows you to create attachment on repeating invoices by file name
*AccountingApi* | [**createRepeatingInvoiceHistory**](Api/AccountingApi.md#createrepeatinginvoicehistory) | **PUT** /RepeatingInvoices/{RepeatingInvoiceID}/History | Allows you to create history for a repeating invoice
*AccountingApi* | [**createTaxRates**](Api/AccountingApi.md#createtaxrates) | **PUT** /TaxRates | Allows you to create one or more Tax Rates
*AccountingApi* | [**createTrackingCategory**](Api/AccountingApi.md#createtrackingcategory) | **PUT** /TrackingCategories | Allows you to create tracking categories
*AccountingApi* | [**createTrackingOptions**](Api/AccountingApi.md#createtrackingoptions) | **PUT** /TrackingCategories/{TrackingCategoryID}/Options | Allows you to create options for a specified tracking category
*AccountingApi* | [**deleteAccount**](Api/AccountingApi.md#deleteaccount) | **DELETE** /Accounts/{AccountID} | Allows you to delete a chart of accounts
*AccountingApi* | [**deleteContactGroupContact**](Api/AccountingApi.md#deletecontactgroupcontact) | **DELETE** /ContactGroups/{ContactGroupID}/Contacts/{ContactID} | Allows you to delete a specific Contact from a Contact Group
*AccountingApi* | [**deleteContactGroupContacts**](Api/AccountingApi.md#deletecontactgroupcontacts) | **DELETE** /ContactGroups/{ContactGroupID}/Contacts | Allows you to delete  all Contacts from a Contact Group
*AccountingApi* | [**deleteItem**](Api/AccountingApi.md#deleteitem) | **DELETE** /Items/{ItemID} | Allows you to delete a specified item
*AccountingApi* | [**deleteLinkedTransaction**](Api/AccountingApi.md#deletelinkedtransaction) | **DELETE** /LinkedTransactions/{LinkedTransactionID} | Allows you to delete a specified linked transactions (billable expenses)
*AccountingApi* | [**deletePayment**](Api/AccountingApi.md#deletepayment) | **POST** /Payments/{PaymentID} | Allows you to update a specified payment for invoices and credit notes
*AccountingApi* | [**deleteTrackingCategory**](Api/AccountingApi.md#deletetrackingcategory) | **DELETE** /TrackingCategories/{TrackingCategoryID} | Allows you to delete tracking categories
*AccountingApi* | [**deleteTrackingOptions**](Api/AccountingApi.md#deletetrackingoptions) | **DELETE** /TrackingCategories/{TrackingCategoryID}/Options/{TrackingOptionID} | Allows you to delete a specified option for a specified tracking category
*AccountingApi* | [**emailInvoice**](Api/AccountingApi.md#emailinvoice) | **POST** /Invoices/{InvoiceID}/Email | Allows you to email a copy of invoice to related Contact
*AccountingApi* | [**getAccount**](Api/AccountingApi.md#getaccount) | **GET** /Accounts/{AccountID} | Allows you to retrieve a single chart of accounts
*AccountingApi* | [**getAccountAttachmentByFileName**](Api/AccountingApi.md#getaccountattachmentbyfilename) | **GET** /Accounts/{AccountID}/Attachments/{FileName} | Allows you to retrieve Attachment on Account by Filename
*AccountingApi* | [**getAccountAttachmentById**](Api/AccountingApi.md#getaccountattachmentbyid) | **GET** /Accounts/{AccountID}/Attachments/{AttachmentID} | Allows you to retrieve specific Attachment on Account
*AccountingApi* | [**getAccountAttachments**](Api/AccountingApi.md#getaccountattachments) | **GET** /Accounts/{AccountID}/Attachments | Allows you to retrieve Attachments for accounts
*AccountingApi* | [**getAccounts**](Api/AccountingApi.md#getaccounts) | **GET** /Accounts | Allows you to retrieve the full chart of accounts
*AccountingApi* | [**getBankTransaction**](Api/AccountingApi.md#getbanktransaction) | **GET** /BankTransactions/{BankTransactionID} | Allows you to retrieve a single spend or receive money transaction
*AccountingApi* | [**getBankTransactionAttachmentByFileName**](Api/AccountingApi.md#getbanktransactionattachmentbyfilename) | **GET** /BankTransactions/{BankTransactionID}/Attachments/{FileName} | Allows you to retrieve Attachments on BankTransaction by Filename
*AccountingApi* | [**getBankTransactionAttachmentById**](Api/AccountingApi.md#getbanktransactionattachmentbyid) | **GET** /BankTransactions/{BankTransactionID}/Attachments/{AttachmentID} | Allows you to retrieve Attachments on a specific BankTransaction
*AccountingApi* | [**getBankTransactionAttachments**](Api/AccountingApi.md#getbanktransactionattachments) | **GET** /BankTransactions/{BankTransactionID}/Attachments | Allows you to retrieve any attachments to bank transactions
*AccountingApi* | [**getBankTransactions**](Api/AccountingApi.md#getbanktransactions) | **GET** /BankTransactions | Allows you to retrieve any spend or receive money transactions
*AccountingApi* | [**getBankTransactionsHistory**](Api/AccountingApi.md#getbanktransactionshistory) | **GET** /BankTransactions/{BankTransactionID}/History | Allows you to retrieve history from a bank transactions
*AccountingApi* | [**getBankTransfer**](Api/AccountingApi.md#getbanktransfer) | **GET** /BankTransfers/{BankTransferID} | Allows you to retrieve any bank transfers
*AccountingApi* | [**getBankTransferAttachmentByFileName**](Api/AccountingApi.md#getbanktransferattachmentbyfilename) | **GET** /BankTransfers/{BankTransferID}/Attachments/{FileName} | Allows you to retrieve Attachments on BankTransfer by file name
*AccountingApi* | [**getBankTransferAttachmentById**](Api/AccountingApi.md#getbanktransferattachmentbyid) | **GET** /BankTransfers/{BankTransferID}/Attachments/{AttachmentID} | Allows you to retrieve Attachments on BankTransfer
*AccountingApi* | [**getBankTransferAttachments**](Api/AccountingApi.md#getbanktransferattachments) | **GET** /BankTransfers/{BankTransferID}/Attachments | Allows you to retrieve Attachments from  bank transfers
*AccountingApi* | [**getBankTransferHistory**](Api/AccountingApi.md#getbanktransferhistory) | **GET** /BankTransfers/{BankTransferID}/History | Allows you to retrieve history from a bank transfers
*AccountingApi* | [**getBankTransfers**](Api/AccountingApi.md#getbanktransfers) | **GET** /BankTransfers | Allows you to retrieve all bank transfers
*AccountingApi* | [**getBatchPaymentHistory**](Api/AccountingApi.md#getbatchpaymenthistory) | **GET** /BatchPayments/{BatchPaymentID}/History | Allows you to retrieve history from a Batch Payment
*AccountingApi* | [**getBatchPayments**](Api/AccountingApi.md#getbatchpayments) | **GET** /BatchPayments | Retrieve either one or many BatchPayments for invoices
*AccountingApi* | [**getBrandingTheme**](Api/AccountingApi.md#getbrandingtheme) | **GET** /BrandingThemes/{BrandingThemeID} | Allows you to retrieve a specific BrandingThemes
*AccountingApi* | [**getBrandingThemePaymentServices**](Api/AccountingApi.md#getbrandingthemepaymentservices) | **GET** /BrandingThemes/{BrandingThemeID}/PaymentServices | Allows you to retrieve the Payment services for a Branding Theme
*AccountingApi* | [**getBrandingThemes**](Api/AccountingApi.md#getbrandingthemes) | **GET** /BrandingThemes | Allows you to retrieve all the BrandingThemes
*AccountingApi* | [**getContact**](Api/AccountingApi.md#getcontact) | **GET** /Contacts/{ContactID} | Allows you to retrieve a single contacts in a Xero organisation
*AccountingApi* | [**getContactAttachmentByFileName**](Api/AccountingApi.md#getcontactattachmentbyfilename) | **GET** /Contacts/{ContactID}/Attachments/{FileName} | Allows you to retrieve Attachments on Contacts by file name
*AccountingApi* | [**getContactAttachmentById**](Api/AccountingApi.md#getcontactattachmentbyid) | **GET** /Contacts/{ContactID}/Attachments/{AttachmentID} | Allows you to retrieve Attachments on Contacts
*AccountingApi* | [**getContactAttachments**](Api/AccountingApi.md#getcontactattachments) | **GET** /Contacts/{ContactID}/Attachments | Allows you to retrieve, add and update contacts in a Xero organisation
*AccountingApi* | [**getContactByContactNumber**](Api/AccountingApi.md#getcontactbycontactnumber) | **GET** /Contacts/{ContactNumber} | Allows you to retrieve a single contact by Contact Number in a Xero organisation
*AccountingApi* | [**getContactCISSettings**](Api/AccountingApi.md#getcontactcissettings) | **GET** /Contacts/{ContactID}/CISSettings | Allows you to retrieve CISSettings for a contact in a Xero organisation
*AccountingApi* | [**getContactGroup**](Api/AccountingApi.md#getcontactgroup) | **GET** /ContactGroups/{ContactGroupID} | Allows you to retrieve a unique Contact Group by ID
*AccountingApi* | [**getContactGroups**](Api/AccountingApi.md#getcontactgroups) | **GET** /ContactGroups | Allows you to retrieve the ContactID and Name of all the contacts in a contact group
*AccountingApi* | [**getContactHistory**](Api/AccountingApi.md#getcontacthistory) | **GET** /Contacts/{ContactID}/History | Allows you to retrieve a history records of an Contact
*AccountingApi* | [**getContacts**](Api/AccountingApi.md#getcontacts) | **GET** /Contacts | Allows you to retrieve all contacts in a Xero organisation
*AccountingApi* | [**getCreditNote**](Api/AccountingApi.md#getcreditnote) | **GET** /CreditNotes/{CreditNoteID} | Allows you to retrieve a specific credit note
*AccountingApi* | [**getCreditNoteAsPdf**](Api/AccountingApi.md#getcreditnoteaspdf) | **GET** /CreditNotes/{CreditNoteID}/pdf | Allows you to retrieve Credit Note as PDF files
*AccountingApi* | [**getCreditNoteAttachmentByFileName**](Api/AccountingApi.md#getcreditnoteattachmentbyfilename) | **GET** /CreditNotes/{CreditNoteID}/Attachments/{FileName} | Allows you to retrieve Attachments on CreditNote by file name
*AccountingApi* | [**getCreditNoteAttachmentById**](Api/AccountingApi.md#getcreditnoteattachmentbyid) | **GET** /CreditNotes/{CreditNoteID}/Attachments/{AttachmentID} | Allows you to retrieve Attachments on CreditNote
*AccountingApi* | [**getCreditNoteAttachments**](Api/AccountingApi.md#getcreditnoteattachments) | **GET** /CreditNotes/{CreditNoteID}/Attachments | Allows you to retrieve Attachments for credit notes
*AccountingApi* | [**getCreditNoteHistory**](Api/AccountingApi.md#getcreditnotehistory) | **GET** /CreditNotes/{CreditNoteID}/History | Allows you to retrieve a history records of an CreditNote
*AccountingApi* | [**getCreditNotes**](Api/AccountingApi.md#getcreditnotes) | **GET** /CreditNotes | Allows you to retrieve any credit notes
*AccountingApi* | [**getCurrencies**](Api/AccountingApi.md#getcurrencies) | **GET** /Currencies | Allows you to retrieve currencies for your organisation
*AccountingApi* | [**getEmployee**](Api/AccountingApi.md#getemployee) | **GET** /Employees/{EmployeeID} | Allows you to retrieve a specific employee used in Xero payrun
*AccountingApi* | [**getEmployees**](Api/AccountingApi.md#getemployees) | **GET** /Employees | Allows you to retrieve employees used in Xero payrun
*AccountingApi* | [**getExpenseClaim**](Api/AccountingApi.md#getexpenseclaim) | **GET** /ExpenseClaims/{ExpenseClaimID} | Allows you to retrieve a specified expense claim
*AccountingApi* | [**getExpenseClaimHistory**](Api/AccountingApi.md#getexpenseclaimhistory) | **GET** /ExpenseClaims/{ExpenseClaimID}/History | Allows you to retrieve a history records of an ExpenseClaim
*AccountingApi* | [**getExpenseClaims**](Api/AccountingApi.md#getexpenseclaims) | **GET** /ExpenseClaims | Allows you to retrieve expense claims
*AccountingApi* | [**getInvoice**](Api/AccountingApi.md#getinvoice) | **GET** /Invoices/{InvoiceID} | Allows you to retrieve a specified sales invoice or purchase bill
*AccountingApi* | [**getInvoiceAsPdf**](Api/AccountingApi.md#getinvoiceaspdf) | **GET** /Invoices/{InvoiceID}/pdf | Allows you to retrieve invoices or purchase bills as PDF files
*AccountingApi* | [**getInvoiceAttachmentByFileName**](Api/AccountingApi.md#getinvoiceattachmentbyfilename) | **GET** /Invoices/{InvoiceID}/Attachments/{FileName} | Allows you to retrieve Attachment on invoices or purchase bills by it&#39;s filename
*AccountingApi* | [**getInvoiceAttachmentById**](Api/AccountingApi.md#getinvoiceattachmentbyid) | **GET** /Invoices/{InvoiceID}/Attachments/{AttachmentID} | Allows you to retrieve a specified Attachment on invoices or purchase bills by it&#39;s ID
*AccountingApi* | [**getInvoiceAttachments**](Api/AccountingApi.md#getinvoiceattachments) | **GET** /Invoices/{InvoiceID}/Attachments | Allows you to retrieve Attachments on invoices or purchase bills
*AccountingApi* | [**getInvoiceHistory**](Api/AccountingApi.md#getinvoicehistory) | **GET** /Invoices/{InvoiceID}/History | Allows you to retrieve a history records of an invoice
*AccountingApi* | [**getInvoiceReminders**](Api/AccountingApi.md#getinvoicereminders) | **GET** /InvoiceReminders/Settings | Allows you to retrieve invoice reminder settings
*AccountingApi* | [**getInvoices**](Api/AccountingApi.md#getinvoices) | **GET** /Invoices | Allows you to retrieve any sales invoices or purchase bills
*AccountingApi* | [**getItem**](Api/AccountingApi.md#getitem) | **GET** /Items/{ItemID} | Allows you to retrieve a specified item
*AccountingApi* | [**getItemHistory**](Api/AccountingApi.md#getitemhistory) | **GET** /Items/{ItemID}/History | Allows you to retrieve history for items
*AccountingApi* | [**getItems**](Api/AccountingApi.md#getitems) | **GET** /Items | Allows you to retrieve any items
*AccountingApi* | [**getJournal**](Api/AccountingApi.md#getjournal) | **GET** /Journals/{JournalID} | Allows you to retrieve a specified journals.
*AccountingApi* | [**getJournals**](Api/AccountingApi.md#getjournals) | **GET** /Journals | Allows you to retrieve any journals.
*AccountingApi* | [**getLinkedTransaction**](Api/AccountingApi.md#getlinkedtransaction) | **GET** /LinkedTransactions/{LinkedTransactionID} | Allows you to retrieve a specified linked transactions (billable expenses)
*AccountingApi* | [**getLinkedTransactions**](Api/AccountingApi.md#getlinkedtransactions) | **GET** /LinkedTransactions | Retrieve linked transactions (billable expenses)
*AccountingApi* | [**getManualJournal**](Api/AccountingApi.md#getmanualjournal) | **GET** /ManualJournals/{ManualJournalID} | Allows you to retrieve a specified manual journals
*AccountingApi* | [**getManualJournalAttachmentByFileName**](Api/AccountingApi.md#getmanualjournalattachmentbyfilename) | **GET** /ManualJournals/{ManualJournalID}/Attachments/{FileName} | Allows you to retrieve specified Attachment on ManualJournal by file name
*AccountingApi* | [**getManualJournalAttachmentById**](Api/AccountingApi.md#getmanualjournalattachmentbyid) | **GET** /ManualJournals/{ManualJournalID}/Attachments/{AttachmentID} | Allows you to retrieve specified Attachment on ManualJournals
*AccountingApi* | [**getManualJournalAttachments**](Api/AccountingApi.md#getmanualjournalattachments) | **GET** /ManualJournals/{ManualJournalID}/Attachments | Allows you to retrieve Attachment for manual journals
*AccountingApi* | [**getManualJournals**](Api/AccountingApi.md#getmanualjournals) | **GET** /ManualJournals | Allows you to retrieve any manual journals
*AccountingApi* | [**getManualJournalsHistory**](Api/AccountingApi.md#getmanualjournalshistory) | **GET** /ManualJournals/{ManualJournalID}/History | Allows you to retrieve history from a manual journal
*AccountingApi* | [**getOnlineInvoice**](Api/AccountingApi.md#getonlineinvoice) | **GET** /Invoices/{InvoiceID}/OnlineInvoice | Allows you to retrieve a URL to an online invoice
*AccountingApi* | [**getOrganisationActions**](Api/AccountingApi.md#getorganisationactions) | **GET** /Organisation/Actions | Retrieve a list of the key actions your app has permission to perform in the connected organisation.
*AccountingApi* | [**getOrganisationCISSettings**](Api/AccountingApi.md#getorganisationcissettings) | **GET** /Organisation/{OrganisationID}/CISSettings | Allows you To verify if an organisation is using contruction industry scheme, you can retrieve the CIS settings for the organistaion.
*AccountingApi* | [**getOrganisations**](Api/AccountingApi.md#getorganisations) | **GET** /Organisation | Allows you to retrieve Organisation details
*AccountingApi* | [**getOverpayment**](Api/AccountingApi.md#getoverpayment) | **GET** /Overpayments/{OverpaymentID} | Allows you to retrieve a specified overpayments
*AccountingApi* | [**getOverpaymentHistory**](Api/AccountingApi.md#getoverpaymenthistory) | **GET** /Overpayments/{OverpaymentID}/History | Allows you to retrieve a history records of an Overpayment
*AccountingApi* | [**getOverpayments**](Api/AccountingApi.md#getoverpayments) | **GET** /Overpayments | Allows you to retrieve overpayments
*AccountingApi* | [**getPayment**](Api/AccountingApi.md#getpayment) | **GET** /Payments/{PaymentID} | Allows you to retrieve a specified payment for invoices and credit notes
*AccountingApi* | [**getPaymentHistory**](Api/AccountingApi.md#getpaymenthistory) | **GET** /Payments/{PaymentID}/History | Allows you to retrieve history records of a payment
*AccountingApi* | [**getPaymentServices**](Api/AccountingApi.md#getpaymentservices) | **GET** /PaymentServices | Allows you to retrieve payment services
*AccountingApi* | [**getPayments**](Api/AccountingApi.md#getpayments) | **GET** /Payments | Allows you to retrieve payments for invoices and credit notes
*AccountingApi* | [**getPrepayment**](Api/AccountingApi.md#getprepayment) | **GET** /Prepayments/{PrepaymentID} | Allows you to retrieve a specified prepayments
*AccountingApi* | [**getPrepaymentHistory**](Api/AccountingApi.md#getprepaymenthistory) | **GET** /Prepayments/{PrepaymentID}/History | Allows you to retrieve a history records of an Prepayment
*AccountingApi* | [**getPrepayments**](Api/AccountingApi.md#getprepayments) | **GET** /Prepayments | Allows you to retrieve prepayments
*AccountingApi* | [**getPurchaseOrder**](Api/AccountingApi.md#getpurchaseorder) | **GET** /PurchaseOrders/{PurchaseOrderID} | Allows you to retrieve a specified purchase orders
*AccountingApi* | [**getPurchaseOrderAsPdf**](Api/AccountingApi.md#getpurchaseorderaspdf) | **GET** /PurchaseOrders/{PurchaseOrderID}/pdf | Allows you to retrieve purchase orders as PDF files
*AccountingApi* | [**getPurchaseOrderAttachmentByFileName**](Api/AccountingApi.md#getpurchaseorderattachmentbyfilename) | **GET** /PurchaseOrders/{PurchaseOrderID}/Attachments/{FileName} | Allows you to retrieve Attachment on a Purchase Order by Filename
*AccountingApi* | [**getPurchaseOrderAttachmentById**](Api/AccountingApi.md#getpurchaseorderattachmentbyid) | **GET** /PurchaseOrders/{PurchaseOrderID}/Attachments/{AttachmentID} | Allows you to retrieve specific Attachment on purchase order
*AccountingApi* | [**getPurchaseOrderAttachments**](Api/AccountingApi.md#getpurchaseorderattachments) | **GET** /PurchaseOrders/{PurchaseOrderID}/Attachments | Allows you to retrieve attachments for purchase orders
*AccountingApi* | [**getPurchaseOrderByNumber**](Api/AccountingApi.md#getpurchaseorderbynumber) | **GET** /PurchaseOrders/{PurchaseOrderNumber} | Allows you to retrieve a specified purchase orders
*AccountingApi* | [**getPurchaseOrderHistory**](Api/AccountingApi.md#getpurchaseorderhistory) | **GET** /PurchaseOrders/{PurchaseOrderID}/History | Allows you to retrieve history for PurchaseOrder
*AccountingApi* | [**getPurchaseOrders**](Api/AccountingApi.md#getpurchaseorders) | **GET** /PurchaseOrders | Allows you to retrieve purchase orders
*AccountingApi* | [**getQuote**](Api/AccountingApi.md#getquote) | **GET** /Quotes/{QuoteID} | Allows you to retrieve a specified quote
*AccountingApi* | [**getQuoteAsPdf**](Api/AccountingApi.md#getquoteaspdf) | **GET** /Quotes/{QuoteID}/pdf | Allows you to retrieve quotes as PDF files
*AccountingApi* | [**getQuoteAttachmentByFileName**](Api/AccountingApi.md#getquoteattachmentbyfilename) | **GET** /Quotes/{QuoteID}/Attachments/{FileName} | Allows you to retrieve Attachment on Quote by Filename
*AccountingApi* | [**getQuoteAttachmentById**](Api/AccountingApi.md#getquoteattachmentbyid) | **GET** /Quotes/{QuoteID}/Attachments/{AttachmentID} | Allows you to retrieve specific Attachment on Quote
*AccountingApi* | [**getQuoteAttachments**](Api/AccountingApi.md#getquoteattachments) | **GET** /Quotes/{QuoteID}/Attachments | Allows you to retrieve Attachments for Quotes
*AccountingApi* | [**getQuoteHistory**](Api/AccountingApi.md#getquotehistory) | **GET** /Quotes/{QuoteID}/History | Allows you to retrieve a history records of an quote
*AccountingApi* | [**getQuotes**](Api/AccountingApi.md#getquotes) | **GET** /Quotes | Allows you to retrieve any sales quotes
*AccountingApi* | [**getReceipt**](Api/AccountingApi.md#getreceipt) | **GET** /Receipts/{ReceiptID} | Allows you to retrieve a specified draft expense claim receipts
*AccountingApi* | [**getReceiptAttachmentByFileName**](Api/AccountingApi.md#getreceiptattachmentbyfilename) | **GET** /Receipts/{ReceiptID}/Attachments/{FileName} | Allows you to retrieve Attachments on expense claim receipts by file name
*AccountingApi* | [**getReceiptAttachmentById**](Api/AccountingApi.md#getreceiptattachmentbyid) | **GET** /Receipts/{ReceiptID}/Attachments/{AttachmentID} | Allows you to retrieve Attachments on expense claim receipts by ID
*AccountingApi* | [**getReceiptAttachments**](Api/AccountingApi.md#getreceiptattachments) | **GET** /Receipts/{ReceiptID}/Attachments | Allows you to retrieve Attachments for expense claim receipts
*AccountingApi* | [**getReceiptHistory**](Api/AccountingApi.md#getreceipthistory) | **GET** /Receipts/{ReceiptID}/History | Allows you to retrieve a history records of an Receipt
*AccountingApi* | [**getReceipts**](Api/AccountingApi.md#getreceipts) | **GET** /Receipts | Allows you to retrieve draft expense claim receipts for any user
*AccountingApi* | [**getRepeatingInvoice**](Api/AccountingApi.md#getrepeatinginvoice) | **GET** /RepeatingInvoices/{RepeatingInvoiceID} | Allows you to retrieve a specified repeating invoice
*AccountingApi* | [**getRepeatingInvoiceAttachmentByFileName**](Api/AccountingApi.md#getrepeatinginvoiceattachmentbyfilename) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{FileName} | Allows you to retrieve specified attachment on repeating invoices by file name
*AccountingApi* | [**getRepeatingInvoiceAttachmentById**](Api/AccountingApi.md#getrepeatinginvoiceattachmentbyid) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{AttachmentID} | Allows you to retrieve a specified Attachments on repeating invoices
*AccountingApi* | [**getRepeatingInvoiceAttachments**](Api/AccountingApi.md#getrepeatinginvoiceattachments) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments | Allows you to retrieve Attachments on repeating invoice
*AccountingApi* | [**getRepeatingInvoiceHistory**](Api/AccountingApi.md#getrepeatinginvoicehistory) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/History | Allows you to retrieve history for a repeating invoice
*AccountingApi* | [**getRepeatingInvoices**](Api/AccountingApi.md#getrepeatinginvoices) | **GET** /RepeatingInvoices | Allows you to retrieve any repeating invoices
*AccountingApi* | [**getReportAgedPayablesByContact**](Api/AccountingApi.md#getreportagedpayablesbycontact) | **GET** /Reports/AgedPayablesByContact | Allows you to retrieve report for AgedPayablesByContact
*AccountingApi* | [**getReportAgedReceivablesByContact**](Api/AccountingApi.md#getreportagedreceivablesbycontact) | **GET** /Reports/AgedReceivablesByContact | Allows you to retrieve report for AgedReceivablesByContact
*AccountingApi* | [**getReportBASorGST**](Api/AccountingApi.md#getreportbasorgst) | **GET** /Reports/{ReportID} | Allows you to retrieve report for BAS only valid for AU orgs
*AccountingApi* | [**getReportBASorGSTList**](Api/AccountingApi.md#getreportbasorgstlist) | **GET** /Reports | Allows you to retrieve report for BAS only valid for AU orgs
*AccountingApi* | [**getReportBalanceSheet**](Api/AccountingApi.md#getreportbalancesheet) | **GET** /Reports/BalanceSheet | Allows you to retrieve report for BalanceSheet
*AccountingApi* | [**getReportBankSummary**](Api/AccountingApi.md#getreportbanksummary) | **GET** /Reports/BankSummary | Allows you to retrieve report for BankSummary
*AccountingApi* | [**getReportBudgetSummary**](Api/AccountingApi.md#getreportbudgetsummary) | **GET** /Reports/BudgetSummary | Allows you to retrieve report for Budget Summary
*AccountingApi* | [**getReportExecutiveSummary**](Api/AccountingApi.md#getreportexecutivesummary) | **GET** /Reports/ExecutiveSummary | Allows you to retrieve report for ExecutiveSummary
*AccountingApi* | [**getReportProfitAndLoss**](Api/AccountingApi.md#getreportprofitandloss) | **GET** /Reports/ProfitAndLoss | Allows you to retrieve report for ProfitAndLoss
*AccountingApi* | [**getReportTenNinetyNine**](Api/AccountingApi.md#getreporttenninetynine) | **GET** /Reports/TenNinetyNine | Allows you to retrieve report for TenNinetyNine
*AccountingApi* | [**getReportTrialBalance**](Api/AccountingApi.md#getreporttrialbalance) | **GET** /Reports/TrialBalance | Allows you to retrieve report for TrialBalance
*AccountingApi* | [**getTaxRates**](Api/AccountingApi.md#gettaxrates) | **GET** /TaxRates | Allows you to retrieve Tax Rates
*AccountingApi* | [**getTrackingCategories**](Api/AccountingApi.md#gettrackingcategories) | **GET** /TrackingCategories | Allows you to retrieve tracking categories and options
*AccountingApi* | [**getTrackingCategory**](Api/AccountingApi.md#gettrackingcategory) | **GET** /TrackingCategories/{TrackingCategoryID} | Allows you to retrieve tracking categories and options for specified category
*AccountingApi* | [**getUser**](Api/AccountingApi.md#getuser) | **GET** /Users/{UserID} | Allows you to retrieve a specified user
*AccountingApi* | [**getUsers**](Api/AccountingApi.md#getusers) | **GET** /Users | Allows you to retrieve users
*AccountingApi* | [**postSetup**](Api/AccountingApi.md#postsetup) | **POST** /Setup | Allows you to set the chart of accounts, the conversion date and conversion balances
*AccountingApi* | [**updateAccount**](Api/AccountingApi.md#updateaccount) | **POST** /Accounts/{AccountID} | Allows you to update a chart of accounts
*AccountingApi* | [**updateAccountAttachmentByFileName**](Api/AccountingApi.md#updateaccountattachmentbyfilename) | **POST** /Accounts/{AccountID}/Attachments/{FileName} | Allows you to update Attachment on Account by Filename
*AccountingApi* | [**updateBankTransaction**](Api/AccountingApi.md#updatebanktransaction) | **POST** /BankTransactions/{BankTransactionID} | Allows you to update a single spend or receive money transaction
*AccountingApi* | [**updateBankTransactionAttachmentByFileName**](Api/AccountingApi.md#updatebanktransactionattachmentbyfilename) | **POST** /BankTransactions/{BankTransactionID}/Attachments/{FileName} | Allows you to update an Attachment on BankTransaction by Filename
*AccountingApi* | [**updateBankTransferAttachmentByFileName**](Api/AccountingApi.md#updatebanktransferattachmentbyfilename) | **POST** /BankTransfers/{BankTransferID}/Attachments/{FileName} | 
*AccountingApi* | [**updateContact**](Api/AccountingApi.md#updatecontact) | **POST** /Contacts/{ContactID} | 
*AccountingApi* | [**updateContactAttachmentByFileName**](Api/AccountingApi.md#updatecontactattachmentbyfilename) | **POST** /Contacts/{ContactID}/Attachments/{FileName} | 
*AccountingApi* | [**updateContactGroup**](Api/AccountingApi.md#updatecontactgroup) | **POST** /ContactGroups/{ContactGroupID} | Allows you to update a Contact Group
*AccountingApi* | [**updateCreditNote**](Api/AccountingApi.md#updatecreditnote) | **POST** /CreditNotes/{CreditNoteID} | Allows you to update a specific credit note
*AccountingApi* | [**updateCreditNoteAttachmentByFileName**](Api/AccountingApi.md#updatecreditnoteattachmentbyfilename) | **POST** /CreditNotes/{CreditNoteID}/Attachments/{FileName} | Allows you to update Attachments on CreditNote by file name
*AccountingApi* | [**updateExpenseClaim**](Api/AccountingApi.md#updateexpenseclaim) | **POST** /ExpenseClaims/{ExpenseClaimID} | Allows you to update specified expense claims
*AccountingApi* | [**updateInvoice**](Api/AccountingApi.md#updateinvoice) | **POST** /Invoices/{InvoiceID} | Allows you to update a specified sales invoices or purchase bills
*AccountingApi* | [**updateInvoiceAttachmentByFileName**](Api/AccountingApi.md#updateinvoiceattachmentbyfilename) | **POST** /Invoices/{InvoiceID}/Attachments/{FileName} | Allows you to update Attachment on invoices or purchase bills by it&#39;s filename
*AccountingApi* | [**updateItem**](Api/AccountingApi.md#updateitem) | **POST** /Items/{ItemID} | Allows you to update a specified item
*AccountingApi* | [**updateLinkedTransaction**](Api/AccountingApi.md#updatelinkedtransaction) | **POST** /LinkedTransactions/{LinkedTransactionID} | Allows you to update a specified linked transactions (billable expenses)
*AccountingApi* | [**updateManualJournal**](Api/AccountingApi.md#updatemanualjournal) | **POST** /ManualJournals/{ManualJournalID} | Allows you to update a specified manual journal
*AccountingApi* | [**updateManualJournalAttachmentByFileName**](Api/AccountingApi.md#updatemanualjournalattachmentbyfilename) | **POST** /ManualJournals/{ManualJournalID}/Attachments/{FileName} | Allows you to update a specified Attachment on ManualJournal by file name
*AccountingApi* | [**updateOrCreateBankTransactions**](Api/AccountingApi.md#updateorcreatebanktransactions) | **POST** /BankTransactions | Allows you to update or create one or more spend or receive money transaction
*AccountingApi* | [**updateOrCreateContacts**](Api/AccountingApi.md#updateorcreatecontacts) | **POST** /Contacts | Allows you to update OR create one or more contacts in a Xero organisation
*AccountingApi* | [**updateOrCreateCreditNotes**](Api/AccountingApi.md#updateorcreatecreditnotes) | **POST** /CreditNotes | Allows you to update OR create one or more credit notes
*AccountingApi* | [**updateOrCreateEmployees**](Api/AccountingApi.md#updateorcreateemployees) | **POST** /Employees | Allows you to create a single new employees used in Xero payrun
*AccountingApi* | [**updateOrCreateInvoices**](Api/AccountingApi.md#updateorcreateinvoices) | **POST** /Invoices | Allows you to update OR create one or more sales invoices or purchase bills
*AccountingApi* | [**updateOrCreateItems**](Api/AccountingApi.md#updateorcreateitems) | **POST** /Items | Allows you to update or create one or more items
*AccountingApi* | [**updateOrCreateManualJournals**](Api/AccountingApi.md#updateorcreatemanualjournals) | **POST** /ManualJournals | Allows you to create a single manual journal
*AccountingApi* | [**updateOrCreatePurchaseOrders**](Api/AccountingApi.md#updateorcreatepurchaseorders) | **POST** /PurchaseOrders | Allows you to update or create one or more purchase orders
*AccountingApi* | [**updateOrCreateQuotes**](Api/AccountingApi.md#updateorcreatequotes) | **POST** /Quotes | Allows you to update OR create one or more quotes
*AccountingApi* | [**updatePurchaseOrder**](Api/AccountingApi.md#updatepurchaseorder) | **POST** /PurchaseOrders/{PurchaseOrderID} | Allows you to update a specified purchase order
*AccountingApi* | [**updatePurchaseOrderAttachmentByFileName**](Api/AccountingApi.md#updatepurchaseorderattachmentbyfilename) | **POST** /PurchaseOrders/{PurchaseOrderID}/Attachments/{FileName} | Allows you to update Attachment on Purchase Order by Filename
*AccountingApi* | [**updateQuote**](Api/AccountingApi.md#updatequote) | **POST** /Quotes/{QuoteID} | Allows you to update a specified quote
*AccountingApi* | [**updateQuoteAttachmentByFileName**](Api/AccountingApi.md#updatequoteattachmentbyfilename) | **POST** /Quotes/{QuoteID}/Attachments/{FileName} | Allows you to update Attachment on Quote by Filename
*AccountingApi* | [**updateReceipt**](Api/AccountingApi.md#updatereceipt) | **POST** /Receipts/{ReceiptID} | Allows you to retrieve a specified draft expense claim receipts
*AccountingApi* | [**updateReceiptAttachmentByFileName**](Api/AccountingApi.md#updatereceiptattachmentbyfilename) | **POST** /Receipts/{ReceiptID}/Attachments/{FileName} | Allows you to update Attachment on expense claim receipts by file name
*AccountingApi* | [**updateRepeatingInvoiceAttachmentByFileName**](Api/AccountingApi.md#updaterepeatinginvoiceattachmentbyfilename) | **POST** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{FileName} | Allows you to update specified attachment on repeating invoices by file name
*AccountingApi* | [**updateTaxRate**](Api/AccountingApi.md#updatetaxrate) | **POST** /TaxRates | Allows you to update Tax Rates
*AccountingApi* | [**updateTrackingCategory**](Api/AccountingApi.md#updatetrackingcategory) | **POST** /TrackingCategories/{TrackingCategoryID} | Allows you to update tracking categories
*AccountingApi* | [**updateTrackingOptions**](Api/AccountingApi.md#updatetrackingoptions) | **POST** /TrackingCategories/{TrackingCategoryID}/Options/{TrackingOptionID} | Allows you to update options for a specified tracking category


## Documentation For Models

 - [Account](Model/Account.md)
 - [AccountType](Model/AccountType.md)
 - [Accounts](Model/Accounts.md)
 - [AccountsPayable](Model/AccountsPayable.md)
 - [AccountsReceivable](Model/AccountsReceivable.md)
 - [Action](Model/Action.md)
 - [Actions](Model/Actions.md)
 - [Address](Model/Address.md)
 - [Allocation](Model/Allocation.md)
 - [Allocations](Model/Allocations.md)
 - [Attachment](Model/Attachment.md)
 - [Attachments](Model/Attachments.md)
 - [BalanceDetails](Model/BalanceDetails.md)
 - [Balances](Model/Balances.md)
 - [BankTransaction](Model/BankTransaction.md)
 - [BankTransactions](Model/BankTransactions.md)
 - [BankTransfer](Model/BankTransfer.md)
 - [BankTransfers](Model/BankTransfers.md)
 - [BatchPayment](Model/BatchPayment.md)
 - [BatchPaymentDetails](Model/BatchPaymentDetails.md)
 - [BatchPayments](Model/BatchPayments.md)
 - [Bill](Model/Bill.md)
 - [BrandingTheme](Model/BrandingTheme.md)
 - [BrandingThemes](Model/BrandingThemes.md)
 - [CISOrgSetting](Model/CISOrgSetting.md)
 - [CISSetting](Model/CISSetting.md)
 - [CISSettings](Model/CISSettings.md)
 - [Contact](Model/Contact.md)
 - [ContactGroup](Model/ContactGroup.md)
 - [ContactGroups](Model/ContactGroups.md)
 - [ContactPerson](Model/ContactPerson.md)
 - [Contacts](Model/Contacts.md)
 - [ConversionBalances](Model/ConversionBalances.md)
 - [ConversionDate](Model/ConversionDate.md)
 - [CountryCode](Model/CountryCode.md)
 - [CreditNote](Model/CreditNote.md)
 - [CreditNotes](Model/CreditNotes.md)
 - [Currencies](Model/Currencies.md)
 - [Currency](Model/Currency.md)
 - [CurrencyCode](Model/CurrencyCode.md)
 - [Element](Model/Element.md)
 - [Employee](Model/Employee.md)
 - [Employees](Model/Employees.md)
 - [Error](Model/Error.md)
 - [ExpenseClaim](Model/ExpenseClaim.md)
 - [ExpenseClaims](Model/ExpenseClaims.md)
 - [ExternalLink](Model/ExternalLink.md)
 - [HistoryRecord](Model/HistoryRecord.md)
 - [HistoryRecords](Model/HistoryRecords.md)
 - [ImportSummary](Model/ImportSummary.md)
 - [ImportSummaryAccounts](Model/ImportSummaryAccounts.md)
 - [ImportSummaryObject](Model/ImportSummaryObject.md)
 - [ImportSummaryOrganisation](Model/ImportSummaryOrganisation.md)
 - [Invoice](Model/Invoice.md)
 - [InvoiceReminder](Model/InvoiceReminder.md)
 - [InvoiceReminders](Model/InvoiceReminders.md)
 - [Invoices](Model/Invoices.md)
 - [Item](Model/Item.md)
 - [Items](Model/Items.md)
 - [Journal](Model/Journal.md)
 - [JournalLine](Model/JournalLine.md)
 - [Journals](Model/Journals.md)
 - [LineAmountTypes](Model/LineAmountTypes.md)
 - [LineItem](Model/LineItem.md)
 - [LineItemTracking](Model/LineItemTracking.md)
 - [LinkedTransaction](Model/LinkedTransaction.md)
 - [LinkedTransactions](Model/LinkedTransactions.md)
 - [ManualJournal](Model/ManualJournal.md)
 - [ManualJournalLine](Model/ManualJournalLine.md)
 - [ManualJournals](Model/ManualJournals.md)
 - [OnlineInvoice](Model/OnlineInvoice.md)
 - [OnlineInvoices](Model/OnlineInvoices.md)
 - [Organisation](Model/Organisation.md)
 - [Organisations](Model/Organisations.md)
 - [Overpayment](Model/Overpayment.md)
 - [Overpayments](Model/Overpayments.md)
 - [Payment](Model/Payment.md)
 - [PaymentDelete](Model/PaymentDelete.md)
 - [PaymentService](Model/PaymentService.md)
 - [PaymentServices](Model/PaymentServices.md)
 - [PaymentTerm](Model/PaymentTerm.md)
 - [PaymentTermType](Model/PaymentTermType.md)
 - [Payments](Model/Payments.md)
 - [Phone](Model/Phone.md)
 - [Prepayment](Model/Prepayment.md)
 - [Prepayments](Model/Prepayments.md)
 - [Purchase](Model/Purchase.md)
 - [PurchaseOrder](Model/PurchaseOrder.md)
 - [PurchaseOrders](Model/PurchaseOrders.md)
 - [Quote](Model/Quote.md)
 - [QuoteLineAmountTypes](Model/QuoteLineAmountTypes.md)
 - [QuoteStatusCodes](Model/QuoteStatusCodes.md)
 - [Quotes](Model/Quotes.md)
 - [Receipt](Model/Receipt.md)
 - [Receipts](Model/Receipts.md)
 - [RepeatingInvoice](Model/RepeatingInvoice.md)
 - [RepeatingInvoices](Model/RepeatingInvoices.md)
 - [Report](Model/Report.md)
 - [ReportAttribute](Model/ReportAttribute.md)
 - [ReportCell](Model/ReportCell.md)
 - [ReportFields](Model/ReportFields.md)
 - [ReportRow](Model/ReportRow.md)
 - [ReportRows](Model/ReportRows.md)
 - [ReportWithRow](Model/ReportWithRow.md)
 - [ReportWithRows](Model/ReportWithRows.md)
 - [Reports](Model/Reports.md)
 - [RequestEmpty](Model/RequestEmpty.md)
 - [RowType](Model/RowType.md)
 - [SalesTrackingCategory](Model/SalesTrackingCategory.md)
 - [Schedule](Model/Schedule.md)
 - [Setup](Model/Setup.md)
 - [TaxComponent](Model/TaxComponent.md)
 - [TaxRate](Model/TaxRate.md)
 - [TaxRates](Model/TaxRates.md)
 - [TaxType](Model/TaxType.md)
 - [TenNinetyNineContact](Model/TenNinetyNineContact.md)
 - [TimeZone](Model/TimeZone.md)
 - [TrackingCategories](Model/TrackingCategories.md)
 - [TrackingCategory](Model/TrackingCategory.md)
 - [TrackingOption](Model/TrackingOption.md)
 - [TrackingOptions](Model/TrackingOptions.md)
 - [User](Model/User.md)
 - [Users](Model/Users.md)
 - [ValidationError](Model/ValidationError.md)


## Documentation For Authorization


## OAuth2

- **Type**: OAuth
- **Flow**: accessCode
- **Authorization URL**: https://login.xero.com/identity/connect/authorize
- **Scopes**: 
 - **email**: Grant read-only access to your email
 - **openid**: Grant read-only access to your open id
 - **profile**: your profile information
 - **accounting.transactions**: Grant read-write access to bank transactions, credit notes, invoices, repeating invoices
 - **accounting.transactions.read**: Grant read-only access to invoices
 - **accounting.reports.read**: Grant read-only access to accounting reports
 - **accounting.journals.read**: Grant read-only access to journals
 - **accounting.settings**: Grant read-write access to organisation and account settings
 - **accounting.settings.read**: Grant read-only access to organisation and account settings
 - **accounting.contacts**: Grant read-write access to contacts and contact groups
 - **accounting.contacts.read**: Grant read-only access to contacts and contact groups
 - **accounting.attachments**: Grant read-write access to attachments
 - **accounting.attachments.read**: Grant read-only access to attachments
 - **assets assets.read**: Grant read-only access to fixed assets
 - **bankfeeds**: Grant read-write access to bankfeeds
 - **files**: Grant read-write access to files and folders
 - **files.read**: Grant read-only access to files and folders
 - **payroll**: Grant read-write access to payroll
 - **payroll.read**: Grant read-only access to payroll
 - **payroll.employees**: Grant read-write access to payroll employees
 - **payroll.employees.read**: Grant read-only access to payroll employees
 - **payroll.leaveapplications**: Grant read-write access to payroll leaveapplications
 - **payroll.leaveapplications.read**: Grant read-only access to payroll leaveapplications
 - **payroll.payitems**: Grant read-write access to payroll payitems
 - **payroll.payitems.read**: Grant read-only access to payroll payitems
 - **payroll.payrollcalendars**: Grant read-write access to payroll calendars
 - **payroll.payrollcalendars.read**: Grant read-only access to payroll calendars
 - **payroll.payruns**: Grant read-write access to payroll payruns
 - **payroll.payruns.read**: Grant read-only access to payroll payruns
 - **payroll.payslip**: Grant read-write access to payroll payslips
 - **payroll.payslip.read**: Grant read-only access to payroll payslips
 - **payroll.settings.read**: Grant read-only access to payroll settings
 - **payroll.superfunds**: Grant read-write access to payroll superfunds
 - **payroll.superfunds.read**: Grant read-only access to payroll superfunds
 - **payroll.superfundproducts.read**: Grant read-only access to payroll superfundproducts
 - **payroll.timesheets**: Grant read-write access to payroll timesheets
 - **payroll.timesheets.read**: Grant read-only access to payroll timesheets
 - **paymentservices**: Grant read-write access to payment services
 - **projects**: Grant read-write access to projects
 - **projects.read**: Grant read-only access to projects


## Author

api@xero.com


