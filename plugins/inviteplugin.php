<?php

/**
 * 
 * v0.2 - 2013-08-28 - set invite via the "sendformat" instead of adding it's own tab
 * v0.1 - initial 
 * 
 */

class inviteplugin extends phplistPlugin {
  public $name = "Invite plugin for phpList";
  public $coderoot = '';
  public $version = "0.2";
  public $authors = 'Michiel Dethmers';
  public $enabled = 1;
  public $description = 'Send an invite to subscribe to the phpList mailing system';
  public $settings = array(
    "inviteplugin_subscribepage" => array (
      'value' => 0,
      'description' => 'Subscribe page for invitation responses',
      'type' => "integer",
      'allowempty' => 0,
      'min'=> 0,
      'max'=> 999999,
      'category'=> 'transactional',
    ),
  );
  
  function __construct() {
    parent::phplistplugin();
  }

  function adminmenu() {
    return array(
    );
  }
  
  function sendFormats() {
    return array ('invite' => s('Invite'));
  }
  
  function XsendMessageTab($messageid = 0, $data = array ()) {
    if (!$this->enabled)
      return null;
      
    $checkbox = '<input type="radio" name="sendInvite" value="1" ';
    if (!empty($data['sendInvite'])) {
      $checkbox .= ' checked="checked"';
    }
    $checkbox .= '/> '. s('Send as invitation');
    $checkbox .= '<input type="radio" name="sendInvite" value="0" ';
    if (empty($data['sendInvite'])) {
      $checkbox .= ' checked="checked"';
    }
    $checkbox .= '/> '. s('Do not send as invitation');

    return $checkbox;
  }

  function upgrade($previous) {
    parent::upgrade($previous);
    return true;
  }
  
  function XsendMessageTabTitle($messageid = 0) {
    if (!$this->enabled)
      return null;

    return s('Invite');
  }
  
  function allowMessageToBeQueued($messagedata = array()) {
    ## we only need to check if this is sent as an invite
    if ($messagedata['sendformat'] == 'invite') {
      $cnt = '';
      $hasConfirmationLink = false;
      foreach ($messagedata as $key => $val) {
        if (!is_array($val)) {
          $cnt .= $key .' = '.$val."\n";
        }
        if (is_string($val)) {
          $hasConfirmationLink = $hasConfirmationLink ||
            (strpos($val,'[CONFIRMATIONURL]') !== false || strpos($val,'[CONFIRMATIONURL]') !== false);
        }
      }
      if (!$hasConfirmationLink) {
        return $GLOBALS['I18N']->get('Your campaign does not contain a the confirmation URL placeholder, which is necessary for an invite mailing. Please add [CONFIRMATIONURL] to the footer or content of the campaign.');
      }
    }
    
    return '';
  }

  function processSendSuccess($messageid, $userdata, $isTestMail = false) {
    $messagedata = loadMessageData($messageid);
    if (!$isTestMail && !empty($messagedata['sendInvite'])) {  
      if (!isBlackListed($userdata['email'])) {
        addUserToBlackList($userdata['email'],s('Blacklisted by the invitation plugin'));
      }
    }
    ## if subscribe page is set, mark this subscriber for that page
    $sPage = getConfig('inviteplugin_subscribepage');
    if (!empty($sPage)) {
      Sql_Query(sprintf('update %s set subscribepage = %d where id = %d',$GLOBALS['tables']['user'],$sPage,$userdata['id']));
    }
  }
  
  
}
