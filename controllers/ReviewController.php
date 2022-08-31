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
        $model = new ReviewForm();

        if ($this->request->isPost) {
            $model->load($this->request->post());
            $model->img = UploadedFile::getInstance($model, 'img');
            if ($model->saveReview()) {
                return $this->redirect(['review/index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
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
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['review/index']);
        }

        return $this->render('update', [
            'model' => $model,
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
