<?php
namespace common\helpers;

use Yii;

class MyActiveDataProvider extends \yii\data\ActiveDataProvider
{
    private $_pagination;

    public function init()
    {
        parent::init();
    }

    public function getPagination()
    {
        if ($this->_pagination === null) {
            $this->setPagination([]);
        }

        return $this->_pagination;
    }

    public function setPagination($value)
    {
        if (is_array($value)) {
            $config = ['class' => MyPagination::className()];
            if ($this->id !== null) {
                $config['pageParam'] = $this->id . '-page';
                $config['pageSizeParam'] = $this->id . '-per-page';
            }

            $this->_pagination = Yii::createObject(array_merge($config, $value));            
        } elseif ($value instanceof MyPagination || $value === false) {
            $this->_pagination = $value;
        } else {
            throw new InvalidParamException('Only Pagination instance, configuration array or false is allowed.');
        }
    }
}