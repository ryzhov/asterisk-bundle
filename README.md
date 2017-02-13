AsteriskBundle
==============

The `AsteriskBundle` provides integration of the [Asterisk PAMI](https://github.com/ryzhov/PAMI)
library into the Symfony2 framework.

To install you can just require `"ryzhov/asterisk-bundle": "~1.0-dev"` in your composer.json
and Composer will automatically pick the latest dev version of the bundle that works with
your current version of Symfony. The minimum version of Symfony2 for this workflow
is 2.3.0.


License
=======

This bundle is released under the [MIT license](LICENSE)

Installation
============

Require the bundle and its dependencies with composer:

```bash
$ composer require ryzhov/asterisk-bundle
```

Register the bundle:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        new Ryzhov\Bundle\AsteriskBundle(),
    );
}
```

Usage
=====

Add the `asterisk` section in your configuration file:

```yaml
asterisk:
    connections:
        default:
            host: "%asterisk_host%"
            port: "%asterisk_ami_port%"
            username: "%asterisk_ami_username%"
            secret: "%asterisk_ami_secret%"
            connect_timeout: 5
            read_timeout: 5

    clients:
        main:
            connection: default
            logger_channel: ami
```

Here we configure the connection parameter and the AMI client that our application will have.
In this example your service container will contain the service `asterisk.main_client` and
 `asterisk.ami_connection.default` connection parameters.
AMI client service interface reference here [Asterisk PAMI](https://github.com/ryzhov/PAMI).

Register async event handler with tag `asterisk.ami_event_handler` will handle only specified events.

```yaml

parameters:
    events: 
        - "PAMI\\Message\\Event\\DeviceStateChangeEvent"
        - "PAMI\\Message\\Event\\PeerStatusEvent"

services:
    service.event_handler:
        class: AppBundle\Service\EventHandler
        calls:
            - [setLogger, ["@logger"]]
        tags:
            - { name: monolog.logger, channel: event }
            - { name: asterisk.ami_event_handler, client: asterisk.main_client, events: "%events%" }

```

Event handler example:

```php

namespace AppBundle\Service;

use PAMI\Listener\IEventListener;
use PAMI\Message\Event\EventMessage;

class EventHandler implements IEventListener
{
    . . .

    public function handle(EventMessage $event)
    {
        $this->logger->debug(sprintf('class: "%s" handle', get_class($event)));
    }
}

```

This is example of code  [ryzhov/example-asterisk-ami](https://github.com/ryzhov/example-asterisk-ami)
