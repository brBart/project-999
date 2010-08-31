/*
 * combo_box.h
 *
 *  Created on: 31/08/2010
 *      Author: pc
 */

#ifndef COMBO_BOX_H_
#define COMBO_BOX_H_

#include <QComboBox>
#include "plugin_widget.h"

class ComboBox: public QComboBox, public PluginWidget
{
public:
	ComboBox(QWidget *parent = 0);
	virtual ~ComboBox() {};
	void init(const QStringList &argumentNames, const QStringList &argumentValues);
};

#endif /* COMBO_BOX_H_ */
