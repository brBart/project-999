TEMPLATE = app
TARGET = _99_exe
QT += core \
    gui \
    xml \
    xmlpatterns \
    network \
    webkit
HEADERS += plugins/bar_code_line_edit.h \
    plugins/plugin_factory.h \
    plugins/plugin_widget.h \
    xml_transformer/xml_transformer_factory.h \
    enter_key_event_filter/enter_key_event_filter.h \
    xml_transformer/invoice_customer_xml_transformer.h \
    xml_transformer/customer_xml_transformer.h \
    line_edit/line_edit.h \
    customer_dialog/customer_state.h \
    customer_dialog/fetched_customer_state.h \
    customer_dialog/not_fetched_customer_state.h \
    customer_dialog/customer_dialog.h \
    console/console_factory.h \
    console/widget_console.h \
    console/html_console.h \
    xml_transformer/stub_xml_transformer.h \
    xml_transformer/shift_list_xml_transformer.h \
    xml_transformer/cash_register_status_xml_transformer.h \
    xml_transformer/invoice_xml_transformer.h \
    actions_manager/actions_manager.h \
    xml_transformer/invoice_list_xml_transformer.h \
    recordset/recordset.h \
    section/sales_section.h \
    xml_transformer/object_key_xml_transformer.h \
    cash_register_dialog/cash_register_dialog.h \
    registry.h \
    section/section.h \
    section/main_section.h \
    xml_transformer/xml_transformer.h \
    console/console.h \
    xml_response_handler/xml_response_handler.h \
    http_request/http_request.h \
    mainwindow.h
SOURCES += plugins/bar_code_line_edit.cpp \
    plugins/plugin_factory.cpp \
	xml_transformer/xml_transformer_factory.cpp \
    enter_key_event_filter/enter_key_event_filter.cpp \
    xml_transformer/invoice_customer_xml_transformer.cpp \
    xml_transformer/customer_xml_transformer.cpp \
    line_edit/line_edit.cpp \
    customer_dialog/customer_state.cpp \
    customer_dialog/fetched_customer_state.cpp \
    customer_dialog/not_fetched_customer_state.cpp \
    customer_dialog/customer_dialog.cpp \
    console/console_factory.cpp \
    console/widget_console.cpp \
    console/html_console.cpp \
    xml_transformer/stub_xml_transformer.cpp \
    xml_transformer/shift_list_xml_transformer.cpp \
    xml_transformer/cash_register_status_xml_transformer.cpp \
    xml_transformer/invoice_xml_transformer.cpp \
    xml_transformer/xml_transformer.cpp \
    actions_manager/actions_manager.cpp \
    xml_transformer/invoice_list_xml_transformer.cpp \
    recordset/recordset.cpp \
    section/sales_section.cpp \
    xml_transformer/object_key_xml_transformer.cpp \
    cash_register_dialog/cash_register_dialog.cpp \
    registry.cpp \
    section/section.cpp \
    section/main_section.cpp \
    console/console.cpp \
    xml_response_handler/xml_response_handler.cpp \
    http_request/http_request.cpp \
    main.cpp \
    mainwindow.cpp
FORMS += customer_dialog/customer_dialog.ui \
    recordset/recordset.ui \
    cash_register_dialog/cash_register_dialog.ui \
    section/section.ui \
    mainwindow.ui
RESOURCES += resources.qrc
TRANSLATIONS = qt_es.ts
