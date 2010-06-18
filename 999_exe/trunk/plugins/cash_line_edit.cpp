/*
 * cash_line_edit.cpp
 *
 *  Created on: 17/06/2010
 *      Author: pc
 */

#include "cash_line_edit.h"

#include <QFont>

/**
 * @class CashLineEdit
 * Widget use for entering cash amounts.
 */

/**
 * Constructs the widget.
 */
CashLineEdit::CashLineEdit(QWidget *parent) : QLineEdit(parent)
{

}

/**
 * Initializes the widget for a html display.
 */
void CashLineEdit::init(const QStringList &argumentNames,
		const QStringList &argumentValues)
{
	//resize(300, 50);
	setAlignment(Qt::AlignRight);

	QFont fon = font();

	// If the screen resolution is large.
	if (argumentValues[argumentNames.indexOf("is_large")] == "1") {
		fon.setPointSize(18);
	} else {
		fon.setPointSize(16);
	}

	setFont(fon);
}
