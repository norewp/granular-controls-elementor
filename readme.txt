=== Granular Controls For Elementor ===
Contributors: norewp, diggeddy, Alex Ischenko
Donate link: https://www.paypal.me/NoreMarketing/5
Tags: Elementor, Custom Controls, Accordion off, Delayed content, Editor Skins, UI Hacks, Elementor Parallax, Elementor Particles
Requires at least: 4.4
Tested up to: 4.9.4
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Custom controls for Elementor Page Builder.

== Description ==

https://www.youtube.com/watch?v=RMeiqS0q3zs

WARNING: Semi breaking change in v1.0.2 - setting options have been added to switch Parallax & Particles on/off. With this update you'll need to turn these on via the settings page!

Granular Controls for Elementor brings additional controls to the ever popular Elementor Page Builder plugin.

This plugin gives you the options to set the Accordion's first tab to closed on page load, select a custom editor skin, set a specific section/column to appear after a certain time (Delayed content),   
schedule a section/column to be displayed during a given date period by setting the start and end date, plus much more to come. 

Featuring David Beckwith's (@diggeddy) Elementor UI hack:    

https://www.youtube.com/watch?v=s7TxNEXh7os   

= Features =
* Keep all accordions closed on page load.
* Turn off Elementor dashboard widget
* Apply a preset color skin to the editor panel
* Apply the Elementor UI hack #2 as seen in the video above.
* Set any Section or Column to appear after a certain time - Delayed Content
* Schedule visibility of any Section or column for a given time period (Days & Hours) - Scheduled Content
* Parallax option for sections. Additional controls added.    
* Particles option for sections.    
* Elementor Library Templates as the Admin Dashboard Welcome Notice Page.
* Draggable Editor Bar with 1 click exit to Dashboard + a View Live Page button that opens in a new tab.
* Exit Point can now be set to the Dashboard, the current page/post/library edit screen, the pages list, posts list or library list.    
* Exit Point button text can now be changed to custom text.
   

= Be a contributor =

If you would like to become a contributer, suggest features and/or report bugs please visit our [Granular Controls for Elementor](https://github.com/norewp/granular-controls-elementor) repository on GitHub to let us know.


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress     
3. Visit Elementor > Granular Controls to access the settings - other settings are accessible via the Editor and set on per page/section/column basis.

== Screenshots ==

== FAQ ==
**I've switched Particles on and they overlay my content, how can I fix this?**

To resolve this any widgets placed in the section will need a higher z-index - Go to the Advanced tab of each widget and set the z-index to 1 & save     

**I can no longer see the controls for Parallax and/or Particles, Why?**

In order to avoid scripts being loaded on sites that are not using these options a setting control has been added where they can be switched on and off as needed - See Elementor > Granular Controls > Editor Options.   

**I do not see the Exit Bar in the Editor, how do I get access to it?**

You can enable or disable the bar via the settings page - Visit Elementor > Granular Controls > Editor Options to activate it.    

**When I turn on the Admin Dashboard Page I loose my admin widgets too, what's wrong?**

As the admin page is being modified this may happen on some sites but the widgets are not lost - simply click on the screen options and turn them on again.

== Changelog ==

= 1.0.4 =
* TWEAK: Changed plugin textdoman to match the plugin slug in order to resolve the WordPress/GlotPress translation issue.    
* NEW: Added option to change the name of the Exit Point button to reflect the choice made for the exit point.    
* FIX: Safari issue with the Editor Bar not showing.
* TWEAKS: CSS adjustments on editor skins for better focus & visibility.    

= 1.0.3 =
* TWEAK: Editor Bar now remembers the last dragged to position
* TWEAK: Changed Editor Bar's snapMode and containment to be inside the preview iframe - this avoids the bar being dragged out of the window and/or over the side panel.
* TWEAK: Added additional exit points + option to set the target of the exit i.e same or new tab.
* TWEAK: Parallax now has additional controls.     
* Minor code adjustments and tweaks


= 1.0.2 =    
* NEW: Added option to enable a custom Editor Bar that includes the Exit to Dashboard button as well as the View the live page. The bar is draggable and therefore can be moved anywhere on the screen.    
* TWEAK: Escalated capability so that only administrators have access to the settings page.
* TWEAK: Added settings options to turn on/off the Parallax & Particles in order to avoid loading the scripts when these are not being used.
* TWEAKS: Minor adjustments to CSS rules in the skin files as well as some code clean up

= 1.0.1 =
* NEW: Added Parallax option to Sections - Editor > Section > Style > Background & switch parallax on.   
* NEW: Added Particles option to Sections - Editor > Section > Style > Background - switch particles on & configure as needed.
* NEW: Added option to select an Elementor Library Template as the Admin Dashboard Welcome page - Elementor > Granular Controls > Advanced & configure the options. Initial implementation! 

= 1.0.0 =
* Initial release