<?php
namespace cpanel\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\forms\PageForm;
use common\models\Page;

class PageController extends \common\controllers\cpanel\Controller
{
	public $menu;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['edit', 'index', 'add', ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $this->menu = $this->renderPartial('menu.tpl');

        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $data['menu'] = $this->menu;

        return $this->render('index.tpl', $data);
    }

    public function actionAdd()
    {
        $data['menu'] = $this->menu;

        $data['pageForm'] = new PageForm;

        return $this->render('form.tpl', $data);
    }

    public function actionEdit($id=0)
    {
        $data['menu'] = $this->menu;

        $page = Page::findOne($id);

        if (!$page) {
            throw new \yii\web\HttpException(404, 'Page not found.');
        }

        $data['pageForm'] = new PageForm;
        $data['pageForm']->setAttributes($page->attributes, FALSE);

        return $this->render('form.tpl', $data);
    }

}