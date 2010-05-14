/*
 * sales_section.cpp
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#include "sales_section.h"

#include <QList>
#include "../xml_transformer/invoice_list_xml_transformer.h"

SalesSection::SalesSection(QNetworkAccessManager *manager,
		QWebPluginFactory *factory, QUrl *serverUrl, QString cRegisterKey,
		QWidget *parent) : Section(manager, factory, serverUrl, parent),
		m_CRegisterKey(cRegisterKey)
{
	m_Window = dynamic_cast<MainWindow*>(parentWidget());
	setActions();
	setMenu();
	setActionsManager();

	m_Request = new HttpRequest(manager, this);
	m_Handler = new XmlResponseHandler(this);

	connect(ui.webView, SIGNAL(loadFinished(bool)), this,
			SLOT(loadFinished(bool)));
	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(&m_Recordset, SIGNAL(recordChanged(QString)), this,
			SLOT(fetchInvoice(QString)));

	refreshRecordset();
	if (m_Recordset.size() > 0) {
		m_Recordset.moveFirst();
	} else {
		fetchEmptyInvoiceList();
	}
}

SalesSection::~SalesSection()
{
	m_Window->menuBar()->clear();
}

void SalesSection::loadFinished(bool ok)
{
	Section::loadFinished(ok);
	m_Console.setFrame(ui.webView->page()->mainFrame());

	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		m_CRegisterStatus =
				CRegisterStatus(frame->evaluateJavaScript("cashRegisterStatus")
						.toInt());
		m_DocumentStatus =
				DocumentStatus(frame->evaluateJavaScript("documentStatus").toInt());
	} else {
		m_CRegisterStatus = Error;
	}

	updateActions();
}

void SalesSection::fetchInvoice(QString id)
{

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

void SalesSection::setActionsManager()
{
	QList<QAction*> *actions = new QList<QAction*>();

	*actions << m_NewAction;
	*actions << m_SaveAction;
	*actions << m_DiscardAction;
	*actions << m_CancelAction;
	*actions << m_ExitAction;

	*actions << m_ClientAction;
	*actions << m_DiscountAction;
	*actions << m_AddProductAction;
	*actions << m_RemoveProductAction;
	*actions << m_SearchProductAction;

	*actions << m_MoveFirstAction;
	*actions << m_MovePreviousAction;
	*actions << m_MoveNextAction;
	*actions << m_MoveLastAction;
	*actions << m_SearchAction;
	*actions << m_ConsultProductAction;

	m_ActionsManager.setActions(actions);
}

void SalesSection::refreshRecordset()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_invoice_list");
	url.addQueryItem("register_key", m_CRegisterKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	QString errorMsg;
	InvoiceListXmlTransformer *transformer = new InvoiceListXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->list();
		m_Recordset.setList(list);
	}

	delete transformer;
}

void SalesSection::fetchEmptyInvoiceList()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "show_empty_invoice_list");
	url.addQueryItem("register_key", m_CRegisterKey);
	ui.webView->load(url);
}

void SalesSection::updateActions()
{
	QString values;

	switch (m_CRegisterStatus) {
		case Open:
			if (m_DocumentStatus == Edit) {
				values = "0110011111000001";
			} else {
				QString cancel =
						(m_DocumentStatus == Idle
								&& m_Recordset.size() > 0) ? "1" : "0";
				values = "100" + cancel + "100000" + viewValues();
			}
			break;

		case Closed:
			values = "0000100000" + viewValues();
			break;

		case Error:
			values = "0000100000000000";
			break;

		default:;
	}

	m_ActionsManager.updateActions(values);
}

QString SalesSection::viewValues()
{
	if (m_Recordset.size() > 0) {
		if (m_Recordset.isFirst()) {
			return "110011";
		} else if (m_Recordset.isLast()) {
			return "001111";
		} else {
			return "111111";
		}
	} else {
		return "000001";
	}
}
