<?php

namespace sbs\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use rmrevin\yii\fontawesome\FA;
use yii\bootstrap\Widget;
use yii\bootstrap\Html;

/**
 * Nav renders a nav HTML component.
 *
 * For example:
 *
 * ```php
 * echo SideBarMenu::widget([
 *     'items' => [
 *          'Navigation',
 *         [
 *             'label' => 'Home',
 *             'url' => ['site/index'],
 *             'linkOptions' => [...],
 *         ],
 *         [
 *             'label' => '',
 *             'icon' => 'envelope-o',
 *             'badge' => '4',
 *             'badgeColor' => 'red',
 *             'items' => [
 *                  ['label' => 'See messages', 'url' => ['/mail/inbox']],
 *             ],
 *         ],
 *         [
 *             'label' => 'Login',
 *             'url' => ['site/login'],
 *             'visible' => Yii::$app->user->isGuest
 *         ],
 *     ],
 *     'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
 * ]);
 * ```
 */
class SideBarMenu extends Widget
{
    /**
     * @var array list of items in the nav widget. Each array element represents a single
     * menu item which can be either a string or an array with the following structure:
     *
     * - label: string, required, the nav item label.
     * - url: optional, the item's URL. Defaults to "#".
     * - visible: boolean, optional, whether this menu item is visible. Defaults to true.
     * - linkOptions: array, optional, the HTML attributes of the item's link.
     * - options: array, optional, the HTML attributes of the item container (LI).
     * - active: boolean, optional, whether the item should be on active state or not.
     * - dropDownOptions: array, optional, the HTML options that will passed to the [[Dropdown]] widget.
     * - items: array|string, optional, the configuration array for creating a [[Dropdown]] widget,
     *   or a string representing the dropdown menu. Note that Bootstrap does not support sub-dropdown menus.
     *
     * If a menu item is a string, it will be rendered directly without HTML encoding.
     */
    public $items = [];

    /**
     * @var string this property allows you to customize the HTML which is used to generate the drop down caret symbol,
     * which is displayed next to the button text to indicate the drop down functionality.
     * Defaults to `null` which means `<i class="fa fa-angle-left pull-right"></i>` will be used. To disable the caret, set this property to be an empty string.
     */
    public $treeViewCaret;

    /**
     * @var string the route used to determine if a menu item is active or not.
     * @see params
     * @see isItemActive
     */
    private $route;

    /**
     * @var array the parameters used to determine if a menu item is active or not.
     * @see route
     * @see isItemActive
     */
    private $params;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        parent::init();
        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }
        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
        if ($this->treeViewCaret === null) {
            $this->treeViewCaret = FA::icon('angle-left')->pullRight();
        }
        Html::addCssClass($this->options, ['widget' => 'sidebar-menu']);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        return $this->renderItems($this->items, $this->options);
    }

    /**
     * Renders widget items.
     *
     * @param $items
     * @param $options
     * @return string
     */
    public function renderItems($items, $options)
    {
        $lines = [];
        foreach ($items as $item) {
            if (isset($item['visible']) && !$item['visible']) {
                continue;
            }
            $lines[] = $this->renderItem($item);
        }

        return Html::tag('ul', implode("\n", $lines), $options);
    }

    /**
     * Renders a widget's item.
     * @param string|array $item the item to render.
     * @return string the rendering result.
     * @throws InvalidConfigException
     */
    public function renderItem($item)
    {
        if (is_string($item)) {
            return Html::tag('li', $item, ['class' => 'header']);
        }

        if (!isset($item['label'])) {
            throw new InvalidConfigException("The 'label' option is required.");
        }

        $label = Html::tag('span', ArrayHelper::getValue($item, 'label', ''));
        $icon = (ArrayHelper::getValue($item, 'icon')) ?: 'circle-o';
        $label = FA::icon($icon) . ' ' . $label;

        $badge = ArrayHelper::getValue($item, 'badge');
        if ($badge) {
            $badgeColor = ArrayHelper::getValue($item, 'badgeColor', 'red');
            $label .= ' ' . Html::tag('small', $badge, ['class' => 'badge pull-right bg-' . $badgeColor]);
        }
        $options = ArrayHelper::getValue($item, 'options', []);
        $items = ArrayHelper::getValue($item, 'items');
        $url = ArrayHelper::getValue($item, 'url', '#');
        $linkOptions = ArrayHelper::getValue($item, 'linkOptions', []);
        $active = $this->isItemActive($item);

        if ($items !== null) {
            Html::addCssClass($options, ['widget' => 'treeview']);
            if ($this->treeViewCaret !== '' && empty($badge)) {
                $label .= ' ' . $this->treeViewCaret;
            }
            if (is_array($items)) {
                $items = $this->isChildActive($items, $active);
                $items = $this->renderItems($items, ['class' => 'treeview-menu']);
            }
        }

        if ($active) {
            Html::addCssClass($options, 'active');
        }

        return Html::tag('li', Html::a($label, $url, $linkOptions) . $items, $options);
    }

    /**
     * Check to see if a child item is active optionally activating the parent.
     * @param array $items @see items
     * @param boolean $active should the parent be active too
     * @return array @see items
     */
    protected function isChildActive($items, &$active)
    {
        foreach ($items as $i => $child) {
            if ($this->isItemActive($child)) {
                Html::addCssClass($items[$i]['options'], 'active');
                $active = true;
            }
        }

        return $items;
    }

    /**
     * Checks whether a menu item is active.
     * This is done by checking if [[route]] and [[params]] match that specified in the `url` option of the menu item.
     * When the `url` option of a menu item is specified in terms of an array, its first element is treated
     * as the route for the item and the rest of the elements are the associated parameters.
     * Only when its route and parameters match [[route]] and [[params]], respectively, will a menu item
     * be considered active.
     * @param array $item the menu item to be checked
     * @return boolean whether the menu item is active
     */
    protected function isItemActive($item)
    {
        if (isset($item['url']) && is_array($item['url']) && isset($item['url'][0])) {
            $route = $item['url'][0];
            if ($route[0] !== '/' && Yii::$app->controller) {
                $route = Yii::$app->controller->module->getUniqueId() . '/' . $route;
            }
            if (ltrim($route, '/') !== $this->route) {
                return false;
            }
            unset($item['url']['#']);
            if (count($item['url']) > 1) {
                $params = $item['url'];
                unset($params[0]);
                foreach ($params as $name => $value) {
                    if ($value !== null && (!isset($this->params[$name]) || $this->params[$name] != $value)) {
                        return false;
                    }
                }
            }

            return true;
        }

        return false;
    }
}
