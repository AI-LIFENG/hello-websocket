<?php
  use Ratchet\Server\IoServer;
  use App\Chat;

require dirname(__DIR__) . '/vendor/autoload.php';

$server = IoServer::factory(
  new Chat(),
  9099
);

$server->run();
