#ifndef RECORDSET_H
#define RECORDSET_H

#include <QtGui/QWidget>
#include "ui_recordset.h"

#include <QList>
#include <QMap>

class Recordset : public QWidget
{
    Q_OBJECT

public:
    Recordset(QWidget *parent = 0);
    ~Recordset() {};
    void setList(QList<QMap<QString, QString>*> list);
    int size();
    bool isFirst();
    bool isLast();
    void refresh();

public slots:
	void moveFirst();
	void movePrevious();
	void moveNext();
	void moveLast();

signals:
	void recordChanged(QString id);

private:
    Ui::RecordsetClass ui;
    QList<QMap<QString, QString>*> m_List;
    QList<QMap<QString, QString>*>::const_iterator m_Iterator;
    int m_Index;

    void updateLabel();
};

#endif // RECORDSET_H
