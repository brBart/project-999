#include "cash_register_dialog.h"

#include "../xml_transformer/shift_list_xml_transformer.h"
#include "../xml_transformer/object_key_xml_transformer.h"

/**
 * @class CashRegisterDialog
 * Dialog used to obtain a cash register object key from the server for future
 * transactions.
 */

/**
 * Constructs the dialog.
 */
CashRegisterDialog::CashRegisterDialog(QNetworkAccessManager *manager, QUrl *url,
		QWidget *parent) : QDialog(parent), m_ServerUrl(url)
{
	ui.setupUi(this);

	ui.webView->setHtml("<div id=\"console\" style=\"font-size: 10px; color: red;\">"
			"</div>");
	m_Console.setFrame(ui.webView->page()->mainFrame());
	m_Request = new HttpRequest(manager, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(ui.okPushButton, SIGNAL(clicked()), this, SLOT(fetchKey()));
}

/**
 * Populates the combo box with a list of all the shifts available.
 */
void CashRegisterDialog::init()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_shift_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	QString errorMsg;
	ShiftListXmlTransformer *transformer = new ShiftListXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		ui.shiftsComboBox->addItem("", "");
		QMap<QString, QString> *shift;
		for (int i = 0; i < list.size(); i++) {
			shift = list[i];
			ui.shiftsComboBox->addItem(shift->value("name"),
					shift->value("shift_id"));
		}
	} else {
		m_Console.displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Fetchs for the cash register object key on the server.
 */
void CashRegisterDialog::fetchKey()
{
	QVariant id = ui.shiftsComboBox->itemData(ui.shiftsComboBox->currentIndex());

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_cash_register");
	url.addQueryItem("shift_id", id.toString());
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	QString errorMsg;
	ObjectKeyXmlTransformer *transformer = new ObjectKeyXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		m_Key = params->value("key");
		accept();
	} else {
		m_Console.displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Returns the cash register object key.
 */
QString CashRegisterDialog::key()
{
	return m_Key;
}
