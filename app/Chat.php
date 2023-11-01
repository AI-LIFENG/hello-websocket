<?php
namespace App;
  use Ratchet\MessageComponentInterface;
  use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

  protected $clients;
  protected $db;
  public function __construct(){
    date_default_timezone_set('PRC');
    $this->clients = new \SplObjectStorage;
    $db_cfg = \Amp\Postgres\ConnectionConfig::fromString(
      "host=172.17.0.1 port=9528 user=root password=Bofang666 dbname=root");
    $this->db = \Amp\Postgres\pool($db_cfg);
  }

  public function onOpen(ConnectionInterface $conn){
    $this->clients->attach($conn);
    echo " ++ Connection#{$conn->resourceId}@{$conn->remoteAddress}\n";
  }

  public function onClose(ConnectionInterface $conn){
    $this->clients->detach($conn);
    echo " -- Connection#{$conn->resourceId}@{$conn->remoteAddress}\n";
  }

  public function onError(ConnectionInterface $conn, \Exception $e){}

  public function onMessage(ConnectionInterface $from, $msg){
    echo " == Connection#{$from->resourceId}@{$from->remoteAddress} SEND ".$msg."\n";
    $from->send(date('c')."\n");

    \Amp\Loop::run(function()use($msg){
      $res = yield $this->db->execute('INSERT INTO "public"."_Hooks" ("triggerName") VALUES ($1)', [$msg]);
    });
  }
}
