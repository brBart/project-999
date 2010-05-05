#include "cash_register_dialog.h"

#include "../xml_transformer/map_string_xml_transformer.h"

CashRegisterDialog::CashRegisterDialog(QNetworkAccessManager *manager, QUrl *url,
		QWidget *parent) : QDialog(parent), m_ServerUrl(url)
{
	ui.setupUi(this);

	ui.webView->setHtml("<div id=\"console\" style=\"color: red;\"></div>");
	m_Console = new Console(ui.webView->page()->mainFrame());

	m_Request = new HttpRequest(manager, this);

	m_Handler = new XmlResponseHandler(this);

	loadShifts();
}

CashRegisterDialog::~CashRegisterDialog()
{
	delete m_Console;
}

void CashRegisterDialog::loadShifts()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_shift_list");
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	QString errorMsg;
	MapStringXmlTransformer *transformer = new MapStringXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->list();

		ui.shiftsComboBox->addItem("", "");
		QMap<QString, QString> *shift;
		for (int i = 0; i < list.size(); i++) {
			shift = list[i];
			ui.shiftsComboBox->addItem(shift->value("name"),
					shift->value("shift_id"));
		}
	} else {
		m_Console->displayError(errorMsg);
	}
}
