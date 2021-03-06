<?php
require_once(__DIR__ . '/../PluginManager.php');


class HelpPlugin extends TB_Plugin {
  public function HelpPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date isNew
   * %condition text matches ^\/(?:help|start)(?:@{#USERNME})?$
   */
  public function onMessageReceived($message) {
    $message->sendReply("Developing... If you want to /test ...");
  }

  public function getChangeLog() {
    return [
      '1459382160' => [
        'version'=>[0, 0, 0, 'alpha'],
        'changes' => [
          'Created plugin',
        ],
      ],
    ];
  }
}
return array(
  'class' => 'HelpPlugin',
  'name' => 'Help',
  'id' => 'HelpPlugin',
  'version' => [0, 0, 0, 'alpha'],
);
