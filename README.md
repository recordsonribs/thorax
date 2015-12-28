Thorax
======

The new version of our record label management software. The successor to [Ribcage](http://github.com/recordsonribs/ribcage). It is designed to be faster, easier to use and easier to setup than Ribcage, using all WordPress's own internals if possible rather than reinventing the wheel.

[![Build Status](https://travis-ci.org/recordsonribs/thorax.svg)](https://travis-ci.org/recordsonribs/thorax)

*This version is not ready for use but is under development.*

# Why WordPress?

Thorax is a plugin for [WordPress](http://wordpress.org), albeit one that heavily abstracts WordPress custom post type methods for ease of coding.

Like its predecesor Ribcage we do so in the hope that since WordPress is widely avaliable, this plugin will enable easy creation of a Creative Commons label. Battlehardened by our own use, Thorax means that you too can setup a record label and begin distributing music in an attractive manner as quickly as possible.

# Installation

1. Install this repository in the usual manner you would install a WordPress theme.
2. `composer install` in that directory.
3. Enjoy!

# Theming Thorax

While Ribcage directly accessed the WordPress MySQL database, Thorax works using standard WordPress custom post types (such things did not exist when Ribcage was first written!). Therefore it can be themed by adding themed custom post types to your WordPress theme and falls back through the WordPress [template hierachy](http://codex.wordpress.org/Template_Hierarchy) if these are not present.

Thorax contains three custom post types: artists, releases and events. Artists have releases and events - standard WordPress blog entries can also be related to an individual artist or release. Releases have reviews.

# Technologies Used

- Under the hood, WordPress metaboxes are created using [CMB2](https://github.com/WebDevStudios/cmb2), managed by Composer.
