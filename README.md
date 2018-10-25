# GuzzleFace

This is a library inspired by OpenFeign. It removes the need to write any concrete
implementations of guzzle clients and instead allows you to annotate an interface
with the functionality you want. It is then auto generated for you so you do not have
to write a single line of code for your HTTP client implementations.

This has the benefits of abstracting away the guzzle version from you since the code
is handled for you and you just have to define an interface. It reduces the boiler 
plate code you have to write in order to get your clients implemented. And it's always
cool to have the ability to just read documentation for your interfaces and have a 
complete outline as to what your clients do.

## Using The Library

It is currently under active development, and once it is complete this section will
link out to the documentation for the annotations, and generation portions of the
code.