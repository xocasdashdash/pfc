#!/bin/bash   
APACHE_CONF_DIR=/etc/apache2
#Incluir hosts en el nuevo archivo
#Modificar gestor.conf para que cambie el directorio de destino
cat gestorapacheconfig.conf | sed 's?$DIR_APACHE_WWW?'`pwd`/..'?' > gestor.conf
#Copia el gestor.conf al sites-available de apache2
cp gestor.conf gestor
sudo cp gestor $APACHE_CONF_DIR/sites-available;
sudo cp gestor.conf $APACHE_CONF_DIR/sites-available;
#Habilitar el site
sudo a2dissite gestor*;
sudo a2ensite gestor*;  
#Reload apache2
sudo service apache2 reload;
#Lanzar composer install
cd ..;
sudo rm -rf app/dev/cache*;
if [ ! -f "../composer.phar" ]; then
    curl -s https://getcomposer.org/installer | php -- --install-dir=..
else
    php ../composer.phar self-update
fi
php ../composer.phar install
#Arreglo la cache
chmod -R 0777 app/dev/cache
chmod -R 0777 app/dev/logs

#Genero el script de la bd
./app/console doctrine:schema:create --dump-sql|sed 's?FK_?'UAH_GAT_FK_'?' |sed 's?IDX_?'UAH_GAT_IDX_'?' > script_creacion.sql 
cd -