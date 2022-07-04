<h1>Raspberry Pi-Wetterstation</h1>

<p>Dieses Repository ermöglicht es, lokale Wetterdaten direkt innnerhalb des lokalen Netzwerkes abzurufen</p>
<p>Hierbei werden mittels der Sensoren BMP180 und DHT11
<ul>
    <li>Lufttemperatur</li>
    <li>Luftdruck</li>
    <li>Luftfeuchtigkeit</li>
    <li>Höhe</li>
</ul>
<p>Erfasst.</p>
<h2>Installation</h2>
<h3>Automatische Installation</h3>
    <code>sudo ./install</code>
<h3>Manuelle Installation</h3>
<p>Dieses Repository stützt sich auf eine Vielzahl von Python-Bibliotheken. Zur Installation werden Root-Zugriffrechte benöigt. </p>
<h4>Python</h4>
    <code>apt install python3-pip -y</code><br>
    <code>pip3 install --user Adafruit-BMP</code><br>
    <code>sudo pip3 install adafruit-circuitpython-dht</code><br>
    <code>sudo apt-get install libgpiod2</code>
<h4>Webserver</h4>
<h5>Nginx</h5>

<h5>PHP</h5>
    <code>sudo apt-get install nginx -y</code>
<h5>MariaDB</h5>
    <code>ysudo apt-get install mariadb-server mariadb-client -y</code>
    <code>sudo apt-get install php7.4 php7.4-fpm php7.4-mysql -y</code>
    
