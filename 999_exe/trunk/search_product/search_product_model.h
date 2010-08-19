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
	virtual ~SearchProductModel() {};
	QStringList* keywords();
	static SearchProductModel* instance();

private:
	QStringList m_Keywords;
	static SearchProductModel *m_Instance;

	SearchProductModel(QObject *parent = 0);
};

#endif /* SEARCH_PRODUCT_MODEL_H_ */
