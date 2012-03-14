/*
 * line_edit.cpp
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#include "line_edit.h"

/**
 * @class LineEdit
 * Subclass the QLineEdit and gives functionality like the blur event on the html
 * input elements.
 */

/**
 * Constructs the widget.
 */
LineEdit::LineEdit(QWidget *parent) : QLineEdit(parent)
{

}

/**
 * Emits the blurAndChanged signal with the actual text.
 */
void LineEdit::focusOutEvent(QFocusEvent *e)
{
	QString value = text();

	// If is empty, continue. But if is not, must be with different value.
	if (m_LastText.isNull() || value != m_LastText) {
		m_LastText = value;

		emit blurAndChanged(value);
	}

	QLineEdit::focusOutEvent(e);
}
