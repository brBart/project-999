/*
 * deposit_section.cpp
 *
 *  Created on: 27/08/2010
 *      Author: pc
 */

#include "deposit_section.h"

#include <QMessageBox>
#include "../xml_transformer/xml_transformer_factory.h"
#include "../available_cash_dialog/available_cash_dialog.h"
#include "../search_deposit_dialog/search_deposit_dialog.h"
#include "../recordset/recordset_searcher_factory.h"

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
 * Sets the slip number to the deposit on the server.
 */
void DepositSection::setNumber(QString number)
{
	HttpRequest *request = new HttpRequest(m_Request->cookieJar(), this);

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "set_number_object");
	url.addQueryItem("value", m_SlipNumberLineEdit->text());
	url.addQueryItem("key", m_NewDocumentKey);
	url.addQueryItem("type", "xml");

	connect(request, SIGNAL(finished(QString)), this, SLOT(numberSetted(QString)));

	request->get(url, true);
}

/**
 * Reads the response of the server after setting the deposit number.
 */
void DepositSection::numberSetted(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("stub");

	QString errorMsg;
	XmlResponseHandler::ResponseType response =
			m_Handler->handle(content, transformer, &errorMsg);
	if (response == XmlResponseHandler::Success) {
		m_Console->cleanFailure("slip_number");
	} else if (response == XmlResponseHandler::Failure) {
		m_Console->cleanFailure("slip_number");
		m_Console->displayFailure(errorMsg, "slip_number");
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Sets the bank account on the index of the combo box to the deposit on the server.
 */
void DepositSection::setBankAccount(int index)
{
	QString bankAccountId = (index != -1)
			? m_BankAccountComboBox->itemData(index).toString()
					: "";

	HttpRequest *request = new HttpRequest(m_Request->cookieJar(), this);

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "set_bank_account_deposit");
	url.addQueryItem("bank_account_id", bankAccountId);
	url.addQueryItem("key", m_NewDocumentKey);
	url.addQueryItem("type", "xml");

	connect(request, SIGNAL(finished(QString)), this,
			SLOT(bankAccountSetted(QString)));

	request->get(url, true);
}

/**
 * Reads the response from the server after setting the bank account to the deposit.
 */
void DepositSection::bankAccountSetted(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
					->create("bank");

	QString errorMsg;
	XmlResponseHandler::ResponseType response =
			m_Handler->handle(content, transformer, &errorMsg);
	if (response == XmlResponseHandler::Success) {
		m_Console->cleanFailure("bank_account_id");

		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];

		ui.webView->page()->mainFrame()->findFirstElement("#bank")
				.setInnerXml(params->value("bank"));

	} else if (response == XmlResponseHandler::Failure) {
		m_Console->cleanFailure("bank_account_id");
		m_Console->displayFailure(errorMsg, "bank_account_id");

		ui.webView->page()->mainFrame()->findFirstElement("#bank")
				.setInnerXml("&nbsp;");
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Shows the AvailableCashDialog for adding cash to the deposit.
 */
void DepositSection::addCashDeposit()
{
	AvailableCashDialog dialog(m_Request->cookieJar(), m_ServerUrl,
			m_CashRegisterKey, m_NewDocumentKey, this, Qt::WindowTitleHint);

	connect(&dialog, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)), Qt::QueuedConnection);

	dialog.init();

	if (dialog.exec() == QDialog::Accepted)
		fetchDocumentDetails(m_NewDocumentKey);
}

