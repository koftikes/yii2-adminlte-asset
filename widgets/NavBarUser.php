<?php

namespace sbs\widgets;

use Yii;
use sbs\models\UserInterface;
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
            return false;
        }
        parent::init();

        echo Html::beginTag('li', ['class' => 'dropdown user user-menu']);
        echo Html::a(
            $this->getAvatar('user-image') . Html::tag('span', $this->user->getName(), ['class' => 'hidden-xs']), '#',
            ['class' => 'dropdown-toggle', 'data-toggle' => 'dropdown']
        );
        echo Html::beginTag('ul', ['class' => 'dropdown-menu']);
        echo Html::beginTag('li', ['class' => 'user-header']);
        echo $this->getAvatar('img-circle');
        $user = $this->user->getName();
        $user .= ($this->user->getTitle()) ? ' - ' . $this->user->getTitle() : '';
        $user .= Html::tag('small', Yii::t('back', 'Member since: ') . $this->user->getMemberSince());
        echo Html::tag('p', $user);
        echo Html::endTag('li');
        if ($this->user->getInfo()) {
            echo Html::beginTag('li', ['class' => 'user-body']);
            echo Html::beginTag('div', ['class' => 'row']);
            echo Html::tag('div', $this->user->getInfo(), ['class' => 'col-xs-12 text-center']);
            echo Html::endTag('div');
            echo Html::endTag('li');
        }
        echo Html::beginTag('li', ['class' => 'user-footer']);
        echo Html::tag('div', Html::a(Yii::t('back', 'Profile'), [$this->profileUrl],
            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']), ['class' => 'pull-left']);
        echo Html::tag('div', Html::a(Yii::t('back', 'Sign out'), [$this->logoutUrl],
            ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']), ['class' => 'pull-right']);
        echo Html::endTag('li');
        echo Html::endTag('ul');
        echo Html::endTag('li');
    }

    protected function getAvatar($class)
    {
        return Html::img(($this->user->getAvatar()) ?: '@web/images/avatar_1.png', ['class' => $class]);
    }
}
