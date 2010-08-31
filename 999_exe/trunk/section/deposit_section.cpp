/*
 * deposit_section.cpp
 *
 *  Created on: 27/08/2010
 *      Author: pc
 */

#include "deposit_section.h"

/**
 * @class DepositSection
 * Section that manages the deposit documents.
 */

/**
 * Constructs the section.
 */
DepositSection::DepositSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
		QUrl *serverUrl, QString cashRegisterKey, QWidget *parent)
		: DocumentSection(jar, factory, serverUrl, cashRegisterKey, parent)
{

}

/**
 * Creates the QActions for the menu bar.
 */
void DepositSection::setActions()
{
	m_NewAction = new QAction("Crear", this);
	m_NewAction->setShortcut(Qt::Key_Insert);
	connect(m_NewAction, SIGNAL(triggered()), this, SLOT(createDocument()));

	m_SaveAction = new QAction("Guardar", this);
	m_SaveAction->setShortcut(tr("Ctrl+S"));

	m_DiscardAction = new QAction("Cancelar", this);
	m_DiscardAction->setShortcut(Qt::Key_Escape);
	connect(m_DiscardAction, SIGNAL(triggered()), this, SLOT(discardDocument()));

	m_CancelAction = new QAction("Anular", this);
	m_CancelAction->setShortcut(Qt::Key_F10);
	connect(m_CancelAction, SIGNAL(triggered()), this,
			SLOT(showAuthenticationDialogForCancel()));

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(Qt::Key_Escape);
	connect(m_ExitAction, SIGNAL(triggered()), this, SLOT(unloadSection()));

	m_AddItemAction = new QAction("Agregar producto", this);
	m_AddItemAction->setShortcut(tr("Ctrl+I"));

	m_DeleteItemAction = new QAction("Quitar producto", this);
	m_DeleteItemAction->setShortcut(tr("Ctrl+D"));

	m_ScrollUpAction = new QAction("Desplazar arriba", this);
	m_ScrollUpAction->setShortcut(tr("Ctrl+Up"));
	connect(m_ScrollUpAction, SIGNAL(triggered()), this, SLOT(scrollUp()));

	m_ScrollDownAction = new QAction("Desplazar abajo", this);
	m_ScrollDownAction->setShortcut(tr("Ctrl+Down"));
	connect(m_ScrollDownAction, SIGNAL(triggered()), this, SLOT(scrollDown()));

	m_MoveFirstAction = new QAction("Primero", this);
	m_MoveFirstAction->setShortcut(tr("Home"));
	connect(m_MoveFirstAction, SIGNAL(triggered()), &m_Recordset,
			SLOT(moveFirst()));

	m_MovePreviousAction = new QAction("Anterior", this);
	m_MovePreviousAction->setShortcut(tr("PgUp"));
	connect(m_MovePreviousAction, SIGNAL(triggered()), &m_Recordset,
			SLOT(movePrevious()));

	m_MoveNextAction = new QAction("Siguiente", this);
	m_MoveNextAction->setShortcut(tr("PgDown"));
	connect(m_MoveNextAction, SIGNAL(triggered()), &m_Recordset, SLOT(moveNext()));

	m_MoveLastAction = new QAction("Ultimo", this);
	m_MoveLastAction->setShortcut(tr("End"));
	connect(m_MoveLastAction, SIGNAL(triggered()), &m_Recordset, SLOT(moveLast()));

	m_SearchAction = new QAction("Buscar", this);
	m_SearchAction->setShortcut(Qt::Key_F1);
}

/**
 * Sets the window's menu bar.
 */
void DepositSection::setMenu()
{
	QMenu *menu;

	menu = m_Window->menuBar()->addMenu("Archivo");
	menu->addAction(m_NewAction);
	menu->addAction(m_SaveAction);
	menu->addAction(m_DiscardAction);
	menu->addAction(m_CancelAction);
	menu->addSeparator();
	menu->addAction(m_ExitAction);

	menu = m_Window->menuBar()->addMenu("Editar");
	menu->addAction(m_AddItemAction);
	menu->addAction(m_DeleteItemAction);

	menu = m_Window->menuBar()->addMenu("Ver");
	menu->addAction(m_ScrollUpAction);
	menu->addAction(m_ScrollDownAction);
	menu->addSeparator();
	menu->addAction(m_MoveFirstAction);
	menu->addAction(m_MovePreviousAction);
	menu->addAction(m_MoveNextAction);
	menu->addAction(m_MoveLastAction);
	menu->addAction(m_SearchAction);
	menu->addSeparator();
}

/**
 * Sets the ActionsManager with the already created QActions.
 */
void DepositSection::setActionsManager()
{
	QList<QAction*> *actions = new QList<QAction*>();

	*actions << m_NewAction;
	*actions << m_SaveAction;
	*actions << m_DiscardAction;
	*actions << m_CancelAction;
	*actions << m_ExitAction;

	*actions << m_AddItemAction;
	*actions << m_DeleteItemAction;

	*actions << m_ScrollUpAction;
	*actions << m_ScrollDownAction;
	*actions << m_MoveFirstAction;
	*actions << m_MovePreviousAction;
	*actions << m_MoveNextAction;
	*actions << m_MoveLastAction;
	*actions << m_SearchAction;

	m_ActionsManager.setActions(actions);
}

/**
 * Installs the recordset searcher.
 */
void DepositSection::installRecordsetSearcher()
{

}

/**
 * Installs the necessary plugins widgets in the plugin factory of the web view.
 */
void DepositSection::setPlugins()
{
	DocumentSection::setPlugins();

	m_DepositNumberLineEdit = new LineEditPlugin();
	webPluginFactory()
			->install("application/x-deposit_number_line_edit",
					m_DepositNumberLineEdit);
}

/**
 * Updates the QActions depending on the actual section status.
 */
void DepositSection::updateActions()
{
	QString values;

	switch (m_CashRegisterStatus) {
		case Open:
			if (m_DocumentStatus == Edit) {
				values = "01100111100000";
			} else {
				QString cancel =
						(m_DocumentStatus == Idle
								&& m_Recordset.size() > 0) ? "1" : "0";
				values = "100" + cancel + "100" + navigateValues();
			}
			break;

		case Closed:
			values = "0000100" + navigateValues();
			break;

		case Error:
			values = "00001000000000";
			break;

		case Loading:
			values = "00001000000000";
			break;

		default:;
	}

	m_ActionsManager.updateActions(values);
}

/**
 * Auxiliary method for updating the QActions related to the recordset.
 */
QString DepositSection::navigateValues()
{
	if (m_Recordset.size() > 0) {
		if (m_Recordset.size() == 1) {
			return "1100000";
		} else if (m_Recordset.isFirst()) {
			return "1100111";
		} else if (m_Recordset.isLast()) {
			return "1111001";
		} else {
			return "1111111";
		}
	} else {
		return "0000000";
	}
}
