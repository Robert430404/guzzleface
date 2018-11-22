# GuzzleFace Client Builder

[Return To Readme](./../../README.md) - [Return To Index](./index.md)

This is the core of the library that handles building the clients for you. It has
two dependencies that it relies on.

* The annotation reader for your application
* A Flysystem adapter for writing the file

## Dependencies In Depth

This covers dependency setup that you may have to do depending on your application.

### Annotation Registry For Doctrine Annotations

When you create your instance of the builder, it wants the annotation reader from
your application, I leave this up to you to inject into the builder for use. If you
are leveraging an application framework like symfony, it's DI container should 
make it easy for you to hand this part to the builder.

All it requires is that you allow PSR-4 autoloading of the annotations for your 
reader. If you do now allow for PSR-4 autoloading of the annotations, then you
will have to add this namespace to the annotation reader.

`Robert430404\GuzzleFace\Annotations`

*Please note that I do not officially support this and it may not work.*

In order to enable PSR-4 autoloading of annotations, you have to make a call to
the annotation registry and allow it to use proper `class_exists`.

```PHP
<?php

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader('class_exists');
```

*Allowing for `class_exists` in the annotation registry is the supported method.*

### Flysystem Dependency

I make use of the Flysystem for writing the clients generated code to the provided
directory and namespace for your application. You will need to pass in an instance 
of the `FilesystemInterface` with which ever driver you want to use. 

Officially the Local Filesystem driver is whats supported from Flysystem since it's 
expected that you will be autoloading and executing the clients that you build at 
run time.

```PHP
<?php

new \League\Flysystem\Filesystem(
    new \League\Flysystem\Adapter\Local('/GenerationStorageDirHere')
);
```

## Leveraging The Builder

Once you have the instance of the builder constructed, the next thing you need to do
is build your interfaces. The builder has a cache flag, and a buildClient method that
you leverage generate and return an instance of your clients. You can also pass in an
array of configuration overrides for global client configurations for guzzle. (Think
authentication headers you want driven off of environment variables as an example.)

An example of leveraging a builder is below:

```PHP
<?php

/** 
 * @var \Robert430404\GuzzleFace\ClientBuilder $builder
 * @var \GuzzleHttp\ClientInterface $concrete 
 */

$concrete = $builder
    ->cache(true) // Defaults to false while you develop, in production you should turn this on
    ->buildClient(
        \Robert430404\GuzzleFace\Tests\Integration\Fixtures\FixtureClientInterface::class,
        'Robert430404\\GuzzleFace\\Tests\\Integration\\Generated',
        [
            'handler' => 'mock-handler', // Typically would be used for auth creds or handler mocks
        ]
    );
```

At the end of this, you are returned a generated client from the builder that you can then 
bind to your interface and use throughout your application.

[You can learn more about writing interfaces here.](./WritingInterfaces.md)

### What Do The Args Mean

The first argument you pass to `buildClient` is the magic `::class` for your annotated interface.
The second is the namespace of the generation directory you set on your Flysystem instance that 
the builder is using. The third are argument overrides that are passed to the client instance
that is returned from the builder.