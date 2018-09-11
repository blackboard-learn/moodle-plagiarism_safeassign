# SafeAssign plagiarism plugin

The [SafeAssign](http://www.blackboard.com/safeassign/index.html) plagiarism plugin (plagiarism_safeassign) provides 
settings and integration that enable the usage of SafeAssign with Moodle assignments. SafeAssign compares submitted
assignments against a set of sources to identify areas of overlap between the submitted assignment and existing works.

## Installation

You can download the plagiarism plugin from:

https://github.com/blackboard-open-source/moodle-plagiarism_safeassign

This plugin should be located and named as:
 [yourmoodledir]/plagiarism/safeassign

## Configuring the SafeAssign plagiarism plugin

To open [SafeAssign](http://www.blackboard.com/safeassign/index.html) settings, you'll need to setup a url in Moodle 
configuration as so.

```php
$CFG->plagiarism_safeassign_urls = [
    [ 'url' => '<SafeAssign host url>', 'type' => 'production']
];
```

Where ```<SafeAssign host url>``` is the url to the SafeAssign server that will score your assignments.
The *SafeAssign host url*, should be populated with values provided to you by a 
[SafeAssign representative](http://www.blackboard.com/safeassign/index.html).

Open the settings for the SafeAssign plagiarism plugin:

*Site Administration > Plugins > Plagiarism > SafeAssign plagiarism plugin*

The Instructor and Student Role Credentials should be populated with values provided to you by a SafeAssign
representative.

You can fill up the rest of the form with the information pertaining your institution's interests.

Upon saving, the *Test connection* button will be availiable for you to test the saved connection information
(Credentials and Service URL section).
 
## License for SafeAssign plagiarism plugin

© Blackboard Inc 2017

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <http://www.gnu.org/licenses/>.
