/*
 * line_edit.h
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#ifndef LINE_EDIT_H_
#define LINE_EDIT_H_

#include <QLineEdit>
#include <QFocusEvent>

class LineEdit : public QLineEdit
{
	Q_OBJECT

public:
	LineEdit(QWidget *parent = 0);
	virtual ~LineEdit() {};

signals:
	void blurAndChanged(QString text);

protected:
	void focusOutEvent(QFocusEvent *e);

private:
	QString m_LastText;
};

#endif /* LINE_EDIT_H_ */
