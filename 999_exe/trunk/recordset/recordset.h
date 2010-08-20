#ifndef RECORDSET_H
#define RECORDSET_H

#include <QObject>
#include <QList>
#include <QMap>
#include "recordset_searcher.h"

class Recordset : public QObject
{
    Q_OBJECT

public:
    Recordset() {};
    ~Recordset();
    void setList(QList<QMap<QString, QString>*> list);
    int size();
    bool isFirst();
    bool isLast();
    void refresh();
    QString text();
    void installSearcher(RecordsetSearcher *searcher);
    bool search(QString value);

public slots:
	void moveFirst();
	void movePrevious();
	void moveNext();
	void moveLast();

signals:
	void recordChanged(QString id);

private:
    QList<QMap<QString, QString>*> m_List;
    QList<QMap<QString, QString>*>::const_iterator m_Iterator;
    int m_Index;
    QString m_Text;
    RecordsetSearcher *m_Searcher;

    void updateLabel();
};

#endif // RECORDSET_H
