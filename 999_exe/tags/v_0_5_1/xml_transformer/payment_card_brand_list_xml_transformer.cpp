/*
 * payment_card_brand_list_xml_transformer.cpp
 *
 *  Created on: 14/07/2010
 *      Author: pc
 */

#include "payment_card_brand_list_xml_transformer.h"

/**
 * @class PaymentCardBrandListXmlTransformer
 * Transforms an xml document into a list of payment card brands.
 */

/**
 * Stores the list into the QList for future retrieval.
 */
void PaymentCardBrandListXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList ids = document->elementsByTagName("payment_card_brand_id");
	QDomNodeList names = document->elementsByTagName("name");

	for (int i = 0; i < ids.size(); i++) {
		QMap<QString, QString> *map = new QMap<QString, QString>();
		map->insert("payment_card_brand_id", ids.at(i).toElement().text());
		map->insert("name", names.at(i).toElement().text());
		m_Content << map;
	}
}
