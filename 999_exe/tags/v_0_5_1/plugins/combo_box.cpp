/*
 * combo_box.cpp
 *
 *  Created on: 31/08/2010
 *      Author: pc
 */

#include "combo_box.h"

/**
 * @class ComboBox
 * ComboBox widget plugin for html display purposes.
 */

/**
 * Constructs the widget with parent.
 */
ComboBox::ComboBox(QWidget *parent) : QComboBox(parent)
{

}

/**
 * Initializes the widget for a html display.
 */
void ComboBox::init(const QStringList &argumentNames,
		const QStringList &argumentValues)
{

}
