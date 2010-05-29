/*
 * xml_transformer_factory.cpp
 *
 *  Created on: 28/05/2010
 *      Author: pc
 */

#include "xml_transformer_factory.h"

#include "shift_list_xml_transformer.h"
#include "object_key_xml_transformer.h"
#include "invoice_list_xml_transformer.h"
#include "invoice_xml_transformer.h"
#include "cash_register_status_xml_transformer.h"
#include "stub_xml_transformer.h"
#include "customer_xml_transformer.h"
#include "invoice_customer_xml_transformer.h"

XmlTransformerFactory* XmlTransformerFactory::m_Instance = 0;

/**
 * Returns the only instance.
 */
XmlTransformerFactory* XmlTransformerFactory::instance()
{
	if (m_Instance == 0)
		m_Instance = new XmlTransformerFactory();

	return m_Instance;
}

XmlTransformer* XmlTransformerFactory::createXmlTransformer(QString name)
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
	} else {
		return NULL;
	}
}
