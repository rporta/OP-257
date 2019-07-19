# A continuación se explica el uso del archivo getInfoCampings.php :

getInfoCampings.php, tiene el objetivo ser ejecutado cada n perido de tiempo por un script en Bash, donde el script de Bash es programado a ejecurase por el servicio Cron, 

El script, en funcion de su configuracion realiza una consuta y obtine informacion para actualizar un reporte en Omanager.

##Parametros 

Puede recibir 1 parametro para consultar una fecha en particular, el formato de la fecha debe tener la el siguiente formato YYYYMMDD, de no definir el parametro el script lo definira por usted.

Un ejemplo de ejecucion pasando el parametro con la fecha '2019-07-15' seria el siguiente:

php getInfoCampings.php 20190715


### NOTA BENJA :

evitemos N crones, mas bien veamos la manera de cronear 1 solo script y que este itere para traerse la info de cada campana.