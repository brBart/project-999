/*
 * plugin_widget.h
 *
 *  Created on: 29/05/2010
 *      Author: pc
 */

#ifndef PLUGIN_WIDGET_H_
#define PLUGIN_WIDGET_H_

#include <QStringList>

class PluginWidget
{
public:
	virtual void init(const QStringList &argumentNames,
			const QStringList &argumentValues) = 0;
};


#endif /* PLUGIN_WIDGET_H_ */
