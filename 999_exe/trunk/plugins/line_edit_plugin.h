/*
 * line_edit_plugin.h
 *
 *  Created on: 31/08/2010
 *      Author: pc
 */

#ifndef LINE_EDIT_PLUGIN_H_
#define LINE_EDIT_PLUGIN_H_

#include "../line_edit/line_edit.h"
#include "plugin_widget.h"

class LineEditPlugin: public LineEdit, public PluginWidget
{
public:
	LineEditPlugin(QWidget *parent = 0);
	virtual ~LineEditPlugin() {};
	void init(const QStringList &argumentNames, const QStringList &argumentValues);
};

#endif /* LINE_EDIT_PLUGIN_H_ */
