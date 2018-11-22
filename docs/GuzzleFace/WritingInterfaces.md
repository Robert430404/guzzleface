# Writing Your First Interface

You can see an example interface [here](./../../tests/GuzzleFace/Integration/Fixtures/FixtureClientInterface.php).

## Interfaces Explained

There are a few expectations from your interfaces. The first expectation is that your interface
will extend the guzzle client interface. This package is coupled to guzzle right now, hence the
name GuzzleFace, rather than re-implement the wheel with curl.

There are plans to use an abstraction package like HTTPlug to remove the concrete dependence on
guzzle when there is time available to swap the implementation.

### How Do The Annotations Work

There are a few parts to leveraging annotations on your interfaces, and they fall into three
groups:

* Method Annotations   - These apply only to the method block
* Class Annotations    - These apply only to the interface block
* Annotations For Both - These can go in either block

Below is a table listing which annotations can go where.

| Name         | Location        | Description                               |
| ------------ | --------------- | ----------------------------------------- |
| ApiName      | CLASS           | Api Name For Client                       |
| BaseUri      | CLASS           | Base URI For Client                       |
| Body         | METHOD          | Request Body                              |
| Endpoint     | METHOD          | Request Endpoint                          |
| Headers      | METHOD          | Request Headers                           |
| Action       | METHOD          | This Describes The Action For The Request | 
| Delete       | METHOD          | Signifies It's A DELETE Request           |
| Get          | METHOD          | Signifies It's A GET Request              |
| Options      | METHOD          | Signifies It's An OPTIONS Request         |
| Post         | METHOD          | Signifies It's A POST Request             |
| Put          | METHOD          | Signifies It's A PUT Request              |
| Patch        | METHOD          | Signifies It's A PATCH Request            |
| AuthBasic    | METHOD or CLASS | Basic Auth For Request Or Client          |
| AuthBearer   | METHOD or CLASS | Bearer Auth For Request Or Client         |
| ContentType  | METHOD or CLASS | Content-Type For Request Or Client        |
| CustomHeader | METHOD or CLASS | Custom Header For Request Or Client       |
| UserAgent    | METHOD or CLASS | User Agent For Request Or Client          |
