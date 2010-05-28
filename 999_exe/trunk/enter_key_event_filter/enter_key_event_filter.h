/*
 * enter_key_event_filter.h
 *
 *  Created on: 28/05/2010
 *      Author: pc
 */

#ifndef ENTER_KEY_EVENT_FILTER_H_
#define ENTER_KEY_EVENT_FILTER_H_

#include <QObject>
#include <QEvent>

class EnterKeyEventFilter : public QObject
{
	Q_OBJECT

public:
	EnterKeyEventFilter(QObject *parent = 0);
	virtual ~EnterKeyEventFilter() {};

protected:
	bool eventFilter(QObject *obj, QEvent *event);
};

#endif /* ENTER_KEY_EVENT_FILTER_H_ */
