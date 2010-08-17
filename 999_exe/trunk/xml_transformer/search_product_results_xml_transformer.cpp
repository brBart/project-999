/*
 * search_product_results_xml_transformer.cpp
 *
 *  Created on: 16/08/2010
 *      Author: pc
 */

#include "search_product_results_xml_transformer.h"

/**
 * @InvoiceListXmlTransformer
 * Transforms an xml document into a invoice list.
 */

/**
 * Stores the invoice list into the QList for future retrieval.
 */
void SearchProductResultsXmlTransformer::transform(QDomDocument *document)
{
	QDomNodeList keywords = document->elementsByTagName("keyword");
	QDomNodeList barCodes = document->elementsByTagName("bar_code");
	QDomNodeList names = document->elementsByTagName("name");
	QDomNodeList packagings = document->elementsByTagName("packaging");

	QMap<QString, QString> *map = new QMap<QString, QString>();
	map->insert("keyword", keywords.at(0).toElement().text());
	m_Content << map;

	for (int i = 1; i < barCodes.size(); i++) {
		map->insert("bar_code", barCodes.at(i).toElement().text());
		map->insert("name", names.at(i).toElement().text());
		map->insert("packaging", packagings.at(i).toElement().text());
		m_Content << map;
	}
}
