<?php
require_once 'vendor/autoload.php';

$settings = [
  'application_key' => '***',
  'private_key'     => '***',
  'email'           => '***',
  'password'        => '***',
];
$account_id = '***';


$client = new Spore('config/route_config.desktop.yaml');

// auth
$client->enable('AddHeader', [
  'header_name'  => 'X-Weborama-Account_Id',
  'header_value' => $account_id
]);
$client->enable('Spore_Middleware_Weborama_Authentication', [
  'application_key' => $settings['application_key'],
  'private_key'     => $settings['private_key'],
  'user_email'      => $settings['email']
]);
$auth = $client->get_authentication_token([
  'format'   => 'json',
  'email'    => $settings['email'],
  'password' => $settings['password']
]);
$client->enable('AddHeader', [
  'header_name'  => 'X-Weborama-UserAuthToken',
  'header_value' => $auth->body->token,
]);
// end auth

$res = $client->datamining_file_list([
  'format' => 'json',
  'month' => '2019-02',
  'account_id' => $account_id,
]);



foreach ($res->body->list as $obj) {
  echo "http://cstatic.weborama.fr/{$obj->file_path}\n";
}