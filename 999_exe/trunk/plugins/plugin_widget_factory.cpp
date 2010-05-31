/*
 * plugin_widget_factory.cpp
 *
 *  Created on: 31/05/2010
 *      Author: pc
 */

#include "plugin_widget_factory.h"

#include "bar_code_line_edit.h"

PluginWidgetFactory* PluginWidgetFactory::m_Instance = 0;

/**
 * Returns the only instance.
 */
PluginWidgetFactory* PluginWidgetFactory::instance()
{
	if (m_Instance == 0)
		m_Instance = new PluginWidgetFactory();

	return m_Instance;
}

PluginWidget* PluginWidgetFactory::create(QString name)
{
	if (name == "bar_code") {
		return new BarCodeLineEdit();
	} else {
		return NULL;
	}
}
