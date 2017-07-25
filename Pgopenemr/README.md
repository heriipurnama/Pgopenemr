=======================================================
  openEMR versi databases postgresql
=======================================================

**Dependencies : 
   Postgresql  : versi 9.2,
   web server  : apache2

**Taruh Direktory Pgopenemr di path :
   /var/www/html

**Import databases postgres, postgres.sql :
   /Pgopenemr/databasesPostgres
  psql -U USERNAME DBNAME < postgres.sql

** User dan passwd Login :
   User  : akmar          user  : admin
   paswd : 123456         paswd : manager247

** sesuaikan koneksi dari databases ke openemr di postgres di pgsqlconf.php path :
   /Pgopenemr/sites/default/pgsqlconf.php
