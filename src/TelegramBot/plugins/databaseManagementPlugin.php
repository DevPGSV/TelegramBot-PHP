<?php
require_once(__DIR__ . '/../PluginManager.php');


class DatabaseManagementPlugin extends TB_Plugin {
  public function DatabaseManagementPlugin($api, $bot, $db) {
    parent::__construct($api, $bot, $db);
  }

  /**
   * %condition date any
   */
  public function onMessageReceived($message) {
    if ($this->getUserInDB($message->getFrom()->getId()) === null) {
      $this->addUserToDB($message->getFrom());
    }
    $currentUser = $this->getUserInDB($message->getFrom()->getId());

    $newData = [];
    if (!in_array($message->getChat()->getId(), $currentUser['chatsSeen'])) {
      $newData['$push']['chatsSeen'] = $message->getChat()->getId();
    }
    $newData['$inc']['stats.messages'] = 1;
    if ($message->getFrom()->getFirstName() !== $currentUser['first_name']) $newData['$set']['first_name'] = $message->getFrom()->getFirstName();
    if ($message->getFrom()->getLastName() !== $currentUser['last_name']) $newData['$set']['last_name'] = empty($message->getFrom()->getLastName())?'':$message->getFrom()->getLastName();
    if ($message->getFrom()->getUsername() !== $currentUser['username']) $newData['$set']['username'] = empty($message->getFrom()->getUsername())?'':$message->getFrom()->getUsername();

    echo '<pre>', print_r($newData, true), '</pre>';
    $this->db->selectCollection('users')->updateOne(['_id' => $message->getFrom()->getId()], $newData);

    //$message->sendReply('->```'.print_r($newData, true).'```', null, null, 'Markdown');
  }

  private function getUserInDB($userId) {
    $o = array(
      'typeMap' => array(
        'root' => 'array',
        'document' => 'array',
      ),
    );
    return $this->db->selectCollection('users', $o)->findOne(['_id' => $userId]);
  }

  private function addUserToDB($user) {
    $currentUser = array(
      "_id" => $user->getId(),
      "first_name" => $user->getFirstName(),
      "username" => ($user->getUsername() !== null)?$user->getUsername():'',
      "last_name" => ($user->getLastName() !== null)?$user->getLastName():'',
      "chatsSeen" => [],
      "role" => "user",
      "ban" => array(
        "status" => false,
        "reason" => "none"
      ),
      "stats" => ['messages' => 0]
    );
    $this->db->selectCollection('users')->insertOne($currentUser);
  }

  public function getChangeLog() {
    return [
      '1460722260' => [
        'version'=>[0, 0, 0, 'alpha'],
        'changes' => [
          'Created plugin',
        ],
      ],
      '1460810520' => [
        'version'=>[0, 1, 0, 'alpha'],
        'changes' => [
          'Added "getChangeLog" function',
        ],
      ],
      '1460829949' => [
        'version'=>[0, 2, 0, 'alpha'],
        'changes' => [
          'Refactored plugin',
        ],
      ],
    ];
  }
}
return array(
  'class' => 'DatabaseManagementPlugin',
  'name' => 'Database Management',
  'id' => 'DatabaseManagementPlugin',
  'version' => [0, 2, 0, 'alpha'],
);
