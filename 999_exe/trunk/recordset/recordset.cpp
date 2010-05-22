#include "recordset.h"

/**
 * @class Recordset
 * Manages a recordset with the list with the ids of the document in use. It also
 * displays the position in which the recordset is at.
 */

/**
 * Constructs a Recordset.
 */
Recordset::Recordset(QWidget *parent)
    : QWidget(parent)
{
	ui.setupUi(this);
}

/**
 * Set the list the Recordset will use.
 */
void Recordset::setList(QList<QMap<QString, QString>*> list)
{
	m_List = list;
	m_Iterator = new QListIterator<QMap<QString, QString>*>(m_List);
}

/**
 * Returns the size of the recordset.
 */
int Recordset::size()
{
	return m_List.size();
}

/**
 * Move to the first position.
 */
void Recordset::moveFirst()
{
	m_Iterator->toFront();
	QMap<QString, QString> *record = m_Iterator->next();
	m_Index = 1;

	emit recordChanged(record->value("id"));

	updateLabel();
}

/**
 * Move to the previous position.
 */
void Recordset::movePrevious()
{
	QMap<QString, QString> *record = m_Iterator->previous();
	m_Index = m_Index - 1;

	emit recordChanged(record->value("id"));

	updateLabel();
}

/**
 * Move to the next position.
 */
void Recordset::moveNext()
{
	QMap<QString, QString> *record = m_Iterator->next();
	m_Index = m_Index + 1;

	emit recordChanged(record->value("id"));

	updateLabel();
}

/**
 * Move to the last position.
 */
void Recordset::moveLast()
{
	m_Iterator->toBack();
	QMap<QString, QString> *record = m_Iterator->previous();
	m_Index = m_List.size();

	emit recordChanged(record->value("id"));

	updateLabel();
}

/**
 * Returns true if it is at the first position.
 */
bool Recordset::isFirst()
{
	return !m_Iterator->hasPrevious();
}

/**
 * Returns true if it is at the last position.
 */
bool Recordset::isLast()
{
	return !m_Iterator->hasNext();
}

/**
 * Emits the recordChanged signal with the actual index position.
 */
void Recordset::refresh()
{
	QMap<QString, QString> *record = m_List.at(m_Index);

	emit recordChanged(record->value("id"));
}

/**
 * Updates the label with the actual index position.
 */
void Recordset::updateLabel()
{
	ui.label->setText(QString("%1 de %2").arg(m_Index).arg(m_List.size()));
}
