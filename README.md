AvroExtraBundle
-----------------

Installation
------------

Add the `Avro` namespace to your autoloader:

``` php
// app/autoload.php

$loader->registerNamespaces(array(
    // ...
    'Avro' => __DIR__.'/../vendor/bundles',
));
```

Enable the bundle in the kernel:

``` php
// app/AppKernel.php

    new Avro\ExtraBundle\AvroExtraBundle
```

```
[AvroExtraBundle]
    git=git://github.com/Avro/ExtraBundle.git
    target=bundles/Avro/ExtraBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

