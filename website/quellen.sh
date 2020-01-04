rm -rf lib
mkdir lib
cd lib
mkdir leaflet
cd leaflet
wget http://cdn.leafletjs.com/leaflet/v1.6.0/leaflet.zip
unzip leaflet.zip leaflet.js leaflet.css images/*
rm leaflet.zip
cd ..
mkdir jquery
cd jquery
wget https://code.jquery.com/jquery-3.4.1.min.js
cd ..
mkdir jquery-ui
cd jquery-ui
wget https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js
wget https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css
cd ..
mkdir leaflet-locator
cd leaflet-locator
wget https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.68.0/dist/L.Control.Locate.min.css
wget https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.68.0/dist/L.Control.Locate.min.js
cd ..
