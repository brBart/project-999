#include "recordset.h"

Recordset::Recordset(QWidget *parent)
    : QWidget(parent)
{
	ui.setupUi(this);
}

Recordset::~Recordset()
{
	delete m_Iterator;
}

void Recordset::setList(QList<QMap<QString, QString>*> list)
{
	m_List = list;
	m_Iterator = new QListIterator<QMap<QString, QString>*>(m_List);
}

int Recordset::size()
{
	return m_List.size();
}

void Recordset::moveFirst()
{
	m_Iterator->toFront();
	QMap<QString, QString> *record = m_Iterator->next();
	m_Index = 1;

	emit recordChanged(record->value("id"));

	updateLabel();
}

void Recordset::movePrevious()
{
	QMap<QString, QString> *record = m_Iterator->previous();
	m_Index = m_Index - 1;

	emit recordChanged(record->value("id"));

	updateLabel();
}

void Recordset::moveNext()
{
	QMap<QString, QString> *record = m_Iterator->next();
	m_Index = m_Index + 1;

	emit recordChanged(record->value("id"));

	updateLabel();
}

void Recordset::moveLast()
{
	m_Iterator->toBack();
	QMap<QString, QString> *record = m_Iterator->previous();
	m_Index = m_List.size();

	emit recordChanged(record->value("id"));

	updateLabel();
}

bool Recordset::isFirst()
{
	return !m_Iterator->hasPrevious();
}

bool Recordset::isLast()
{
	return !m_Iterator->hasNext();
}

void Recordset::updateLabel()
{
	ui.label->setText(QString("%1 de %2").arg(m_Index).arg(m_List.size()));
}
