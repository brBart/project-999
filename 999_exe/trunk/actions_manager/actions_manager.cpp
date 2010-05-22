/*
 * actions_manager.cpp
 *
 *  Created on: 13/05/2010
 *      Author: pc
 */

#include "actions_manager.h"

/**
 * @class ActionsManager
 * Stores and updates (enable/disable) QActions objects.
 */

/**
 * Stores the QActions objects.
 */
void ActionsManager::setActions(QList<QAction*> *actions)
{
	m_Actions = actions;
}

/**
 * Enable or disable each QAction object stored.
 * It receives a string of ceros and ones values e.g. "001100...", which enables
 * ("1") or disables ("0") an action depending of the order it was stored.
 */
void ActionsManager::updateActions(QString values)
{
	for (int i = 0; i < m_Actions->size(); i++) {
		m_Actions->at(i)->setEnabled(values.at(i) == '1');
	}
}
