/*
 * web_plugin_factory.cpp
 *
 *  Created on: 31/05/2010
 *      Author: pc
 */

#include "web_plugin_factory.h"

/**
 * @class PluginFactory
 * Class in charge of creating or passing widgets to the QWebPage for display.
 */

/**
 * Constructs a PluginFactory.
 */
WebPluginFactory::WebPluginFactory(QObject *parent) : QWebPluginFactory(parent)
{
}

/**
 * Returns the widget of the passed mime type.
 */
QObject* WebPluginFactory::create(const QString &mimeType, const QUrl &url,
		const QStringList &argumentNames,
		const QStringList &argumentValues) const
{
	if (m_Plugins.contains(mimeType)) {
		PluginWidget *widget = m_Plugins[mimeType];
		widget->init(argumentNames, argumentValues);
		return dynamic_cast<QObject*>(widget);
	} else {
		return NULL;
	}
}

/**
 * Returns a empty list just for compliance.
 */
QList<QWebPluginFactory::Plugin> WebPluginFactory::plugins() const
{
	QList<QWebPluginFactory::Plugin> plugins;
	return plugins;
}

/**
 * Installs a plugin in the factory.
 */
void WebPluginFactory::install(QString mimeType, PluginWidget *widget)
{
	m_Plugins[mimeType] = widget;
}

/**
 * Removes a plugin from the factory.
 */
void WebPluginFactory::remove(QString mimeType)
{
	m_Plugins.remove(mimeType);
}
