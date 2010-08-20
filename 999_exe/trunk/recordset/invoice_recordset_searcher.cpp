/*
 * invoice_recordset_searcher.cpp
 *
 *  Created on: 20/08/2010
 *      Author: pc
 */

#include "invoice_recordset_searcher.h"

#include <QStringList>

/**
 * @class InvoiceRecordsetSearcher
 * Search in the internal list of the recordset for the value provided.
 */

/**
 * Searches the value within the recordset's list. Returns true if it was found.
 */
bool InvoiceRecordsetSearcher::search(QString value,
		QList<QMap<QString, QString>*> *list)
{
	QStringList values = value.split(" ");
	QString serialNumber = values[0].toUpper();
	QString number = values[1].toUpper();

	int index = 0;
	QList<QMap<QString, QString>*>::const_iterator i;
	for (i = list->begin(); i != list->end(); ++i) {

		if ((*i)->value("serial_number").toUpper() == serialNumber
				&& (*i)->value("number").toUpper() == number) {
			m_Iterator = i;
			m_Index = index;
			return true;
		}

		index++;
	}

	return false;
}
