<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\forms\LoginForm;
use common\models\Company;
use common\models\Page;
use common\helpers\Razor;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $menu;

    public function behaviors()
    {

        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'faq', 'terms', 'marketingoffer', 'platformupdates', 'mclub-privacy', 'p9club-privacy'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionMclubPrivacy()
    {
        $this->layout = 'public';

        return $this->render('mclub-privacy.tpl');
    }

    public function actionP9clubPrivacy()
    {
        $this->layout = 'public';

        return $this->render('p9club-privacy.tpl');
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'common\web\ServerErrorAction',
            ],
             //The document preview addesss:http://api.yourhost.com/site/doc
             'doc' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => \yii\helpers\Url::to(['/site/api'], true),

            ],
            //The resultUrl action.
            'api' => [
                'class' => 'light\swagger\SwaggerApiAction',
                //The scan directories, you should use real path there.
                'scanDir' => [
                    //Yii::getAlias('@swagger'), 
                    Yii::getAlias('@swagger'),
                ],
                //The security key
                'api_key' => null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $data['funds'] = $this->renderPartial('/account/funds.tpl');
        
        $data['menu'] = $this->renderPartial('menu.tpl', $data);

        return $this->render('index.tpl', $data);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        $this->layout = 'login';
        
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {                       
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * Public pages
     */
    public function actionFaq()
    {
        $pageName = 'faq';

        if (Yii::$app->user->isGuest) $this->layout = 'login';

        $page = Page::getByName($pageName);

        $razor = new Razor;
        $content = $razor->renderString($pageName, $page->content, $page->attributes);

        return $this->render('page.tpl', ['content' => $content]);
    }

    public function actionTerms()
    {
        $pageName = 'terms';

        if (Yii::$app->user->isGuest) $this->layout = 'login';

        $page = Page::getByName($pageName);

        $razor = new Razor;
        $content = $razor->renderString($pageName, $page->content, $page->attributes);

        return $this->render('page.tpl', ['content' => $content]);
    }

    public function actionMarketingoffer()
    {
        $pageName = 'marketing-offer';

        if (Yii::$app->user->isGuest) $this->layout = 'login';

        $page = Page::getByName($pageName);

        $razor = new Razor;
        $content = $razor->renderString($pageName, $page->content, $page->attributes);

        return $this->render('page.tpl', ['content' => $content]);
    }

    public function actionPlatformupdates()
    {
        $pageName = 'platform-updates';

        if (Yii::$app->user->isGuest) $this->layout = 'login';

        $page = Page::getByName($pageName);

        $razor = new Razor;
        $content = $razor->renderString($pageName, $page->content, $page->attributes);

        return $this->render('page.tpl', ['content' => $content]);
    }

    

    
}