void DepositSection::saveDeposit()
{
	if (QMessageBox::question(this, "Guardar",
			"Una vez guardado el documento no se podra editar mas. ¿Desea guardar?",
			QMessageBox::Yes | QMessageBox::No) == QMessageBox::No)
		return;

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "save_object");
	url.addQueryItem("key", m_NewDocumentKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()->create("stub");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response = m_Handler->handle(content,
			transformer, &errorMsg, &elementId);
	if (response == XmlResponseHandler::Success) {

		removeNewDocumentFromSession();
		refreshRecordset();
		m_Recordset.moveLast();

	} else if (response == XmlResponseHandler::Failure) {
		m_Console->reset();
		m_Console->displayFailure(errorMsg, elementId);

		if (elementId == "slip_number") {
			m_SlipNumberLineEdit->setFocus();
		} else if (elementId == "bank_account_id") {
			m_BankAccountComboBox->setFocus();
		}

	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Use the SearchDepositDialog class for searching a deposit.
 */
void DepositSection::searchDeposit()
{
	SearchDepositDialog dialog(m_Request->cookieJar(), m_ServerUrl, this,
			Qt::WindowTitleHint);

	connect(&dialog, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)), Qt::QueuedConnection);

	dialog.init();

	if (dialog.exec() == QDialog::Accepted) {
		if (dialog.searchMode() == SearchDepositDialog::ById) {
			if (dialog.depositId() != "") {
				RecordsetSearcher *searcher =
						RecordsetSearcherFactory::instance()->create("deposit_id");

				m_Recordset.installSearcher(searcher);

				if (!m_Recordset.search(dialog.depositId()))
					m_Console
						->displayError("Deposito no se encuentra en esta caja.");

				delete searcher;
			}
		} else {
			QString value = dialog.bankId() + " " + dialog.slipNumber();

			if (value.trimmed() != "") {
				RecordsetSearcher *searcher =
						RecordsetSearcherFactory::instance()
								->create("deposit_number_bank");

				m_Recordset.installSearcher(searcher);

				if (!m_Recordset.search(value))
					m_Console
						->displayError("Deposito no se encuentra en esta caja o "
								"esta anulado.");

				delete searcher;
			}
		}
	}
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
	connect(m_SaveAction, SIGNAL(triggered()), this, SLOT(saveDeposit()));

	m_DiscardAction = new QAction("Cancelar", this);
	m_DiscardAction->setShortcut(Qt::Key_Escape);
	connect(m_DiscardAction, SIGNAL(triggered()), this, SLOT(discardDocument()));

	m_CancelAction = new QAction("Anular", this);
	m_CancelAction->setShortcut(Qt::Key_F12);
	connect(m_CancelAction, SIGNAL(triggered()), this,
			SLOT(showAuthenticationDialogForCancel()));

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(Qt::Key_Escape);
	connect(m_ExitAction, SIGNAL(triggered()), this, SLOT(unloadSection()));

	m_AddItemAction = new QAction("Agregar efectivo", this);
	m_AddItemAction->setShortcut(tr("Ctrl+I"));
	connect(m_AddItemAction, SIGNAL(triggered()), this, SLOT(addCashDeposit()));

	m_DeleteItemAction = new QAction("Quitar efectivo", this);
	m_DeleteItemAction->setShortcut(tr("Ctrl+D"));
	connect(m_DeleteItemAction, SIGNAL(triggered()), this,
			SLOT(deleteItemDocument()));

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
	connect(m_SearchAction, SIGNAL(triggered()), this, SLOT(searchDeposit()));
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
 * Installs the necessary plugins widgets in the plugin factory of the web view.
 */
void DepositSection::setPlugins()
{
	DocumentSection::setPlugins();

	// Ownership taken by the section in case the widget is never shown.
	m_SlipNumberLineEdit = new LineEditPlugin(this);
	m_SlipNumberLineEdit->hide();
	webPluginFactory()
			->install("application/x-slip_number_line_edit",
					m_SlipNumberLineEdit);

	connect(m_SlipNumberLineEdit, SIGNAL(blurAndChanged(QString)), this,
			SLOT(setNumber(QString)));

	// Ownership taken by the section in case the widget is never shown.
	m_BankAccountComboBox = new ComboBox(this);
	m_BankAccountComboBox->hide();
	webPluginFactory()
			->install("application/x-bank_account_combo_box", m_BankAccountComboBox);
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
			values = "00000000000000";
			break;

		default:;
	}

	m_ActionsManager.updateActions(values);
}

/**
 * Prepare the deposit form for creating a new deposit.
 */
void DepositSection::prepareDocumentForm(QString username)
{
	DocumentSection::prepareDocumentForm(username);

	QWebFrame *frame = ui.webView->page()->mainFrame();
	QWebElement element;

	element = frame->findFirstElement("#document_id");
	element.setInnerXml("");

	// Change div css style from disabled to enabled.
	element = frame->findFirstElement("#details");
	element.removeClass("disabled");
	element.addClass("enabled");

	element = frame->findFirstElement("#slip_number_label span");
	element.removeClass("hidden");

	element = frame->findFirstElement("#slip_number_value");
	element.addClass("hidden");

	element = frame->findFirstElement("#slip_number");
	element.removeClass("hidden");

	element = frame->findFirstElement("#bank_account_label span");
	element.removeClass("hidden");

	element = frame->findFirstElement("#bank_account");
	element.addClass("hidden");

	element = frame->findFirstElement("#bank_account_id");
	element.removeClass("hidden");

	element = frame->findFirstElement("#bank");
	element.setInnerXml("&nbsp;");
}

/**
 * Extends functionality after the event.
 */
void DepositSection::createDocumentEvent(bool ok,
		QList<QMap<QString, QString>*> *list)
{
	if (ok) {
		// Add bank accounts to the combo box.
		QMap<QString, QString> *params = list->at(1);
		m_BankAccountComboBox->addItem("");
		QMapIterator<QString, QString> i(*params);
		while (i.hasNext()) {
			i.next();
			m_BankAccountComboBox->addItem(i.key() + ", " + i.value(), i.key());
		}

		// Has to be here. QComboBox signal fires even programmatically!
		connect(m_BankAccountComboBox, SIGNAL(currentIndexChanged(int)), this,
					SLOT(setBankAccount(int)));

		m_SlipNumberLineEdit->setFocus();
	}
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
