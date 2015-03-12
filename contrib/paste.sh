#!/bin/sh
#
#  paste.sh
#
#  pastes the stdin to your favorite paste
#

BASE_URL='http://paste.bytespeicher.org'
PASTE_URL="$BASE_URL/add"

if [ "$1" == "--hidden" ]
then
	LOCATION=$(curl -v --user-agent "paste.sh" -XPOST \
		--data "brobdingnagian=" --data-urlencode "text@-" \
		--data "hidden=1" "$PASTE_URL" 2>&1 | \
		grep -i "location" | awk '{print $3}')
else
	LOCATION=$(curl -v --user-agent "paste.sh" -XPOST \
		--data "brobdingnagian=" --data-urlencode "text@-" \
		"$PASTE_URL" 2>&1 | grep -i "location" | awk '{print $3}')
fi

echo "Your paste is at $BASE_URL$LOCATION"
