/*
 * actions_manager.h
 *
 *  Created on: 13/05/2010
 *      Author: pc
 */

#ifndef ACTIONS_MANAGER_H_
#define ACTIONS_MANAGER_H_

#include <QList>
#include <QAction>

class ActionsManager
{
public:
	ActionsManager() {};
	virtual ~ActionsManager() {};
	void setActions(QList<QAction*> *actions);
	void updateActions(QString values);

private:
	QList<QAction*> *m_Actions;
};

#endif /* ACTIONS_MANAGER_H_ */
