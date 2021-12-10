# Sharesimple 
Eine website, mithilfe der Dateien auf den Server hochgeladen werden können.

# Maximale Dateigröße einstellen
In der PHP-Konfigurationsdatei (php.ini) unter den Werten  
**post_max_size** und **upload_max_filesize** den Maximalwert eintragen.  

In der datei upload.php musst du die Dateigröße in der Variable **$max_size** in Bytes eintragen.

<details>
<summary>Beispiel</summary>
php.ini:  

- post_max_size=1.5G  
- upload_max_filesize=1.5G  

upload.php:  
- $max_size = 1610612736;
</details>

<br>  

# Lösch-skript
Das Skript, welches die Dateien löscht, die nicht mehr gebraucht werden.  
Um dieses skript zu nutzen musst du einmalig in dem Verzeichnis, den folgenden Befehl ausführen:
```nodejs
npm i
```
Anschließend kannst du das Skript jederzeit mit folgendem Befehl starten:
```nodejs
node app.js
```