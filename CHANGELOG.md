LogMeIn GoToWebinar Provider for OAuth 2.0 Client
=================================================

v4.0
----
_Released: 2024-01-29_

Break changes:
- strict_type for each php file allow more efficient compilation
- upgrade min php version to 8.2
- drop AccessTokenDecorator that's because accessToken haven't anymore informations about owner
- saveToken has different signature, because because accessToken haven't anymore informations about owner
- drop @param comments datatypes, now using methods signature

Improvements:
- break Redis Storage in two files: 
  - PredisTokenStorage for Predis
  - RedisTokenStorate for Redis
- Provider\GotoWebinar
  - domainAuth for new authorization mechanism as in "https://developer.goto.com/guides/Authentication/New_Token_Retrieval_Migration_Guide/"
- Resources\Webinar
  - drop @deprecated getUpcoming()
  - drop @deprecated getPast()

v3.0
----
_Released: 2020-12-09_

Potential break change: DateUtcHelper is static

- \Resources\Webinar.php
  - reorganization of getWebinars: 
    - added getWebinarsByAccount, 
    - added getWebinarsByOrganizer
    - getAudioInformation, 
    - getInSessionWebinars
- \Resources\CoOrganizer.php
  - deprecated resendInvitation 
  - added resendCoorganizerInvitation
- \Resources\Registrant.php
  - added getRegistrationFields
  - added $resendConfirmation in createRegistrant
- \ResultSet\ResultSetInterface.php 
  - implements JsonSerializable 
- new \Resources\Webhook.php
- \Helper\DateUtcHelper.php 
  - is now static
- \Resources now return SimpleResult or PageResult as return type

v2.0
----
_Released: 2020-12-02_

Potential break change:
Now all data returned by Resources are instances of ResultSetInterface. 
These are in practice \ArrayObjects, so working with them is quite the same as working with Arrays (but not always ;-).

Resources:

- \Resources\Webinar.php
  - getWebinars new params for define from, to, page, size
  - getUpcoming is deprecated: params are the same of getWebinars
  - getPast is deprecated: params are the same of getWebinars
- \Resources\AuthenticatedResourceAbstract.php
  - Add Method getRequestUrl for improved efficiency in creating request url for Resources
- \Resources\Attendee.php 
  - using getRequestUrl for building urls
- \Resources\AuthenticatedResourceAbstract.php 
  - using getRequestUrl for building urls
- \Resources\CoOrganizer.php 
  - using getRequestUrl for building urls
- \Resources\Registrant.php 
  - using getRequestUrl for building urls
- \Resources\Session.php 
  - using getRequestUrl for building urls
- \Resources\Webinar.php 
  - using getRequestUrl for building urls

Added ResultSets for managing GotoWebinar Responses.
All these extends or implements interfaces of \ArrayObject with an additional method getData().

- \ResultSet\PageResultSet.php
  - Used for working with paged results
- \ResultSet\ResultSetInterface.php
  - The interface to extend from all ResultSet
- \ResultSet\SimpleResultSet.php
  - It's usable for all kind of Responses 

Added missing resources to:

- \Loader\ResourceLoader.php

v1.5.3
------