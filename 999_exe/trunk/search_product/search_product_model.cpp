/*
 * search_product_model.cpp
 *
 *  Created on: 18/08/2010
 *      Author: pc
 */

#include "search_product_model.h"

/**
 * @class SearchProductModel
 * Extends the QStandardItemModel a sets the column count property to 3. Also made
 * a singleton for avoiding replication of cached data.
 */

SearchProductModel* SearchProductModel::m_Instance = 0;

/**
 * Constructs the model.
 */
SearchProductModel::SearchProductModel(QObject *parent) : QStandardItemModel(parent)
{
	setColumnCount(3);
}

/**
 * Returns the only instance.
 */
SearchProductModel* SearchProductModel::instance()
{
	if (m_Instance == 0)
		m_Instance = new SearchProductModel();

	return m_Instance;
}

/**
 * Returns the list of keywords that have been searched.
 */
QStringList* SearchProductModel::keywords()
{
	return &m_Keywords;
}
