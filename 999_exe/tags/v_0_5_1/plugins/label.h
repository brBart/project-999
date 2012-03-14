/*
 * label.h
 *
 *  Created on: 30/07/2010
 *      Author: pc
 */

#ifndef LABEL_H_
#define LABEL_H_

#include <QLabel>
#include "plugin_widget.h"

class Label: public QLabel, public PluginWidget
{
public:
	Label(QWidget *parent = 0);
	virtual ~Label() {};
	void init(const QStringList &argumentNames, const QStringList &argumentValues);
};

#endif /* LABEL_H_ */
