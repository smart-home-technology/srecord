Motorola SRecord file helper


Usage
----------
    

	C:\path\srecord>php bin/srecord.php
    Usage: bin/srecord.php filename.s19
    Prints header record, if available, and total data size of srecord file.
    S19, S28, S37 file format is supported (honestly, it's the same)


	
	C:\path\srecord>php bin/record.php C:\path\demo\Release\demo.s19
	SRECORD C:\path\demo\Release\demo.s19
    Header : "Demo 1.0.4 @ 2015 Purrworks"
    Total data size: 6260

    Have a spacevangerous day! purr
    


Installation
---------
No special installation process needed.

Available through composer/packagist package

    composer require tschiemer/srecord

https://packagist.org/packages/tschiemer/srecord


License: MIT
-----------


