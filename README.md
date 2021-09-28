## Usage

 - [Instal](#install)
 - Auth
	- [Basic](#basic-auth)
	- [JWT](#jwt-auth)
 - Samples
	- [Campaign info](#campaign-info)
	- [Custom events](#custom-events)


## Install

```sh
$ composer install
```


## Auth

### Basic auth
```php
$settings = [
	'application_key' => '***',
	'private_key'     => '***',
	'email'           => '***',
	'password'        => '***',
];

$client = new Spore(__DIR__ . '/config/route_config.desktop.yaml');

$client->enable('Spore_Middleware_Weborama_Authentication', [
	'application_key' => $settings['application_key'],
	'private_key'     => $settings['private_key'],
	'user_email'      => $settings['email']
]);
$auth = $client->get_authentication_token([
	'email'    => $settings['email'],
	'password' => $settings['password']
]);
$client->enable('AddHeader', [
	'header_name'  => 'X-Weborama-UserAuthToken',
	'header_value' => $auth->body->token,
]);
```


### JWT auth
```php
$client = new Spore(__DIR__ . '/config/route_config.desktop.yaml');

$auth = $client->get_authentication_api_jwt_token([
	'email'    => 'xxx',
	'password' => 'xxx'
]);
$client->enable('AddHeader', [
	'header_name' => 'X-Weborama-JWTUserAuthToken',
	'header_value' => $auth->body->jwt_token
]);
```



### Samples

#### Campaign info
```php
// auth

$res = $client->accountId(account_id)->get_campaign([
	'id' => campaign_id
]);

print_r($res);
```

#### Custom events
```php
// auth

$res = $client->accountId(account_id)->get_statistics([
  'dimensions' => ['campaign', 'custom_event'],
  'metrics'    => ['event'],
  'dimension_filters' => ['campaign_id' => ['-in' => [campaign_id_list]]],
]);

print_r($res);
```
