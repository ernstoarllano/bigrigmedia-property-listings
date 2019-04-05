# Big Rig Media Property Listings

A WordPress plugin to manage property listings.

## Table of Contents
- [Table of Contents](#markdown-header-table-of-contents)
    - [Installation](#markdown-header-installation)
    - [Plugin Overview](#markdown-header-plugin-overview)

## Installation

1. Install the BRM Property Listings plugin repository via `git`. The repository lives at https://github.com/ernstoarllano/bigrigmedia-property-listings.
    ```
    git clone https://github.com/ernstoarllano/bigrigmedia-property-listings.git
    ```
2. Activate the plugin via the WordPress backend or with [WP CLI](https://developer.wordpress.org/cli/commands/plugin/).
    ```
    wp plugin activate brm-property-listings
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
* `admin/class-brm-property-listings-admin.php` - This is where admin specific functionality eg: Custom Post Types, Taxonomies, Metaboxes, etc.
* `includes/class-brm-property-listings.php` - There is where all admin / public hooks are defined.
* `public/js/brm-property-listings-public.js` - This is where all public JavaScript goes.
* `public/class-brm-property-listings-public.php` - This is where public specific functionality eg: Shortcode, Register JavaScript, Register CSS, etc