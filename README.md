# AdTrails UTM Grabber

Contributors: upworksanjeev

Tags: UTM, GCLID, Contact Form 7, Gravity Form 

Requires at least: 5.0

Tested up to: 5.3

Requires PHP: 7.2

Stable tag: 4.3

License: GPLv2 or later

License URI: https://www.gnu.org/licenses/gpl-2.0.html

AdTrails UTM Grabber for CF7

## Description

AdTrails UTM Grabber for CF7 will push UTM data into your form submissions for ROI purposes. Are you looking for Gravity Forms, WP Forms, and Ninja Forms compatability? We offer a premium version of the plugin at AdTrails.com.

## Installation

1) Download the plugin, unzip it and move the unzipped folder to the “wp-content/plugins” directory in your WordPress installation.
2) In your admin panel, go to Plugins and you’ll find AdTrails UTM Grabber in the plugins section.
3) Click on the ‘Activate’ button to use your new plugin right away.

## Requirments

# available shortcodes

````
[utm_source]
[utm_medium]
[utm_term]
[utm_content]
[utm_campaign]
[gclid]
```

These shortcodes will fetch the UTM parameters values from cookies.

## Developer's Guide

### overwriting views

You can overwrite the view file with Pro Plugin. In plgin's root and place the files in views folder structure.

### Plugin Filters

Filter are used to overwrite some outputes through your theme file. AdTrails UTM Grabber plugin use a couple of filters which can be extends into theme. Below is the list of filters you can use.

```
ad_utmgrabber_wpforms
ad_utmgrabber_gravity
ad_utmgrabber_ninja
ad_utmgrabber_zapier
```

## Changelog


### version 1.0.0 

initial AdTrails UTM Grabber plugin version