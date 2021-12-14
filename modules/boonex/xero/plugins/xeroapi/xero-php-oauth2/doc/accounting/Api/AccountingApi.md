# XeroAPI\XeroPHP\AccountingApi

All URIs are relative to *https://api.xero.com/api.xro/2.0*

Method | HTTP request | Description
------------- | ------------- | -------------
[**createAccount**](AccountingApi.md#createAccount) | **PUT** /Accounts | Creates a new chart of accounts
[**createAccountAttachmentByFileName**](AccountingApi.md#createAccountAttachmentByFileName) | **PUT** /Accounts/{AccountID}/Attachments/{FileName} | Creates an attachment on a specific account
[**createBankTransactionAttachmentByFileName**](AccountingApi.md#createBankTransactionAttachmentByFileName) | **PUT** /BankTransactions/{BankTransactionID}/Attachments/{FileName} | Creates an attachment for a specific bank transaction by filename
[**createBankTransactionHistoryRecord**](AccountingApi.md#createBankTransactionHistoryRecord) | **PUT** /BankTransactions/{BankTransactionID}/History | Creates a history record for a specific bank transactions
[**createBankTransactions**](AccountingApi.md#createBankTransactions) | **PUT** /BankTransactions | Creates one or more spent or received money transaction
[**createBankTransfer**](AccountingApi.md#createBankTransfer) | **PUT** /BankTransfers | Creates a bank transfer
[**createBankTransferAttachmentByFileName**](AccountingApi.md#createBankTransferAttachmentByFileName) | **PUT** /BankTransfers/{BankTransferID}/Attachments/{FileName} | 
[**createBankTransferHistoryRecord**](AccountingApi.md#createBankTransferHistoryRecord) | **PUT** /BankTransfers/{BankTransferID}/History | Creates a history record for a specific bank transfer
[**createBatchPayment**](AccountingApi.md#createBatchPayment) | **PUT** /BatchPayments | Creates one or many batch payments for invoices
[**createBatchPaymentHistoryRecord**](AccountingApi.md#createBatchPaymentHistoryRecord) | **PUT** /BatchPayments/{BatchPaymentID}/History | Creates a history record for a specific batch payment
[**createBrandingThemePaymentServices**](AccountingApi.md#createBrandingThemePaymentServices) | **POST** /BrandingThemes/{BrandingThemeID}/PaymentServices | Creates a new custom payment service for a specific branding theme
[**createContactAttachmentByFileName**](AccountingApi.md#createContactAttachmentByFileName) | **PUT** /Contacts/{ContactID}/Attachments/{FileName} | 
[**createContactGroup**](AccountingApi.md#createContactGroup) | **PUT** /ContactGroups | Creates a contact group
[**createContactGroupContacts**](AccountingApi.md#createContactGroupContacts) | **PUT** /ContactGroups/{ContactGroupID}/Contacts | Creates contacts to a specific contact group
[**createContactHistory**](AccountingApi.md#createContactHistory) | **PUT** /Contacts/{ContactID}/History | Creates a new history record for a specific contact
[**createContacts**](AccountingApi.md#createContacts) | **PUT** /Contacts | Creates multiple contacts (bulk) in a Xero organisation
[**createCreditNoteAllocation**](AccountingApi.md#createCreditNoteAllocation) | **PUT** /CreditNotes/{CreditNoteID}/Allocations | Creates allocation for a specific credit note
[**createCreditNoteAttachmentByFileName**](AccountingApi.md#createCreditNoteAttachmentByFileName) | **PUT** /CreditNotes/{CreditNoteID}/Attachments/{FileName} | Creates an attachment for a specific credit note
[**createCreditNoteHistory**](AccountingApi.md#createCreditNoteHistory) | **PUT** /CreditNotes/{CreditNoteID}/History | Retrieves history records of a specific credit note
[**createCreditNotes**](AccountingApi.md#createCreditNotes) | **PUT** /CreditNotes | Creates a new credit note
[**createCurrency**](AccountingApi.md#createCurrency) | **PUT** /Currencies | Create a new currency for a Xero organisation
[**createEmployees**](AccountingApi.md#createEmployees) | **PUT** /Employees | Creates new employees used in Xero payrun
[**createExpenseClaimHistory**](AccountingApi.md#createExpenseClaimHistory) | **PUT** /ExpenseClaims/{ExpenseClaimID}/History | Creates a history record for a specific expense claim
[**createExpenseClaims**](AccountingApi.md#createExpenseClaims) | **PUT** /ExpenseClaims | Creates expense claims
[**createInvoiceAttachmentByFileName**](AccountingApi.md#createInvoiceAttachmentByFileName) | **PUT** /Invoices/{InvoiceID}/Attachments/{FileName} | Creates an attachment for a specific invoice or purchase bill by filename
[**createInvoiceHistory**](AccountingApi.md#createInvoiceHistory) | **PUT** /Invoices/{InvoiceID}/History | Creates a history record for a specific invoice
[**createInvoices**](AccountingApi.md#createInvoices) | **PUT** /Invoices | Creates one or more sales invoices or purchase bills
[**createItemHistory**](AccountingApi.md#createItemHistory) | **PUT** /Items/{ItemID}/History | Creates a history record for a specific item
[**createItems**](AccountingApi.md#createItems) | **PUT** /Items | Creates one or more items
[**createLinkedTransaction**](AccountingApi.md#createLinkedTransaction) | **PUT** /LinkedTransactions | Creates linked transactions (billable expenses)
[**createManualJournalAttachmentByFileName**](AccountingApi.md#createManualJournalAttachmentByFileName) | **PUT** /ManualJournals/{ManualJournalID}/Attachments/{FileName} | Creates a specific attachment for a specific manual journal by file name
[**createManualJournalHistoryRecord**](AccountingApi.md#createManualJournalHistoryRecord) | **PUT** /ManualJournals/{ManualJournalID}/History | Creates a history record for a specific manual journal
[**createManualJournals**](AccountingApi.md#createManualJournals) | **PUT** /ManualJournals | Creates one or more manual journals
[**createOverpaymentAllocations**](AccountingApi.md#createOverpaymentAllocations) | **PUT** /Overpayments/{OverpaymentID}/Allocations | Creates a single allocation for a specific overpayment
[**createOverpaymentHistory**](AccountingApi.md#createOverpaymentHistory) | **PUT** /Overpayments/{OverpaymentID}/History | Creates a history record for a specific overpayment
[**createPayment**](AccountingApi.md#createPayment) | **POST** /Payments | Creates a single payment for invoice or credit notes
[**createPaymentHistory**](AccountingApi.md#createPaymentHistory) | **PUT** /Payments/{PaymentID}/History | Creates a history record for a specific payment
[**createPaymentService**](AccountingApi.md#createPaymentService) | **PUT** /PaymentServices | Creates a payment service
[**createPayments**](AccountingApi.md#createPayments) | **PUT** /Payments | Creates multiple payments for invoices or credit notes
[**createPrepaymentAllocations**](AccountingApi.md#createPrepaymentAllocations) | **PUT** /Prepayments/{PrepaymentID}/Allocations | Allows you to create an Allocation for prepayments
[**createPrepaymentHistory**](AccountingApi.md#createPrepaymentHistory) | **PUT** /Prepayments/{PrepaymentID}/History | Creates a history record for a specific prepayment
[**createPurchaseOrderAttachmentByFileName**](AccountingApi.md#createPurchaseOrderAttachmentByFileName) | **PUT** /PurchaseOrders/{PurchaseOrderID}/Attachments/{FileName} | Creates attachment for a specific purchase order
[**createPurchaseOrderHistory**](AccountingApi.md#createPurchaseOrderHistory) | **PUT** /PurchaseOrders/{PurchaseOrderID}/History | Creates a history record for a specific purchase orders
[**createPurchaseOrders**](AccountingApi.md#createPurchaseOrders) | **PUT** /PurchaseOrders | Creates one or more purchase orders
[**createQuoteAttachmentByFileName**](AccountingApi.md#createQuoteAttachmentByFileName) | **PUT** /Quotes/{QuoteID}/Attachments/{FileName} | Creates attachment for a specific quote
[**createQuoteHistory**](AccountingApi.md#createQuoteHistory) | **PUT** /Quotes/{QuoteID}/History | Creates a history record for a specific quote
[**createQuotes**](AccountingApi.md#createQuotes) | **PUT** /Quotes | Create one or more quotes
[**createReceipt**](AccountingApi.md#createReceipt) | **PUT** /Receipts | Creates draft expense claim receipts for any user
[**createReceiptAttachmentByFileName**](AccountingApi.md#createReceiptAttachmentByFileName) | **PUT** /Receipts/{ReceiptID}/Attachments/{FileName} | Creates an attachment on a specific expense claim receipts by file name
[**createReceiptHistory**](AccountingApi.md#createReceiptHistory) | **PUT** /Receipts/{ReceiptID}/History | Creates a history record for a specific receipt
[**createRepeatingInvoiceAttachmentByFileName**](AccountingApi.md#createRepeatingInvoiceAttachmentByFileName) | **PUT** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{FileName} | Creates an attachment from a specific repeating invoices by file name
[**createRepeatingInvoiceHistory**](AccountingApi.md#createRepeatingInvoiceHistory) | **PUT** /RepeatingInvoices/{RepeatingInvoiceID}/History | Creates a  history record for a specific repeating invoice
[**createTaxRates**](AccountingApi.md#createTaxRates) | **PUT** /TaxRates | Creates one or more tax rates
[**createTrackingCategory**](AccountingApi.md#createTrackingCategory) | **PUT** /TrackingCategories | Create tracking categories
[**createTrackingOptions**](AccountingApi.md#createTrackingOptions) | **PUT** /TrackingCategories/{TrackingCategoryID}/Options | Creates options for a specific tracking category
[**deleteAccount**](AccountingApi.md#deleteAccount) | **DELETE** /Accounts/{AccountID} | Deletes a chart of accounts
[**deleteContactGroupContact**](AccountingApi.md#deleteContactGroupContact) | **DELETE** /ContactGroups/{ContactGroupID}/Contacts/{ContactID} | Deletes a specific contact from a contact group using a unique contact Id
[**deleteContactGroupContacts**](AccountingApi.md#deleteContactGroupContacts) | **DELETE** /ContactGroups/{ContactGroupID}/Contacts | Deletes all contacts from a specific contact group
[**deleteItem**](AccountingApi.md#deleteItem) | **DELETE** /Items/{ItemID} | Deletes a specific item
[**deleteLinkedTransaction**](AccountingApi.md#deleteLinkedTransaction) | **DELETE** /LinkedTransactions/{LinkedTransactionID} | Deletes a specific linked transactions (billable expenses)
[**deletePayment**](AccountingApi.md#deletePayment) | **POST** /Payments/{PaymentID} | Updates a specific payment for invoices and credit notes
[**deleteTrackingCategory**](AccountingApi.md#deleteTrackingCategory) | **DELETE** /TrackingCategories/{TrackingCategoryID} | Deletes a specific tracking category
[**deleteTrackingOptions**](AccountingApi.md#deleteTrackingOptions) | **DELETE** /TrackingCategories/{TrackingCategoryID}/Options/{TrackingOptionID} | Deletes a specific option for a specific tracking category
[**emailInvoice**](AccountingApi.md#emailInvoice) | **POST** /Invoices/{InvoiceID}/Email | Sends a copy of a specific invoice to related contact via email
[**getAccount**](AccountingApi.md#getAccount) | **GET** /Accounts/{AccountID} | Retrieves a single chart of accounts by using a unique account Id
[**getAccountAttachmentByFileName**](AccountingApi.md#getAccountAttachmentByFileName) | **GET** /Accounts/{AccountID}/Attachments/{FileName} | Retrieves an attachment for a specific account by filename
[**getAccountAttachmentById**](AccountingApi.md#getAccountAttachmentById) | **GET** /Accounts/{AccountID}/Attachments/{AttachmentID} | Retrieves a specific attachment from a specific account using a unique attachment Id
[**getAccountAttachments**](AccountingApi.md#getAccountAttachments) | **GET** /Accounts/{AccountID}/Attachments | Retrieves attachments for a specific accounts by using a unique account Id
[**getAccounts**](AccountingApi.md#getAccounts) | **GET** /Accounts | Retrieves the full chart of accounts
[**getBankTransaction**](AccountingApi.md#getBankTransaction) | **GET** /BankTransactions/{BankTransactionID} | Retrieves a single spent or received money transaction by using a unique bank transaction Id
[**getBankTransactionAttachmentByFileName**](AccountingApi.md#getBankTransactionAttachmentByFileName) | **GET** /BankTransactions/{BankTransactionID}/Attachments/{FileName} | Retrieves a specific attachment from a specific bank transaction by filename
[**getBankTransactionAttachmentById**](AccountingApi.md#getBankTransactionAttachmentById) | **GET** /BankTransactions/{BankTransactionID}/Attachments/{AttachmentID} | Retrieves specific attachments from a specific BankTransaction using a unique attachment Id
[**getBankTransactionAttachments**](AccountingApi.md#getBankTransactionAttachments) | **GET** /BankTransactions/{BankTransactionID}/Attachments | Retrieves any attachments from a specific bank transactions
[**getBankTransactions**](AccountingApi.md#getBankTransactions) | **GET** /BankTransactions | Retrieves any spent or received money transactions
[**getBankTransactionsHistory**](AccountingApi.md#getBankTransactionsHistory) | **GET** /BankTransactions/{BankTransactionID}/History | Retrieves history from a specific bank transaction using a unique bank transaction Id
[**getBankTransfer**](AccountingApi.md#getBankTransfer) | **GET** /BankTransfers/{BankTransferID} | Retrieves specific bank transfers by using a unique bank transfer Id
[**getBankTransferAttachmentByFileName**](AccountingApi.md#getBankTransferAttachmentByFileName) | **GET** /BankTransfers/{BankTransferID}/Attachments/{FileName} | Retrieves a specific attachment on a specific bank transfer by file name
[**getBankTransferAttachmentById**](AccountingApi.md#getBankTransferAttachmentById) | **GET** /BankTransfers/{BankTransferID}/Attachments/{AttachmentID} | Retrieves a specific attachment from a specific bank transfer using a unique attachment ID
[**getBankTransferAttachments**](AccountingApi.md#getBankTransferAttachments) | **GET** /BankTransfers/{BankTransferID}/Attachments | Retrieves attachments from a specific bank transfer
[**getBankTransferHistory**](AccountingApi.md#getBankTransferHistory) | **GET** /BankTransfers/{BankTransferID}/History | Retrieves history from a specific bank transfer using a unique bank transfer Id
[**getBankTransfers**](AccountingApi.md#getBankTransfers) | **GET** /BankTransfers | Retrieves all bank transfers
[**getBatchPaymentHistory**](AccountingApi.md#getBatchPaymentHistory) | **GET** /BatchPayments/{BatchPaymentID}/History | Retrieves history from a specific batch payment
[**getBatchPayments**](AccountingApi.md#getBatchPayments) | **GET** /BatchPayments | Retrieves either one or many batch payments for invoices
[**getBrandingTheme**](AccountingApi.md#getBrandingTheme) | **GET** /BrandingThemes/{BrandingThemeID} | Retrieves a specific branding theme using a unique branding theme Id
[**getBrandingThemePaymentServices**](AccountingApi.md#getBrandingThemePaymentServices) | **GET** /BrandingThemes/{BrandingThemeID}/PaymentServices | Retrieves the payment services for a specific branding theme
[**getBrandingThemes**](AccountingApi.md#getBrandingThemes) | **GET** /BrandingThemes | Retrieves all the branding themes
[**getContact**](AccountingApi.md#getContact) | **GET** /Contacts/{ContactID} | Retrieves a specific contacts in a Xero organisation using a unique contact Id
[**getContactAttachmentByFileName**](AccountingApi.md#getContactAttachmentByFileName) | **GET** /Contacts/{ContactID}/Attachments/{FileName} | Retrieves a specific attachment from a specific contact by file name
[**getContactAttachmentById**](AccountingApi.md#getContactAttachmentById) | **GET** /Contacts/{ContactID}/Attachments/{AttachmentID} | Retrieves a specific attachment from a specific contact using a unique attachment Id
[**getContactAttachments**](AccountingApi.md#getContactAttachments) | **GET** /Contacts/{ContactID}/Attachments | Retrieves attachments for a specific contact in a Xero organisation
[**getContactByContactNumber**](AccountingApi.md#getContactByContactNumber) | **GET** /Contacts/{ContactNumber} | Retrieves a specific contact by contact number in a Xero organisation
[**getContactCISSettings**](AccountingApi.md#getContactCISSettings) | **GET** /Contacts/{ContactID}/CISSettings | Retrieves CIS settings for a specific contact in a Xero organisation
[**getContactGroup**](AccountingApi.md#getContactGroup) | **GET** /ContactGroups/{ContactGroupID} | Retrieves a specific contact group by using a unique contact group Id
[**getContactGroups**](AccountingApi.md#getContactGroups) | **GET** /ContactGroups | Retrieves the contact Id and name of all the contacts in a contact group
[**getContactHistory**](AccountingApi.md#getContactHistory) | **GET** /Contacts/{ContactID}/History | Retrieves history records for a specific contact
[**getContacts**](AccountingApi.md#getContacts) | **GET** /Contacts | Retrieves all contacts in a Xero organisation
[**getCreditNote**](AccountingApi.md#getCreditNote) | **GET** /CreditNotes/{CreditNoteID} | Retrieves a specific credit note using a unique credit note Id
[**getCreditNoteAsPdf**](AccountingApi.md#getCreditNoteAsPdf) | **GET** /CreditNotes/{CreditNoteID}/pdf | Retrieves credit notes as PDF files
[**getCreditNoteAttachmentByFileName**](AccountingApi.md#getCreditNoteAttachmentByFileName) | **GET** /CreditNotes/{CreditNoteID}/Attachments/{FileName} | Retrieves a specific attachment on a specific credit note by file name
[**getCreditNoteAttachmentById**](AccountingApi.md#getCreditNoteAttachmentById) | **GET** /CreditNotes/{CreditNoteID}/Attachments/{AttachmentID} | Retrieves a specific attachment from a specific credit note using a unique attachment Id
[**getCreditNoteAttachments**](AccountingApi.md#getCreditNoteAttachments) | **GET** /CreditNotes/{CreditNoteID}/Attachments | Retrieves attachments for a specific credit notes
[**getCreditNoteHistory**](AccountingApi.md#getCreditNoteHistory) | **GET** /CreditNotes/{CreditNoteID}/History | Retrieves history records of a specific credit note
[**getCreditNotes**](AccountingApi.md#getCreditNotes) | **GET** /CreditNotes | Retrieves any credit notes
[**getCurrencies**](AccountingApi.md#getCurrencies) | **GET** /Currencies | Retrieves currencies for your Xero organisation
[**getEmployee**](AccountingApi.md#getEmployee) | **GET** /Employees/{EmployeeID} | Retrieves a specific employee used in Xero payrun using a unique employee Id
[**getEmployees**](AccountingApi.md#getEmployees) | **GET** /Employees | Retrieves employees used in Xero payrun
[**getExpenseClaim**](AccountingApi.md#getExpenseClaim) | **GET** /ExpenseClaims/{ExpenseClaimID} | Retrieves a specific expense claim using a unique expense claim Id
[**getExpenseClaimHistory**](AccountingApi.md#getExpenseClaimHistory) | **GET** /ExpenseClaims/{ExpenseClaimID}/History | Retrieves history records of a specific expense claim
[**getExpenseClaims**](AccountingApi.md#getExpenseClaims) | **GET** /ExpenseClaims | Retrieves expense claims
[**getInvoice**](AccountingApi.md#getInvoice) | **GET** /Invoices/{InvoiceID} | Retrieves a specific sales invoice or purchase bill using a unique invoice Id
[**getInvoiceAsPdf**](AccountingApi.md#getInvoiceAsPdf) | **GET** /Invoices/{InvoiceID}/pdf | Retrieves invoices or purchase bills as PDF files
[**getInvoiceAttachmentByFileName**](AccountingApi.md#getInvoiceAttachmentByFileName) | **GET** /Invoices/{InvoiceID}/Attachments/{FileName} | Retrieves an attachment from a specific invoice or purchase bill by filename
[**getInvoiceAttachmentById**](AccountingApi.md#getInvoiceAttachmentById) | **GET** /Invoices/{InvoiceID}/Attachments/{AttachmentID} | Retrieves a specific attachment from a specific invoices or purchase bills by using a unique attachment Id
[**getInvoiceAttachments**](AccountingApi.md#getInvoiceAttachments) | **GET** /Invoices/{InvoiceID}/Attachments | Retrieves attachments for a specific invoice or purchase bill
[**getInvoiceHistory**](AccountingApi.md#getInvoiceHistory) | **GET** /Invoices/{InvoiceID}/History | Retrieves history records for a specific invoice
[**getInvoiceReminders**](AccountingApi.md#getInvoiceReminders) | **GET** /InvoiceReminders/Settings | Retrieves invoice reminder settings
[**getInvoices**](AccountingApi.md#getInvoices) | **GET** /Invoices | Retrieves sales invoices or purchase bills
[**getItem**](AccountingApi.md#getItem) | **GET** /Items/{ItemID} | Retrieves a specific item using a unique item Id
[**getItemHistory**](AccountingApi.md#getItemHistory) | **GET** /Items/{ItemID}/History | Retrieves history for a specific item
[**getItems**](AccountingApi.md#getItems) | **GET** /Items | Retrieves items
[**getJournal**](AccountingApi.md#getJournal) | **GET** /Journals/{JournalID} | Retrieves a specific journal using a unique journal Id.
[**getJournals**](AccountingApi.md#getJournals) | **GET** /Journals | Retrieves journals
[**getLinkedTransaction**](AccountingApi.md#getLinkedTransaction) | **GET** /LinkedTransactions/{LinkedTransactionID} | Retrieves a specific linked transaction (billable expenses) using a unique linked transaction Id
[**getLinkedTransactions**](AccountingApi.md#getLinkedTransactions) | **GET** /LinkedTransactions | Retrieves linked transactions (billable expenses)
[**getManualJournal**](AccountingApi.md#getManualJournal) | **GET** /ManualJournals/{ManualJournalID} | Retrieves a specific manual journal
[**getManualJournalAttachmentByFileName**](AccountingApi.md#getManualJournalAttachmentByFileName) | **GET** /ManualJournals/{ManualJournalID}/Attachments/{FileName} | Retrieves a specific attachment from a specific manual journal by file name
[**getManualJournalAttachmentById**](AccountingApi.md#getManualJournalAttachmentById) | **GET** /ManualJournals/{ManualJournalID}/Attachments/{AttachmentID} | Allows you to retrieve a specific attachment from a specific manual journal using a unique attachment Id
[**getManualJournalAttachments**](AccountingApi.md#getManualJournalAttachments) | **GET** /ManualJournals/{ManualJournalID}/Attachments | Retrieves attachment for a specific manual journal
[**getManualJournals**](AccountingApi.md#getManualJournals) | **GET** /ManualJournals | Retrieves manual journals
[**getManualJournalsHistory**](AccountingApi.md#getManualJournalsHistory) | **GET** /ManualJournals/{ManualJournalID}/History | Retrieves history for a specific manual journal
[**getOnlineInvoice**](AccountingApi.md#getOnlineInvoice) | **GET** /Invoices/{InvoiceID}/OnlineInvoice | Retrieves a URL to an online invoice
[**getOrganisationActions**](AccountingApi.md#getOrganisationActions) | **GET** /Organisation/Actions | Retrieves a list of the key actions your app has permission to perform in the connected Xero organisation.
[**getOrganisationCISSettings**](AccountingApi.md#getOrganisationCISSettings) | **GET** /Organisation/{OrganisationID}/CISSettings | Retrieves the CIS settings for the Xero organistaion.
[**getOrganisations**](AccountingApi.md#getOrganisations) | **GET** /Organisation | Retrieves Xero organisation details
[**getOverpayment**](AccountingApi.md#getOverpayment) | **GET** /Overpayments/{OverpaymentID} | Retrieves a specific overpayment using a unique overpayment Id
[**getOverpaymentHistory**](AccountingApi.md#getOverpaymentHistory) | **GET** /Overpayments/{OverpaymentID}/History | Retrieves history records of a specific overpayment
[**getOverpayments**](AccountingApi.md#getOverpayments) | **GET** /Overpayments | Retrieves overpayments
[**getPayment**](AccountingApi.md#getPayment) | **GET** /Payments/{PaymentID} | Retrieves a specific payment for invoices and credit notes using a unique payment Id
[**getPaymentHistory**](AccountingApi.md#getPaymentHistory) | **GET** /Payments/{PaymentID}/History | Retrieves history records of a specific payment
[**getPaymentServices**](AccountingApi.md#getPaymentServices) | **GET** /PaymentServices | Retrieves payment services
[**getPayments**](AccountingApi.md#getPayments) | **GET** /Payments | Retrieves payments for invoices and credit notes
[**getPrepayment**](AccountingApi.md#getPrepayment) | **GET** /Prepayments/{PrepaymentID} | Allows you to retrieve a specified prepayments
[**getPrepaymentHistory**](AccountingApi.md#getPrepaymentHistory) | **GET** /Prepayments/{PrepaymentID}/History | Retrieves history record for a specific prepayment
[**getPrepayments**](AccountingApi.md#getPrepayments) | **GET** /Prepayments | Retrieves prepayments
[**getPurchaseOrder**](AccountingApi.md#getPurchaseOrder) | **GET** /PurchaseOrders/{PurchaseOrderID} | Retrieves a specific purchase order using a unique purchase order Id
[**getPurchaseOrderAsPdf**](AccountingApi.md#getPurchaseOrderAsPdf) | **GET** /PurchaseOrders/{PurchaseOrderID}/pdf | Retrieves specific purchase order as PDF files using a unique purchase order Id
[**getPurchaseOrderAttachmentByFileName**](AccountingApi.md#getPurchaseOrderAttachmentByFileName) | **GET** /PurchaseOrders/{PurchaseOrderID}/Attachments/{FileName} | Retrieves a specific attachment for a specific purchase order by filename
[**getPurchaseOrderAttachmentById**](AccountingApi.md#getPurchaseOrderAttachmentById) | **GET** /PurchaseOrders/{PurchaseOrderID}/Attachments/{AttachmentID} | Retrieves specific attachment for a specific purchase order using a unique attachment Id
[**getPurchaseOrderAttachments**](AccountingApi.md#getPurchaseOrderAttachments) | **GET** /PurchaseOrders/{PurchaseOrderID}/Attachments | Retrieves attachments for a specific purchase order
[**getPurchaseOrderByNumber**](AccountingApi.md#getPurchaseOrderByNumber) | **GET** /PurchaseOrders/{PurchaseOrderNumber} | Retrieves a specific purchase order using purchase order number
[**getPurchaseOrderHistory**](AccountingApi.md#getPurchaseOrderHistory) | **GET** /PurchaseOrders/{PurchaseOrderID}/History | Retrieves history for a specific purchase order
[**getPurchaseOrders**](AccountingApi.md#getPurchaseOrders) | **GET** /PurchaseOrders | Retrieves purchase orders
[**getQuote**](AccountingApi.md#getQuote) | **GET** /Quotes/{QuoteID} | Retrieves a specific quote using a unique quote Id
[**getQuoteAsPdf**](AccountingApi.md#getQuoteAsPdf) | **GET** /Quotes/{QuoteID}/pdf | Retrieves a specific quote as a PDF file using a unique quote Id
[**getQuoteAttachmentByFileName**](AccountingApi.md#getQuoteAttachmentByFileName) | **GET** /Quotes/{QuoteID}/Attachments/{FileName} | Retrieves a specific attachment from a specific quote by filename
[**getQuoteAttachmentById**](AccountingApi.md#getQuoteAttachmentById) | **GET** /Quotes/{QuoteID}/Attachments/{AttachmentID} | Retrieves a specific attachment from a specific quote using a unique attachment Id
[**getQuoteAttachments**](AccountingApi.md#getQuoteAttachments) | **GET** /Quotes/{QuoteID}/Attachments | Retrieves attachments for a specific quote
[**getQuoteHistory**](AccountingApi.md#getQuoteHistory) | **GET** /Quotes/{QuoteID}/History | Retrieves history records of a specific quote
[**getQuotes**](AccountingApi.md#getQuotes) | **GET** /Quotes | Retrieves sales quotes
[**getReceipt**](AccountingApi.md#getReceipt) | **GET** /Receipts/{ReceiptID} | Retrieves a specific draft expense claim receipt by using a unique receipt Id
[**getReceiptAttachmentByFileName**](AccountingApi.md#getReceiptAttachmentByFileName) | **GET** /Receipts/{ReceiptID}/Attachments/{FileName} | Retrieves a specific attachment from a specific expense claim receipts by file name
[**getReceiptAttachmentById**](AccountingApi.md#getReceiptAttachmentById) | **GET** /Receipts/{ReceiptID}/Attachments/{AttachmentID} | Retrieves a specific attachments from a specific expense claim receipts by using a unique attachment Id
[**getReceiptAttachments**](AccountingApi.md#getReceiptAttachments) | **GET** /Receipts/{ReceiptID}/Attachments | Retrieves attachments for a specific expense claim receipt
[**getReceiptHistory**](AccountingApi.md#getReceiptHistory) | **GET** /Receipts/{ReceiptID}/History | Retrieves a history record for a specific receipt
[**getReceipts**](AccountingApi.md#getReceipts) | **GET** /Receipts | Retrieves draft expense claim receipts for any user
[**getRepeatingInvoice**](AccountingApi.md#getRepeatingInvoice) | **GET** /RepeatingInvoices/{RepeatingInvoiceID} | Retrieves a specific repeating invoice by using a unique repeating invoice Id
[**getRepeatingInvoiceAttachmentByFileName**](AccountingApi.md#getRepeatingInvoiceAttachmentByFileName) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{FileName} | Retrieves a specific attachment from a specific repeating invoices by file name
[**getRepeatingInvoiceAttachmentById**](AccountingApi.md#getRepeatingInvoiceAttachmentById) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{AttachmentID} | Retrieves a specific attachment from a specific repeating invoice
[**getRepeatingInvoiceAttachments**](AccountingApi.md#getRepeatingInvoiceAttachments) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments | Retrieves attachments from a specific repeating invoice
[**getRepeatingInvoiceHistory**](AccountingApi.md#getRepeatingInvoiceHistory) | **GET** /RepeatingInvoices/{RepeatingInvoiceID}/History | Retrieves history record for a specific repeating invoice
[**getRepeatingInvoices**](AccountingApi.md#getRepeatingInvoices) | **GET** /RepeatingInvoices | Retrieves repeating invoices
[**getReportAgedPayablesByContact**](AccountingApi.md#getReportAgedPayablesByContact) | **GET** /Reports/AgedPayablesByContact | Retrieves report for aged payables by contact
[**getReportAgedReceivablesByContact**](AccountingApi.md#getReportAgedReceivablesByContact) | **GET** /Reports/AgedReceivablesByContact | Retrieves report for aged receivables by contact
[**getReportBASorGST**](AccountingApi.md#getReportBASorGST) | **GET** /Reports/{ReportID} | Retrieves a specific report for BAS using a unique report Id (only valid for AU orgs)
[**getReportBASorGSTList**](AccountingApi.md#getReportBASorGSTList) | **GET** /Reports | Retrieves report for BAS (only valid for AU orgs)
[**getReportBalanceSheet**](AccountingApi.md#getReportBalanceSheet) | **GET** /Reports/BalanceSheet | Retrieves report for balancesheet
[**getReportBankSummary**](AccountingApi.md#getReportBankSummary) | **GET** /Reports/BankSummary | Retrieves report for bank summary
[**getReportBudgetSummary**](AccountingApi.md#getReportBudgetSummary) | **GET** /Reports/BudgetSummary | Retrieves report for budget summary
[**getReportExecutiveSummary**](AccountingApi.md#getReportExecutiveSummary) | **GET** /Reports/ExecutiveSummary | Retrieves report for executive summary
[**getReportProfitAndLoss**](AccountingApi.md#getReportProfitAndLoss) | **GET** /Reports/ProfitAndLoss | Retrieves report for profit and loss
[**getReportTenNinetyNine**](AccountingApi.md#getReportTenNinetyNine) | **GET** /Reports/TenNinetyNine | Retrieve reports for 1099
[**getReportTrialBalance**](AccountingApi.md#getReportTrialBalance) | **GET** /Reports/TrialBalance | Retrieves report for trial balance
[**getTaxRates**](AccountingApi.md#getTaxRates) | **GET** /TaxRates | Retrieves tax rates
[**getTrackingCategories**](AccountingApi.md#getTrackingCategories) | **GET** /TrackingCategories | Retrieves tracking categories and options
[**getTrackingCategory**](AccountingApi.md#getTrackingCategory) | **GET** /TrackingCategories/{TrackingCategoryID} | Retrieves specific tracking categories and options using a unique tracking category Id
[**getUser**](AccountingApi.md#getUser) | **GET** /Users/{UserID} | Retrieves a specific user
[**getUsers**](AccountingApi.md#getUsers) | **GET** /Users | Retrieves users
[**postSetup**](AccountingApi.md#postSetup) | **POST** /Setup | Sets the chart of accounts, the conversion date and conversion balances
[**updateAccount**](AccountingApi.md#updateAccount) | **POST** /Accounts/{AccountID} | Updates a chart of accounts
[**updateAccountAttachmentByFileName**](AccountingApi.md#updateAccountAttachmentByFileName) | **POST** /Accounts/{AccountID}/Attachments/{FileName} | Updates attachment on a specific account by filename
[**updateBankTransaction**](AccountingApi.md#updateBankTransaction) | **POST** /BankTransactions/{BankTransactionID} | Updates a single spent or received money transaction
[**updateBankTransactionAttachmentByFileName**](AccountingApi.md#updateBankTransactionAttachmentByFileName) | **POST** /BankTransactions/{BankTransactionID}/Attachments/{FileName} | Updates a specific attachment from a specific bank transaction by filename
[**updateBankTransferAttachmentByFileName**](AccountingApi.md#updateBankTransferAttachmentByFileName) | **POST** /BankTransfers/{BankTransferID}/Attachments/{FileName} | 
[**updateContact**](AccountingApi.md#updateContact) | **POST** /Contacts/{ContactID} | Updates a specific contact in a Xero organisation
[**updateContactAttachmentByFileName**](AccountingApi.md#updateContactAttachmentByFileName) | **POST** /Contacts/{ContactID}/Attachments/{FileName} | 
[**updateContactGroup**](AccountingApi.md#updateContactGroup) | **POST** /ContactGroups/{ContactGroupID} | Updates a specific contact group
[**updateCreditNote**](AccountingApi.md#updateCreditNote) | **POST** /CreditNotes/{CreditNoteID} | Updates a specific credit note
[**updateCreditNoteAttachmentByFileName**](AccountingApi.md#updateCreditNoteAttachmentByFileName) | **POST** /CreditNotes/{CreditNoteID}/Attachments/{FileName} | Updates attachments on a specific credit note by file name
[**updateExpenseClaim**](AccountingApi.md#updateExpenseClaim) | **POST** /ExpenseClaims/{ExpenseClaimID} | Updates a specific expense claims
[**updateInvoice**](AccountingApi.md#updateInvoice) | **POST** /Invoices/{InvoiceID} | Updates a specific sales invoices or purchase bills
[**updateInvoiceAttachmentByFileName**](AccountingApi.md#updateInvoiceAttachmentByFileName) | **POST** /Invoices/{InvoiceID}/Attachments/{FileName} | Updates an attachment from a specific invoices or purchase bill by filename
[**updateItem**](AccountingApi.md#updateItem) | **POST** /Items/{ItemID} | Updates a specific item
[**updateLinkedTransaction**](AccountingApi.md#updateLinkedTransaction) | **POST** /LinkedTransactions/{LinkedTransactionID} | Updates a specific linked transactions (billable expenses)
[**updateManualJournal**](AccountingApi.md#updateManualJournal) | **POST** /ManualJournals/{ManualJournalID} | Updates a specific manual journal
[**updateManualJournalAttachmentByFileName**](AccountingApi.md#updateManualJournalAttachmentByFileName) | **POST** /ManualJournals/{ManualJournalID}/Attachments/{FileName} | Updates a specific attachment from a specific manual journal by file name
[**updateOrCreateBankTransactions**](AccountingApi.md#updateOrCreateBankTransactions) | **POST** /BankTransactions | Updates or creates one or more spent or received money transaction
[**updateOrCreateContacts**](AccountingApi.md#updateOrCreateContacts) | **POST** /Contacts | Updates or creates one or more contacts in a Xero organisation
[**updateOrCreateCreditNotes**](AccountingApi.md#updateOrCreateCreditNotes) | **POST** /CreditNotes | Updates or creates one or more credit notes
[**updateOrCreateEmployees**](AccountingApi.md#updateOrCreateEmployees) | **POST** /Employees | Creates a single new employees used in Xero payrun
[**updateOrCreateInvoices**](AccountingApi.md#updateOrCreateInvoices) | **POST** /Invoices | Updates or creates one or more sales invoices or purchase bills
[**updateOrCreateItems**](AccountingApi.md#updateOrCreateItems) | **POST** /Items | Updates or creates one or more items
[**updateOrCreateManualJournals**](AccountingApi.md#updateOrCreateManualJournals) | **POST** /ManualJournals | Updates or creates a single manual journal
[**updateOrCreatePurchaseOrders**](AccountingApi.md#updateOrCreatePurchaseOrders) | **POST** /PurchaseOrders | Updates or creates one or more purchase orders
[**updateOrCreateQuotes**](AccountingApi.md#updateOrCreateQuotes) | **POST** /Quotes | Updates or creates one or more quotes
[**updatePurchaseOrder**](AccountingApi.md#updatePurchaseOrder) | **POST** /PurchaseOrders/{PurchaseOrderID} | Updates a specific purchase order
[**updatePurchaseOrderAttachmentByFileName**](AccountingApi.md#updatePurchaseOrderAttachmentByFileName) | **POST** /PurchaseOrders/{PurchaseOrderID}/Attachments/{FileName} | Updates a specific attachment for a specific purchase order by filename
[**updateQuote**](AccountingApi.md#updateQuote) | **POST** /Quotes/{QuoteID} | Updates a specific quote
[**updateQuoteAttachmentByFileName**](AccountingApi.md#updateQuoteAttachmentByFileName) | **POST** /Quotes/{QuoteID}/Attachments/{FileName} | Updates a specific attachment from a specific quote by filename
[**updateReceipt**](AccountingApi.md#updateReceipt) | **POST** /Receipts/{ReceiptID} | Updates a specific draft expense claim receipts
[**updateReceiptAttachmentByFileName**](AccountingApi.md#updateReceiptAttachmentByFileName) | **POST** /Receipts/{ReceiptID}/Attachments/{FileName} | Updates a specific attachment on a specific expense claim receipts by file name
[**updateRepeatingInvoiceAttachmentByFileName**](AccountingApi.md#updateRepeatingInvoiceAttachmentByFileName) | **POST** /RepeatingInvoices/{RepeatingInvoiceID}/Attachments/{FileName} | Updates a specific attachment from a specific repeating invoices by file name
[**updateTaxRate**](AccountingApi.md#updateTaxRate) | **POST** /TaxRates | Updates tax rates
[**updateTrackingCategory**](AccountingApi.md#updateTrackingCategory) | **POST** /TrackingCategories/{TrackingCategoryID} | Updates a specific tracking category
[**updateTrackingOptions**](AccountingApi.md#updateTrackingOptions) | **POST** /TrackingCategories/{TrackingCategoryID}/Options/{TrackingOptionID} | Updates a specific option for a specific tracking category


# **createAccount**
> \XeroAPI\XeroPHP\Models\Accounting\Accounts createAccount($xero_tenant_id, $account)

Creates a new chart of accounts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account = { "Code":"123456", "Name":"Foobar", "Type":"EXPENSE", "Description":"Hello World" }; // \XeroAPI\XeroPHP\Models\Accounting\Account | Account object in body of request

try {
    $result = $apiInstance->createAccount($xero_tenant_id, $account);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createAccount: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account** | [**\XeroAPI\XeroPHP\Models\Accounting\Account**](../Model/Account.md)| Account object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Accounts**](../Model/Accounts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createAccountAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createAccountAttachmentByFileName($xero_tenant_id, $account_id, $file_name, $body)

Creates an attachment on a specific account

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createAccountAttachmentByFileName($xero_tenant_id, $account_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createAccountAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBankTransactionAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createBankTransactionAttachmentByFileName($xero_tenant_id, $bank_transaction_id, $file_name, $body)

Creates an attachment for a specific bank transaction by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createBankTransactionAttachmentByFileName($xero_tenant_id, $bank_transaction_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBankTransactionAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBankTransactionHistoryRecord**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createBankTransactionHistoryRecord($xero_tenant_id, $bank_transaction_id, $history_records)

Creates a history record for a specific bank transactions

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createBankTransactionHistoryRecord($xero_tenant_id, $bank_transaction_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBankTransactionHistoryRecord: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBankTransactions**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransactions createBankTransactions($xero_tenant_id, $bank_transactions, $summarize_errors, $unitdp)

Creates one or more spent or received money transaction

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transactions = { bankTransactions: [{ type: BankTransaction.TypeEnum.SPEND, contact: { contactID: "00000000-0000-0000-0000-000000000000" }, lineItems: [{ description: "Foobar", quantity: 1.0, unitAmount: 20.0, accountCode: "000" } ], bankAccount: { code: "000" }}]}; // \XeroAPI\XeroPHP\Models\Accounting\BankTransactions | BankTransactions with an array of BankTransaction objects in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->createBankTransactions($xero_tenant_id, $bank_transactions, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBankTransactions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transactions** | [**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)| BankTransactions with an array of BankTransaction objects in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBankTransfer**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransfers createBankTransfer($xero_tenant_id, $bank_transfers)

Creates a bank transfer

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfers = { "BankTransfers": [ { "FromBankAccount": { "Code": "090", "Name": "My Savings", "AccountID": "00000000-0000-0000-0000-000000000000", "Type": "BANK", "BankAccountNumber": "123455", "Status": "ACTIVE", "BankAccountType": "BANK", "CurrencyCode": "USD", "TaxType": "NONE", "EnablePaymentsToAccount": false, "ShowInExpenseClaims": false, "Class": "ASSET", "ReportingCode": "ASS", "ReportingCodeName": "Assets", "HasAttachments": false, "UpdatedDateUTC": "2016-10-17T13:45:33.993-07:00" }, "ToBankAccount": { "Code": "088", "Name": "Business Wells Fargo", "AccountID": "00000000-0000-0000-0000-000000000000", "Type": "BANK", "BankAccountNumber": "123455", "Status": "ACTIVE", "BankAccountType": "BANK", "CurrencyCode": "USD", "TaxType": "NONE", "EnablePaymentsToAccount": false, "ShowInExpenseClaims": false, "Class": "ASSET", "ReportingCode": "ASS", "ReportingCodeName": "Assets", "HasAttachments": false, "UpdatedDateUTC": "2016-06-03T08:31:14.517-07:00" }, "Amount": "50.00" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\BankTransfers | BankTransfers with array of BankTransfer objects in request body

try {
    $result = $apiInstance->createBankTransfer($xero_tenant_id, $bank_transfers);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBankTransfer: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfers** | [**\XeroAPI\XeroPHP\Models\Accounting\BankTransfers**](../Model/BankTransfers.md)| BankTransfers with array of BankTransfer objects in request body |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransfers**](../Model/BankTransfers.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBankTransferAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createBankTransferAttachmentByFileName($xero_tenant_id, $bank_transfer_id, $file_name, $body)



### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createBankTransferAttachmentByFileName($xero_tenant_id, $bank_transfer_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBankTransferAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBankTransferHistoryRecord**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createBankTransferHistoryRecord($xero_tenant_id, $bank_transfer_id, $history_records)

Creates a history record for a specific bank transfer

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createBankTransferHistoryRecord($xero_tenant_id, $bank_transfer_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBankTransferHistoryRecord: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBatchPayment**
> \XeroAPI\XeroPHP\Models\Accounting\BatchPayments createBatchPayment($xero_tenant_id, $batch_payments, $summarize_errors)

Creates one or many batch payments for invoices

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$batch_payments = { "BatchPayments": [ { "Account": { "AccountID": "00000000-0000-0000-0000-000000000000" }, "Reference": "ref", "Date": "2018-08-01", "Payments": [ { "Account": { "Code": "001" }, "Date": "2019-12-31", "Amount": 500, "Invoice": { "InvoiceID": "00000000-0000-0000-0000-000000000000", "LineItems": [], "Contact": {}, "Type": "ACCPAY" } } ] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\BatchPayments | BatchPayments with an array of Payments in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createBatchPayment($xero_tenant_id, $batch_payments, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBatchPayment: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **batch_payments** | [**\XeroAPI\XeroPHP\Models\Accounting\BatchPayments**](../Model/BatchPayments.md)| BatchPayments with an array of Payments in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BatchPayments**](../Model/BatchPayments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBatchPaymentHistoryRecord**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createBatchPaymentHistoryRecord($xero_tenant_id, $batch_payment_id, $history_records)

Creates a history record for a specific batch payment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$batch_payment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for BatchPayment
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createBatchPaymentHistoryRecord($xero_tenant_id, $batch_payment_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBatchPaymentHistoryRecord: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **batch_payment_id** | [**string**](../Model/.md)| Unique identifier for BatchPayment |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createBrandingThemePaymentServices**
> \XeroAPI\XeroPHP\Models\Accounting\PaymentServices createBrandingThemePaymentServices($xero_tenant_id, $branding_theme_id, $payment_service)

Creates a new custom payment service for a specific branding theme

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$branding_theme_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Branding Theme
$payment_service = { "PaymentServiceID": "00000000-0000-0000-0000-000000000000", "PaymentServiceName": "ACME Payments", "PaymentServiceUrl": "https://www.payupnow.com/", "PayNowText": "Pay Now" }; // \XeroAPI\XeroPHP\Models\Accounting\PaymentService | PaymentService object in body of request

try {
    $result = $apiInstance->createBrandingThemePaymentServices($xero_tenant_id, $branding_theme_id, $payment_service);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createBrandingThemePaymentServices: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **branding_theme_id** | [**string**](../Model/.md)| Unique identifier for a Branding Theme |
 **payment_service** | [**\XeroAPI\XeroPHP\Models\Accounting\PaymentService**](../Model/PaymentService.md)| PaymentService object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PaymentServices**](../Model/PaymentServices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createContactAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createContactAttachmentByFileName($xero_tenant_id, $contact_id, $file_name, $body)



### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createContactAttachmentByFileName($xero_tenant_id, $contact_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createContactAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createContactGroup**
> \XeroAPI\XeroPHP\Models\Accounting\ContactGroups createContactGroup($xero_tenant_id, $contact_groups)

Creates a contact group

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_groups = { "ContactGroups": [{ "Name": "VIPs" }]}; // \XeroAPI\XeroPHP\Models\Accounting\ContactGroups | ContactGroups with an array of names in request body

try {
    $result = $apiInstance->createContactGroup($xero_tenant_id, $contact_groups);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createContactGroup: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_groups** | [**\XeroAPI\XeroPHP\Models\Accounting\ContactGroups**](../Model/ContactGroups.md)| ContactGroups with an array of names in request body |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ContactGroups**](../Model/ContactGroups.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createContactGroupContacts**
> \XeroAPI\XeroPHP\Models\Accounting\Contacts createContactGroupContacts($xero_tenant_id, $contact_group_id, $contacts)

Creates contacts to a specific contact group

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_group_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact Group
$contacts = { "Contacts": [ { "ContactID": "a3675fc4-f8dd-4f03-ba5b-f1870566bcd7" }, { "ContactID": "4e1753b9-018a-4775-b6aa-1bc7871cfee3" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Contacts | Contacts with array of contacts specifying the ContactID to be added to ContactGroup in body of request

try {
    $result = $apiInstance->createContactGroupContacts($xero_tenant_id, $contact_group_id, $contacts);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createContactGroupContacts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_group_id** | [**string**](../Model/.md)| Unique identifier for a Contact Group |
 **contacts** | [**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)| Contacts with array of contacts specifying the ContactID to be added to ContactGroup in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createContactHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createContactHistory($xero_tenant_id, $contact_id, $history_records)

Creates a new history record for a specific contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createContactHistory($xero_tenant_id, $contact_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createContactHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createContacts**
> \XeroAPI\XeroPHP\Models\Accounting\Contacts createContacts($xero_tenant_id, $contacts, $summarize_errors)

Creates multiple contacts (bulk) in a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contacts = { "Id": "e997d6d7-6dad-4458-beb8-d9c1bf7f2edf", "Status": "OK", "ProviderName": "Xero API Partner", "DateTimeUTC": "/Date(1551399321121)/", "Contacts": [ { "ContactID": "3ff6d40c-af9a-40a3-89ce-3c1556a25591", "ContactStatus": "ACTIVE", "Name": "Foo9987", "EmailAddress": "sid32476@blah.com", "BankAccountDetails": "", "Addresses": [ { "AddressType": "STREET", "City": "", "Region": "", "PostalCode": "", "Country": "" }, { "AddressType": "POBOX", "City": "", "Region": "", "PostalCode": "", "Country": "" } ], "Phones": [ { "PhoneType": "DEFAULT", "PhoneNumber": "", "PhoneAreaCode": "", "PhoneCountryCode": "" }, { "PhoneType": "DDI", "PhoneNumber": "", "PhoneAreaCode": "", "PhoneCountryCode": "" }, { "PhoneType": "FAX", "PhoneNumber": "", "PhoneAreaCode": "", "PhoneCountryCode": "" }, { "PhoneType": "MOBILE", "PhoneNumber": "555-1212", "PhoneAreaCode": "415", "PhoneCountryCode": "" } ], "UpdatedDateUTC": "/Date(1551399321043+0000)/", "ContactGroups": [], "IsSupplier": false, "IsCustomer": false, "SalesTrackingCategories": [], "PurchasesTrackingCategories": [], "PaymentTerms": { "Bills": { "Day": 15, "Type": "OFCURRENTMONTH" }, "Sales": { "Day": 10, "Type": "DAYSAFTERBILLMONTH" } }, "ContactPersons": [], "HasValidationErrors": false } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Contacts | Contacts with an array of Contact objects to create in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createContacts($xero_tenant_id, $contacts, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createContacts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contacts** | [**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)| Contacts with an array of Contact objects to create in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createCreditNoteAllocation**
> \XeroAPI\XeroPHP\Models\Accounting\Allocations createCreditNoteAllocation($xero_tenant_id, $credit_note_id, $allocations, $summarize_errors)

Creates allocation for a specific credit note

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$allocations = { "Allocations": [ { "Invoice": { "LineItems": [], "InvoiceID": "c45720a1-ade3-4a38-a064-d15489be6841" }, "Amount": 1, "Date": "2019-03-05" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Allocations | Allocations with array of Allocation object in body of request.
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createCreditNoteAllocation($xero_tenant_id, $credit_note_id, $allocations, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createCreditNoteAllocation: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **allocations** | [**\XeroAPI\XeroPHP\Models\Accounting\Allocations**](../Model/Allocations.md)| Allocations with array of Allocation object in body of request. |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Allocations**](../Model/Allocations.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createCreditNoteAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createCreditNoteAttachmentByFileName($xero_tenant_id, $credit_note_id, $file_name, $body, $include_online)

Creates an attachment for a specific credit note

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request
$include_online = true; // bool | Allows an attachment to be seen by the end customer within their online invoice

try {
    $result = $apiInstance->createCreditNoteAttachmentByFileName($xero_tenant_id, $credit_note_id, $file_name, $body, $include_online);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createCreditNoteAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |
 **include_online** | **bool**| Allows an attachment to be seen by the end customer within their online invoice | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createCreditNoteHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createCreditNoteHistory($xero_tenant_id, $credit_note_id, $history_records)

Retrieves history records of a specific credit note

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createCreditNoteHistory($xero_tenant_id, $credit_note_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createCreditNoteHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createCreditNotes**
> \XeroAPI\XeroPHP\Models\Accounting\CreditNotes createCreditNotes($xero_tenant_id, $credit_notes, $summarize_errors, $unitdp)

Creates a new credit note

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_notes = { "CreditNotes":[ { "Type":"ACCPAYCREDIT", "Contact":{ "ContactID":"430fa14a-f945-44d3-9f97-5df5e28441b8" }, "Date":"2019-01-05", "LineItems":[ { "Description":"Foobar", "Quantity":2.0, "UnitAmount":20.0, "AccountCode":"400" } ] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\CreditNotes | Credit Notes with array of CreditNote object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->createCreditNotes($xero_tenant_id, $credit_notes, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createCreditNotes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_notes** | [**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)| Credit Notes with array of CreditNote object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createCurrency**
> \XeroAPI\XeroPHP\Models\Accounting\Currencies createCurrency($xero_tenant_id, $currency)

Create a new currency for a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$currency = { "Code": "USD", "Description": "United States Dollar" }; // \XeroAPI\XeroPHP\Models\Accounting\Currency | Currency object in the body of request

try {
    $result = $apiInstance->createCurrency($xero_tenant_id, $currency);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createCurrency: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **currency** | [**\XeroAPI\XeroPHP\Models\Accounting\Currency**](../Model/Currency.md)| Currency object in the body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Currencies**](../Model/Currencies.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createEmployees**
> \XeroAPI\XeroPHP\Models\Accounting\Employees createEmployees($xero_tenant_id, $employees, $summarize_errors)

Creates new employees used in Xero payrun

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$employees = { "Employees": [ { "FirstName": "Nick", "LastName": "Fury", "ExternalLink": { "Url": "http://twitter.com/#!/search/Nick+Fury" } } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Employees | Employees with array of Employee object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createEmployees($xero_tenant_id, $employees, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createEmployees: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employees** | [**\XeroAPI\XeroPHP\Models\Accounting\Employees**](../Model/Employees.md)| Employees with array of Employee object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Employees**](../Model/Employees.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createExpenseClaimHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createExpenseClaimHistory($xero_tenant_id, $expense_claim_id, $history_records)

Creates a history record for a specific expense claim

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$expense_claim_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ExpenseClaim
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createExpenseClaimHistory($xero_tenant_id, $expense_claim_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createExpenseClaimHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **expense_claim_id** | [**string**](../Model/.md)| Unique identifier for a ExpenseClaim |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createExpenseClaims**
> \XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims createExpenseClaims($xero_tenant_id, $expense_claims)

Creates expense claims

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$expense_claims = { "ExpenseClaims": [ { "Status": "SUBMITTED", "User": { "UserID": "d1164823-0ac1-41ad-987b-b4e30fe0b273" }, "Receipts": [ { "Lineitems": [], "ReceiptID": "dc1c7f6d-0a4c-402f-acac-551d62ce5816" } ] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims | ExpenseClaims with array of ExpenseClaim object in body of request

try {
    $result = $apiInstance->createExpenseClaims($xero_tenant_id, $expense_claims);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createExpenseClaims: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **expense_claims** | [**\XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims**](../Model/ExpenseClaims.md)| ExpenseClaims with array of ExpenseClaim object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims**](../Model/ExpenseClaims.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createInvoiceAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createInvoiceAttachmentByFileName($xero_tenant_id, $invoice_id, $file_name, $body, $include_online)

Creates an attachment for a specific invoice or purchase bill by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request
$include_online = true; // bool | Allows an attachment to be seen by the end customer within their online invoice

try {
    $result = $apiInstance->createInvoiceAttachmentByFileName($xero_tenant_id, $invoice_id, $file_name, $body, $include_online);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createInvoiceAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |
 **include_online** | **bool**| Allows an attachment to be seen by the end customer within their online invoice | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createInvoiceHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createInvoiceHistory($xero_tenant_id, $invoice_id, $history_records)

Creates a history record for a specific invoice

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createInvoiceHistory($xero_tenant_id, $invoice_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createInvoiceHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createInvoices**
> \XeroAPI\XeroPHP\Models\Accounting\Invoices createInvoices($xero_tenant_id, $invoices, $summarize_errors, $unitdp)

Creates one or more sales invoices or purchase bills

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoices = { "Invoices": [ { "Type": "ACCREC", "Contact": { "ContactID": "430fa14a-f945-44d3-9f97-5df5e28441b8" }, "LineItems": [ { "Description": "Acme Tires", "Quantity": 2, "UnitAmount": 20, "AccountCode": "200", "TaxType": "NONE", "LineAmount": 40 } ], "Date": "2019-03-11", "DueDate": "2018-12-10", "Reference": "Website Design", "Status": "AUTHORISED" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Invoices | Invoices with an array of invoice objects in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->createInvoices($xero_tenant_id, $invoices, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createInvoices: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoices** | [**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)| Invoices with an array of invoice objects in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createItemHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createItemHistory($xero_tenant_id, $item_id, $history_records)

Creates a history record for a specific item

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$item_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Item
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createItemHistory($xero_tenant_id, $item_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createItemHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **item_id** | [**string**](../Model/.md)| Unique identifier for an Item |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createItems**
> \XeroAPI\XeroPHP\Models\Accounting\Items createItems($xero_tenant_id, $items, $summarize_errors, $unitdp)

Creates one or more items

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$items = { "Items": [ { "Code": "code123", "Name": "Item Name XYZ", "Description": "Foobar", "InventoryAssetAccountCode": "140", "PurchaseDetails": { "COGSAccountCode": "500" } } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Items | Items with an array of Item objects in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->createItems($xero_tenant_id, $items, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createItems: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **items** | [**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)| Items with an array of Item objects in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createLinkedTransaction**
> \XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions createLinkedTransaction($xero_tenant_id, $linked_transaction)

Creates linked transactions (billable expenses)

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$linked_transaction = { "LinkedTransactions": [ { "SourceTransactionID": "a848644a-f20f-4630-98c3-386bd7505631", "SourceLineItemID": "b0df260d-3cc8-4ced-9bd6-41924f624ed3" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\LinkedTransaction | LinkedTransaction object in body of request

try {
    $result = $apiInstance->createLinkedTransaction($xero_tenant_id, $linked_transaction);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createLinkedTransaction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **linked_transaction** | [**\XeroAPI\XeroPHP\Models\Accounting\LinkedTransaction**](../Model/LinkedTransaction.md)| LinkedTransaction object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions**](../Model/LinkedTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createManualJournalAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createManualJournalAttachmentByFileName($xero_tenant_id, $manual_journal_id, $file_name, $body)

Creates a specific attachment for a specific manual journal by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createManualJournalAttachmentByFileName($xero_tenant_id, $manual_journal_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createManualJournalAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createManualJournalHistoryRecord**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createManualJournalHistoryRecord($xero_tenant_id, $manual_journal_id, $history_records)

Creates a history record for a specific manual journal

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createManualJournalHistoryRecord($xero_tenant_id, $manual_journal_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createManualJournalHistoryRecord: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createManualJournals**
> \XeroAPI\XeroPHP\Models\Accounting\ManualJournals createManualJournals($xero_tenant_id, $manual_journals, $summarize_errors)

Creates one or more manual journals

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journals = { "ManualJournals": [ { "Narration": "Journal Desc", "JournalLines": [ { "LineAmount": 100, "AccountCode": "400", "Description": "Money Movement" }, { "LineAmount": -100, "AccountCode": "400", "Description": "Prepayment of things", "Tracking": [ { "Name": "North", "Option": "Region" } ] } ], "Date": "2019-03-14" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\ManualJournals | ManualJournals array with ManualJournal object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createManualJournals($xero_tenant_id, $manual_journals, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createManualJournals: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journals** | [**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)| ManualJournals array with ManualJournal object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createOverpaymentAllocations**
> \XeroAPI\XeroPHP\Models\Accounting\Allocations createOverpaymentAllocations($xero_tenant_id, $overpayment_id, $allocations, $summarize_errors)

Creates a single allocation for a specific overpayment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$overpayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Overpayment
$allocations = { "Allocations": [ { "Invoice": { "InvoiceID": "00000000-0000-0000-0000-000000000000", "LineItems": [], "Contact": {}, "Type": "ACCPAY" }, "Amount": 10.00, "Date": "2019-03-12" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Allocations | Allocations array with Allocation object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createOverpaymentAllocations($xero_tenant_id, $overpayment_id, $allocations, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createOverpaymentAllocations: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **overpayment_id** | [**string**](../Model/.md)| Unique identifier for a Overpayment |
 **allocations** | [**\XeroAPI\XeroPHP\Models\Accounting\Allocations**](../Model/Allocations.md)| Allocations array with Allocation object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Allocations**](../Model/Allocations.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createOverpaymentHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createOverpaymentHistory($xero_tenant_id, $overpayment_id, $history_records)

Creates a history record for a specific overpayment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$overpayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Overpayment
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createOverpaymentHistory($xero_tenant_id, $overpayment_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createOverpaymentHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **overpayment_id** | [**string**](../Model/.md)| Unique identifier for a Overpayment |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPayment**
> \XeroAPI\XeroPHP\Models\Accounting\Payments createPayment($xero_tenant_id, $payment)

Creates a single payment for invoice or credit notes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$payment = { "Payments": [ { "Invoice": { "LineItems": [], "InvoiceID": "00000000-0000-0000-0000-000000000000" }, "Account": { "Code": "970" }, "Date": "2019-03-12", "Amount": 1 } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Payment | Request body with a single Payment object

try {
    $result = $apiInstance->createPayment($xero_tenant_id, $payment);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPayment: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payment** | [**\XeroAPI\XeroPHP\Models\Accounting\Payment**](../Model/Payment.md)| Request body with a single Payment object |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Payments**](../Model/Payments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPaymentHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createPaymentHistory($xero_tenant_id, $payment_id, $history_records)

Creates a history record for a specific payment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$payment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Payment
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createPaymentHistory($xero_tenant_id, $payment_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPaymentHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payment_id** | [**string**](../Model/.md)| Unique identifier for a Payment |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPaymentService**
> \XeroAPI\XeroPHP\Models\Accounting\PaymentServices createPaymentService($xero_tenant_id, $payment_services)

Creates a payment service

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$payment_services = { "PaymentServices": [ { "PaymentServiceName": "PayUpNow", "PaymentServiceUrl": "https://www.payupnow.com/", "PayNowText": "Time To Pay" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\PaymentServices | PaymentServices array with PaymentService object in body of request

try {
    $result = $apiInstance->createPaymentService($xero_tenant_id, $payment_services);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPaymentService: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payment_services** | [**\XeroAPI\XeroPHP\Models\Accounting\PaymentServices**](../Model/PaymentServices.md)| PaymentServices array with PaymentService object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PaymentServices**](../Model/PaymentServices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPayments**
> \XeroAPI\XeroPHP\Models\Accounting\Payments createPayments($xero_tenant_id, $payments, $summarize_errors)

Creates multiple payments for invoices or credit notes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$payments = { "Payments": [ { "Invoice": { "LineItems": [], "InvoiceID": "00000000-0000-0000-0000-000000000000" }, "Account": { "Code": "970" }, "Date": "2019-03-12", "Amount": 1 } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Payments | Payments array with Payment object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createPayments($xero_tenant_id, $payments, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPayments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payments** | [**\XeroAPI\XeroPHP\Models\Accounting\Payments**](../Model/Payments.md)| Payments array with Payment object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Payments**](../Model/Payments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPrepaymentAllocations**
> \XeroAPI\XeroPHP\Models\Accounting\Allocations createPrepaymentAllocations($xero_tenant_id, $prepayment_id, $allocations, $summarize_errors)

Allows you to create an Allocation for prepayments

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$prepayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a PrePayment
$allocations = { "Allocations": [ { "Invoice": { "LineItems": [], "InvoiceID": "00000000-0000-0000-0000-000000000000" }, "Amount": 1, "Date": "2019-01-10" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Allocations | Allocations with an array of Allocation object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createPrepaymentAllocations($xero_tenant_id, $prepayment_id, $allocations, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPrepaymentAllocations: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **prepayment_id** | [**string**](../Model/.md)| Unique identifier for a PrePayment |
 **allocations** | [**\XeroAPI\XeroPHP\Models\Accounting\Allocations**](../Model/Allocations.md)| Allocations with an array of Allocation object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Allocations**](../Model/Allocations.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPrepaymentHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createPrepaymentHistory($xero_tenant_id, $prepayment_id, $history_records)

Creates a history record for a specific prepayment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$prepayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a PrePayment
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createPrepaymentHistory($xero_tenant_id, $prepayment_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPrepaymentHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **prepayment_id** | [**string**](../Model/.md)| Unique identifier for a PrePayment |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPurchaseOrderAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createPurchaseOrderAttachmentByFileName($xero_tenant_id, $purchase_order_id, $file_name, $body)

Creates attachment for a specific purchase order

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createPurchaseOrderAttachmentByFileName($xero_tenant_id, $purchase_order_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPurchaseOrderAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPurchaseOrderHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createPurchaseOrderHistory($xero_tenant_id, $purchase_order_id, $history_records)

Creates a history record for a specific purchase orders

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createPurchaseOrderHistory($xero_tenant_id, $purchase_order_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPurchaseOrderHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createPurchaseOrders**
> \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders createPurchaseOrders($xero_tenant_id, $purchase_orders, $summarize_errors)

Creates one or more purchase orders

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_orders = { "PurchaseOrders": [ { "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000" }, "LineItems": [ { "Description": "Foobar", "Quantity": 1, "UnitAmount": 20, "AccountCode": "710" } ], "Date": "2019-03-13" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders | PurchaseOrders with an array of PurchaseOrder object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createPurchaseOrders($xero_tenant_id, $purchase_orders, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createPurchaseOrders: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_orders** | [**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)| PurchaseOrders with an array of PurchaseOrder object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createQuoteAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createQuoteAttachmentByFileName($xero_tenant_id, $quote_id, $file_name, $body)

Creates attachment for a specific quote

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createQuoteAttachmentByFileName($xero_tenant_id, $quote_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createQuoteAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createQuoteHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createQuoteHistory($xero_tenant_id, $quote_id, $history_records)

Creates a history record for a specific quote

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createQuoteHistory($xero_tenant_id, $quote_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createQuoteHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createQuotes**
> \XeroAPI\XeroPHP\Models\Accounting\Quotes createQuotes($xero_tenant_id, $quotes, $summarize_errors)

Create one or more quotes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quotes = { "Quotes": [ { "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000" }, "LineItems": [ { "Description": "Foobar", "Quantity": 1, "UnitAmount": 20, "AccountCode": "12775" } ], "Date": "2020-02-01" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Quotes | Quotes with an array of Quote object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->createQuotes($xero_tenant_id, $quotes, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createQuotes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quotes** | [**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)| Quotes with an array of Quote object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createReceipt**
> \XeroAPI\XeroPHP\Models\Accounting\Receipts createReceipt($xero_tenant_id, $receipts, $unitdp)

Creates draft expense claim receipts for any user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipts = { "Receipts": [ { "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000" }, "Lineitems": [ { "Description": "Foobar", "Quantity": 2, "UnitAmount": 20, "AccountCode": "400", "TaxType": "NONE", "LineAmount": 40 } ], "User": { "UserID": "00000000-0000-0000-0000-000000000000" }, "LineAmountTypes": "NoTax", "Status": "DRAFT" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Receipts | Receipts with an array of Receipt object in body of request
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->createReceipt($xero_tenant_id, $receipts, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createReceipt: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipts** | [**\XeroAPI\XeroPHP\Models\Accounting\Receipts**](../Model/Receipts.md)| Receipts with an array of Receipt object in body of request |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Receipts**](../Model/Receipts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createReceiptAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createReceiptAttachmentByFileName($xero_tenant_id, $receipt_id, $file_name, $body)

Creates an attachment on a specific expense claim receipts by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createReceiptAttachmentByFileName($xero_tenant_id, $receipt_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createReceiptAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createReceiptHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createReceiptHistory($xero_tenant_id, $receipt_id, $history_records)

Creates a history record for a specific receipt

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createReceiptHistory($xero_tenant_id, $receipt_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createReceiptHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createRepeatingInvoiceAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments createRepeatingInvoiceAttachmentByFileName($xero_tenant_id, $repeating_invoice_id, $file_name, $body)

Creates an attachment from a specific repeating invoices by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->createRepeatingInvoiceAttachmentByFileName($xero_tenant_id, $repeating_invoice_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createRepeatingInvoiceAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createRepeatingInvoiceHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords createRepeatingInvoiceHistory($xero_tenant_id, $repeating_invoice_id, $history_records)

Creates a  history record for a specific repeating invoice

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice
$history_records = { "HistoryRecords": [ { "Details": "Hello World" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords | HistoryRecords containing an array of HistoryRecord objects in body of request

try {
    $result = $apiInstance->createRepeatingInvoiceHistory($xero_tenant_id, $repeating_invoice_id, $history_records);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createRepeatingInvoiceHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |
 **history_records** | [**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)| HistoryRecords containing an array of HistoryRecord objects in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createTaxRates**
> \XeroAPI\XeroPHP\Models\Accounting\TaxRates createTaxRates($xero_tenant_id, $tax_rates)

Creates one or more tax rates

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tax_rates = { "TaxRates": [ { "Name": "CA State Tax", "TaxComponents": [ { "Name": "State Tax", "Rate": 2.25 } ] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\TaxRates | TaxRates array with TaxRate object in body of request

try {
    $result = $apiInstance->createTaxRates($xero_tenant_id, $tax_rates);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createTaxRates: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tax_rates** | [**\XeroAPI\XeroPHP\Models\Accounting\TaxRates**](../Model/TaxRates.md)| TaxRates array with TaxRate object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TaxRates**](../Model/TaxRates.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createTrackingCategory**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingCategories createTrackingCategory($xero_tenant_id, $tracking_category)

Create tracking categories

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tracking_category = { name: "FooBar" }; // \XeroAPI\XeroPHP\Models\Accounting\TrackingCategory | TrackingCategory object in body of request

try {
    $result = $apiInstance->createTrackingCategory($xero_tenant_id, $tracking_category);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createTrackingCategory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tracking_category** | [**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategory**](../Model/TrackingCategory.md)| TrackingCategory object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategories**](../Model/TrackingCategories.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **createTrackingOptions**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingOptions createTrackingOptions($xero_tenant_id, $tracking_category_id, $tracking_option)

Creates options for a specific tracking category

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tracking_category_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a TrackingCategory
$tracking_option = { name: " Bar" }; // \XeroAPI\XeroPHP\Models\Accounting\TrackingOption | TrackingOption object in body of request

try {
    $result = $apiInstance->createTrackingOptions($xero_tenant_id, $tracking_category_id, $tracking_option);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->createTrackingOptions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tracking_category_id** | [**string**](../Model/.md)| Unique identifier for a TrackingCategory |
 **tracking_option** | [**\XeroAPI\XeroPHP\Models\Accounting\TrackingOption**](../Model/TrackingOption.md)| TrackingOption object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingOptions**](../Model/TrackingOptions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteAccount**
> \XeroAPI\XeroPHP\Models\Accounting\Accounts deleteAccount($xero_tenant_id, $account_id)

Deletes a chart of accounts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object

try {
    $result = $apiInstance->deleteAccount($xero_tenant_id, $account_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deleteAccount: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Accounts**](../Model/Accounts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteContactGroupContact**
> deleteContactGroupContact($xero_tenant_id, $contact_group_id, $contact_id)

Deletes a specific contact from a contact group using a unique contact Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_group_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact Group
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact

try {
    $apiInstance->deleteContactGroupContact($xero_tenant_id, $contact_group_id, $contact_id);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deleteContactGroupContact: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_group_id** | [**string**](../Model/.md)| Unique identifier for a Contact Group |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteContactGroupContacts**
> deleteContactGroupContacts($xero_tenant_id, $contact_group_id)

Deletes all contacts from a specific contact group

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_group_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact Group

try {
    $apiInstance->deleteContactGroupContacts($xero_tenant_id, $contact_group_id);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deleteContactGroupContacts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_group_id** | [**string**](../Model/.md)| Unique identifier for a Contact Group |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: Not defined

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteItem**
> deleteItem($xero_tenant_id, $item_id)

Deletes a specific item

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$item_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Item

try {
    $apiInstance->deleteItem($xero_tenant_id, $item_id);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deleteItem: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **item_id** | [**string**](../Model/.md)| Unique identifier for an Item |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteLinkedTransaction**
> deleteLinkedTransaction($xero_tenant_id, $linked_transaction_id)

Deletes a specific linked transactions (billable expenses)

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$linked_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a LinkedTransaction

try {
    $apiInstance->deleteLinkedTransaction($xero_tenant_id, $linked_transaction_id);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deleteLinkedTransaction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **linked_transaction_id** | [**string**](../Model/.md)| Unique identifier for a LinkedTransaction |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deletePayment**
> \XeroAPI\XeroPHP\Models\Accounting\Payments deletePayment($xero_tenant_id, $payment_id, $payment_delete)

Updates a specific payment for invoices and credit notes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$payment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Payment
$payment_delete = { "Payments":[ { "Status":"DELETED" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\PaymentDelete | 

try {
    $result = $apiInstance->deletePayment($xero_tenant_id, $payment_id, $payment_delete);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deletePayment: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payment_id** | [**string**](../Model/.md)| Unique identifier for a Payment |
 **payment_delete** | [**\XeroAPI\XeroPHP\Models\Accounting\PaymentDelete**](../Model/PaymentDelete.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Payments**](../Model/Payments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteTrackingCategory**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingCategories deleteTrackingCategory($xero_tenant_id, $tracking_category_id)

Deletes a specific tracking category

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tracking_category_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a TrackingCategory

try {
    $result = $apiInstance->deleteTrackingCategory($xero_tenant_id, $tracking_category_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deleteTrackingCategory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tracking_category_id** | [**string**](../Model/.md)| Unique identifier for a TrackingCategory |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategories**](../Model/TrackingCategories.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **deleteTrackingOptions**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingOptions deleteTrackingOptions($xero_tenant_id, $tracking_category_id, $tracking_option_id)

Deletes a specific option for a specific tracking category

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tracking_category_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a TrackingCategory
$tracking_option_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Tracking Option

try {
    $result = $apiInstance->deleteTrackingOptions($xero_tenant_id, $tracking_category_id, $tracking_option_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->deleteTrackingOptions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tracking_category_id** | [**string**](../Model/.md)| Unique identifier for a TrackingCategory |
 **tracking_option_id** | [**string**](../Model/.md)| Unique identifier for a Tracking Option |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingOptions**](../Model/TrackingOptions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **emailInvoice**
> emailInvoice($xero_tenant_id, $invoice_id, $request_empty)

Sends a copy of a specific invoice to related contact via email

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$request_empty = {}; // \XeroAPI\XeroPHP\Models\Accounting\RequestEmpty | 

try {
    $apiInstance->emailInvoice($xero_tenant_id, $invoice_id, $request_empty);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->emailInvoice: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **request_empty** | [**\XeroAPI\XeroPHP\Models\Accounting\RequestEmpty**](../Model/RequestEmpty.md)|  |

### Return type

void (empty response body)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAccount**
> \XeroAPI\XeroPHP\Models\Accounting\Accounts getAccount($xero_tenant_id, $account_id)

Retrieves a single chart of accounts by using a unique account Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object

try {
    $result = $apiInstance->getAccount($xero_tenant_id, $account_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getAccount: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Accounts**](../Model/Accounts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAccountAttachmentByFileName**
> \SplFileObject getAccountAttachmentByFileName($xero_tenant_id, $account_id, $file_name, $content_type)

Retrieves an attachment for a specific account by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getAccountAttachmentByFileName($xero_tenant_id, $account_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getAccountAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAccountAttachmentById**
> \SplFileObject getAccountAttachmentById($xero_tenant_id, $account_id, $attachment_id, $content_type)

Retrieves a specific attachment from a specific account using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getAccountAttachmentById($xero_tenant_id, $account_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getAccountAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAccountAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getAccountAttachments($xero_tenant_id, $account_id)

Retrieves attachments for a specific accounts by using a unique account Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object

try {
    $result = $apiInstance->getAccountAttachments($xero_tenant_id, $account_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getAccountAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getAccounts**
> \XeroAPI\XeroPHP\Models\Accounting\Accounts getAccounts($xero_tenant_id, $if_modified_since, $where, $order)

Retrieves the full chart of accounts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status==&quot;ACTIVE&quot; AND Type==&quot;BANK&quot;; // string | Filter by an any element
$order = Name ASC; // string | Order by an any element

try {
    $result = $apiInstance->getAccounts($xero_tenant_id, $if_modified_since, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getAccounts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Accounts**](../Model/Accounts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransaction**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransactions getBankTransaction($xero_tenant_id, $bank_transaction_id, $unitdp)

Retrieves a single spent or received money transaction by using a unique bank transaction Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getBankTransaction($xero_tenant_id, $bank_transaction_id, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransaction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransactionAttachmentByFileName**
> \SplFileObject getBankTransactionAttachmentByFileName($xero_tenant_id, $bank_transaction_id, $file_name, $content_type)

Retrieves a specific attachment from a specific bank transaction by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getBankTransactionAttachmentByFileName($xero_tenant_id, $bank_transaction_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransactionAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransactionAttachmentById**
> \SplFileObject getBankTransactionAttachmentById($xero_tenant_id, $bank_transaction_id, $attachment_id, $content_type)

Retrieves specific attachments from a specific BankTransaction using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getBankTransactionAttachmentById($xero_tenant_id, $bank_transaction_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransactionAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransactionAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getBankTransactionAttachments($xero_tenant_id, $bank_transaction_id)

Retrieves any attachments from a specific bank transactions

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction

try {
    $result = $apiInstance->getBankTransactionAttachments($xero_tenant_id, $bank_transaction_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransactionAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransactions**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransactions getBankTransactions($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp)

Retrieves any spent or received money transactions

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="AUTHORISED"; // string | Filter by an any element
$order = Type ASC; // string | Order by an any element
$page = 1; // int | Up to 100 bank transactions will be returned in a single API call with line items details
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getBankTransactions($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransactions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **page** | **int**| Up to 100 bank transactions will be returned in a single API call with line items details | [optional]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransactionsHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getBankTransactionsHistory($xero_tenant_id, $bank_transaction_id)

Retrieves history from a specific bank transaction using a unique bank transaction Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction

try {
    $result = $apiInstance->getBankTransactionsHistory($xero_tenant_id, $bank_transaction_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransactionsHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransfer**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransfers getBankTransfer($xero_tenant_id, $bank_transfer_id)

Retrieves specific bank transfers by using a unique bank transfer Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer

try {
    $result = $apiInstance->getBankTransfer($xero_tenant_id, $bank_transfer_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransfer: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransfers**](../Model/BankTransfers.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransferAttachmentByFileName**
> \SplFileObject getBankTransferAttachmentByFileName($xero_tenant_id, $bank_transfer_id, $file_name, $content_type)

Retrieves a specific attachment on a specific bank transfer by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getBankTransferAttachmentByFileName($xero_tenant_id, $bank_transfer_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransferAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransferAttachmentById**
> \SplFileObject getBankTransferAttachmentById($xero_tenant_id, $bank_transfer_id, $attachment_id, $content_type)

Retrieves a specific attachment from a specific bank transfer using a unique attachment ID

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getBankTransferAttachmentById($xero_tenant_id, $bank_transfer_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransferAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransferAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getBankTransferAttachments($xero_tenant_id, $bank_transfer_id)

Retrieves attachments from a specific bank transfer

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer

try {
    $result = $apiInstance->getBankTransferAttachments($xero_tenant_id, $bank_transfer_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransferAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransferHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getBankTransferHistory($xero_tenant_id, $bank_transfer_id)

Retrieves history from a specific bank transfer using a unique bank transfer Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer

try {
    $result = $apiInstance->getBankTransferHistory($xero_tenant_id, $bank_transfer_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransferHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBankTransfers**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransfers getBankTransfers($xero_tenant_id, $if_modified_since, $where, $order)

Retrieves all bank transfers

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = HasAttachments==true; // string | Filter by an any element
$order = Amount ASC; // string | Order by an any element

try {
    $result = $apiInstance->getBankTransfers($xero_tenant_id, $if_modified_since, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBankTransfers: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransfers**](../Model/BankTransfers.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBatchPaymentHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getBatchPaymentHistory($xero_tenant_id, $batch_payment_id)

Retrieves history from a specific batch payment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$batch_payment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for BatchPayment

try {
    $result = $apiInstance->getBatchPaymentHistory($xero_tenant_id, $batch_payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBatchPaymentHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **batch_payment_id** | [**string**](../Model/.md)| Unique identifier for BatchPayment |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBatchPayments**
> \XeroAPI\XeroPHP\Models\Accounting\BatchPayments getBatchPayments($xero_tenant_id, $if_modified_since, $where, $order)

Retrieves either one or many batch payments for invoices

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="AUTHORISED"; // string | Filter by an any element
$order = Date ASC; // string | Order by an any element

try {
    $result = $apiInstance->getBatchPayments($xero_tenant_id, $if_modified_since, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBatchPayments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BatchPayments**](../Model/BatchPayments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBrandingTheme**
> \XeroAPI\XeroPHP\Models\Accounting\BrandingThemes getBrandingTheme($xero_tenant_id, $branding_theme_id)

Retrieves a specific branding theme using a unique branding theme Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$branding_theme_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Branding Theme

try {
    $result = $apiInstance->getBrandingTheme($xero_tenant_id, $branding_theme_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBrandingTheme: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **branding_theme_id** | [**string**](../Model/.md)| Unique identifier for a Branding Theme |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BrandingThemes**](../Model/BrandingThemes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBrandingThemePaymentServices**
> \XeroAPI\XeroPHP\Models\Accounting\PaymentServices getBrandingThemePaymentServices($xero_tenant_id, $branding_theme_id)

Retrieves the payment services for a specific branding theme

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$branding_theme_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Branding Theme

try {
    $result = $apiInstance->getBrandingThemePaymentServices($xero_tenant_id, $branding_theme_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBrandingThemePaymentServices: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **branding_theme_id** | [**string**](../Model/.md)| Unique identifier for a Branding Theme |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PaymentServices**](../Model/PaymentServices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getBrandingThemes**
> \XeroAPI\XeroPHP\Models\Accounting\BrandingThemes getBrandingThemes($xero_tenant_id)

Retrieves all the branding themes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getBrandingThemes($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getBrandingThemes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BrandingThemes**](../Model/BrandingThemes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContact**
> \XeroAPI\XeroPHP\Models\Accounting\Contacts getContact($xero_tenant_id, $contact_id)

Retrieves a specific contacts in a Xero organisation using a unique contact Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact

try {
    $result = $apiInstance->getContact($xero_tenant_id, $contact_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContact: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactAttachmentByFileName**
> \SplFileObject getContactAttachmentByFileName($xero_tenant_id, $contact_id, $file_name, $content_type)

Retrieves a specific attachment from a specific contact by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getContactAttachmentByFileName($xero_tenant_id, $contact_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactAttachmentById**
> \SplFileObject getContactAttachmentById($xero_tenant_id, $contact_id, $attachment_id, $content_type)

Retrieves a specific attachment from a specific contact using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getContactAttachmentById($xero_tenant_id, $contact_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getContactAttachments($xero_tenant_id, $contact_id)

Retrieves attachments for a specific contact in a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact

try {
    $result = $apiInstance->getContactAttachments($xero_tenant_id, $contact_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactByContactNumber**
> \XeroAPI\XeroPHP\Models\Accounting\Contacts getContactByContactNumber($xero_tenant_id, $contact_number)

Retrieves a specific contact by contact number in a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_number = SB2; // string | This field is read only on the Xero contact screen, used to identify contacts in external systems (max length = 50).

try {
    $result = $apiInstance->getContactByContactNumber($xero_tenant_id, $contact_number);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactByContactNumber: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_number** | **string**| This field is read only on the Xero contact screen, used to identify contacts in external systems (max length &#x3D; 50). |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactCISSettings**
> \XeroAPI\XeroPHP\Models\Accounting\CISSettings getContactCISSettings($xero_tenant_id, $contact_id)

Retrieves CIS settings for a specific contact in a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact

try {
    $result = $apiInstance->getContactCISSettings($xero_tenant_id, $contact_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactCISSettings: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\CISSettings**](../Model/CISSettings.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactGroup**
> \XeroAPI\XeroPHP\Models\Accounting\ContactGroups getContactGroup($xero_tenant_id, $contact_group_id)

Retrieves a specific contact group by using a unique contact group Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_group_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact Group

try {
    $result = $apiInstance->getContactGroup($xero_tenant_id, $contact_group_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactGroup: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_group_id** | [**string**](../Model/.md)| Unique identifier for a Contact Group |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ContactGroups**](../Model/ContactGroups.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactGroups**
> \XeroAPI\XeroPHP\Models\Accounting\ContactGroups getContactGroups($xero_tenant_id, $where, $order)

Retrieves the contact Id and name of all the contacts in a contact group

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$where = Status=="ACTIVE"; // string | Filter by an any element
$order = Name ASC; // string | Order by an any element

try {
    $result = $apiInstance->getContactGroups($xero_tenant_id, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactGroups: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ContactGroups**](../Model/ContactGroups.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContactHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getContactHistory($xero_tenant_id, $contact_id)

Retrieves history records for a specific contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact

try {
    $result = $apiInstance->getContactHistory($xero_tenant_id, $contact_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContactHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getContacts**
> \XeroAPI\XeroPHP\Models\Accounting\Contacts getContacts($xero_tenant_id, $if_modified_since, $where, $order, $i_ds, $page, $include_archived, $summary_only)

Retrieves all contacts in a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = ContactStatus==&quot;ACTIVE&quot;; // string | Filter by an any element
$order = Name ASC; // string | Order by an any element
$i_ds = &quot;00000000-0000-0000-0000-000000000000&quot;; // string[] | Filter by a comma separated list of ContactIDs. Allows you to retrieve a specific set of contacts in a single call.
$page = 1; // int | e.g. page=1 - Up to 100 contacts will be returned in a single API call.
$include_archived = True; // bool | e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included in the response
$summary_only = true; // bool | Use summaryOnly=true in GET Contacts endpoint to retrieve a smaller version of the response object. This returns only lightweight fields, excluding computation-heavy fields from the response, making the API calls quick and efficient.

try {
    $result = $apiInstance->getContacts($xero_tenant_id, $if_modified_since, $where, $order, $i_ds, $page, $include_archived, $summary_only);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getContacts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **i_ds** | [**string[]**](../Model/string.md)| Filter by a comma separated list of ContactIDs. Allows you to retrieve a specific set of contacts in a single call. | [optional]
 **page** | **int**| e.g. page&#x3D;1 - Up to 100 contacts will be returned in a single API call. | [optional]
 **include_archived** | **bool**| e.g. includeArchived&#x3D;true - Contacts with a status of ARCHIVED will be included in the response | [optional]
 **summary_only** | **bool**| Use summaryOnly&#x3D;true in GET Contacts endpoint to retrieve a smaller version of the response object. This returns only lightweight fields, excluding computation-heavy fields from the response, making the API calls quick and efficient. | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCreditNote**
> \XeroAPI\XeroPHP\Models\Accounting\CreditNotes getCreditNote($xero_tenant_id, $credit_note_id, $unitdp)

Retrieves a specific credit note using a unique credit note Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getCreditNote($xero_tenant_id, $credit_note_id, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCreditNote: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCreditNoteAsPdf**
> \SplFileObject getCreditNoteAsPdf($xero_tenant_id, $credit_note_id)

Retrieves credit notes as PDF files

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note

try {
    $result = $apiInstance->getCreditNoteAsPdf($xero_tenant_id, $credit_note_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCreditNoteAsPdf: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/pdf

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCreditNoteAttachmentByFileName**
> \SplFileObject getCreditNoteAttachmentByFileName($xero_tenant_id, $credit_note_id, $file_name, $content_type)

Retrieves a specific attachment on a specific credit note by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getCreditNoteAttachmentByFileName($xero_tenant_id, $credit_note_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCreditNoteAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCreditNoteAttachmentById**
> \SplFileObject getCreditNoteAttachmentById($xero_tenant_id, $credit_note_id, $attachment_id, $content_type)

Retrieves a specific attachment from a specific credit note using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getCreditNoteAttachmentById($xero_tenant_id, $credit_note_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCreditNoteAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCreditNoteAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getCreditNoteAttachments($xero_tenant_id, $credit_note_id)

Retrieves attachments for a specific credit notes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note

try {
    $result = $apiInstance->getCreditNoteAttachments($xero_tenant_id, $credit_note_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCreditNoteAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCreditNoteHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getCreditNoteHistory($xero_tenant_id, $credit_note_id)

Retrieves history records of a specific credit note

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note

try {
    $result = $apiInstance->getCreditNoteHistory($xero_tenant_id, $credit_note_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCreditNoteHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCreditNotes**
> \XeroAPI\XeroPHP\Models\Accounting\CreditNotes getCreditNotes($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp)

Retrieves any credit notes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="DRAFT"; // string | Filter by an any element
$order = CreditNoteNumber ASC; // string | Order by an any element
$page = 1; // int | e.g. page=1 – Up to 100 credit notes will be returned in a single API call with line items shown for each credit note
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getCreditNotes($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCreditNotes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **page** | **int**| e.g. page&#x3D;1 – Up to 100 credit notes will be returned in a single API call with line items shown for each credit note | [optional]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getCurrencies**
> \XeroAPI\XeroPHP\Models\Accounting\Currencies getCurrencies($xero_tenant_id, $where, $order)

Retrieves currencies for your Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$where = Code=="USD"; // string | Filter by an any element
$order = Code ASC; // string | Order by an any element

try {
    $result = $apiInstance->getCurrencies($xero_tenant_id, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getCurrencies: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Currencies**](../Model/Currencies.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployee**
> \XeroAPI\XeroPHP\Models\Accounting\Employees getEmployee($xero_tenant_id, $employee_id)

Retrieves a specific employee used in Xero payrun using a unique employee Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$employee_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Employee

try {
    $result = $apiInstance->getEmployee($xero_tenant_id, $employee_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getEmployee: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employee_id** | [**string**](../Model/.md)| Unique identifier for a Employee |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Employees**](../Model/Employees.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getEmployees**
> \XeroAPI\XeroPHP\Models\Accounting\Employees getEmployees($xero_tenant_id, $if_modified_since, $where, $order)

Retrieves employees used in Xero payrun

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="ACTIVE"; // string | Filter by an any element
$order = LastName ASC; // string | Order by an any element

try {
    $result = $apiInstance->getEmployees($xero_tenant_id, $if_modified_since, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getEmployees: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Employees**](../Model/Employees.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getExpenseClaim**
> \XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims getExpenseClaim($xero_tenant_id, $expense_claim_id)

Retrieves a specific expense claim using a unique expense claim Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$expense_claim_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ExpenseClaim

try {
    $result = $apiInstance->getExpenseClaim($xero_tenant_id, $expense_claim_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getExpenseClaim: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **expense_claim_id** | [**string**](../Model/.md)| Unique identifier for a ExpenseClaim |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims**](../Model/ExpenseClaims.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getExpenseClaimHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getExpenseClaimHistory($xero_tenant_id, $expense_claim_id)

Retrieves history records of a specific expense claim

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$expense_claim_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ExpenseClaim

try {
    $result = $apiInstance->getExpenseClaimHistory($xero_tenant_id, $expense_claim_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getExpenseClaimHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **expense_claim_id** | [**string**](../Model/.md)| Unique identifier for a ExpenseClaim |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getExpenseClaims**
> \XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims getExpenseClaims($xero_tenant_id, $if_modified_since, $where, $order)

Retrieves expense claims

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="SUBMITTED"; // string | Filter by an any element
$order = Status ASC; // string | Order by an any element

try {
    $result = $apiInstance->getExpenseClaims($xero_tenant_id, $if_modified_since, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getExpenseClaims: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims**](../Model/ExpenseClaims.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoice**
> \XeroAPI\XeroPHP\Models\Accounting\Invoices getInvoice($xero_tenant_id, $invoice_id, $unitdp)

Retrieves a specific sales invoice or purchase bill using a unique invoice Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getInvoice($xero_tenant_id, $invoice_id, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoice: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoiceAsPdf**
> \SplFileObject getInvoiceAsPdf($xero_tenant_id, $invoice_id)

Retrieves invoices or purchase bills as PDF files

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice

try {
    $result = $apiInstance->getInvoiceAsPdf($xero_tenant_id, $invoice_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoiceAsPdf: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/pdf

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoiceAttachmentByFileName**
> \SplFileObject getInvoiceAttachmentByFileName($xero_tenant_id, $invoice_id, $file_name, $content_type)

Retrieves an attachment from a specific invoice or purchase bill by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getInvoiceAttachmentByFileName($xero_tenant_id, $invoice_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoiceAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoiceAttachmentById**
> \SplFileObject getInvoiceAttachmentById($xero_tenant_id, $invoice_id, $attachment_id, $content_type)

Retrieves a specific attachment from a specific invoices or purchase bills by using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getInvoiceAttachmentById($xero_tenant_id, $invoice_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoiceAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoiceAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getInvoiceAttachments($xero_tenant_id, $invoice_id)

Retrieves attachments for a specific invoice or purchase bill

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice

try {
    $result = $apiInstance->getInvoiceAttachments($xero_tenant_id, $invoice_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoiceAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoiceHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getInvoiceHistory($xero_tenant_id, $invoice_id)

Retrieves history records for a specific invoice

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice

try {
    $result = $apiInstance->getInvoiceHistory($xero_tenant_id, $invoice_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoiceHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoiceReminders**
> \XeroAPI\XeroPHP\Models\Accounting\InvoiceReminders getInvoiceReminders($xero_tenant_id)

Retrieves invoice reminder settings

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getInvoiceReminders($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoiceReminders: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\InvoiceReminders**](../Model/InvoiceReminders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getInvoices**
> \XeroAPI\XeroPHP\Models\Accounting\Invoices getInvoices($xero_tenant_id, $if_modified_since, $where, $order, $i_ds, $invoice_numbers, $contact_i_ds, $statuses, $page, $include_archived, $created_by_my_app, $unitdp)

Retrieves sales invoices or purchase bills

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="DRAFT"; // string | Filter by an any element
$order = InvoiceNumber ASC; // string | Order by an any element
$i_ds = &quot;00000000-0000-0000-0000-000000000000&quot;; // string[] | Filter by a comma-separated list of InvoicesIDs.
$invoice_numbers = &quot;INV-001&quot;, &quot;INV-002&quot;; // string[] | Filter by a comma-separated list of InvoiceNumbers.
$contact_i_ds = &quot;00000000-0000-0000-0000-000000000000&quot;; // string[] | Filter by a comma-separated list of ContactIDs.
$statuses = &quot;DRAFT&quot;, &quot;SUBMITTED&quot;; // string[] | Filter by a comma-separated list Statuses. For faster response times we recommend using these explicit parameters instead of passing OR conditions into the Where filter.
$page = 1; // int | e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items shown for each invoice
$include_archived = True; // bool | e.g. includeArchived=true - Invoices with a status of ARCHIVED will be included in the response
$created_by_my_app = false; // bool | When set to true you'll only retrieve Invoices created by your app
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getInvoices($xero_tenant_id, $if_modified_since, $where, $order, $i_ds, $invoice_numbers, $contact_i_ds, $statuses, $page, $include_archived, $created_by_my_app, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getInvoices: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **i_ds** | [**string[]**](../Model/string.md)| Filter by a comma-separated list of InvoicesIDs. | [optional]
 **invoice_numbers** | [**string[]**](../Model/string.md)| Filter by a comma-separated list of InvoiceNumbers. | [optional]
 **contact_i_ds** | [**string[]**](../Model/string.md)| Filter by a comma-separated list of ContactIDs. | [optional]
 **statuses** | [**string[]**](../Model/string.md)| Filter by a comma-separated list Statuses. For faster response times we recommend using these explicit parameters instead of passing OR conditions into the Where filter. | [optional]
 **page** | **int**| e.g. page&#x3D;1 – Up to 100 invoices will be returned in a single API call with line items shown for each invoice | [optional]
 **include_archived** | **bool**| e.g. includeArchived&#x3D;true - Invoices with a status of ARCHIVED will be included in the response | [optional]
 **created_by_my_app** | **bool**| When set to true you&#39;ll only retrieve Invoices created by your app | [optional]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getItem**
> \XeroAPI\XeroPHP\Models\Accounting\Items getItem($xero_tenant_id, $item_id, $unitdp)

Retrieves a specific item using a unique item Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$item_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Item
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getItem($xero_tenant_id, $item_id, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getItem: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **item_id** | [**string**](../Model/.md)| Unique identifier for an Item |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getItemHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getItemHistory($xero_tenant_id, $item_id)

Retrieves history for a specific item

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$item_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Item

try {
    $result = $apiInstance->getItemHistory($xero_tenant_id, $item_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getItemHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **item_id** | [**string**](../Model/.md)| Unique identifier for an Item |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getItems**
> \XeroAPI\XeroPHP\Models\Accounting\Items getItems($xero_tenant_id, $if_modified_since, $where, $order, $unitdp)

Retrieves items

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = IsSold==true; // string | Filter by an any element
$order = Code ASC; // string | Order by an any element
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getItems($xero_tenant_id, $if_modified_since, $where, $order, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getItems: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getJournal**
> \XeroAPI\XeroPHP\Models\Accounting\Journals getJournal($xero_tenant_id, $journal_id)

Retrieves a specific journal using a unique journal Id.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Journal

try {
    $result = $apiInstance->getJournal($xero_tenant_id, $journal_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getJournal: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **journal_id** | [**string**](../Model/.md)| Unique identifier for a Journal |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Journals**](../Model/Journals.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getJournals**
> \XeroAPI\XeroPHP\Models\Accounting\Journals getJournals($xero_tenant_id, $if_modified_since, $offset, $payments_only)

Retrieves journals

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$offset = 10; // int | Offset by a specified journal number. e.g. journals with a JournalNumber greater than the offset will be returned
$payments_only = True; // bool | Filter to retrieve journals on a cash basis. Journals are returned on an accrual basis by default.

try {
    $result = $apiInstance->getJournals($xero_tenant_id, $if_modified_since, $offset, $payments_only);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getJournals: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **offset** | **int**| Offset by a specified journal number. e.g. journals with a JournalNumber greater than the offset will be returned | [optional]
 **payments_only** | **bool**| Filter to retrieve journals on a cash basis. Journals are returned on an accrual basis by default. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Journals**](../Model/Journals.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getLinkedTransaction**
> \XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions getLinkedTransaction($xero_tenant_id, $linked_transaction_id)

Retrieves a specific linked transaction (billable expenses) using a unique linked transaction Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$linked_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a LinkedTransaction

try {
    $result = $apiInstance->getLinkedTransaction($xero_tenant_id, $linked_transaction_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getLinkedTransaction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **linked_transaction_id** | [**string**](../Model/.md)| Unique identifier for a LinkedTransaction |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions**](../Model/LinkedTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getLinkedTransactions**
> \XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions getLinkedTransactions($xero_tenant_id, $page, $linked_transaction_id, $source_transaction_id, $contact_id, $status, $target_transaction_id)

Retrieves linked transactions (billable expenses)

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$page = 1; // int | Up to 100 linked transactions will be returned in a single API call. Use the page parameter to specify the page to be returned e.g. page=1.
$linked_transaction_id = 00000000-0000-0000-0000-000000000000; // string | The Xero identifier for an Linked Transaction
$source_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Filter by the SourceTransactionID. Get the linked transactions created from a particular ACCPAY invoice
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Filter by the ContactID. Get all the linked transactions that have been assigned to a particular customer.
$status = APPROVED; // string | Filter by the combination of ContactID and Status. Get  the linked transactions associated to a  customer and with a status
$target_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Filter by the TargetTransactionID. Get all the linked transactions allocated to a particular ACCREC invoice

try {
    $result = $apiInstance->getLinkedTransactions($xero_tenant_id, $page, $linked_transaction_id, $source_transaction_id, $contact_id, $status, $target_transaction_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getLinkedTransactions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **page** | **int**| Up to 100 linked transactions will be returned in a single API call. Use the page parameter to specify the page to be returned e.g. page&#x3D;1. | [optional]
 **linked_transaction_id** | [**string**](../Model/.md)| The Xero identifier for an Linked Transaction | [optional]
 **source_transaction_id** | [**string**](../Model/.md)| Filter by the SourceTransactionID. Get the linked transactions created from a particular ACCPAY invoice | [optional]
 **contact_id** | [**string**](../Model/.md)| Filter by the ContactID. Get all the linked transactions that have been assigned to a particular customer. | [optional]
 **status** | **string**| Filter by the combination of ContactID and Status. Get  the linked transactions associated to a  customer and with a status | [optional]
 **target_transaction_id** | [**string**](../Model/.md)| Filter by the TargetTransactionID. Get all the linked transactions allocated to a particular ACCREC invoice | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions**](../Model/LinkedTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getManualJournal**
> \XeroAPI\XeroPHP\Models\Accounting\ManualJournals getManualJournal($xero_tenant_id, $manual_journal_id)

Retrieves a specific manual journal

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal

try {
    $result = $apiInstance->getManualJournal($xero_tenant_id, $manual_journal_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getManualJournal: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getManualJournalAttachmentByFileName**
> \SplFileObject getManualJournalAttachmentByFileName($xero_tenant_id, $manual_journal_id, $file_name, $content_type)

Retrieves a specific attachment from a specific manual journal by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getManualJournalAttachmentByFileName($xero_tenant_id, $manual_journal_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getManualJournalAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getManualJournalAttachmentById**
> \SplFileObject getManualJournalAttachmentById($xero_tenant_id, $manual_journal_id, $attachment_id, $content_type)

Allows you to retrieve a specific attachment from a specific manual journal using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getManualJournalAttachmentById($xero_tenant_id, $manual_journal_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getManualJournalAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getManualJournalAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getManualJournalAttachments($xero_tenant_id, $manual_journal_id)

Retrieves attachment for a specific manual journal

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal

try {
    $result = $apiInstance->getManualJournalAttachments($xero_tenant_id, $manual_journal_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getManualJournalAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getManualJournals**
> \XeroAPI\XeroPHP\Models\Accounting\ManualJournals getManualJournals($xero_tenant_id, $if_modified_since, $where, $order, $page)

Retrieves manual journals

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="DRAFT"; // string | Filter by an any element
$order = Date ASC; // string | Order by an any element
$page = 1; // int | e.g. page=1 – Up to 100 manual journals will be returned in a single API call with line items shown for each overpayment

try {
    $result = $apiInstance->getManualJournals($xero_tenant_id, $if_modified_since, $where, $order, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getManualJournals: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **page** | **int**| e.g. page&#x3D;1 – Up to 100 manual journals will be returned in a single API call with line items shown for each overpayment | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getManualJournalsHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getManualJournalsHistory($xero_tenant_id, $manual_journal_id)

Retrieves history for a specific manual journal

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal

try {
    $result = $apiInstance->getManualJournalsHistory($xero_tenant_id, $manual_journal_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getManualJournalsHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getOnlineInvoice**
> \XeroAPI\XeroPHP\Models\Accounting\OnlineInvoices getOnlineInvoice($xero_tenant_id, $invoice_id)

Retrieves a URL to an online invoice

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice

try {
    $result = $apiInstance->getOnlineInvoice($xero_tenant_id, $invoice_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getOnlineInvoice: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\OnlineInvoices**](../Model/OnlineInvoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getOrganisationActions**
> \XeroAPI\XeroPHP\Models\Accounting\Actions getOrganisationActions($xero_tenant_id)

Retrieves a list of the key actions your app has permission to perform in the connected Xero organisation.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getOrganisationActions($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getOrganisationActions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Actions**](../Model/Actions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getOrganisationCISSettings**
> \XeroAPI\XeroPHP\Models\Accounting\CISOrgSettings getOrganisationCISSettings($xero_tenant_id, $organisation_id)

Retrieves the CIS settings for the Xero organistaion.

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$organisation_id = 00000000-0000-0000-0000-000000000000; // string | The unique Xero identifier for an organisation

try {
    $result = $apiInstance->getOrganisationCISSettings($xero_tenant_id, $organisation_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getOrganisationCISSettings: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **organisation_id** | [**string**](../Model/.md)| The unique Xero identifier for an organisation |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\CISOrgSettings**](../Model/CISOrgSettings.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getOrganisations**
> \XeroAPI\XeroPHP\Models\Accounting\Organisations getOrganisations($xero_tenant_id)

Retrieves Xero organisation details

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getOrganisations($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getOrganisations: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Organisations**](../Model/Organisations.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getOverpayment**
> \XeroAPI\XeroPHP\Models\Accounting\Overpayments getOverpayment($xero_tenant_id, $overpayment_id)

Retrieves a specific overpayment using a unique overpayment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$overpayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Overpayment

try {
    $result = $apiInstance->getOverpayment($xero_tenant_id, $overpayment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getOverpayment: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **overpayment_id** | [**string**](../Model/.md)| Unique identifier for a Overpayment |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Overpayments**](../Model/Overpayments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getOverpaymentHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getOverpaymentHistory($xero_tenant_id, $overpayment_id)

Retrieves history records of a specific overpayment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$overpayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Overpayment

try {
    $result = $apiInstance->getOverpaymentHistory($xero_tenant_id, $overpayment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getOverpaymentHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **overpayment_id** | [**string**](../Model/.md)| Unique identifier for a Overpayment |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getOverpayments**
> \XeroAPI\XeroPHP\Models\Accounting\Overpayments getOverpayments($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp)

Retrieves overpayments

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="AUTHORISED"; // string | Filter by an any element
$order = Status ASC; // string | Order by an any element
$page = 1; // int | e.g. page=1 – Up to 100 overpayments will be returned in a single API call with line items shown for each overpayment
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getOverpayments($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getOverpayments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **page** | **int**| e.g. page&#x3D;1 – Up to 100 overpayments will be returned in a single API call with line items shown for each overpayment | [optional]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Overpayments**](../Model/Overpayments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPayment**
> \XeroAPI\XeroPHP\Models\Accounting\Payments getPayment($xero_tenant_id, $payment_id)

Retrieves a specific payment for invoices and credit notes using a unique payment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$payment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Payment

try {
    $result = $apiInstance->getPayment($xero_tenant_id, $payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPayment: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payment_id** | [**string**](../Model/.md)| Unique identifier for a Payment |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Payments**](../Model/Payments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPaymentHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getPaymentHistory($xero_tenant_id, $payment_id)

Retrieves history records of a specific payment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$payment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Payment

try {
    $result = $apiInstance->getPaymentHistory($xero_tenant_id, $payment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPaymentHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **payment_id** | [**string**](../Model/.md)| Unique identifier for a Payment |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPaymentServices**
> \XeroAPI\XeroPHP\Models\Accounting\PaymentServices getPaymentServices($xero_tenant_id)

Retrieves payment services

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getPaymentServices($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPaymentServices: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PaymentServices**](../Model/PaymentServices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPayments**
> \XeroAPI\XeroPHP\Models\Accounting\Payments getPayments($xero_tenant_id, $if_modified_since, $where, $order, $page)

Retrieves payments for invoices and credit notes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="AUTHORISED"; // string | Filter by an any element
$order = Amount ASC; // string | Order by an any element
$page = 1; // int | Up to 100 payments will be returned in a single API call

try {
    $result = $apiInstance->getPayments($xero_tenant_id, $if_modified_since, $where, $order, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPayments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **page** | **int**| Up to 100 payments will be returned in a single API call | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Payments**](../Model/Payments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPrepayment**
> \XeroAPI\XeroPHP\Models\Accounting\Prepayments getPrepayment($xero_tenant_id, $prepayment_id)

Allows you to retrieve a specified prepayments

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$prepayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a PrePayment

try {
    $result = $apiInstance->getPrepayment($xero_tenant_id, $prepayment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPrepayment: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **prepayment_id** | [**string**](../Model/.md)| Unique identifier for a PrePayment |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Prepayments**](../Model/Prepayments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPrepaymentHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getPrepaymentHistory($xero_tenant_id, $prepayment_id)

Retrieves history record for a specific prepayment

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$prepayment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a PrePayment

try {
    $result = $apiInstance->getPrepaymentHistory($xero_tenant_id, $prepayment_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPrepaymentHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **prepayment_id** | [**string**](../Model/.md)| Unique identifier for a PrePayment |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPrepayments**
> \XeroAPI\XeroPHP\Models\Accounting\Prepayments getPrepayments($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp)

Retrieves prepayments

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="AUTHORISED"; // string | Filter by an any element
$order = Reference ASC; // string | Order by an any element
$page = 1; // int | e.g. page=1 – Up to 100 prepayments will be returned in a single API call with line items shown for each overpayment
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getPrepayments($xero_tenant_id, $if_modified_since, $where, $order, $page, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPrepayments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **page** | **int**| e.g. page&#x3D;1 – Up to 100 prepayments will be returned in a single API call with line items shown for each overpayment | [optional]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Prepayments**](../Model/Prepayments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrder**
> \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders getPurchaseOrder($xero_tenant_id, $purchase_order_id)

Retrieves a specific purchase order using a unique purchase order Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order

try {
    $result = $apiInstance->getPurchaseOrder($xero_tenant_id, $purchase_order_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrder: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrderAsPdf**
> \SplFileObject getPurchaseOrderAsPdf($xero_tenant_id, $purchase_order_id)

Retrieves specific purchase order as PDF files using a unique purchase order Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order

try {
    $result = $apiInstance->getPurchaseOrderAsPdf($xero_tenant_id, $purchase_order_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrderAsPdf: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/pdf

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrderAttachmentByFileName**
> \SplFileObject getPurchaseOrderAttachmentByFileName($xero_tenant_id, $purchase_order_id, $file_name, $content_type)

Retrieves a specific attachment for a specific purchase order by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getPurchaseOrderAttachmentByFileName($xero_tenant_id, $purchase_order_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrderAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrderAttachmentById**
> \SplFileObject getPurchaseOrderAttachmentById($xero_tenant_id, $purchase_order_id, $attachment_id, $content_type)

Retrieves specific attachment for a specific purchase order using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getPurchaseOrderAttachmentById($xero_tenant_id, $purchase_order_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrderAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrderAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getPurchaseOrderAttachments($xero_tenant_id, $purchase_order_id)

Retrieves attachments for a specific purchase order

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order

try {
    $result = $apiInstance->getPurchaseOrderAttachments($xero_tenant_id, $purchase_order_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrderAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrderByNumber**
> \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders getPurchaseOrderByNumber($xero_tenant_id, $purchase_order_number)

Retrieves a specific purchase order using purchase order number

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_number = PO1234; // string | Unique identifier for a PurchaseOrder

try {
    $result = $apiInstance->getPurchaseOrderByNumber($xero_tenant_id, $purchase_order_number);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrderByNumber: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_number** | **string**| Unique identifier for a PurchaseOrder |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrderHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getPurchaseOrderHistory($xero_tenant_id, $purchase_order_id)

Retrieves history for a specific purchase order

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order

try {
    $result = $apiInstance->getPurchaseOrderHistory($xero_tenant_id, $purchase_order_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrderHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getPurchaseOrders**
> \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders getPurchaseOrders($xero_tenant_id, $if_modified_since, $status, $date_from, $date_to, $order, $page)

Retrieves purchase orders

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$status = SUBMITTED; // string | Filter by purchase order status
$date_from = 2019-12-01; // string | Filter by purchase order date (e.g. GET https://.../PurchaseOrders?DateFrom=2015-12-01&DateTo=2015-12-31
$date_to = 2019-12-31; // string | Filter by purchase order date (e.g. GET https://.../PurchaseOrders?DateFrom=2015-12-01&DateTo=2015-12-31
$order = PurchaseOrderNumber ASC; // string | Order by an any element
$page = 1; // int | To specify a page, append the page parameter to the URL e.g. ?page=1. If there are 100 records in the response you will need to check if there is any more data by fetching the next page e.g ?page=2 and continuing this process until no more results are returned.

try {
    $result = $apiInstance->getPurchaseOrders($xero_tenant_id, $if_modified_since, $status, $date_from, $date_to, $order, $page);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getPurchaseOrders: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **status** | **string**| Filter by purchase order status | [optional]
 **date_from** | **string**| Filter by purchase order date (e.g. GET https://.../PurchaseOrders?DateFrom&#x3D;2015-12-01&amp;DateTo&#x3D;2015-12-31 | [optional]
 **date_to** | **string**| Filter by purchase order date (e.g. GET https://.../PurchaseOrders?DateFrom&#x3D;2015-12-01&amp;DateTo&#x3D;2015-12-31 | [optional]
 **order** | **string**| Order by an any element | [optional]
 **page** | **int**| To specify a page, append the page parameter to the URL e.g. ?page&#x3D;1. If there are 100 records in the response you will need to check if there is any more data by fetching the next page e.g ?page&#x3D;2 and continuing this process until no more results are returned. | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getQuote**
> \XeroAPI\XeroPHP\Models\Accounting\Quotes getQuote($xero_tenant_id, $quote_id)

Retrieves a specific quote using a unique quote Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote

try {
    $result = $apiInstance->getQuote($xero_tenant_id, $quote_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getQuote: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getQuoteAsPdf**
> \SplFileObject getQuoteAsPdf($xero_tenant_id, $quote_id)

Retrieves a specific quote as a PDF file using a unique quote Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote

try {
    $result = $apiInstance->getQuoteAsPdf($xero_tenant_id, $quote_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getQuoteAsPdf: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/pdf

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getQuoteAttachmentByFileName**
> \SplFileObject getQuoteAttachmentByFileName($xero_tenant_id, $quote_id, $file_name, $content_type)

Retrieves a specific attachment from a specific quote by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getQuoteAttachmentByFileName($xero_tenant_id, $quote_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getQuoteAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getQuoteAttachmentById**
> \SplFileObject getQuoteAttachmentById($xero_tenant_id, $quote_id, $attachment_id, $content_type)

Retrieves a specific attachment from a specific quote using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getQuoteAttachmentById($xero_tenant_id, $quote_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getQuoteAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getQuoteAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getQuoteAttachments($xero_tenant_id, $quote_id)

Retrieves attachments for a specific quote

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote

try {
    $result = $apiInstance->getQuoteAttachments($xero_tenant_id, $quote_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getQuoteAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getQuoteHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getQuoteHistory($xero_tenant_id, $quote_id)

Retrieves history records of a specific quote

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote

try {
    $result = $apiInstance->getQuoteHistory($xero_tenant_id, $quote_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getQuoteHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getQuotes**
> \XeroAPI\XeroPHP\Models\Accounting\Quotes getQuotes($xero_tenant_id, $if_modified_since, $date_from, $date_to, $expiry_date_from, $expiry_date_to, $contact_id, $status, $page, $order, $quote_number)

Retrieves sales quotes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$date_from = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Filter for quotes after a particular date
$date_to = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Filter for quotes before a particular date
$expiry_date_from = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Filter for quotes expiring after a particular date
$expiry_date_to = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | Filter for quotes before a particular date
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Filter for quotes belonging to a particular contact
$status = DRAFT; // string | Filter for quotes of a particular Status
$page = 1; // int | e.g. page=1 – Up to 100 Quotes will be returned in a single API call with line items shown for each quote
$order = Status ASC; // string | Order by an any element
$quote_number = QU-0001; // string | Filter by quote number (e.g. GET https://.../Quotes?QuoteNumber=QU-0001)

try {
    $result = $apiInstance->getQuotes($xero_tenant_id, $if_modified_since, $date_from, $date_to, $expiry_date_from, $expiry_date_to, $contact_id, $status, $page, $order, $quote_number);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getQuotes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **date_from** | **\DateTime**| Filter for quotes after a particular date | [optional]
 **date_to** | **\DateTime**| Filter for quotes before a particular date | [optional]
 **expiry_date_from** | **\DateTime**| Filter for quotes expiring after a particular date | [optional]
 **expiry_date_to** | **\DateTime**| Filter for quotes before a particular date | [optional]
 **contact_id** | [**string**](../Model/.md)| Filter for quotes belonging to a particular contact | [optional]
 **status** | **string**| Filter for quotes of a particular Status | [optional]
 **page** | **int**| e.g. page&#x3D;1 – Up to 100 Quotes will be returned in a single API call with line items shown for each quote | [optional]
 **order** | **string**| Order by an any element | [optional]
 **quote_number** | **string**| Filter by quote number (e.g. GET https://.../Quotes?QuoteNumber&#x3D;QU-0001) | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReceipt**
> \XeroAPI\XeroPHP\Models\Accounting\Receipts getReceipt($xero_tenant_id, $receipt_id, $unitdp)

Retrieves a specific draft expense claim receipt by using a unique receipt Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getReceipt($xero_tenant_id, $receipt_id, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReceipt: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Receipts**](../Model/Receipts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReceiptAttachmentByFileName**
> \SplFileObject getReceiptAttachmentByFileName($xero_tenant_id, $receipt_id, $file_name, $content_type)

Retrieves a specific attachment from a specific expense claim receipts by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getReceiptAttachmentByFileName($xero_tenant_id, $receipt_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReceiptAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReceiptAttachmentById**
> \SplFileObject getReceiptAttachmentById($xero_tenant_id, $receipt_id, $attachment_id, $content_type)

Retrieves a specific attachments from a specific expense claim receipts by using a unique attachment Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getReceiptAttachmentById($xero_tenant_id, $receipt_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReceiptAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReceiptAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getReceiptAttachments($xero_tenant_id, $receipt_id)

Retrieves attachments for a specific expense claim receipt

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt

try {
    $result = $apiInstance->getReceiptAttachments($xero_tenant_id, $receipt_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReceiptAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReceiptHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getReceiptHistory($xero_tenant_id, $receipt_id)

Retrieves a history record for a specific receipt

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt

try {
    $result = $apiInstance->getReceiptHistory($xero_tenant_id, $receipt_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReceiptHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReceipts**
> \XeroAPI\XeroPHP\Models\Accounting\Receipts getReceipts($xero_tenant_id, $if_modified_since, $where, $order, $unitdp)

Retrieves draft expense claim receipts for any user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = Status=="DRAFT"; // string | Filter by an any element
$order = ReceiptNumber ASC; // string | Order by an any element
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->getReceipts($xero_tenant_id, $if_modified_since, $where, $order, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReceipts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Receipts**](../Model/Receipts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRepeatingInvoice**
> \XeroAPI\XeroPHP\Models\Accounting\RepeatingInvoices getRepeatingInvoice($xero_tenant_id, $repeating_invoice_id)

Retrieves a specific repeating invoice by using a unique repeating invoice Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice

try {
    $result = $apiInstance->getRepeatingInvoice($xero_tenant_id, $repeating_invoice_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getRepeatingInvoice: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\RepeatingInvoices**](../Model/RepeatingInvoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRepeatingInvoiceAttachmentByFileName**
> \SplFileObject getRepeatingInvoiceAttachmentByFileName($xero_tenant_id, $repeating_invoice_id, $file_name, $content_type)

Retrieves a specific attachment from a specific repeating invoices by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice
$file_name = xero-dev.jpg; // string | Name of the attachment
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getRepeatingInvoiceAttachmentByFileName($xero_tenant_id, $repeating_invoice_id, $file_name, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getRepeatingInvoiceAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |
 **file_name** | **string**| Name of the attachment |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRepeatingInvoiceAttachmentById**
> \SplFileObject getRepeatingInvoiceAttachmentById($xero_tenant_id, $repeating_invoice_id, $attachment_id, $content_type)

Retrieves a specific attachment from a specific repeating invoice

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice
$attachment_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Attachment object
$content_type = image/jpg; // string | The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf

try {
    $result = $apiInstance->getRepeatingInvoiceAttachmentById($xero_tenant_id, $repeating_invoice_id, $attachment_id, $content_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getRepeatingInvoiceAttachmentById: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |
 **attachment_id** | [**string**](../Model/.md)| Unique identifier for Attachment object |
 **content_type** | **string**| The mime type of the attachment file you are retrieving i.e image/jpg, application/pdf |

### Return type

[**\SplFileObject**](../Model/\SplFileObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/octet-stream

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRepeatingInvoiceAttachments**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments getRepeatingInvoiceAttachments($xero_tenant_id, $repeating_invoice_id)

Retrieves attachments from a specific repeating invoice

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice

try {
    $result = $apiInstance->getRepeatingInvoiceAttachments($xero_tenant_id, $repeating_invoice_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getRepeatingInvoiceAttachments: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRepeatingInvoiceHistory**
> \XeroAPI\XeroPHP\Models\Accounting\HistoryRecords getRepeatingInvoiceHistory($xero_tenant_id, $repeating_invoice_id)

Retrieves history record for a specific repeating invoice

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice

try {
    $result = $apiInstance->getRepeatingInvoiceHistory($xero_tenant_id, $repeating_invoice_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getRepeatingInvoiceHistory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\HistoryRecords**](../Model/HistoryRecords.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getRepeatingInvoices**
> \XeroAPI\XeroPHP\Models\Accounting\RepeatingInvoices getRepeatingInvoices($xero_tenant_id, $where, $order)

Retrieves repeating invoices

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$where = Status=="DRAFT"; // string | Filter by an any element
$order = Total ASC; // string | Order by an any element

try {
    $result = $apiInstance->getRepeatingInvoices($xero_tenant_id, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getRepeatingInvoices: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\RepeatingInvoices**](../Model/RepeatingInvoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportAgedPayablesByContact**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportAgedPayablesByContact($xero_tenant_id, $contact_id, $date, $from_date, $to_date)

Retrieves report for aged payables by contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | The date of the Aged Payables By Contact report
$from_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the from date of the report e.g. 2021-02-01
$to_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the to date of the report e.g. 2021-02-28

try {
    $result = $apiInstance->getReportAgedPayablesByContact($xero_tenant_id, $contact_id, $date, $from_date, $to_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportAgedPayablesByContact: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **date** | **\DateTime**| The date of the Aged Payables By Contact report | [optional]
 **from_date** | **\DateTime**| filter by the from date of the report e.g. 2021-02-01 | [optional]
 **to_date** | **\DateTime**| filter by the to date of the report e.g. 2021-02-28 | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportAgedReceivablesByContact**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportAgedReceivablesByContact($xero_tenant_id, $contact_id, $date, $from_date, $to_date)

Retrieves report for aged receivables by contact

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | The date of the Aged Receivables By Contact report
$from_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the from date of the report e.g. 2021-02-01
$to_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the to date of the report e.g. 2021-02-28

try {
    $result = $apiInstance->getReportAgedReceivablesByContact($xero_tenant_id, $contact_id, $date, $from_date, $to_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportAgedReceivablesByContact: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **date** | **\DateTime**| The date of the Aged Receivables By Contact report | [optional]
 **from_date** | **\DateTime**| filter by the from date of the report e.g. 2021-02-01 | [optional]
 **to_date** | **\DateTime**| filter by the to date of the report e.g. 2021-02-28 | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportBASorGST**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportBASorGST($xero_tenant_id, $report_id)

Retrieves a specific report for BAS using a unique report Id (only valid for AU orgs)

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$report_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Report

try {
    $result = $apiInstance->getReportBASorGST($xero_tenant_id, $report_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportBASorGST: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **report_id** | **string**| Unique identifier for a Report |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportBASorGSTList**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportBASorGSTList($xero_tenant_id)

Retrieves report for BAS (only valid for AU orgs)

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant

try {
    $result = $apiInstance->getReportBASorGSTList($xero_tenant_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportBASorGSTList: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportBalanceSheet**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportBalanceSheet($xero_tenant_id, $date, $periods, $timeframe, $tracking_option_id1, $tracking_option_id2, $standard_layout, $payments_only)

Retrieves report for balancesheet

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$date = 2019-11-01; // \DateTime | The date of the Balance Sheet report
$periods = 3; // int | The number of periods for the Balance Sheet report
$timeframe = MONTH; // string | The period size to compare to (MONTH, QUARTER, YEAR)
$tracking_option_id1 = 00000000-0000-0000-0000-000000000000; // string | The tracking option 1 for the Balance Sheet report
$tracking_option_id2 = 00000000-0000-0000-0000-000000000000; // string | The tracking option 2 for the Balance Sheet report
$standard_layout = true; // bool | The standard layout boolean for the Balance Sheet report
$payments_only = false; // bool | return a cash basis for the Balance Sheet report

try {
    $result = $apiInstance->getReportBalanceSheet($xero_tenant_id, $date, $periods, $timeframe, $tracking_option_id1, $tracking_option_id2, $standard_layout, $payments_only);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportBalanceSheet: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **date** | **\DateTime**| The date of the Balance Sheet report | [optional]
 **periods** | **int**| The number of periods for the Balance Sheet report | [optional]
 **timeframe** | **string**| The period size to compare to (MONTH, QUARTER, YEAR) | [optional]
 **tracking_option_id1** | **string**| The tracking option 1 for the Balance Sheet report | [optional]
 **tracking_option_id2** | **string**| The tracking option 2 for the Balance Sheet report | [optional]
 **standard_layout** | **bool**| The standard layout boolean for the Balance Sheet report | [optional]
 **payments_only** | **bool**| return a cash basis for the Balance Sheet report | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportBankSummary**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportBankSummary($xero_tenant_id, $from_date, $to_date)

Retrieves report for bank summary

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$from_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the from date of the report e.g. 2021-02-01
$to_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the to date of the report e.g. 2021-02-28

try {
    $result = $apiInstance->getReportBankSummary($xero_tenant_id, $from_date, $to_date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportBankSummary: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **from_date** | **\DateTime**| filter by the from date of the report e.g. 2021-02-01 | [optional]
 **to_date** | **\DateTime**| filter by the to date of the report e.g. 2021-02-28 | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportBudgetSummary**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportBudgetSummary($xero_tenant_id, $date, $period, $timeframe)

Retrieves report for budget summary

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$date = 2019-03-31; // \DateTime | The date for the Bank Summary report e.g. 2018-03-31
$period = 2; // int | The number of periods to compare (integer between 1 and 12)
$timeframe = 3; // int | The period size to compare to (1=month, 3=quarter, 12=year)

try {
    $result = $apiInstance->getReportBudgetSummary($xero_tenant_id, $date, $period, $timeframe);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportBudgetSummary: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **date** | **\DateTime**| The date for the Bank Summary report e.g. 2018-03-31 | [optional]
 **period** | **int**| The number of periods to compare (integer between 1 and 12) | [optional]
 **timeframe** | **int**| The period size to compare to (1&#x3D;month, 3&#x3D;quarter, 12&#x3D;year) | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportExecutiveSummary**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportExecutiveSummary($xero_tenant_id, $date)

Retrieves report for executive summary

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$date = 2019-03-31; // \DateTime | The date for the Bank Summary report e.g. 2018-03-31

try {
    $result = $apiInstance->getReportExecutiveSummary($xero_tenant_id, $date);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportExecutiveSummary: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **date** | **\DateTime**| The date for the Bank Summary report e.g. 2018-03-31 | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportProfitAndLoss**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportProfitAndLoss($xero_tenant_id, $from_date, $to_date, $periods, $timeframe, $tracking_category_id, $tracking_category_id2, $tracking_option_id, $tracking_option_id2, $standard_layout, $payments_only)

Retrieves report for profit and loss

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$from_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the from date of the report e.g. 2021-02-01
$to_date = new \DateTime("2013-10-20T19:20:30+01:00"); // \DateTime | filter by the to date of the report e.g. 2021-02-28
$periods = 3; // int | The number of periods to compare (integer between 1 and 12)
$timeframe = MONTH; // string | The period size to compare to (MONTH, QUARTER, YEAR)
$tracking_category_id = 00000000-0000-0000-0000-000000000000; // string | The trackingCategory 1 for the ProfitAndLoss report
$tracking_category_id2 = 00000000-0000-0000-0000-000000000000; // string | The trackingCategory 2 for the ProfitAndLoss report
$tracking_option_id = 00000000-0000-0000-0000-000000000000; // string | The tracking option 1 for the ProfitAndLoss report
$tracking_option_id2 = 00000000-0000-0000-0000-000000000000; // string | The tracking option 2 for the ProfitAndLoss report
$standard_layout = true; // bool | Return the standard layout for the ProfitAndLoss report
$payments_only = false; // bool | Return cash only basis for the ProfitAndLoss report

try {
    $result = $apiInstance->getReportProfitAndLoss($xero_tenant_id, $from_date, $to_date, $periods, $timeframe, $tracking_category_id, $tracking_category_id2, $tracking_option_id, $tracking_option_id2, $standard_layout, $payments_only);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportProfitAndLoss: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **from_date** | **\DateTime**| filter by the from date of the report e.g. 2021-02-01 | [optional]
 **to_date** | **\DateTime**| filter by the to date of the report e.g. 2021-02-28 | [optional]
 **periods** | **int**| The number of periods to compare (integer between 1 and 12) | [optional]
 **timeframe** | **string**| The period size to compare to (MONTH, QUARTER, YEAR) | [optional]
 **tracking_category_id** | **string**| The trackingCategory 1 for the ProfitAndLoss report | [optional]
 **tracking_category_id2** | **string**| The trackingCategory 2 for the ProfitAndLoss report | [optional]
 **tracking_option_id** | **string**| The tracking option 1 for the ProfitAndLoss report | [optional]
 **tracking_option_id2** | **string**| The tracking option 2 for the ProfitAndLoss report | [optional]
 **standard_layout** | **bool**| Return the standard layout for the ProfitAndLoss report | [optional]
 **payments_only** | **bool**| Return cash only basis for the ProfitAndLoss report | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportTenNinetyNine**
> \XeroAPI\XeroPHP\Models\Accounting\Reports getReportTenNinetyNine($xero_tenant_id, $report_year)

Retrieve reports for 1099

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$report_year = 2019; // string | The year of the 1099 report

try {
    $result = $apiInstance->getReportTenNinetyNine($xero_tenant_id, $report_year);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportTenNinetyNine: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **report_year** | **string**| The year of the 1099 report | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Reports**](../Model/Reports.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getReportTrialBalance**
> \XeroAPI\XeroPHP\Models\Accounting\ReportWithRows getReportTrialBalance($xero_tenant_id, $date, $payments_only)

Retrieves report for trial balance

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$date = 2019-10-31; // \DateTime | The date for the Trial Balance report e.g. 2018-03-31
$payments_only = true; // bool | Return cash only basis for the Trial Balance report

try {
    $result = $apiInstance->getReportTrialBalance($xero_tenant_id, $date, $payments_only);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getReportTrialBalance: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **date** | **\DateTime**| The date for the Trial Balance report e.g. 2018-03-31 | [optional]
 **payments_only** | **bool**| Return cash only basis for the Trial Balance report | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ReportWithRows**](../Model/ReportWithRows.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTaxRates**
> \XeroAPI\XeroPHP\Models\Accounting\TaxRates getTaxRates($xero_tenant_id, $where, $order, $tax_type)

Retrieves tax rates

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$where = Status=="ACTIVE"; // string | Filter by an any element
$order = Name ASC; // string | Order by an any element
$tax_type = INPUT; // string | Filter by tax type

try {
    $result = $apiInstance->getTaxRates($xero_tenant_id, $where, $order, $tax_type);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getTaxRates: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **tax_type** | **string**| Filter by tax type | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TaxRates**](../Model/TaxRates.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTrackingCategories**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingCategories getTrackingCategories($xero_tenant_id, $where, $order, $include_archived)

Retrieves tracking categories and options

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$where = Status=="ACTIVE"; // string | Filter by an any element
$order = Name ASC; // string | Order by an any element
$include_archived = True; // bool | e.g. includeArchived=true - Categories and options with a status of ARCHIVED will be included in the response

try {
    $result = $apiInstance->getTrackingCategories($xero_tenant_id, $where, $order, $include_archived);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getTrackingCategories: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]
 **include_archived** | **bool**| e.g. includeArchived&#x3D;true - Categories and options with a status of ARCHIVED will be included in the response | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategories**](../Model/TrackingCategories.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getTrackingCategory**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingCategories getTrackingCategory($xero_tenant_id, $tracking_category_id)

Retrieves specific tracking categories and options using a unique tracking category Id

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tracking_category_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a TrackingCategory

try {
    $result = $apiInstance->getTrackingCategory($xero_tenant_id, $tracking_category_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getTrackingCategory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tracking_category_id** | [**string**](../Model/.md)| Unique identifier for a TrackingCategory |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategories**](../Model/TrackingCategories.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getUser**
> \XeroAPI\XeroPHP\Models\Accounting\Users getUser($xero_tenant_id, $user_id)

Retrieves a specific user

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$user_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a User

try {
    $result = $apiInstance->getUser($xero_tenant_id, $user_id);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getUser: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **user_id** | [**string**](../Model/.md)| Unique identifier for a User |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Users**](../Model/Users.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **getUsers**
> \XeroAPI\XeroPHP\Models\Accounting\Users getUsers($xero_tenant_id, $if_modified_since, $where, $order)

Retrieves users

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$if_modified_since = 2020-02-06T12:17:43.202-08:00; // \DateTime | Only records created or modified since this timestamp will be returned
$where = IsSubscriber==true; // string | Filter by an any element
$order = LastName ASC; // string | Order by an any element

try {
    $result = $apiInstance->getUsers($xero_tenant_id, $if_modified_since, $where, $order);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->getUsers: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **if_modified_since** | **\DateTime**| Only records created or modified since this timestamp will be returned | [optional]
 **where** | **string**| Filter by an any element | [optional]
 **order** | **string**| Order by an any element | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Users**](../Model/Users.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: Not defined
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **postSetup**
> \XeroAPI\XeroPHP\Models\Accounting\ImportSummaryObject postSetup($xero_tenant_id, $setup)

Sets the chart of accounts, the conversion date and conversion balances

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$setup = { "ConversionDate": {}, "ConversionBalances": [], "Accounts": [ { "Code": "200", "Name": "Sales", "Type": "SALES", "ReportingCode": "REV.TRA.GOO" }, { "Code": "400", "Name": "Advertising", "Type": "OVERHEADS", "ReportingCode": "EXP" }, { "Code": "610", "Name": "Accounts Receivable", "Type": "CURRENT", "SystemAccount": "DEBTORS", "ReportingCode": "ASS.CUR.REC.TRA" }, { "Code": "800", "Name": "Accounts Payable", "Type": "CURRLIAB", "SystemAccount": "CREDITORS", "ReportingCode": "LIA.CUR.PAY" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Setup | Object including an accounts array, a conversion balances array and a conversion date object in body of request

try {
    $result = $apiInstance->postSetup($xero_tenant_id, $setup);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->postSetup: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **setup** | [**\XeroAPI\XeroPHP\Models\Accounting\Setup**](../Model/Setup.md)| Object including an accounts array, a conversion balances array and a conversion date object in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ImportSummaryObject**](../Model/ImportSummaryObject.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateAccount**
> \XeroAPI\XeroPHP\Models\Accounting\Accounts updateAccount($xero_tenant_id, $account_id, $accounts)

Updates a chart of accounts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object
$accounts = { "Accounts":[ { "Code":"123456", "Name":"BarFoo", "AccountID":"99ce6032-0678-4aa0-8148-240c75fee33a", "Type":"EXPENSE", "Description":"GoodBye World", "TaxType":"INPUT", "EnablePaymentsToAccount":false, "ShowInExpenseClaims":false, "Class":"EXPENSE", "ReportingCode":"EXP", "ReportingCodeName":"Expense", "UpdatedDateUTC":"2019-02-21T16:29:47.96-08:00" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Accounts | Request of type Accounts array with one Account

try {
    $result = $apiInstance->updateAccount($xero_tenant_id, $account_id, $accounts);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateAccount: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |
 **accounts** | [**\XeroAPI\XeroPHP\Models\Accounting\Accounts**](../Model/Accounts.md)| Request of type Accounts array with one Account |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Accounts**](../Model/Accounts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateAccountAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateAccountAttachmentByFileName($xero_tenant_id, $account_id, $file_name, $body)

Updates attachment on a specific account by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$account_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for Account object
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateAccountAttachmentByFileName($xero_tenant_id, $account_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateAccountAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **account_id** | [**string**](../Model/.md)| Unique identifier for Account object |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateBankTransaction**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransactions updateBankTransaction($xero_tenant_id, $bank_transaction_id, $bank_transactions, $unitdp)

Updates a single spent or received money transaction

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction
$bank_transactions = { "BankTransactions": [ { "Type": "SPEND", "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000", "ContactStatus": "ACTIVE", "Name": "Buzz Lightyear", "FirstName": "Buzz", "LastName": "Lightyear", "EmailAddress": "buzz.Lightyear@email.com", "ContactPersons": [], "BankAccountDetails": "", "Addresses": [ { "AddressType": "STREET", "City": "", "Region": "", "PostalCode": "", "Country": "" }, { "AddressType": "POBOX", "AddressLine1": "", "AddressLine2": "", "AddressLine3": "", "AddressLine4": "", "City": "Palo Alto", "Region": "CA", "PostalCode": "94020", "Country": "United States" } ], "Phones": [ { "PhoneType": "DEFAULT", "PhoneNumber": "847-1294", "PhoneAreaCode": "(626)", "PhoneCountryCode": "" }, { "PhoneType": "DDI", "PhoneNumber": "", "PhoneAreaCode": "", "PhoneCountryCode": "" }, { "PhoneType": "FAX", "PhoneNumber": "", "PhoneAreaCode": "", "PhoneCountryCode": "" }, { "PhoneType": "MOBILE", "PhoneNumber": "", "PhoneAreaCode": "", "PhoneCountryCode": "" } ], "UpdatedDateUTC": "2017-08-21T13:49:04.227-07:00", "ContactGroups": [] }, "Lineitems": [], "BankAccount": { "Code": "088", "Name": "Business Wells Fargo", "AccountID": "00000000-0000-0000-0000-000000000000" }, "IsReconciled": false, "Date": "2019-02-25", "Reference": "You just updated", "CurrencyCode": "USD", "CurrencyRate": 1, "Status": "AUTHORISED", "LineAmountTypes": "Inclusive", "TotalTax": 1.74, "BankTransactionID": "00000000-0000-0000-0000-000000000000", "UpdatedDateUTC": "2019-02-26T12:39:27.813-08:00" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\BankTransactions | 
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateBankTransaction($xero_tenant_id, $bank_transaction_id, $bank_transactions, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateBankTransaction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |
 **bank_transactions** | [**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)|  |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateBankTransactionAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateBankTransactionAttachmentByFileName($xero_tenant_id, $bank_transaction_id, $file_name, $body)

Updates a specific attachment from a specific bank transaction by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transaction
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateBankTransactionAttachmentByFileName($xero_tenant_id, $bank_transaction_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateBankTransactionAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transaction_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transaction |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateBankTransferAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateBankTransferAttachmentByFileName($xero_tenant_id, $bank_transfer_id, $file_name, $body)



### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transfer_id = 00000000-0000-0000-0000-000000000000; // string | Xero generated unique identifier for a bank transfer
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateBankTransferAttachmentByFileName($xero_tenant_id, $bank_transfer_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateBankTransferAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transfer_id** | [**string**](../Model/.md)| Xero generated unique identifier for a bank transfer |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateContact**
> \XeroAPI\XeroPHP\Models\Accounting\Contacts updateContact($xero_tenant_id, $contact_id, $contacts)

Updates a specific contact in a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$contacts = { "Contacts": [{ "ContactID": "00000000-0000-0000-0000-000000000000", "Name": "Thanos" }]}; // \XeroAPI\XeroPHP\Models\Accounting\Contacts | an array of Contacts containing single Contact object with properties to update

try {
    $result = $apiInstance->updateContact($xero_tenant_id, $contact_id, $contacts);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateContact: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **contacts** | [**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)| an array of Contacts containing single Contact object with properties to update |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateContactAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateContactAttachmentByFileName($xero_tenant_id, $contact_id, $file_name, $body)



### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateContactAttachmentByFileName($xero_tenant_id, $contact_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateContactAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_id** | [**string**](../Model/.md)| Unique identifier for a Contact |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateContactGroup**
> \XeroAPI\XeroPHP\Models\Accounting\ContactGroups updateContactGroup($xero_tenant_id, $contact_group_id, $contact_groups)

Updates a specific contact group

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contact_group_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Contact Group
$contact_groups = { "ContactGroups":[ { "Name":"Suppliers" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\ContactGroups | an array of Contact groups with Name of specific group to update

try {
    $result = $apiInstance->updateContactGroup($xero_tenant_id, $contact_group_id, $contact_groups);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateContactGroup: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contact_group_id** | [**string**](../Model/.md)| Unique identifier for a Contact Group |
 **contact_groups** | [**\XeroAPI\XeroPHP\Models\Accounting\ContactGroups**](../Model/ContactGroups.md)| an array of Contact groups with Name of specific group to update |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ContactGroups**](../Model/ContactGroups.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateCreditNote**
> \XeroAPI\XeroPHP\Models\Accounting\CreditNotes updateCreditNote($xero_tenant_id, $credit_note_id, $credit_notes, $unitdp)

Updates a specific credit note

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$credit_notes = { "CreditNotes": [ { "Type": "ACCPAYCREDIT", "Contact": { "ContactID": "430fa14a-f945-44d3-9f97-5df5e28441b8" }, "Date": "2019-01-05", "Status": "AUTHORISED", "Reference": "HelloWorld", "LineItems": [ { "Description": "Foobar", "Quantity": 2, "UnitAmount": 20, "AccountCode": "400" } ] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\CreditNotes | an array of Credit Notes containing credit note details to update
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateCreditNote($xero_tenant_id, $credit_note_id, $credit_notes, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateCreditNote: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **credit_notes** | [**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)| an array of Credit Notes containing credit note details to update |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateCreditNoteAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateCreditNoteAttachmentByFileName($xero_tenant_id, $credit_note_id, $file_name, $body)

Updates attachments on a specific credit note by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_note_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Credit Note
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateCreditNoteAttachmentByFileName($xero_tenant_id, $credit_note_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateCreditNoteAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_note_id** | [**string**](../Model/.md)| Unique identifier for a Credit Note |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateExpenseClaim**
> \XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims updateExpenseClaim($xero_tenant_id, $expense_claim_id, $expense_claims)

Updates a specific expense claims

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$expense_claim_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ExpenseClaim
$expense_claims = { "ExpenseClaims": [ { "Status": "SUBMITTED", "User": { "UserID": "d1164823-0ac1-41ad-987b-b4e30fe0b273" }, "Receipts": [ { "Lineitems": [], "ReceiptID": "dc1c7f6d-0a4c-402f-acac-551d62ce5816" } ] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims | 

try {
    $result = $apiInstance->updateExpenseClaim($xero_tenant_id, $expense_claim_id, $expense_claims);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateExpenseClaim: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **expense_claim_id** | [**string**](../Model/.md)| Unique identifier for a ExpenseClaim |
 **expense_claims** | [**\XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims**](../Model/ExpenseClaims.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ExpenseClaims**](../Model/ExpenseClaims.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateInvoice**
> \XeroAPI\XeroPHP\Models\Accounting\Invoices updateInvoice($xero_tenant_id, $invoice_id, $invoices, $unitdp)

Updates a specific sales invoices or purchase bills

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$invoices = { "Invoices": [{ Reference: "May the force be with you", "InvoiceID": "00000000-0000-0000-0000-000000000000", "LineItems": [], "Contact": {}, "Type": "ACCPAY" }]}; // \XeroAPI\XeroPHP\Models\Accounting\Invoices | 
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateInvoice($xero_tenant_id, $invoice_id, $invoices, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateInvoice: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **invoices** | [**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)|  |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateInvoiceAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateInvoiceAttachmentByFileName($xero_tenant_id, $invoice_id, $file_name, $body)

Updates an attachment from a specific invoices or purchase bill by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Invoice
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateInvoiceAttachmentByFileName($xero_tenant_id, $invoice_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateInvoiceAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoice_id** | [**string**](../Model/.md)| Unique identifier for an Invoice |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateItem**
> \XeroAPI\XeroPHP\Models\Accounting\Items updateItem($xero_tenant_id, $item_id, $items, $unitdp)

Updates a specific item

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$item_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Item
$items = { "Items": [ { "Code": "ItemCode123", "Description": "Description 123" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Items | 
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateItem($xero_tenant_id, $item_id, $items, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateItem: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **item_id** | [**string**](../Model/.md)| Unique identifier for an Item |
 **items** | [**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)|  |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateLinkedTransaction**
> \XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions updateLinkedTransaction($xero_tenant_id, $linked_transaction_id, $linked_transactions)

Updates a specific linked transactions (billable expenses)

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$linked_transaction_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a LinkedTransaction
$linked_transactions = { "LinkedTransactions": [ { "SourceTransactionID": "00000000-0000-0000-0000-000000000000", "SourceLineItemID": "00000000-0000-0000-0000-000000000000" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions | 

try {
    $result = $apiInstance->updateLinkedTransaction($xero_tenant_id, $linked_transaction_id, $linked_transactions);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateLinkedTransaction: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **linked_transaction_id** | [**string**](../Model/.md)| Unique identifier for a LinkedTransaction |
 **linked_transactions** | [**\XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions**](../Model/LinkedTransactions.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\LinkedTransactions**](../Model/LinkedTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateManualJournal**
> \XeroAPI\XeroPHP\Models\Accounting\ManualJournals updateManualJournal($xero_tenant_id, $manual_journal_id, $manual_journals)

Updates a specific manual journal

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal
$manual_journals = { "ManualJournals": [ { "Narration": "Hello Xero", "ManualJournalID": "00000000-0000-0000-0000-000000000000", "JournalLines": [] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\ManualJournals | 

try {
    $result = $apiInstance->updateManualJournal($xero_tenant_id, $manual_journal_id, $manual_journals);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateManualJournal: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |
 **manual_journals** | [**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateManualJournalAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateManualJournalAttachmentByFileName($xero_tenant_id, $manual_journal_id, $file_name, $body)

Updates a specific attachment from a specific manual journal by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journal_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a ManualJournal
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateManualJournalAttachmentByFileName($xero_tenant_id, $manual_journal_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateManualJournalAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journal_id** | [**string**](../Model/.md)| Unique identifier for a ManualJournal |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateBankTransactions**
> \XeroAPI\XeroPHP\Models\Accounting\BankTransactions updateOrCreateBankTransactions($xero_tenant_id, $bank_transactions, $summarize_errors, $unitdp)

Updates or creates one or more spent or received money transaction

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$bank_transactions = { "BankTransactions": [ { "Type": "SPEND", "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000" }, "Lineitems": [ { "Description": "Foobar", "Quantity": 1, "UnitAmount": 20, "AccountCode": "400" } ], "BankAccount": { "Code": "088" } } ] }; // \XeroAPI\XeroPHP\Models\Accounting\BankTransactions | 
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateOrCreateBankTransactions($xero_tenant_id, $bank_transactions, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateBankTransactions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **bank_transactions** | [**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)|  |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\BankTransactions**](../Model/BankTransactions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateContacts**
> \XeroAPI\XeroPHP\Models\Accounting\Contacts updateOrCreateContacts($xero_tenant_id, $contacts, $summarize_errors)

Updates or creates one or more contacts in a Xero organisation

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$contacts = { "Contacts": [ { "Name": "Bruce Banner", "EmailAddress": "hulk@avengers.com", "Phones": [ { "PhoneType": "MOBILE", "PhoneNumber": "555-1212", "PhoneAreaCode": "415" } ], "PaymentTerms": { "Bills": { "Day": 15, "Type": "OFCURRENTMONTH" }, "Sales": { "Day": 10, "Type": "DAYSAFTERBILLMONTH" } } } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Contacts | 
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->updateOrCreateContacts($xero_tenant_id, $contacts, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateContacts: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **contacts** | [**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)|  |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Contacts**](../Model/Contacts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateCreditNotes**
> \XeroAPI\XeroPHP\Models\Accounting\CreditNotes updateOrCreateCreditNotes($xero_tenant_id, $credit_notes, $summarize_errors, $unitdp)

Updates or creates one or more credit notes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$credit_notes = { "CreditNotes":[ { "Type":"ACCPAYCREDIT", "Contact":{ "ContactID":"430fa14a-f945-44d3-9f97-5df5e28441b8" }, "Date":"2019-01-05", "Status":"AUTHORISED", "Reference": "HelloWorld", "LineItems":[ { "Description":"Foobar", "Quantity":2.0, "UnitAmount":20.0, "AccountCode":"400" } ] } ] }; // \XeroAPI\XeroPHP\Models\Accounting\CreditNotes | an array of Credit Notes with a single CreditNote object.
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateOrCreateCreditNotes($xero_tenant_id, $credit_notes, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateCreditNotes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **credit_notes** | [**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)| an array of Credit Notes with a single CreditNote object. |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\CreditNotes**](../Model/CreditNotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateEmployees**
> \XeroAPI\XeroPHP\Models\Accounting\Employees updateOrCreateEmployees($xero_tenant_id, $employees, $summarize_errors)

Creates a single new employees used in Xero payrun

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$employees = { "Employees": [ { "FirstName": "Nick", "LastName": "Fury", "ExternalLink": { "Url": "http://twitter.com/#!/search/Nick+Fury" } } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Employees | Employees with array of Employee object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->updateOrCreateEmployees($xero_tenant_id, $employees, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateEmployees: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **employees** | [**\XeroAPI\XeroPHP\Models\Accounting\Employees**](../Model/Employees.md)| Employees with array of Employee object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Employees**](../Model/Employees.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateInvoices**
> \XeroAPI\XeroPHP\Models\Accounting\Invoices updateOrCreateInvoices($xero_tenant_id, $invoices, $summarize_errors, $unitdp)

Updates or creates one or more sales invoices or purchase bills

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$invoices = { "Invoices": [ { "Type": "ACCREC", "Contact": { "ContactID": "430fa14a-f945-44d3-9f97-5df5e28441b8" }, "LineItems": [ { "Description": "Acme Tires", "Quantity": 2, "UnitAmount": 20, "AccountCode": "200", "TaxType": "NONE", "LineAmount": 40 } ], "Date": "2019-03-11", "DueDate": "2018-12-10", "Reference": "Website Design", "Status": "AUTHORISED" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Invoices | 
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateOrCreateInvoices($xero_tenant_id, $invoices, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateInvoices: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **invoices** | [**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)|  |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Invoices**](../Model/Invoices.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateItems**
> \XeroAPI\XeroPHP\Models\Accounting\Items updateOrCreateItems($xero_tenant_id, $items, $summarize_errors, $unitdp)

Updates or creates one or more items

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$items = { "Items": [ { "Code": "ItemCode123", "Name": "ItemName XYZ", "Description": "Item Description ABC" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Items | 
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateOrCreateItems($xero_tenant_id, $items, $summarize_errors, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateItems: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **items** | [**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)|  |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Items**](../Model/Items.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateManualJournals**
> \XeroAPI\XeroPHP\Models\Accounting\ManualJournals updateOrCreateManualJournals($xero_tenant_id, $manual_journals, $summarize_errors)

Updates or creates a single manual journal

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$manual_journals = { "ManualJournals": [ { "Narration": "Journal Desc", "JournalLines": [ { "LineAmount": 100, "AccountCode": "400", "Description": "Money Movement" }, { "LineAmount": -100, "AccountCode": "400", "Description": "Prepayment of things", "Tracking": [ { "Name": "North", "Option": "Region" } ] } ], "Date": "2019-03-14" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\ManualJournals | ManualJournals array with ManualJournal object in body of request
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->updateOrCreateManualJournals($xero_tenant_id, $manual_journals, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateManualJournals: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **manual_journals** | [**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)| ManualJournals array with ManualJournal object in body of request |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\ManualJournals**](../Model/ManualJournals.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreatePurchaseOrders**
> \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders updateOrCreatePurchaseOrders($xero_tenant_id, $purchase_orders, $summarize_errors)

Updates or creates one or more purchase orders

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_orders = { "PurchaseOrders": [ { "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000" }, "LineItems": [ { "Description": "Foobar", "Quantity": 1, "UnitAmount": 20, "AccountCode": "710" } ], "Date": "2019-03-13" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders | 
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->updateOrCreatePurchaseOrders($xero_tenant_id, $purchase_orders, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreatePurchaseOrders: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_orders** | [**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)|  |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateOrCreateQuotes**
> \XeroAPI\XeroPHP\Models\Accounting\Quotes updateOrCreateQuotes($xero_tenant_id, $quotes, $summarize_errors)

Updates or creates one or more quotes

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quotes = { "Quotes": [ { "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000" }, "LineItems": [ { "Description": "Foobar", "Quantity": 1, "UnitAmount": 20, "AccountCode": "12775" } ], "Date": "2020-02-01" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Quotes | 
$summarize_errors = true; // bool | If false return 200 OK and mix of successfully created objects and any with validation errors

try {
    $result = $apiInstance->updateOrCreateQuotes($xero_tenant_id, $quotes, $summarize_errors);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateOrCreateQuotes: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quotes** | [**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)|  |
 **summarize_errors** | **bool**| If false return 200 OK and mix of successfully created objects and any with validation errors | [optional] [default to false]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updatePurchaseOrder**
> \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders updatePurchaseOrder($xero_tenant_id, $purchase_order_id, $purchase_orders)

Updates a specific purchase order

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order
$purchase_orders = { "PurchaseOrders": [ { "AttentionTo": "Peter Parker", "LineItems": [], "Contact": {} } ] }; // \XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders | 

try {
    $result = $apiInstance->updatePurchaseOrder($xero_tenant_id, $purchase_order_id, $purchase_orders);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updatePurchaseOrder: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |
 **purchase_orders** | [**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\PurchaseOrders**](../Model/PurchaseOrders.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updatePurchaseOrderAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updatePurchaseOrderAttachmentByFileName($xero_tenant_id, $purchase_order_id, $file_name, $body)

Updates a specific attachment for a specific purchase order by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$purchase_order_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Purchase Order
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updatePurchaseOrderAttachmentByFileName($xero_tenant_id, $purchase_order_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updatePurchaseOrderAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **purchase_order_id** | [**string**](../Model/.md)| Unique identifier for an Purchase Order |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateQuote**
> \XeroAPI\XeroPHP\Models\Accounting\Quotes updateQuote($xero_tenant_id, $quote_id, $quotes)

Updates a specific quote

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote
$quotes = { "Quotes": [ { "Reference": "I am an update", "Contact": { "ContactID": "00000000-0000-0000-0000-000000000000" }, "Date": "2020-02-01" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Quotes | 

try {
    $result = $apiInstance->updateQuote($xero_tenant_id, $quote_id, $quotes);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateQuote: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |
 **quotes** | [**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Quotes**](../Model/Quotes.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateQuoteAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateQuoteAttachmentByFileName($xero_tenant_id, $quote_id, $file_name, $body)

Updates a specific attachment from a specific quote by filename

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$quote_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for an Quote
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateQuoteAttachmentByFileName($xero_tenant_id, $quote_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateQuoteAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **quote_id** | [**string**](../Model/.md)| Unique identifier for an Quote |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateReceipt**
> \XeroAPI\XeroPHP\Models\Accounting\Receipts updateReceipt($xero_tenant_id, $receipt_id, $receipts, $unitdp)

Updates a specific draft expense claim receipts

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt
$receipts = { "Receipts": [ { "Lineitems": [], "User": { "UserID": "00000000-0000-0000-0000-000000000000" }, "Reference": "Foobar" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\Receipts | 
$unitdp = 4; // int | e.g. unitdp=4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts

try {
    $result = $apiInstance->updateReceipt($xero_tenant_id, $receipt_id, $receipts, $unitdp);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateReceipt: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |
 **receipts** | [**\XeroAPI\XeroPHP\Models\Accounting\Receipts**](../Model/Receipts.md)|  |
 **unitdp** | **int**| e.g. unitdp&#x3D;4 – (Unit Decimal Places) You can opt in to use four decimal places for unit amounts | [optional]

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Receipts**](../Model/Receipts.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateReceiptAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateReceiptAttachmentByFileName($xero_tenant_id, $receipt_id, $file_name, $body)

Updates a specific attachment on a specific expense claim receipts by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$receipt_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Receipt
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateReceiptAttachmentByFileName($xero_tenant_id, $receipt_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateReceiptAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **receipt_id** | [**string**](../Model/.md)| Unique identifier for a Receipt |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateRepeatingInvoiceAttachmentByFileName**
> \XeroAPI\XeroPHP\Models\Accounting\Attachments updateRepeatingInvoiceAttachmentByFileName($xero_tenant_id, $repeating_invoice_id, $file_name, $body)

Updates a specific attachment from a specific repeating invoices by file name

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$repeating_invoice_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Repeating Invoice
$file_name = xero-dev.jpg; // string | Name of the attachment
$body = 'body_example'; // string | Byte array of file in body of request

try {
    $result = $apiInstance->updateRepeatingInvoiceAttachmentByFileName($xero_tenant_id, $repeating_invoice_id, $file_name, $body);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateRepeatingInvoiceAttachmentByFileName: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **repeating_invoice_id** | [**string**](../Model/.md)| Unique identifier for a Repeating Invoice |
 **file_name** | **string**| Name of the attachment |
 **body** | **string**| Byte array of file in body of request |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\Attachments**](../Model/Attachments.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/octet-stream
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateTaxRate**
> \XeroAPI\XeroPHP\Models\Accounting\TaxRates updateTaxRate($xero_tenant_id, $tax_rates)

Updates tax rates

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tax_rates = { "TaxRates": [ { "Name": "State Tax NY", "TaxComponents": [ { "Name": "State Tax", "Rate": 2.25 } ], "Status": "DELETED", "ReportTaxType": "INPUT" } ] }; // \XeroAPI\XeroPHP\Models\Accounting\TaxRates | 

try {
    $result = $apiInstance->updateTaxRate($xero_tenant_id, $tax_rates);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateTaxRate: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tax_rates** | [**\XeroAPI\XeroPHP\Models\Accounting\TaxRates**](../Model/TaxRates.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TaxRates**](../Model/TaxRates.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateTrackingCategory**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingCategories updateTrackingCategory($xero_tenant_id, $tracking_category_id, $tracking_category)

Updates a specific tracking category

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tracking_category_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a TrackingCategory
$tracking_category = { "Name": "Avengers" }; // \XeroAPI\XeroPHP\Models\Accounting\TrackingCategory | 

try {
    $result = $apiInstance->updateTrackingCategory($xero_tenant_id, $tracking_category_id, $tracking_category);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateTrackingCategory: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tracking_category_id** | [**string**](../Model/.md)| Unique identifier for a TrackingCategory |
 **tracking_category** | [**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategory**](../Model/TrackingCategory.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingCategories**](../Model/TrackingCategories.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

# **updateTrackingOptions**
> \XeroAPI\XeroPHP\Models\Accounting\TrackingOptions updateTrackingOptions($xero_tenant_id, $tracking_category_id, $tracking_option_id, $tracking_option)

Updates a specific option for a specific tracking category

### Example
```php
<?php
require_once(__DIR__ . '/vendor/autoload.php');

// Configure OAuth2 access token for authorization: OAuth2
$config = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken('YOUR_ACCESS_TOKEN');

$apiInstance = new XeroAPI\XeroPHP\Api\AccountingApi(
    // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
    // This is optional, `GuzzleHttp\Client` will be used as default.
    new GuzzleHttp\Client(),
    $config
);
$xero_tenant_id = YOUR_XERO_TENANT_ID; // string | Xero identifier for Tenant
$tracking_category_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a TrackingCategory
$tracking_option_id = 00000000-0000-0000-0000-000000000000; // string | Unique identifier for a Tracking Option
$tracking_option = { name: "Vision" }; // \XeroAPI\XeroPHP\Models\Accounting\TrackingOption | 

try {
    $result = $apiInstance->updateTrackingOptions($xero_tenant_id, $tracking_category_id, $tracking_option_id, $tracking_option);
    print_r($result);
} catch (Exception $e) {
    echo 'Exception when calling AccountingApi->updateTrackingOptions: ', $e->getMessage(), PHP_EOL;
}
?>
```

### Parameters

Name | Type | Description  | Notes
------------- | ------------- | ------------- | -------------
 **xero_tenant_id** | **string**| Xero identifier for Tenant |
 **tracking_category_id** | [**string**](../Model/.md)| Unique identifier for a TrackingCategory |
 **tracking_option_id** | [**string**](../Model/.md)| Unique identifier for a Tracking Option |
 **tracking_option** | [**\XeroAPI\XeroPHP\Models\Accounting\TrackingOption**](../Model/TrackingOption.md)|  |

### Return type

[**\XeroAPI\XeroPHP\Models\Accounting\TrackingOptions**](../Model/TrackingOptions.md)

### Authorization

[OAuth2](../../README.md#OAuth2)

### HTTP request headers

 - **Content-Type**: application/json
 - **Accept**: application/json

[[Back to top]](#) [[Back to API list]](../../README.md#documentation-for-api-endpoints) [[Back to Model list]](../../README.md#documentation-for-models) [[Back to README]](../../README.md)

