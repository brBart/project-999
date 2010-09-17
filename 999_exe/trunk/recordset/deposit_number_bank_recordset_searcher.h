/*
 * deposit_number_bank_recordset_searcher.h
 *
 *  Created on: 17/09/2010
 *      Author: pc
 */

#ifndef DEPOSIT_NUMBER_BANK_RECORDSET_SEARCHER_H_
#define DEPOSIT_NUMBER_BANK_RECORDSET_SEARCHER_H_

#include "recordset_searcher.h"

class DepositNumberBankRecordsetSearcher: public RecordsetSearcher
{
public:
	DepositNumberBankRecordsetSearcher() {}
	virtual ~DepositNumberBankRecordsetSearcher() {}
	bool search(QString value, QList<QMap<QString, QString>*> *list);
};

#endif /* DEPOSIT_NUMBER_BANK_RECORDSET_SEARCHER_H_ */
