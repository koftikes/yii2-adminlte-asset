<?php
namespace sbs\helpers;

use yii\helpers\Html;

class Content
{
    public static function pageHeader($text, $small_text = '')
    {
        if ($small_text) {
            $text .= ' ' . Html::tag('small', $small_text);
        }

        return Html::tag('h1', $text);
    }
}
