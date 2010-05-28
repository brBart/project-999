/*
 * line_edit.cpp
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#include "line_edit.h"

LineEdit::LineEdit(QWidget *parent) : QLineEdit(parent)
{

}

void LineEdit::focusOutEvent(QFocusEvent *e)
{
	QString value = text();

	// If is empty, continue. But if is not, must be with different value.
	if (value == "" || (value != "" && value != m_LastText)) {
		m_LastText = value;

		emit blurAndChanged(value);
	}

	QLineEdit::focusOutEvent(e);
}
