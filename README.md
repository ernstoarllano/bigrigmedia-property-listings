![Plugin Version](https://img.shields.io/github/package-json/v/ernstoarllano/bigrigmedia-property-listings.svg?style=for-the-badge)

# Big Rig Media Property Listings

A WordPress plugin to manage property listings.

## Table of Contents
- [Table of Contents](#markdown-header-table-of-contents)
    - [Installation](#markdown-header-installation)
    - [Plugin Overview](#markdown-header-plugin-overview)
    - [Baic Usage](#markdown-header-basic-usage)
    - [Advanced Usage](#markdown-header-advanced-usage)

## Installation

1. Install the BRM Property Listings plugin repository via `git`. The repository lives at https://github.com/ernstoarllano/bigrigmedia-property-listings.
    ```
    git clone https://github.com/ernstoarllano/bigrigmedia-property-listings.git
    ```
2. Activate the plugin via the WordPress backend or with [WP CLI](https://developer.wordpress.org/cli/commands/plugin/).
    ```
    wp plugin activate bigrigmedia-property-listings
    ```

## Plugin Overview
Here's a quick overview of the important directories / files.

```
├── admin
│   ├── js
│   │   ├── brm-property-listings-admin.js
│   ├── class-brm-property-listings-admin.php
├── includes
│   ├── class-brm-property-listings.php
├── public
│   ├── js
│   │   ├── brm-property-listings-public.js
│   └── class-brm-property-listings-public.php
```

* `admin/js/brm-property-listings-public.js` - This is where all admin JavaScript goes.
* `admin/class-brm-property-listings-admin.php` - This is where admin specific functionality eg: Custom Post Types, Taxonomies, Metaboxes, etc. goes.
* `includes/class-brm-property-listings.php` - There is where all admin / public hooks are defined.
* `public/js/brm-property-listings-public.js` - This is where all public JavaScript goes.
* `public/class-brm-property-listings-public.php` - This is where public specific functionality eg: Shortcode, Register JavaScript, Register CSS, etc

## Basic Usage
First thing you'll need to do is generate a [Google Maps API key](https://developers.google.com/maps/documentation/javascript/get-api-key). Make sure to enable the `Maps JavaScript API` and `Geocoding API` inside of the Google APIs & Services Dashboard.

Once you've generated an API key open up `class-brm-property-listings.php` and set the `google_maps_api` object on **line 85**.

To output a Google map simply use the built-in `[map]` shortcode. By default the shortcode has two modes: **multiple markers** and **single marker**. If you use the shortcode inside of the `single-listings.php` template then it will output a single marker, but any other template will output multiple markers.

## Advanced Usage
Coming Soon