/*
 * line_edit_plugin.cpp
 *
 *  Created on: 31/08/2010
 *      Author: pc
 */

#include "line_edit_plugin.h"

/**
 * @class LineEdit
 * LineEdit widget plugin for html display purposes.
 */

/**
 * Constructs the widget with parent.
 */
LineEditPlugin::LineEditPlugin(QWidget *parent) : LineEdit(parent)
{

}

/**
 * Initializes the widget for a html display.
 */
void LineEditPlugin::init(const QStringList &argumentNames,
		const QStringList &argumentValues)
{

}
