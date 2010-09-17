/*
 * recordset_searcher_factory.cpp
 *
 *  Created on: 13/09/2010
 *      Author: pc
 */

#include "recordset_searcher_factory.h"

#include <QApplication>
#include "invoice_recordset_searcher.h"
#include "deposit_id_recordset_searcher.h"

/**
 * @class RecordsetSearcherFactory
 * Singleton class in charge of creating all the searcher objects for the recordset.
 */

RecordsetSearcherFactory* RecordsetSearcherFactory::m_Instance = 0;

/**
 * Constructs the factory with a parent.
 */
RecordsetSearcherFactory::RecordsetSearcherFactory(QObject *parent) : QObject(parent)
{

}

/**
 * Creates and returns the corresponding searcher object.
 */
RecordsetSearcher* RecordsetSearcherFactory::create(QString name)
{
	if (name == "invoice") {
		return new InvoiceRecordsetSearcher();
	} else if (name == "deposit_id") {
		return new DepositIdRecordsetSearcher();
	} else {
		return 0;
	}
}

/**
 * Returns the only instance.
 */
RecordsetSearcherFactory* RecordsetSearcherFactory::instance()
{
	if (m_Instance == 0)
		m_Instance = new RecordsetSearcherFactory(qApp);

	return m_Instance;
}
