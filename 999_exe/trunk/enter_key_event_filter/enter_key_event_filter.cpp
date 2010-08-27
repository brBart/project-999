/*
 * enter_key_event_filter.cpp
 *
 *  Created on: 28/05/2010
 *      Author: pc
 */

#include "enter_key_event_filter.h"

#include <QKeyEvent>
#include <QPushButton>

/**
 * @class EnterKeyEventFilter
 * Gives the enter key functionality to the QPushButton objects.
 */

/**
 * Constructs the object with a parent.
 */
EnterKeyEventFilter::EnterKeyEventFilter(QObject *parent) : QObject(parent)
{

}

/**
 * Handles the enter key event.
 * If it is the enter key that was pressed, the animatedClick slot is calle on the
 * QPushButton object.
 */
bool EnterKeyEventFilter::eventFilter(QObject *obj, QEvent *event)
{
	if (event->type() == QEvent::KeyRelease) {
		QKeyEvent *keyEvent = static_cast<QKeyEvent *>(event);
		if (keyEvent->key() == Qt::Key_Enter || keyEvent->key() == Qt::Key_Return) {
			QPushButton *button = static_cast<QPushButton*>(obj);
			button->animateClick();
			return true;
		}
	}

	return false;
}
