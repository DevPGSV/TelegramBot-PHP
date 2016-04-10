<?php
require_once(__DIR__ . '/../PluginManager.php');


class IdPlugin extends TB_Plugin {
  public function IdPlugin($api, $bot) {
    parent::__construct($api, $bot);
  }

  /**
   * %condition text matches ^\/(?:id|start id)(?:@{#USERNME})?$
   */
  public function onTextMessageReceived($message) {
    $message->sendReply($message->getFrom()->getId());
  }
}
return array(
  'class' => 'IdPlugin',
  'name' => 'Id',
  'id' => 'IdPlugin'
);