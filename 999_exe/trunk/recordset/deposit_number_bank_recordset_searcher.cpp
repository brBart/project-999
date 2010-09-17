/*
 * deposit_number_bank_recordset_searcher.cpp
 *
 *  Created on: 17/09/2010
 *      Author: pc
 */

#include "deposit_number_bank_recordset_searcher.h"

#include <QStringList>

/**
 * @class DepositNumberBankRecordsetSearcher
 * Search in the internal list of the recordset for the value provided.
 */

/**
 * Searches the value within the recordset's list. Returns true if it was found.
 */
bool DepositNumberBankRecordsetSearcher::search(QString value,
		QList<QMap<QString, QString>*> *list)
{
	QStringList values = value.split(" ");
	QString bankId = values[0];
	QString slipNumber = values[1].toUpper();

	int index = 0;
	QList<QMap<QString, QString>*>::const_iterator i;
	for (i = list->begin(); i != list->end(); ++i) {

		if ((*i)->value("bank_id") == bankId
				&& (*i)->value("number").toUpper() == slipNumber
				&& (*i)->value("status") == "1") {
			m_Iterator = i;
			m_Index = index;
			return true;
		}

		index++;
	}

	return false;
}
