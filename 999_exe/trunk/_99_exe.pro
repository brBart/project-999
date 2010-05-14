TEMPLATE = app
TARGET = _99_exe
QT += core \
    gui \
    xml \
    xmlpatterns \
    network \
    webkit
HEADERS += actions_manager/actions_manager.h \
    xml_transformer/invoice_list_xml_transformer.h \
    recordset/recordset.h \
    section/sales_section.h \
    xml_transformer/object_key_xml_transformer.h \
    cash_register_dialog/cash_register_dialog.h \
    plugin_factory/plugin_factory.h \
    registry.h \
    section/section.h \
    section/main_section.h \
    xml_transformer/map_string_xml_transformer.h \
    xml_transformer/xml_transformer.h \
    console/console.h \
    xml_response_handler/xml_response_handler.h \
    http_request/http_request.h \
    mainwindow.h
SOURCES += actions_manager/actions_manager.cpp \
    xml_transformer/invoice_list_xml_transformer.cpp \
    recordset/recordset.cpp \
    section/sales_section.cpp \
    xml_transformer/object_key_xml_transformer.cpp \
    cash_register_dialog/cash_register_dialog.cpp \
    plugin_factory/plugin_factory.cpp \
    registry.cpp \
    section/section.cpp \
    section/main_section.cpp \
    xml_transformer/map_string_xml_transformer.cpp \
    console/console.cpp \
    xml_response_handler/xml_response_handler.cpp \
    http_request/http_request.cpp \
    main.cpp \
    mainwindow.cpp
FORMS += recordset/recordset.ui \
    cash_register_dialog/cash_register_dialog.ui \
    section/section.ui \
    mainwindow.ui
RESOURCES += resources.qrc
TRANSLATIONS = qt_es.ts
