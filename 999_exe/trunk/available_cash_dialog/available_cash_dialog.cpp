#include "available_cash_dialog.h"

#include <QRadioButton>
#include <QSignalMapper>
#include "../console/console_factory.h"
#include "../enter_key_event_filter/enter_key_event_filter.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class AvailableCashDialog
 * Dialog for displaying and adding available cash from the cash receipts to a
 * deposit document on the server.
 */

/**
 * Constructs the dialog.
 */
AvailableCashDialog::AvailableCashDialog(QNetworkCookieJar *jar, QUrl *url,
		QString cashRegisterKey, QString depositKey, QWidget *parent,
		Qt::WindowFlags f) : QDialog(parent, f), m_ServerUrl(url),
		m_CashRegisterKey(cashRegisterKey), m_DepositKey(depositKey)
{
	ui.setupUi(this);

	EnterKeyEventFilter *filter = new EnterKeyEventFilter(this);
	ui.okPushButton->installEventFilter(filter);
	ui.cancelPushButton->installEventFilter(filter);

	setConsole();

	QStringList headers;
	headers << "" << "Recibo No." << "Factura" << "Total efectivo" << "Disponible";
	ui.availableCashReceiptTreeWidget->setColumnCount(5);
	ui.availableCashReceiptTreeWidget->setHeaderLabels(headers);

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(ui.availableCashReceiptTreeWidget,
			SIGNAL(itemActivated(QTreeWidgetItem*, int)), this,
			SLOT(selectRadioButton(QTreeWidgetItem*, int)));
	connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(addCashDeposit()));
}

/**
 * Destroys the console object.
 */
AvailableCashDialog::~AvailableCashDialog()
{
	delete m_Console;
}

/**
 * Populates the the cash list with all the cash receipts available.
 */
void AvailableCashDialog::init()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_available_cash_receipt_list");
	url.addQueryItem("key", m_CashRegisterKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("available_cash_receipt_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {

		populateList(transformer->content());

	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Sets the receipt id to the selected id from the tree widget.
 */
void AvailableCashDialog::setCashReceiptId(const QString id)
{
	m_CashReceiptId = id;
}

/**
 * Obtains the radio button on the activated item and fires its clicked signal.
 */
void AvailableCashDialog::selectRadioButton(QTreeWidgetItem *item, int column)
{
	QRadioButton *button =
			dynamic_cast<QRadioButton*>(ui.availableCashReceiptTreeWidget->
					itemWidget(item, 0));
	button->click();
}

/**
 * Adds cash to the deposit on the server.
 */
void AvailableCashDialog::addCashDeposit()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "add_cash_deposit");
	url.addQueryItem("cash_receipt_id", m_CashReceiptId);
	url.addQueryItem("amount", ui.amountLineEdit->text());
	url.addQueryItem("deposit_key", m_DepositKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()->create("stub");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response =
			m_Handler->handle(content, transformer, &errorMsg, &elementId);

	if (response == XmlResponseHandler::Success) {
		accept();
	} else if (response == XmlResponseHandler::Failure) {
		m_Console->reset();
		m_Console->displayFailure(errorMsg, elementId);
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Sets the Console object.
 */
void AvailableCashDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("cash_receipt_id", ui.cashReceiptIdFailedLabel);
	elements.insert("amount", ui.amountFailedLabel);

	m_Console = ConsoleFactory::instance()->createWidgetConsole(elements);
	m_Console->setFrame(ui.webView->page()->mainFrame());
}

/**
 * Populates the tree widget with the cash receipt available list.
 */
void AvailableCashDialog::populateList(QList<QMap<QString, QString>*> list)
{
	QTreeWidgetItem *item;
	QRadioButton *button;

	QSignalMapper *mapper = new QSignalMapper(this);

	connect(mapper, SIGNAL(mapped(const QString)), this,
			SLOT(setCashReceiptId(const QString)));

	for (int i = 0; i < list.size(); i++) {
		item = new QTreeWidgetItem(ui.availableCashReceiptTreeWidget);
		item->setText(1, list.at(i)->value("id"));
		item->setText(2, list.at(i)->value("serial_number") + "-" +
				list.at(i)->value("number"));
		item->setText(3, list.at(i)->value("received_cash"));
		item->setText(4, list.at(i)->value("available_cash"));

		ui.availableCashReceiptTreeWidget->addTopLevelItem(item);

		button = new QRadioButton(ui.availableCashReceiptTreeWidget);
		button->setFocusPolicy(Qt::NoFocus);

		connect(button, SIGNAL(clicked()), mapper, SLOT(map()));

		mapper->setMapping(button, list.at(i)->value("id"));

		ui.availableCashReceiptTreeWidget->setItemWidget(item, 0, button);
	}
}
