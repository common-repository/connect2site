===Connect2site===
Contributors: mlazarid
Plugin URI: http://tech.kounoupaki.gr/?p=6
Author URI: http://www.kounoupaki.gr
Tags: subdomain, posts, category, widget,connect, blog, domain
Tested up to: 2.7.1
Requires at least: 2.6.2
Stable tag: 1.2.3

This plugin can be used to connect to another wordpress blog located in a subdomain on the same server. It provides various ways to display posts.

== Description ==
This plugin can be used to connect to another wordpress blog. I created this widget to display a list of posts from another blog of mine that resided in a subdomain on the same server. The options you have is to display posts from multiple categories from your sub blog. Lets say that you have a blog 'www.yourblog.com' and another blog named sub.yourblog.com and you wish to display a list of posts in your 'www.yourblog.com' from 'sub.yourblog.com' what you can do is install the plugin in 'www.yourblog.com' and configure it. This plugin needs some information in order to connect to the database of your other blog. You can find all the needed information in your wp-config.php file of your 'sub.yourblog.com'. In this version you can display a summary of the post and a link to the category that it belongs to.

Basic Features:
<ul>
<li>Display a list of posts from another blog with multiple category options, display or not category name, display or not comment count, open post or category in a new window and display or not summary of posts</li>
<li>Include short code in a post or page and display the above mentioned including a single post with the option of full post or the exceprt</li>
<li>Include short code and define different multiple categories criteria to display in a single post or page with various options</li>
</ul>
<a href="http://tech.kounoupaki.gr/?p=6">To see it in action please visit the plugins blog page.</a>

== Installation ==
Plugin installation is very easy. Just unzip its contents and upload connect2.php  into '/wp-content/plugins/' , and active it. Set the configuration options in the Settings in your admin panel.

=Release History
<ul>
<li>Release 1.2.3 Fixed some minor issues, added class connect2site for custom formating</li>
<li>Release 1.2.2 Post lists are displayed in descending order</li>
<li>Release 1.2.2 Fixed bug when number of posts limit was not set</li>
<li>Release 1.2.1 corrected  H3 to post header when using short codes</li>
</ul>

<a href="http://tech.kounoupaki.gr/?p=6">To see it in action please visit the plugins blog page.</a>

== Frequently Asked Questions ==

= Is there a widget ? =

Yes. In this version there is no seup for the widget. It is configured in the main plugins setup

= Can I display different categories in my pages ? =

Yes. The short codes use only the database connection options from the main setup. The rest of the parameters can be different and they are defined in the short code

= Is the output formated using a CSS ? =
No. The plugin does not use any CSS of its own. Just keep in mind that when you display a full post from another blog the look may be different from the original. 
There is a class connect2site for custom CSS, otherwise the plugin uses the CSS properties of your theme.

== How to use ==
There is an extensive description of howtos in the plugin site.
<a href="http://tech.kounoupaki.gr/?p=6">For detailed instructions please visit the plugins blog page.</a> 

== Screenshots ==
Due to the nature of the plugin it is better to see it in action.
<a href="http://tech.kounoupaki.gr/?p=6">To see it in action please visit the plugins blog page.</a>