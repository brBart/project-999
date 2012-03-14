/*
 * invoice_recordset_searcher.h
 *
 *  Created on: 20/08/2010
 *      Author: pc
 */

#ifndef INVOICE_RECORDSET_SEARCHER_H_
#define INVOICE_RECORDSET_SEARCHER_H_

#include "recordset_searcher.h"

class InvoiceRecordsetSearcher : public RecordsetSearcher
{
public:
	InvoiceRecordsetSearcher() {};
	virtual ~InvoiceRecordsetSearcher() {};
	bool search(QString value, QList<QMap<QString, QString>*> *list);
};

#endif /* INVOICE_RECORDSET_SEARCHER_H_ */
