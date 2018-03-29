<?php
use Symfony\Component\HttpFoundation\{Request, Response};

require __DIR__ . '/../bootstrap/app.php';

date_default_timezone_set('UTC');

$app->post('/clear_data', function (Silex\Application $app) {
  $stmt = $app['db']->prepare("TRUNCATE table pings");
  $stmt->execute();
  return new Response(null, 200);
});

$app->get('/devices', function (Silex\Application $app) {
  $stmt = $app['db']->query("SELECT DISTINCT device_id FROM pings");
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $app->json($result);
});

$app->post('/{deviceId}/{epochTime}', function($deviceId, $epochTime, Silex\Application $app) {
  $stmt = $app['db']->prepare("INSERT INTO pings (device_id, epoch_time) VALUES ('$deviceId', '$epochTime')");
  $stmt->execute();
});

$app->get('/all/{from}/{to}', function($from, $to = null, Silex\Application $app) {
  $arr = [];
  $startDate = is_numeric($from) ? $from : strtotime($from);
  $endDate = is_numeric($to) ? $to : strtotime($to);
  $stmt = $app['db']->query("SELECT * FROM pings where (pings.epoch_time >= $startDate AND pings.epoch_time < $endDate)");

  while ($row = $stmt->fetch()) {
    $arr[$row["device_id"]][] = $row["epoch_time"];
  }
  return $app->json($arr);
});

$app->get('/all/{date}', function($date, Silex\Application $app) {
  $startDate = is_numeric($date) ? $date : strtotime($date);
  $endDate = strtotime("tomorrow", $startDate);
  $stmt = $app['db']->query("SELECT * FROM pings where (pings.epoch_time >= $startDate AND pings.epoch_time < $endDate)");
  while ($row = $stmt->fetch()) {
    $arr[$row["device_id"]][] = $row["epoch_time"];
  }
  return $app->json($arr);
});


$app->get('/{deviceId}/{date}', function($deviceId, $date, Silex\Application $app) {
  $startDate = strtotime($date);
  $endDate = strtotime("tomorrow", $startDate);
  $stmt = $app['db']->query("SELECT * FROM pings where (pings.epoch_time >= $startDate AND pings.epoch_time < $endDate) AND device_id = '$deviceId'");
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $app->json($result);
});

$app->get('/{deviceId}/{from}/{to}', function($deviceId, $from, $to, Silex\Application $app) {
  $startDate = is_numeric($from) ? $from : strtotime($from);
  $endDate = is_numeric($to) ? $to : strtotime($to);
  $stmt = $app['db']->query("SELECT * FROM pings where (pings.epoch_time >= $startDate AND pings.epoch_time < $endDate) AND pings.device_id = '$deviceId'");
  $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $app->json($result);
});

$app->run();
