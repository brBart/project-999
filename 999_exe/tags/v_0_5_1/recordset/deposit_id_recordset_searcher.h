/*
 * deposit_id_recordset_searcher.h
 *
 *  Created on: 17/09/2010
 *      Author: pc
 */

#ifndef DEPOSIT_ID_RECORDSET_SEARCHER_H_
#define DEPOSIT_ID_RECORDSET_SEARCHER_H_

#include "recordset_searcher.h"

class DepositIdRecordsetSearcher: public RecordsetSearcher
{
public:
	DepositIdRecordsetSearcher() {}
	virtual ~DepositIdRecordsetSearcher() {}
	bool search(QString value, QList<QMap<QString, QString>*> *list);
};

#endif /* DEPOSIT_ID_RECORDSET_SEARCHER_H_ */
