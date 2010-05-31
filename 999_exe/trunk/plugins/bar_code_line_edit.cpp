/*
 * bar_code_line_edit.cpp
 *
 *  Created on: 29/05/2010
 *      Author: pc
 */

#include "bar_code_line_edit.h"

#include <QPalette>

BarCodeLineEdit::BarCodeLineEdit(QWidget *parent) : QLineEdit(parent)
{

}

void BarCodeLineEdit::init(const QStringList &argumentNames,
		const QStringList &argumentValues)
{
	setFrame(false);

	QPalette pale = palette();
	pale.setColor(QPalette::Disabled, QPalette::Base, Qt::white);
	setPalette(pale);
}
