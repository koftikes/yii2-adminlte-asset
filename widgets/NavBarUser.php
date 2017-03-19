<?php

namespace sbs\widgets;

use Yii;
use sbs\models\UserInterface;
use yii\base\InvalidConfigException;
use yii\bootstrap\Widget;
use yii\bootstrap\Html;

class NavBarUser extends Widget
{
    /**
     * @var UserInterface
     */
    private $user;

    public $profileUrl;

    public $logoutUrl;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        $this->user = Yii::$app->user->identity;

        $interfaces = class_implements($this->user);
        if (!isset($interfaces['sbs\models\UserInterface'])) {
            throw new InvalidConfigException('User should implement UserInterface.');
        }
        parent::init();
    }

    public function run()
    {
        return Html::tag('li',
            Html::a(
                $this->getAvatar('user-image') . Html::tag('span', $this->user->getName(), ['class' => 'hidden-xs']),
                '#', ['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown']) .
            Html::tag('ul', $this->getHeader() . $this->getInfo() . $this->getFooter(), ['class' => 'dropdown-menu']),
            ['class' => 'dropdown user user-menu']);
    }

    protected function getHeader()
    {
        $user = $this->user->getName();
        $user .= ($this->user->getTitle()) ? ' - ' . $this->user->getTitle() : '';
        $user .= Html::tag('small', Yii::t('backend', 'Member since: ') . $this->user->getMemberSince());

        return Html::tag('li', $this->getAvatar('img-circle') . Html::tag('p', $user), ['class' => 'user-header']);
    }

    protected function getInfo()
    {
        if ($this->user->getInfo()) {
            return Html::tag('li',
                Html::tag('div', Html::tag('div', $this->user->getInfo(), ['class' => 'col-xs-12 text-center']),
                    ['class' => 'row']), ['class' => 'user-body']);
        }

        return '';
    }

    protected function getFooter()
    {
        $profile = Html::tag('div', Html::a(Yii::t('backend', 'Profile'), [$this->profileUrl],
            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']), ['class' => 'pull-left']);
        $logout = Html::tag('div', Html::a(Yii::t('backend', 'Sign out'), [$this->logoutUrl],
            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']), ['class' => 'pull-right']);

        return Html::tag('li', $profile . $logout, ['class' => 'user-footer']);
    }

    protected function getAvatar($class)
    {
        return Html::img(($this->user->getAvatar()) ?: '@web/images/avatar_1.png', ['class' => $class]);
    }
}
