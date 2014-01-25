sublime-svn-customdiff
======================

custom diff arguments reformater for sublime svn plugin

description
-----------

this little script allows you to start a custom diff tool.
it parses the default sublime svn plugin's arguments passed to default diff.
those arguments are not compatible with some diff tools like diffuse.
so it needs to be reformated and thats what this script does.

install
-------

[linux]  
copy this script to any place where you want to run it (you may remove file extension).
set executable flag to it `sudo chmod +x sublime-svn-customdiff`.
don't forget to set proper rights so sublime svn can run it.

setup sublime svn
-----------------

set sublime svn setting:  
  `,"diff_command": "/path/to/sublime-svn-customdiff"`

and you're done!

modify code
-----------

if you intend to use something different than diffuse, you'll probably need to
modify this script according to the arguments of the diff tool you intend to use.

help
----

if you wish to suggest improvements, you are welcome!
if you need help, feel free to contact me
