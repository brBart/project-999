TEMPLATE = app
TARGET = _99_exe
CONFIG += console
QT += core \
    gui \
    xml \
    xmlpatterns \
    network \
    webkit
HEADERS += plugins/line_edit_plugin.h \
    section/deposit_section.h \
    section/document_section.h \
    consult_product_dialog/consult_product_dialog.h \
    search_invoice_dialog/search_invoice_dialog.h \
    recordset/invoice_recordset_searcher.h \
    recordset/recordset_searcher.h \
    search_product/search_product_model.h \
    search_product/search_product_line_edit.h \
    xml_transformer/search_product_results_xml_transformer.h \
    main_window.h \
    search_product_dialog/search_product_dialog.h \
    product_quantity_dialog/product_quantity_dialog.h \
    printer_status_handler/printer_status_handler.h \
    printer_status_handler/StatusAPI.h \
    printer_status_handler/EpsStmApi.h \
    plugins/label.h \
    xml_transformer/object_id_xml_transformer.h \
    xml_transformer/total_xml_transformer.h \
    xml_transformer/payment_card_brand_list_xml_transformer.h \
    xml_transformer/payment_card_type_list_xml_transformer.h \
    voucher_dialog/voucher_dialog.h \
    xml_transformer/change_xml_transformer.h \
    section/cash_receipt_section.h \
    discount_dialog/discount_dialog.h \
    authentication_dialog/authentication_dialog.h \
    plugins/web_plugin_factory.h \
    plugins/bar_code_line_edit.h \
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
    http_request/http_request.h
SOURCES += plugins/line_edit_plugin.cpp \
    section/deposit_section.cpp \
    section/document_section.cpp \
    consult_product_dialog/consult_product_dialog.cpp \
    search_invoice_dialog/search_invoice_dialog.cpp \
    recordset/recordset_searcher.cpp \
    recordset/invoice_recordset_searcher.cpp \
    search_product/search_product_model.cpp \
    search_product/search_product_line_edit.cpp \
    xml_transformer/search_product_results_xml_transformer.cpp \
    main_window.cpp \
    search_product_dialog/search_product_dialog.cpp \
    product_quantity_dialog/product_quantity_dialog.cpp \
    printer_status_handler/printer_status_handler.cpp \
    printer_status_handler/StatusAPI.cpp \
    plugins/label.cpp \
    xml_transformer/object_id_xml_transformer.cpp \
    xml_transformer/total_xml_transformer.cpp \
    xml_transformer/payment_card_brand_list_xml_transformer.cpp \
    xml_transformer/payment_card_type_list_xml_transformer.cpp \
    voucher_dialog/voucher_dialog.cpp \
    xml_transformer/change_xml_transformer.cpp \
    section/cash_receipt_section.cpp \
    discount_dialog/discount_dialog.cpp \
    authentication_dialog/authentication_dialog.cpp \
    plugins/web_plugin_factory.cpp \
    plugins/bar_code_line_edit.cpp \
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
    main.cpp
FORMS += consult_product_dialog/consult_product_dialog.ui \
    search_invoice_dialog/search_invoice_dialog.ui \
    search_product_dialog/search_product_dialog.ui \
    product_quantity_dialog/product_quantity_dialog.ui \
    voucher_dialog/voucher_dialog.ui \
    discount_dialog/discount_dialog.ui \
    authentication_dialog/authentication_dialog.ui \
    customer_dialog/customer_dialog.ui \
    cash_register_dialog/cash_register_dialog.ui \
    section/section.ui \
    mainwindow.ui
RESOURCES += resources.qrc
TRANSLATIONS = qt_es.ts
