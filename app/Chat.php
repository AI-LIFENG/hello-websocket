<?php
namespace App;
  use Ratchet\MessageComponentInterface;
  use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {

  protected $clients;
  public function __construct(){
    $this->clients = new \SplObjectStorage;
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
  }
}
