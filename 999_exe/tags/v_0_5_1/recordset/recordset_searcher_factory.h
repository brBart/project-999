/*
 * recordset_searcher_factory.h
 *
 *  Created on: 13/09/2010
 *      Author: pc
 */

#ifndef RECORDSET_SEARCHER_FACTORY_H_
#define RECORDSET_SEARCHER_FACTORY_H_

#include <QObject>
#include "recordset_searcher.h"

class RecordsetSearcherFactory : public QObject
{
	Q_OBJECT

public:
	virtual ~RecordsetSearcherFactory() {};
	RecordsetSearcher* create(QString name);
	static RecordsetSearcherFactory* instance();

private:
	static RecordsetSearcherFactory *m_Instance;

	RecordsetSearcherFactory(QObject *parent = 0);
};

#endif /* RECORDSET_SEARCHER_FACTORY_H_ */
