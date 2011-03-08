/*
 * search_product_model.cpp
 *
 *  Created on: 18/08/2010
 *      Author: pc
 */

#include "search_product_model.h"

#include <QApplication>

/**
 * @class SearchProductModel
 * Extends the QStandardItemModel a sets the column count property to 3.
 */

/**
 * Constructs the model.
 */
SearchProductModel::SearchProductModel(QObject *parent) : QStandardItemModel(parent)
{
	setColumnCount(3);
}

/**
 * Returns the list of keywords that have been searched.
 */
QStringList* SearchProductModel::keywords()
{
	return &m_Keywords;
}
