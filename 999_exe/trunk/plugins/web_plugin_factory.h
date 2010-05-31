/*
 * web_plugin_factory.h
 *
 *  Created on: 31/05/2010
 *      Author: pc
 */

#ifndef WEB_PLUGIN_FACTORY_H_
#define WEB_PLUGIN_FACTORY_H_

#include <QWebPluginFactory>
#include <QMap>
#include "plugin_widget.h"

class WebPluginFactory : public QWebPluginFactory
{
public:
	WebPluginFactory(QObject *parent = 0);
	virtual ~WebPluginFactory() {};
	QObject* create(const QString &mimeType, const QUrl &url,
			const QStringList &argumentNames,
			const QStringList &argumentValues) const;
	QList<QWebPluginFactory::Plugin> plugins() const;
	void install(QString mimeType, PluginWidget *widget);
	void remove(QString mimeType);

private:
	QMap<QString, PluginWidget*> m_Plugins;
};

#endif /* WEB_PLUGIN_FACTORY_H_ */
