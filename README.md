Configuración de Dominio Local en XAMPP
Sigue estos pasos para configurar un dominio local en tu entorno de desarrollo con XAMPP:

1. Configurar Virtual Host en Apache
Abre el archivo de configuración de hosts virtuales de Apache:
C:\xampp\apache\conf\extra\httpd-vhosts.conf
Agrega el siguiente bloque al final del archivo:

<VirtualHost *:80>
    ServerName prueba.test
    DocumentRoot "C:/xampp/htdocs/Ejercicio/public"
    <Directory "C:/xampp/htdocs/Ejercicio/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

2. Editar el archivo hosts de Windows
Edita el archivo hosts ubicado en:
C:\Windows\System32\drivers\etc\hosts
Agrega la siguiente línea al final del archivo:

127.0.0.1    prueba.test
📝 Es posible que necesites permisos de administrador para editar este archivo.

3. Reiniciar Apache
Abre el panel de control de XAMPP y reinicia el servicio de Apache para aplicar los cambios