/*
 * recordset_searcher.h
 *
 *  Created on: 20/08/2010
 *      Author: pc
 */

#ifndef RECORDSET_SEARCHER_H_
#define RECORDSET_SEARCHER_H_

#include <QString>
#include <QList>
#include <QMap>

class RecordsetSearcher
{
public:
	virtual ~RecordsetSearcher() {};
	virtual bool search(QString value, QList<QMap<QString, QString>*> *list) = 0;
	virtual QList<QMap<QString, QString>*>::const_iterator newIterator();
	virtual int newIndex();

protected:
	QList<QMap<QString, QString>*>::const_iterator m_Iterator;
	int m_Index;
};

#endif /* RECORDSET_SEARCHER_H_ */
