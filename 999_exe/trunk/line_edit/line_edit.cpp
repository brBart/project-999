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
	emit blur(text());

	QLineEdit::focusOutEvent(e);
}
