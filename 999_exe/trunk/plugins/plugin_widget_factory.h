/*
 * plugin_widget_factory.h
 *
 *  Created on: 31/05/2010
 *      Author: pc
 */

#ifndef PLUGIN_WIDGET_FACTORY_H_
#define PLUGIN_WIDGET_FACTORY_H_

#include "plugin_widget.h"

class PluginWidgetFactory
{
public:
	virtual ~PluginWidgetFactory() {};
	PluginWidget* create(QString name);
	static PluginWidgetFactory* instance();

private:
	static PluginWidgetFactory *m_Instance;

	PluginWidgetFactory() {};
};

#endif /* PLUGIN_WIDGET_FACTORY_H_ */
