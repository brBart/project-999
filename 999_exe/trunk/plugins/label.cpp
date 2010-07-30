/*
 * label.cpp
 *
 *  Created on: 30/07/2010
 *      Author: pc
 */

#include "label.h"

#include <QFont>

/**
 * @class Label
 * Widget use for displaying text on html page.
 */

/**
 * Constructs the widget.
 */
Label::Label(QWidget *parent) : QLabel(parent)
{

}

/**
 * Initializes the widget for a html display.
 */
void Label::init(const QStringList &argumentNames,
		const QStringList &argumentValues)
{
	QFont fon = font();
	fon.setBold(true);
	setFont(fon);
}
