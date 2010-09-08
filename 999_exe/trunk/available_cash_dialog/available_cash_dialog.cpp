#include "available_cash_dialog.h"

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
		QString cashRegisterKey, QWidget *parent, Qt::WindowFlags f)
    : QDialog(parent, f), m_ServerUrl(url), m_CashRegisterKey(cashRegisterKey)
{
	ui.setupUi(this);

	EnterKeyEventFilter *filter = new EnterKeyEventFilter(this);
	ui.okPushButton->installEventFilter(filter);
	ui.cancelPushButton->installEventFilter(filter);

	setConsole();

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
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
 * Sets the Console object.
 */
void AvailableCashDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("cash_receipt", ui.cashReceiptFailedLabel);
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

	for (int i = 0; i < list.size(); i++) {
		item = new QTreeWidgetItem(ui.availableCashReceiptTreeWidget);
		item->setText(1, list.at(i)->value("id"));
		item->setText(2, list.at(i)->value("serial_number") + " " +
				list.at(i)->value("number"));
		item->setText(3, list.at(i)->value("received_cash"));
		item->setText(4, list.at(i)->value("available_cash"));

		ui.availableCashReceiptTreeWidget->addTopLevelItem(item);
	}
}
