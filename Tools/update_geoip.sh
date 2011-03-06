#!/bin/bash

# this file updates the local geoip lite files

# Example crontab entry:
# m     h   dom mon dow command
# 30    3   2   *   *   /bin/bash /root/scripts/update_geoip.sh

# set the location of the geoip data files. this ist the prefered default
# which is also used in the online examples at
# http://geolite.maxmind.com/download/geoip/api/
GEOIPDIR="/usr/local/share/GeoIP"

# create the folder if it not exists
mkdir -p $GEOIPDIR

# get the actual GeoLiteCity.dat.gz file from maxmind.com (creators of geoip)
wget --quiet http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz -O /tmp/newGeoCity.dat.gz && \
gunzip -c /tmp/newGeoCity.dat.gz > $GEOIPDIR/GeoIPCity.dat && \
rm /tmp/newGeoCity.dat.gz

# get the actual GeoLiteCountry/GeoIP.dat.gz file from maxmind.com (creators of geoip)
wget --quiet http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz -O /tmp/newGeoIP.dat.gz && \
gunzip -c /tmp/newGeoIP.dat.gz > $GEOIPDIR/GeoIP.dat && \
rm /tmp/newGeoIP.dat.gz
