Emergency block
===============

This module provides a block that displays only when a separate configuration
form is set.  That allows selected users to be able to display it during an
emergency situation without giving them access to the block configuration or
layout itself.

Additionally, the block may also be triggered by a temperature threshold, ie,
when it's below a certain temperature.  If enabled, the site will poll
OpenWeatherMaps.com for data on cron and show a separate message should it be
below some safe temperature threshold.