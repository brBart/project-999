/*
 * search_product_model.h
 *
 *  Created on: 18/08/2010
 *      Author: pc
 */

#ifndef SEARCH_PRODUCT_MODEL_H_
#define SEARCH_PRODUCT_MODEL_H_

#include <QStandardItemModel>
#include <QStringList>

class SearchProductModel : public QStandardItemModel
{
	Q_OBJECT

public:
	SearchProductModel(QObject *parent = 0);
	virtual ~SearchProductModel() {};
	QStringList* keywords();

private:
	QStringList m_Keywords;
};

#endif /* SEARCH_PRODUCT_MODEL_H_ */
