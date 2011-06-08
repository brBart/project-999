/*
 * xml_transformer_factory.cpp
 *
 *  Created on: 28/05/2010
 *      Author: pc
 */

#include "xml_transformer_factory.h"

#include <QApplication>
#include "shift_list_xml_transformer.h"
#include "object_key_xml_transformer.h"
#include "invoice_list_xml_transformer.h"
#include "invoice_xml_transformer.h"
#include "cash_register_status_xml_transformer.h"
#include "stub_xml_transformer.h"
#include "customer_xml_transformer.h"
#include "invoice_customer_xml_transformer.h"
#include "change_xml_transformer.h"
#include "payment_card_type_list_xml_transformer.h"
#include "payment_card_brand_list_xml_transformer.h"
#include "total_xml_transformer.h"
#include "object_id_xml_transformer.h"
#include "search_product_results_xml_transformer.h"
#include "deposit_xml_transformer.h"
#include "bank_xml_transformer.h"
#include "available_cash_receipt_list_xml_transformer.h"
#include "bank_list_xml_transformer.h"
#include "deposit_list_xml_transformer.h"
#include "correlative_warning_xml_transformer.h"

/**
 * @class XmlTransformerFactory
 * Class responsible for creating all the transformers.
 */

XmlTransformerFactory* XmlTransformerFactory::m_Instance = 0;

/**
 * Constructs factory with a parent.
 */
XmlTransformerFactory::XmlTransformerFactory(QObject *parent) : QObject(parent)
{

}

/**
 * Returns the only instance.
 */
XmlTransformerFactory* XmlTransformerFactory::instance()
{
	if (m_Instance == 0)
		m_Instance = new XmlTransformerFactory(qApp);

	return m_Instance;
}

/**
 * Creates and returns the corresponding XmlTransformer object.
 */
XmlTransformer* XmlTransformerFactory::create(QString name)
{
	if (name == "shift_list") {
		return new ShiftListXmlTransformer();
	} else if (name == "object_key") {
		return new ObjectKeyXmlTransformer();
	} else if (name == "invoice_list") {
		return new InvoiceListXmlTransformer();
	} else if (name == "invoice") {
		return new InvoiceXmlTransformer();
	} else if (name == "cash_register_status") {
		return new CashRegisterStatusXmlTransformer();
	} else if (name == "stub") {
		return new StubXmlTransformer();
	} else if (name == "customer") {
		return new CustomerXmlTransformer();
	} else if (name == "invoice_customer") {
		return new InvoiceCustomerXmlTransformer();
	} else if (name == "change") {
		return new ChangeXmlTransformer();
	} else if (name == "payment_card_type_list") {
		return new PaymentCardTypeListXmlTransformer();
	} else if (name == "payment_card_brand_list") {
		return new PaymentCardBrandListXmlTransformer();
	} else if (name == "total") {
		return new TotalXmlTransformer();
	} else if (name == "object_id") {
		return new ObjectIdXmlTransformer();
	} else if (name == "search_product_results") {
		return new SearchProductResultsXmlTransformer();
	} else if (name == "deposit") {
		return new DepositXmlTransformer();
	} else if (name == "bank") {
		return new BankXmlTransformer();
	} else if (name == "available_cash_receipt_list") {
		return new AvailableCashReceiptListXmlTransformer();
	} else if (name == "bank_list") {
		return new BankListXmlTransformer();
	} else if (name == "deposit_list") {
		return new DepositListXmlTransformer();
	} else if (name == "correlative_warning") {
			return new CorrelativeWarningXmlTransformer();
	} else {
		return 0;
	}
}
