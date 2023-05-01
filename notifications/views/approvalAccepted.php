<?php

/**
 * @var yii\web\View                                                    $this
 * @var humhub\modules\space\notifications\ApprovalRequestAccepted      $viewable
 * @var string                                                          $url
 * @var string                                                          $date
 * @var bool                                                            $isNew
 * @var \humhub\modules\user\models\User                                $originator
 * @var \fhnw\modules\gamecenter\models\Game                            $source
 * @var \humhub\modules\content\components\ContentContainerActiveRecord $contentContainer
 * @var humhub\modules\space\models\Space                               $space
 * @var \humhub\modules\notification\models\Notification                $record
 * @var string                                                          $html
 * @var string                                                          $text
 */

use fhnw\modules\gamecenter\GameCenterModule;
use humhub\widgets\mails\MailButtonList;
use humhub\widgets\mails\MailContentContainerImage;
use yii\helpers\Url;

?>

<?php $this->beginContent('@notification/views/layouts/mail.php', $_params_); ?>
  <table style="width:100%; border:0; cellspacing: 0; cellpadding: 0; align: left;">
    <tr>
      <td
        style="font-size: 14px; line-height: 22px; font-family:Open Sans,Arial,Tahoma, Helvetica, sans-serif; color:#555555; font-weight:300; text-align:left;">
        <?= $viewable->html(); ?>
      </td>
    </tr>
    <tr>
      <td height="10"></td>
    </tr>
    <tr>
      <td height="10" style="border-top: 1px solid #eee;"></td>
    </tr>
    <tr>
      <td style="padding-top: 10px;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
          <tr>
            <td width="109"></td>
            <td width="50"><?= MailContentContainerImage::widget(['container' => $originator]) ?></td>
            <td width="109"></td>
            <td width="25"><img src="<?= Url::to('@web-static/img/mail_ico_check.png', true); ?>"/></td>
            <td width="109"></td>
            <td width="50"><?= MailContentContainerImage::widget(['container' => $space]) ?></td>
            <td></td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td height="20"></td>
    </tr>
    <tr>
      <td>
        <?= MailButtonList::widget(
          [
            'buttons' => [
              humhub\widgets\mails\MailButton::widget(
                ['url' => $url, 'text' => GameCenterModule::t('notification', 'View Online')]
              )
            ]
          ]
        ); ?>
      </td>
    </tr>
  </table>
<?php $this->endContent();
