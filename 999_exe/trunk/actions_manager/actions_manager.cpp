/*
 * actions_manager.cpp
 *
 *  Created on: 13/05/2010
 *      Author: pc
 */

#include "actions_manager.h"

void ActionsManager::setActions(QList<QAction*> *actions)
{
	m_Actions = actions;
}

void ActionsManager::updateActions(QString values)
{
	for (int i = 0; i < m_Actions->size(); i++) {
		m_Actions->at(i)->setEnabled(values.at(i) == '1');
	}
}
