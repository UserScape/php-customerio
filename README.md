php-customerio
==============

PHP API Integration with Customer.io

[![Build Status](https://travis-ci.org/UserScape/php-customerio.svg?branch=master)](https://travis-ci.org/UserScape/php-customerio)

## Usage

```php
$api = new Customerio\Api($siteId, $apiSecret, new Customerio\Request);

$result = $api->addCustomer('someid001', 'some@email.com', array('arbitrary-data' => 'foobarbaz'));

if( $result->success() )
{
    // Continue on with life    
}
```

## API Methods

Add Customer:

    addCustomer('someid001', 'some@email.com', array('arbitrary-data' => 'foobarbaz'));

Update Customer:

    updateCustomer('someid001', 'some@email.com', array('arbitrary-data' => 'foobarbaz'));

Delete Customer:

    deleteCustomer('someid001');


Fire Event:

    fireEvent('someid001', 'event-name' array('arbitrary-value', 3.14));

## Response Object

All methods return a `Response` object which contains the following methods:

    success() // Boolean

    message() // String
