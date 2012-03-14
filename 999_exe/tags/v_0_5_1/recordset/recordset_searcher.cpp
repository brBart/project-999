/*
 * recordset_searcher.cpp
 *
 *  Created on: 20/08/2010
 *      Author: pc
 */

#include "recordset_searcher.h"

/**
 * @class RecordsetSearcher
 * Abstract class that defines common functionality for other searchers.
 */

/**
 * Returns the iterator used for the search.
 */
QList<QMap<QString, QString>*>::const_iterator RecordsetSearcher::newIterator()
{
	return m_Iterator;
}

/**
 * Returns the new index position of the value within the list.
 */
int RecordsetSearcher::newIndex()
{
	return m_Index;
}
