/*
 * search_product_results_xml_transformer.h
 *
 *  Created on: 16/08/2010
 *      Author: pc
 */

#ifndef SEARCH_PRODUCT_RESULTS_XML_TRANSFORMER_H_
#define SEARCH_PRODUCT_RESULTS_XML_TRANSFORMER_H_

#include "xml_transformer.h"

class SearchProductResultsXmlTransformer: public XmlTransformer
{
public:
	SearchProductResultsXmlTransformer() {};
	virtual ~SearchProductResultsXmlTransformer() {};
	virtual void transform(QDomDocument *document);
};

#endif /* SEARCH_PRODUCT_RESULTS_XML_TRANSFORMER_H_ */
