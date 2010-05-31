/*
 * plugin_factory.cpp
 *
 *  Created on: 03/05/2010
 *      Author: pc
 */

#include "plugin_factory.h"

#include <QWidget>

/**
 * @class PluginFactory
 * Class in charge of creating or passing widgets to the QWebPage for display.
 */

/**
 * Constructs a PluginFactory.
 */
PluginFactory::PluginFactory(QObject *parent) : QWebPluginFactory(parent)
{
}

/**
 * Returns the widget of the passed mime type.
 */
QObject* PluginFactory::create(const QString &mimeType, const QUrl &url,
		const QStringList &argumentNames,
		const QStringList &argumentValues) const
{
	PluginWidget *widget = m_Plugins[mimeType];
	widget->init(argumentNames, argumentValues);
	return dynamic_cast<QObject*>(widget);
}

/**
 * Returns a empty list just for compliance.
 */
QList<QWebPluginFactory::Plugin> PluginFactory::plugins() const
{
	QList<QWebPluginFactory::Plugin> plugins;
	return plugins;
}

/**
 * Installs a plugin in the factory.
 */
void PluginFactory::install(QString mimeType, PluginWidget *widget)
{
	m_Plugins[mimeType] = widget;
}

/**
 * Removes a plugin from the factory.
 */
void PluginFactory::remove(QString mimeType)
{
	m_Plugins.remove(mimeType);
}
