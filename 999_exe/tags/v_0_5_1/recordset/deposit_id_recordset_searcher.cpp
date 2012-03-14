/*
 * deposit_id_recordset_searcher.cpp
 *
 *  Created on: 17/09/2010
 *      Author: pc
 */

#include "deposit_id_recordset_searcher.h"

/**
 * @class DepositIdRecordsetSearcher
 * Search in the internal list of the recordset for the value provided.
 */

/**
 * Searches the value within the recordset's list. Returns true if it was found.
 */
bool DepositIdRecordsetSearcher::search(QString value,
		QList<QMap<QString, QString>*> *list)
{
	int index = 0;
	QList<QMap<QString, QString>*>::const_iterator i;
	for (i = list->begin(); i != list->end(); ++i) {

		if ((*i)->value("id") == value) {
			m_Iterator = i;
			m_Index = index;
			return true;
		}

		index++;
	}

	return false;
}
