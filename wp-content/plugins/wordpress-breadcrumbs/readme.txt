=== Wordpress Breadcrumbs ===
Contributors: drnaylor
Tags: breadcrumbs, navigation
Requires at least: 2.2
Tested up to: 2.6.3
Stable tag: 1.2.3

Allows for breadcrumbs in a Wordpress Installation with little effort.

== Description ==

This plugin allows the use of breadcrumbs in your Wordpress installation. Please refer to the installation instructions for instructions on how to configure your theme to allow for breadcrumbs.

== Installation ==

1. (Optional) - In bread.php, change the seperator between breadcrumbs in the configuration section of the file.
1. Upload bread.php into your plugins directory and activate "Wordpress Breadcrumbs"
1. In your theme's template, add the following line where you require your breadcrumbs to be

`&lt;?php if(function_exists('breadcrumbs')) { breadcrumbs(); } else { bloginfo('name'); echo '(breadcrumbs are unavailable)'; } ?&gt;`

1. You should now have working breadcrumbs!

==License==

This code is licenced under the GPL v2 or later.

== Frequently Asked Questions ==

Q. I have an idea for the breadcrumbs, can I submit the idea?  
A. Yep. [Click here](http://drnaylor.co.uk/contact/) and report it!

Q. What about that problem?  
A. Go to the same place - it all gets to me!

==Changelog==

* V1.0 - First Release
* V1.1 - Added support for searching, now the bredacrumbs show "> Search Results for..."
* V1.2 - Added support for multi-level pages. Also a configuaration item, but needs to be edited in the script. Put on Wordpress Extensions.
* V1.2.* - Bug fix releases.


