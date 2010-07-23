#include "voucher_dialog.h"

#include <QUrl>
#include "../console/console_factory.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class VoucherDialog
 * Dialog for adding a voucher to cash receipt on the server.
 */

/**
 * Constructs the dialog.
 */
VoucherDialog::VoucherDialog(QNetworkCookieJar *jar, QUrl *url,
		QString cashReceiptKey, QString invoiceKey, QWidget *parent,
		Qt::WindowFlags f) : QDialog(parent, f), m_ServerUrl(url),
		m_CashReceiptKey(cashReceiptKey), m_InvoiceKey(invoiceKey)
{
	ui.setupUi(this);

	setConsole();

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(ui.okPushButton, SIGNAL(clicked()), this,
			SLOT(addVoucherCashReceipt()));
}

/**
 * Destroys the console object.
 */
VoucherDialog::~VoucherDialog()
{
	delete m_Console;
}

/**
 * Populates the combo boxes with data from the server.
 */
void VoucherDialog::init()
{
	fetchTypes();
	fetchBrands();
}

/**
 * Adds a voucher to the cash receipt on the server.
 */
void VoucherDialog::addVoucherCashReceipt()
{
	QVariant id;

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "add_voucher_cash_receipt");
	url.addQueryItem("cash_receipt_key", m_CashReceiptKey);
	url.addQueryItem("invoice_key", m_InvoiceKey);
	url.addQueryItem("transaction_number", ui.transactionNumberLineEdit->text());
	url.addQueryItem("payment_card_number", ui.paymentCardNumberLineEdit->text());

	id = ui.paymentCardTypeIdComboBox
			->itemData(ui.paymentCardTypeIdComboBox->currentIndex());
	url.addQueryItem("payment_card_type_id", id.toString());

	id = ui.paymentCardBrandIdComboBox
			->itemData(ui.paymentCardBrandIdComboBox->currentIndex());
	url.addQueryItem("payment_card_brand_id", id.toString());

	url.addQueryItem("holder_name", ui.holderNameLineEdit->text());
	url.addQueryItem("expiration_date", ui.expirationDateLineEdit->text());
	url.addQueryItem("amount", ui.amountLineEdit->text());
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response =
			m_Handler->handle(content, transformer, &errorMsg, &elementId);
	if (response == XmlResponseHandler::Success) {
		accept();
	} else if (response == XmlResponseHandler::Failure) {
		m_Console->reset();
		m_Console->displayFailure(errorMsg, elementId);

		m_FocusWidgets.value(elementId)->setFocus();

		QLineEdit *lineEdit =
				dynamic_cast<QLineEdit*>(m_FocusWidgets.value(elementId));
		if (lineEdit != 0)
			lineEdit->selectAll();
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Sets the Console object.
 */
void VoucherDialog::setConsole()
{
	QMap<QString, QLabel*> elements;
	elements.insert("transaction_number", ui.transactionNumberFailedLabel);
	elements.insert("payment_card_number", ui.paymentCardNumberFailedLabel);
	elements.insert("payment_card_type_id", ui.paymentCardTypeIdFailedLabel);
	elements.insert("payment_card_brand_id", ui.paymentCardBrandIdFailedLabel);
	elements.insert("holder_name", ui.holderNameFailedLabel);
	elements.insert("expiration_date", ui.expirationDateFailedLabel);
	elements.insert("amount", ui.amountFailedLabel);

	m_FocusWidgets.insert("transaction_number", ui.transactionNumberLineEdit);
	m_FocusWidgets.insert("payment_card_number", ui.paymentCardNumberLineEdit);
	m_FocusWidgets.insert("payment_card_type_id", ui.paymentCardTypeIdComboBox);
	m_FocusWidgets.insert("payment_card_brand_id", ui.paymentCardBrandIdComboBox);
	m_FocusWidgets.insert("holder_name", ui.holderNameLineEdit);
	m_FocusWidgets.insert("expiration_date", ui.expirationDateLineEdit);
	m_FocusWidgets.insert("amount", ui.amountLineEdit);

	m_Console = ConsoleFactory::instance()->createWidgetConsole(elements);
	m_Console->setFrame(ui.webView->page()->mainFrame());
}

/**
 * Populates the types combo box with data from the server.
 */
void VoucherDialog::fetchTypes()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_payment_card_type_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("payment_card_type_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		ui.paymentCardTypeIdComboBox->addItem("", "");
		QMap<QString, QString> *type;
		for (int i = 0; i < list.size(); i++) {
			type = list[i];
			ui.paymentCardTypeIdComboBox->addItem(type->value("name"),
					type->value("payment_card_type_id"));
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Populates the brands combo box with data from the server.
 */
void VoucherDialog::fetchBrands()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_payment_card_brand_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("payment_card_brand_list");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		ui.paymentCardBrandIdComboBox->addItem("", "");
		QMap<QString, QString> *brand;
		for (int i = 0; i < list.size(); i++) {
			brand = list[i];
			ui.paymentCardBrandIdComboBox->addItem(brand->value("name"),
					brand->value("payment_card_brand_id"));
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}
