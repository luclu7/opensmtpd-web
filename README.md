# opensmtpd-web

Pour être utilisé avec https://blog.gamindustri.fr/setup-un-serveur-mail-avec-opensmtpd-dovecot-rspamd-et-postgresql-sur-openbsd/

Installes PHP:
```sh
# pkg_add php php-pdo_pgsql
```
Sélectionnez évidemment la dernière version de PHP.

Git clonez dans /var/www/sites/openweb, configuez /etc/httpd.conf comme-ci:
```
ext_if="egress"

types { include "/usr/share/misc/mime.types" }

server "default" {
 listen on $ext_if port 80
 directory {index "index.php" }
 location "/*.php*" {
     root { "/sites/openweb" }
     fastcgi socket "/run/php-fpm.sock"
 }
 location "/*" {
     root { "/sites/openweb" }
 }
}
```

Démarrez PHP-FPM et HTTPD:
```sh
# rcctl -d start httpd php82_fpm
# rcctl -d enable httpd php82_fpm
```

Créez un compte `web` sur postgres et donnez lui accès à la BDD, configurez le mdp dans `includes/connect.php` (par défaut, 'password').

Et ça devrait être bon !

Oui c'est du php dégueulasse mais je suis pas dev php ok?
