<?php

namespace app\controllers;

use app\models\City;
use app\models\Review;
use app\models\ReviewForm;
use app\models\ReviewSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ReviewController implements the CRUD actions for Review model.
 */
class ReviewController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index'],
                            'roles' => ['?'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['index', 'create', 'update'],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => false,
                            'roles' => ['?']
                        ],
                    ],
                ],
            ],
        );
    }

    /**
     * Lists all Review models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $query = Review::find();
        $userCity = Yii::$app->session->get('user_city');

        if ($this->request->get('id_author')) {
            $query->andWhere(['id_author' => $this->request->get('id_author')])->all();
        } elseif ($userCity && $userCity !== 'Undefined') {
            $query->where(['or', ['id_city' => $this->getCityIdByName($userCity)], ['id_city' => null]]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_create' => SORT_DESC,
                ]
            ],
        ]);

        $searchModel = new ReviewSearch();
//        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Review model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Review model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate(): Response|string
    {
        $form = new ReviewForm();

        if (Yii::$app->request->isAjax) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $form->img = UploadedFile::getInstance($form, 'img');
                if ($form->saveReview()) {
                    return <<<HTML
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p>Review successfully created</p>
                    </div>
                    HTML;
                }
            }
        }

//        if ($this->request->isPost) {
//            $form->load($this->request->post());
//            $form->img = UploadedFile::getInstance($form, 'img');
//            if ($form->saveReview()) {
//                return $this->redirect(['review/index']);
//            }
//        }

        return $this->render('create', [
            'model' => $form,
        ]);
    }

    /**
     * Updates an existing Review model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $modelFromDb = $this->findModel($id);

        //fill form model with data from db
        //TODO: maybe there is a better way to do this
        $form = new ReviewForm();
        $form->title = $modelFromDb->title;
        $form->text = $modelFromDb->text;
        $form->city = $modelFromDb->id_city;
        $form->rating = $modelFromDb->rating;


        if (Yii::$app->request->isAjax) {
            if ($form->load(Yii::$app->request->post()) && $form->validate()) {
                $form->img = UploadedFile::getInstance($form, 'img');
                if ($form->updateReview($modelFromDb)) {
                    return <<<HTML
                    <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close"
                                data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p>Review successfully updated</p>
                    </div>
                    HTML;
                }
            }
        }

//        if ($this->request->isPost && $modelFromDb->load($this->request->post()) && $modelFromDb->save()) {
//            return $this->redirect(['review/index']);
//        }

        //pass model from db to display id in url
        return $this->render('update', [
            'modelFromDb' => $modelFromDb,
            'model' => $form,
        ]);
    }

    /**
     * Deletes an existing Review model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Review model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Review the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Review
    {
        if (($model = Review::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Gets city id by name from DB.
     *
     * @param string $name city name
     * @return int city id
     */
    protected function getCityIdByName(string $name): int
    {
        return City::find()
            ->select('id')
            ->where(['name' => $name])
            ->one()->id;
    }
}
