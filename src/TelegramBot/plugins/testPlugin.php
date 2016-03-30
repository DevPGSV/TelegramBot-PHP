<?php
require_once(__DIR__ . '/../PluginManager.php');

return 'TestPlugin';
class TestPlugin extends TB_Plugin {
  public function TestPlugin($api) {
    parent::__construct($api);
  }

  public function onMessageReceived($message) {
    if ($message->hasText()) {
      if ($message->getText() === "/test") {
        $k = new TA_ReplyKeyboardMarkup([['/test'],['/test_reply'],['/test_typing'],['/test_forceReplay'],['/test_keyboard'],['/test_hideKeyboard'],['/test_profilephotos']], null, true);
        $this->api->sendMessage($message->getFrom(), "/test\n/test_reply\n/test_typing\n/test_forceReplay\n/test_keyboard\n/test_hideKeyboard\n/test_profilephotos", null, null, $k);
      } else if ($message->getText() === "/test_keyboard") {
        $k = new TA_ReplyKeyboardMarkup([[' - - - ']]); // 0
        $k->addRow()->addOption("/test_hideKeyboard") // 1
          ->addRow()->addOption("A") // 2
          ->addRow()->addOption("C")->addOption("D") // 2
          ->addOption("B", 2); // Add "B" to row 2
        $this->api->sendMessage($message->getFrom(), "Keyboard! Hide with /test_hideKeyboard", null, null, $k);
      } else if ($message->getText() === "/test_hideKeyboard") {
        $this->api->sendMessage($message->getFrom(), "Hide!", null, null, new TA_ReplyKeyboardHide());
      } else if ($message->getText() === "/test_reply") {
        // $this->api->sendMessage($message->getFrom(), "Reply to message with id: " . $message->getMessageId(), null, $message->getMessageId());
        $message->sendReply("Reply to message with id: " . $message->getMessageId());
      } else if ($message->getText() === "/test_typing") {
        $this->api->sendChatAction($message->getFrom(), "typing");
      } else if ($message->getText() === "/test_forceReplay") {
        $this->api->sendMessage($message->getFrom(), "Reply to me!", null, null, new TA_ForceReply());
      } else if ($message->getText() === "/test_profilephotos") {
        $profilePhotos = $this->api->getUserProfilePhotos($message->getFrom());
        //$this->api->sendPhoto($message->getFrom(), $profilePhotos->getPhoto($profilePhotos->getNumberOfPhotos() - 1), "First"); // Gets first profile photo
        //$this->api->sendPhoto($message->getFrom(), $profilePhotos->getPhoto(0), "Current"); // Gets last (current) profile photo
        foreach ($profilePhotos->getAll() as $key => $photo) {
          $this->api->sendPhoto($message->getFrom(), $photo, $key, $message);
        }
      }
    }
  }
}
