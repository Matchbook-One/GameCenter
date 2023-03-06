<?php

/**
 * @package GameCenter
 * @author  Christian Seiler <christian@christianseiler.ch>
 * @since   1.0.0
 */

namespace fhnw\modules\gamecenter;

use humhub\modules\content\components\{ContentContainerActiveRecord, ContentContainerModule};
use humhub\modules\space\models\Space;
use Yii;
use yii\helpers\Url;

/**
 * The GameCenter Module
 *
 * @property-read string   $configUrl
 * @property-read string[] $contentContainerTypes
 */
class GameCenterModule extends ContentContainerModule
{

    /** @var string $controllerNamespace */
    public $controllerNamespace = 'fhnw\modules\gamecenter\controllers';

    /** @var string $icon defines the icon */
    public $icon = 'gamepad';

    /** @var string $resourcesPath defines path for resources, including the screenshots path for the marketplace */
    public $resourcesPath = 'resources';

    /** @return void */
    public function init(): void
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Translates a message to the specified language.
     * This is a shortcut method of [[\yii\i18n\I18N::translate()]].
     * The translation will be conducted according to the message category and the target language will be used.
     * You can add parameters to a translation message that will be substituted with the corresponding value after translation.
     * The format for this is to use curly brackets around the parameter name as you can see in the following example:
     * ```php
     * $username = 'Alexander';
     * echo \GameCenterModule::t('app', 'Hello, {username}!', ['username' => $username]);
     * ```
     * Further formatting of message parameters is supported using the [PHP intl extensions](https://www.php.net/manual/en/intro.intl.php)
     * message formatter.
     * See [[\yii\i18n\I18N::translate()]] for more details.
     *
     * @param string   $category the message category.
     * @param string   $message  the message to be translated.
     * @param string[] $params   the parameters that will be used to replace the corresponding placeholders in the message.
     * @param ?string  $language the language code (e.g. `en-US`, `en`).
     *                           If this is null, the current
     *                           [[\yii\base\Application::language|application
     *                           language]] will be used.
     *
     * @return string the translated message.
     */
    public static function t(string $category, string $message, array $params=[], string $language=null): string
    {
        return Yii::t("gamecenter/$category", $message, $params, $language);
    }

    /**
     * @inheritdoc
     * @return string
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getConfigUrl(): string
    {
        return Url::to(['/gamecenter/admin']);
    }

    /**
     * @inheritdoc
     *
     * @param ContentContainerActiveRecord $container unused
     *
     * @return  string
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container): string
    {
        return $this->getDescription();
    }

    /**
     * @inheritdoc
     *
     * @param ContentContainerActiveRecord $container unused
     *
     * @return string
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getContentContainerName(ContentContainerActiveRecord $container): string
    {
        return $this->getName();
    }

    /**
     * @inheritdoc
     * @phpstan-return array<class-string>
     * @return string[]
     * @noinspection PhpMissingParentCallCommonInspection
     */
    public function getContentContainerTypes(): array
    {
        return [Space::class];
    }

    /**
     * Returns modules description provided by module.json file
     *
     * @return string Description
     */
    public function getDescription(): string
    {
        return self::t('config', 'Manage Games');
    }

    /**
     * Returns modules name provided by module.json file
     *
     * @return string Name
     */
    public function getName(): string
    {
        return self::t('config', 'GameCenter');
    }

    /**
     * @return void
     */
    private function registerTranslations(): void
    {
        Yii::$app->i18n->translations['gamecenter/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@gamecenter/messages'
        ];
    }
}
