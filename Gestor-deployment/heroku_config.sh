cd ..;
heroku config:set SYMFONY__DATABASE_DRIVER=pdo_mysql;
heroku config:set SYMFONY__DATABASE_HOST=us-cdbr-east.cleardb.com;
heroku config:set SYMFONY__DATABASE_PORT=1521;
heroku config:set SYMFONY__DATABASE_NAME=heroku_2828f8d5aedccd2;
heroku config:set SYMFONY__DATABASE_USER=bffe16f09a19ea;
heroku config:set SYMFONY__DATABASE_PASSWORD=ab052d1d;
heroku config:set SYMFONY__SECRETE=ThisTokenIsNotSoSecretChangeIt;
cd -;