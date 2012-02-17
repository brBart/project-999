@ECHO OFF
REM bat to run all tests on data_test folder.
REM Didn't make phing unit tests work :-(

cd agent
php tests.php

cd ..
cd cash
php tests.php

cd ..
cd document
php tests.php

cd ..
cd document_search
php tests.php

cd ..
cd inventory
php tests.php

cd ..
cd list
php tests.php

cd ..
cd product
php tests.php

cd ..
cd user_account
php tests.php

cd ..
cd various
php tests.php

cd ..