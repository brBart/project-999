/*
 * invoice_list_xml_transformer.h
 *
 *  Created on: 11/05/2010
 *      Author: pc
 */

#ifndef INVOICE_LIST_XML_TRANSFORMER_H_
#define INVOICE_LIST_XML_TRANSFORMER_H_

#include "xml_transformer.h"

#include <QMap>

class InvoiceListXmlTransformer: public XmlTransformer {
public:
	InvoiceListXmlTransformer() {};
	virtual ~InvoiceListXmlTransformer() {};
	virtual bool transform(QDomDocument *document, QString *errorMsg = 0);
	QList<QMap<QString, QString>*> list();

private:
	QList<QMap<QString, QString>*> m_List;
};

#endif /* INVOICE_LIST_XML_TRANSFORMER_H_ */
