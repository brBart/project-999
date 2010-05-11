/*
 * sales_section.cpp
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#include "sales_section.h"

#include <QMessageBox>

SalesSection::SalesSection(QNetworkAccessManager *manager,
		QWebPluginFactory *factory, QUrl *serverUrl, QString cRegisterKey,
		QWidget *parent) : Section(manager, factory, serverUrl, parent),
		m_CRegisterKey(cRegisterKey)
{
	m_Window = dynamic_cast<MainWindow*>(parentWidget());
	setActions();
	setMenu();
}

void SalesSection::setActions()
{
	m_NewAction = new QAction("Crear", this);
	m_NewAction->setShortcut(Qt::Key_Insert);

	m_SaveAction = new QAction("Guardar", this);
	m_SaveAction->setShortcut(tr("Ctrl+S"));

	m_DiscardAction = new QAction("Cancelar", this);
	m_DiscardAction->setShortcut(tr("Ctrl+W"));

	m_CancelAction = new QAction("Anular", this);
	m_CancelAction->setShortcut(Qt::Key_F10);

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(tr("Ctrl+Q"));
	connect(m_ExitAction, SIGNAL(triggered()), m_Window, SLOT(loadMainSection()));

	m_ClientAction = new QAction("Cliente", this);
	m_ClientAction->setShortcut(tr("Ctrl+E"));

	m_DiscountAction = new QAction("Descuento", this);
	m_DiscountAction->setShortcut(Qt::Key_F7);

	m_AddProductAction = new QAction("Agregar producto", this);
	m_AddProductAction->setShortcut(tr("Ctrl+I"));

	m_RemoveProductAction = new QAction("Quitar producto", this);
	m_RemoveProductAction->setShortcut(tr("Del"));

	m_SearchProductAction = new QAction("Buscar producto", this);
	m_SearchProductAction->setShortcut(Qt::Key_F5);

	m_MoveFirstAction = new QAction("Primero", this);
	m_MoveFirstAction->setShortcut(tr("Home"));

	m_MovePreviousAction = new QAction("Anterior", this);
	m_MovePreviousAction->setShortcut(tr("PgUp"));

	m_MoveNextAction = new QAction("Siguiente", this);
	m_MoveNextAction->setShortcut(tr("PgDown"));

	m_MoveLastAction = new QAction("Ultimo", this);
	m_MoveLastAction->setShortcut(tr("End"));

	m_SearchAction = new QAction("Buscar", this);
	m_SearchAction->setShortcut(Qt::Key_F1);

	m_ConsultProductAction = new QAction("Consultar producto", this);
	m_ConsultProductAction->setShortcut(Qt::Key_F6);
}

void SalesSection::setMenu()
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
	menu->addAction(m_ClientAction);
	menu->addAction(m_DiscountAction);
	menu->addAction(m_AddProductAction);
	menu->addAction(m_RemoveProductAction);
	menu->addAction(m_SearchProductAction);

	menu = m_Window->menuBar()->addMenu("Ver");
	menu->addAction(m_MoveFirstAction);
	menu->addAction(m_MovePreviousAction);
	menu->addAction(m_MoveNextAction);
	menu->addAction(m_MoveLastAction);
	menu->addAction(m_SearchAction);
	menu->addSeparator();
	menu->addAction(m_ConsultProductAction);
}
