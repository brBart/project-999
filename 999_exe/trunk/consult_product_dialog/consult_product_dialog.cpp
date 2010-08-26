#include "consult_product_dialog.h"

#include <QSignalMapper>
#include "../console/console_factory.h"
#include "../enter_key_event_filter/enter_key_event_filter.h"

/**
 * @class ConsultProductDialog
 * Dialog for entering the product bar code, id or name for consult purposes.
 */

/**
 * Constructs the dialog.
 */
ConsultProductDialog::ConsultProductDialog(QNetworkCookieJar *jar, QUrl *url,
		SearchProductModel *model, QWidget *parent, Qt::WindowFlags f)
    : QDialog(parent, f), m_Jar(jar), m_ServerUrl(url)
{
	ui.setupUi(this);

	m_Console = ConsoleFactory::instance()
				->createWidgetConsole(QMap<QString, QLabel*>());
	m_Console->setFrame(ui.webView->page()->mainFrame());

	ui.nameSearchProductLineEdit->init(jar, url, m_Console, model);

	EnterKeyEventFilter *filter = new EnterKeyEventFilter(this);
	ui.barCodePushButton->installEventFilter(filter);
	ui.idPushButton->installEventFilter(filter);
	ui.cancelPushButton->installEventFilter(filter);

	QSignalMapper *mapper = new QSignalMapper(this);
	mapper->setMapping(ui.barCodePushButton, 0);
	mapper->setMapping(ui.idPushButton, 1);
	mapper->setMapping(ui.nameSearchProductLineEdit, 2);

	connect(ui.barCodePushButton, SIGNAL(clicked()), mapper, SLOT(map()));
	connect(ui.idPushButton, SIGNAL(clicked()), mapper, SLOT(map()));
	connect(ui.nameSearchProductLineEdit, SIGNAL(activated()), mapper, SLOT(map()));

	connect(ui.nameSearchProductLineEdit, SIGNAL(sessionStatusChanged(bool)), this,
				SIGNAL(sessionStatusChanged(bool)));
	connect(mapper, SIGNAL(mapped(int)), this, SLOT(search(int)));
}

/**
 * Destroys the console object.
 */
ConsultProductDialog::~ConsultProductDialog()
{
	delete m_Console;
}

/**
 * Gets the product bar code or id depending on which widget sends the signal.
 */
void ConsultProductDialog::search(int id)
{
	switch (id) {
	case 0:
		fetchProduct(ui.barCodeLineEdit->text());
		break;

	case 1:
		fetchProduct(ui.idLineEdit->text(), false);
		break;

	case 2:
		fetchProduct(ui.nameSearchProductLineEdit->barCode());
		break;

	default:;
	}
}

/**
 * Fetches the product data from the server.
 */
void ConsultProductDialog::fetchProduct(QString value, bool isBarCode)
{
	if (value != "") {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd",
				isBarCode ? "show_product_by_bar_code" : "show_product_by_id");
		if (isBarCode) {
			url.addQueryItem("bar_code", value);
		} else {
			url.addQueryItem("id", value);
		}

		QDialog dialog(this, Qt::WindowTitleHint);
		dialog.setWindowTitle("Producto");
		QHBoxLayout *layout = new QHBoxLayout(&dialog);

		QWebView view;
		view.page()->networkAccessManager()->setCookieJar(m_Jar);
		m_Jar->setParent(0);
		view.load(url);

		layout->addWidget(&view);
		dialog.exec();
	}
}
