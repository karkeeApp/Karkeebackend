<?php
namespace common\helpers;

use Yii;
use yii\base\InvalidParamException;
use yii\db\ActiveRecordInterface;
use yii\validators\StringValidator;
use yii\web\Request;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class Html extends \yii\helpers\Html
{
	public static function activePrint($model, $attribute, $options = [])
    {
    	$name = isset($options['name']) ? $options['name'] : static::getInputName($model, $attribute);
        if (isset($options['value'])) {
            $value = $options['value'];
            unset($options['value']);
        } else {
            $value = static::getAttributeValue2($model, $attribute);
        }

        $value = "<div class='grey-bg form-control-view input-sm form-control-view'>{$value}</div>";

        return static::tag('div', $value, $options);
    }

    public static function getAttributeValue2($model, $attribute)
    {
        if (!preg_match(static::$attributeRegex, $attribute, $matches)) {
            throw new InvalidParamException('Attribute name must contain word characters only.');
        }
        $attribute = $matches[2];

		if (method_exists($model, $attribute)) {
		    $value = $model->{$attribute}();
		} else {
		    $value = $model->{$attribute};
		}

        if ($matches[3] !== '') {
            foreach (explode('][', trim($matches[3], '[]')) as $id) {
                if ((is_array($value) || $value instanceof \ArrayAccess) && isset($value[$id])) {
                    $value = $value[$id];
                } else {
                    return null;
                }
            }
        }

        // https://github.com/yiisoft/yii2/issues/1457
        if (is_array($value)) {
            foreach ($value as $i => $v) {
                if ($v instanceof ActiveRecordInterface) {
                    $v = $v->getPrimaryKey(false);
                    $value[$i] = is_array($v) ? json_encode($v) : $v;
                }
            }
        } elseif ($value instanceof ActiveRecordInterface) {
            $value = $value->getPrimaryKey(false);

            return is_array($value) ? json_encode($value) : $value;
        }

        return $value;
    }
}