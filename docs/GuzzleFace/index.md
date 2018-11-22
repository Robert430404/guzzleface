# GuzzleFace Documentation Index

[Return To Readme](./../../README.md)

You can find a list of documentation for the various interactive parts of GuzzleFace
below. This includes documentation for setting up the client builder and the various
annotations that you use with the interfaces for your clients.

## Documented Functionality 

* [Client Builder Use](./ClientBuilder.md)
* [Writing An Interface](./WritingInterfaces.md)

## Examples For Use

There is a setup of integration tests for GuzzleFace that show a complete interface, and
the client builder setup to have the library generate and use your newly annotated clients.

These examples and how they are run can be found here:

* [Test Cases For Builder](./../../tests/GuzzleFace/Integration/ClientBuilderTest.php)
* [Test Cases For Client](./../../tests/GuzzleFace/Integration/ClientTest.php)
* [Annotated Interface](./../../tests/GuzzleFace/Integration/Fixtures/FixtureClientInterface.php)
* [Generated Client](./../../tests/GuzzleFace/Integration/Generated/FixtureClient.php)