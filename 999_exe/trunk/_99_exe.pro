TEMPLATE = app
TARGET = _99_exe
QT += core \
    gui \
    xml \
    xmlpatterns \
    network \
    webkit
HEADERS += cash_register_dialog/cash_register_dialog.h \
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
SOURCES += cash_register_dialog/cash_register_dialog.cpp \
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
FORMS += cash_register_dialog/cash_register_dialog.ui \
    section/section.ui \
    mainwindow.ui
RESOURCES += resources.qrc
