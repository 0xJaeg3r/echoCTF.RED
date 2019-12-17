<?php

namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use app\modules\target\models\Target;
use app\modules\target\models\Treasure;
use app\models\PlayerTreasure;
use app\models\PlayerScore;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class DashboardController extends \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                ],
            ],
        ];
    }

    public function actionIndex()
    {

      $command = Yii::$app->db->createCommand('SELECT (SELECT IFNULL(SUM(points),0) FROM finding)+(SELECT IFNULL(SUM(points),0) FROM treasure)+(SELECT IFNULL(SUM(points),0) FROM badge)+(SELECT IFNULL(SUM(points),0) FROM question WHERE player_type=:player_type)');
      $command->bindValue(':player_type','offense');
      $totalPoints = $command->queryScalar();
      $treasureStats=new \stdClass();
      $treasureStats->total=(int)Treasure::find()->count();
      $treasureStats->claims=(int)PlayerTreasure::find()->count();
      $treasureStats->claimed=(int)PlayerTreasure::find()->where(['player_id'=>Yii::$app->user->id])->count();
      $totalHeadshots=0;
      $tmod=Target::find()->active();
      foreach($tmod->all() as $model)
      {
        $totalHeadshots+=count($model->getHeadshots());
      }

      $userHeadshots=Target::findBySql('SELECT t.*,inet_ntoa(t.ip) as ipoctet,count(distinct t2.id) as total_treasures,count(distinct t4.treasure_id) as player_treasures, count(distinct t3.id) as total_findings, count(distinct t5.finding_id) as player_findings FROM target AS t left join treasure as t2 on t2.target_id=t.id left join finding as t3 on t3.target_id=t.id LEFT JOIN player_treasure as t4 on t4.treasure_id=t2.id and t4.player_id=:player_id left join player_finding as t5 on t5.finding_id=t3.id and t5.player_id=:player_id GROUP BY t.id HAVING player_treasures=total_treasures and player_findings=total_findings ORDER BY t.ip,t.fqdn,t.name')
        ->params([':player_id'=>\Yii::$app->user->id])->all();

      return $this->render('index', [
          'totalPoints'=>$totalPoints,
          'treasureStats'=>$treasureStats,
          'totalHeadshots'=>$totalHeadshots,
          'userHeadshots'=>$userHeadshots,
      ]);
    }

}
