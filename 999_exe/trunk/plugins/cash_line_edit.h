/*
 * cash_line_edit.h
 *
 *  Created on: 17/06/2010
 *      Author: pc
 */

#ifndef CASH_LINE_EDIT_H_
#define CASH_LINE_EDIT_H_

#include <QLineEdit>
#include "plugin_widget.h"

class CashLineEdit: public QLineEdit, public PluginWidget
{
	Q_OBJECT

public:
	CashLineEdit(QWidget *parent = 0);
	virtual ~CashLineEdit() {};
	void init(const QStringList &argumentNames, const QStringList &argumentValues);
};

#endif /* CASH_LINE_EDIT_H_ */
