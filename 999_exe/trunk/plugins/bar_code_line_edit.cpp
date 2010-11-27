/*
 * bar_code_line_edit.cpp
 *
 *  Created on: 29/05/2010
 *      Author: pc
 */

#include "bar_code_line_edit.h"

#include <QPalette>
#include <QRegExpValidator>

/**
 * @class BarCodeLineEdit
 * Widget use for entering products' bar codes.
 */

/**
 * Constructs the widget.
 */
BarCodeLineEdit::BarCodeLineEdit(QWidget *parent) : QLineEdit(parent)
{
	connect(this, SIGNAL(returnPressed()), this, SLOT(returnKeyPressed()));
}

/**
 * Initializes the widget for a html display.
 */
void BarCodeLineEdit::init(const QStringList &argumentNames,
		const QStringList &argumentValues)
{
	setFrame(false);

	QPalette pale = palette();
	pale.setColor(QPalette::Disabled, QPalette::Base, Qt::white);
	setPalette(pale);

	QRegExp rx("[^\\*]+\\*?[^\\*]*");
	QValidator *validator = new QRegExpValidator(rx, this);
	setValidator(validator);
}

/**
 * Emits the signal when the return key is hit with the text value.
 */
void BarCodeLineEdit::returnKeyPressed()
{
	QString barCode, quantity;

	QStringList values = text().split("*");

	if (values.length() > 1) {
		quantity = values[0];
		barCode = values[1];
	} else {
		quantity = "1";
		barCode = values [0];
	}

	emit returnPressedBarCode(barCode, quantity);
}
