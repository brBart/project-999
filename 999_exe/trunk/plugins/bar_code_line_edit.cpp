/*
 * bar_code_line_edit.cpp
 *
 *  Created on: 29/05/2010
 *      Author: pc
 */

#include "bar_code_line_edit.h"

#include <QPalette>

/**
 * @class BarCodeLineEdit
 * Widget use for entering products' bar codes.
 */

/**
 * Constructs the widget.
 */
BarCodeLineEdit::BarCodeLineEdit(QWidget *parent) : QLineEdit(parent)
{

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
}
