#include "recordset.h"

/**
 * @class Recordset
 * Manages a recordset with the list with the ids of the document in use. It also
 * displays the position in which the recordset is at.
 */

/**
 * Set the list the Recordset will use.
 */
void Recordset::setList(QList<QMap<QString, QString>*> list)
{
	m_List = list;
	m_Iterator = m_List.begin();
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
	m_Iterator = m_List.begin();
	m_Index = 0;
	updateLabel();

	emit recordChanged((*m_Iterator)->value("id"));
}

/**
 * Move to the previous position.
 */
void Recordset::movePrevious()
{
	--m_Iterator;
	m_Index = m_Index - 1;
	updateLabel();

	emit recordChanged((*m_Iterator)->value("id"));
}

/**
 * Move to the next position.
 */
void Recordset::moveNext()
{
	++m_Iterator;
	m_Index = m_Index + 1;
	updateLabel();

	emit recordChanged((*m_Iterator)->value("id"));
}

/**
 * Move to the last position.
 */
void Recordset::moveLast()
{
	m_Iterator = m_List.end();
	--m_Iterator;
	m_Index = m_List.size() - 1;
	updateLabel();

	emit recordChanged((*m_Iterator)->value("id"));
}

/**
 * Returns true if it is at the first position.
 */
bool Recordset::isFirst()
{
	return (m_Index == 0);
}

/**
 * Returns true if it is at the last position.
 */
bool Recordset::isLast()
{
	return (m_Index == m_List.size() - 1);
}

/**
 * Emits the recordChanged signal with the actual index position.
 */
void Recordset::refresh()
{
	emit recordChanged((m_List.at(m_Index))->value("id"));
}

/**
 * Returns the text to display on a label or else.
 */
QString Recordset::text()
{
	return m_Text;
}

/**
 * Sets the searcher this recordset will use.
 */
void Recordset::installSearcher(RecordsetSearcher *searcher)
{
	m_Searcher = searcher;
}

/**
 * Searches for the specified value in the recordset. Returns true if found.
 */
bool Recordset::search(QString value)
{
	if (m_Searcher->search(value, &m_List)) {
		// Sets the new values to reflect the new position.
		m_Iterator = m_Searcher->newIterator();
		m_Index = m_Searcher->newIndex();
		updateLabel();
		refresh();

		return true;
	}

	return false;
}

/**
 * Updates the label with the actual index position.
 */
void Recordset::updateLabel()
{
	m_Text = QString("%1 de %2").arg(m_Index + 1).arg(m_List.size());
}
