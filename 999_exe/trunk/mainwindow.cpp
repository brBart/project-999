#include "mainwindow.h"

#include <QMessageBox>
#include <QUrl>
#include <QCloseEvent>
#include "registry.h"
#include "section/main_section.h"
#include "cash_register_dialog/cash_register_dialog.h"
#include "section/sales_section.h"

/**
 * @class MainWindow
 * Class which handles when and how to display the system sections.
 */

/**
 * Constructs a MainWindow.
 */
MainWindow::MainWindow(QWidget *parent)
    : QMainWindow(parent)
{
	ui.setupUi(this);

	QWebSettings::globalSettings()->
			setAttribute(QWebSettings::PluginsEnabled, true);

	m_IsSessionActive = false;
	m_ServerUrl = Registry::instance()->serverUrl();
	loadMainSection();
}

/**
 * Sets the isSessionActive property to the isActive boolean value.
 * The MainWindow will not close if the session still active.
 */
void MainWindow::setIsSessionActive(bool isActive)
{
	m_IsSessionActive = isActive;
}

/**
 * Loads the MainSection.
 */
void MainWindow::loadMainSection()
{
	menuBar()->clear();

	MainSection *section =
			new MainSection(&m_Manager, &m_PluginFactory, m_ServerUrl, this);

	connect(section, SIGNAL(workingDayKeyReceived(QString)), this,
			SLOT(setWorkingDayKey(QString)));

	setSection(section);
}

/**
 * Loads the SalesSection.
 */
void MainWindow::loadSalesSection()
{
	CashRegisterDialog dialog(&m_Manager, m_ServerUrl, this);

	connect(&dialog, SIGNAL(sessionStatusChanged(bool)), this,
			SLOT(setIsSessionActive(bool)), Qt::QueuedConnection);

	dialog.init();
	if (dialog.exec() == QDialog::Accepted) {
		setSection(new SalesSection(&m_Manager, &m_PluginFactory, m_ServerUrl,
				dialog.key(), this));
	}
}

/**
 * Override closeEvent method for avoiding closing the MainWindow if the session
 * still active.
 */
void MainWindow::closeEvent(QCloseEvent *event)
{
	if (!m_IsSessionActive) {
		event->accept();
	} else {
		QMessageBox::information(this, "Sesión Activa", "La sesión aun esta "
				"activa, favor de desloguearse del sistema para poder salir.");
		event->ignore();
	}
}

/**
 * Sets the section as the central widget of the MainWindow.
 */
void MainWindow::setSection(Section *section)
{
	connect(section, SIGNAL(sessionStatusChanged(bool)), this,
				SLOT(setIsSessionActive(bool)));

	setCentralWidget(section);
}
