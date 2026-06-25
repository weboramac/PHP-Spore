<?php
require_once 'vendor/autoload.php';

/** auth **/

$res = $client->datamining_file_list([
  'format' => 'json',
  'month' => '2026-02',
  'account_id' => $account_id,
]);


foreach ($res->body->list as $obj) {
  echo " https://cstatic-ru-cv.weborama-tech.ru/public/{$obj->file_path}\n";
}
