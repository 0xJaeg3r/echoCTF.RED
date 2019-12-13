<?php
use yii\grid\GridView;
use app\components\JustGage;
use yii\helpers\Html;
use yii\helpers\Url;

echo GridView::widget([
   'id'=>$divID,
   'pager'=>[
     'class'=>'yii\bootstrap4\LinkPager',
     'options'=>['id'=>'target-pager','class'=>'align-middle'],
     'firstPageLabel' => '<i class="fas fa-step-backward"></i>',
     'lastPageLabel' => '<i class="fas fa-step-forward"></i>',
     'maxButtonCount'=>3,
     'disableCurrentPageButton'=>true,
     'prevPageLabel'=>'<i class="fas fa-chevron-left"></i>',
     'nextPageLabel'=>'<i class="fas fa-chevron-right"></i>',
   ],
   'options'=>['class'=>'card'],
   'tableOptions'=>['class'=>'table table-xl'],
   'layout'=>$layout,
   'dataProvider' => $dataProvider,
   'summary'=>$summary,
   'columns' => [
     [
       'label'=>'',
       'contentOptions' => ['class' => 'text-center'],
       'headerOptions' => ['class' => 'text-center',"style"=>'width: 1.5em'],
       'format'=>'raw',
       'value'=>function($model){return sprintf('<img src="/images/targets/_%s.png" alt="%s" class="img-fluid" style="max-width: 20px;max-heigh:30px">',$model->name, $model->fqdn);}
     ],
     [
       'attribute'=>'name',
       'label'=>'Target',
     ],
     [
       'attribute'=>'ip',
       'label'=>'IP',
       'headerOptions' => ["style"=>'width: 6vw;'],
       'value'=>function($model){return long2ip($model->ip);}
     ],
     [
       'attribute'=>'difficulty',
       'format'=>'raw',
       'encodeLabel'=>false,
       'label'=>'<abbr title="Difficulty rating">Difficulty</abbr>',
       'contentOptions' => ['class' => 'd-none d-xl-table-cell'],
       'headerOptions' => ['class' => 'text-center d-none d-xl-table-cell'],
       'value'=>function($model){
         $progress=($model->difficulty*20);
         $color="";
         switch($model->difficulty)
         {
           case 0:
             $progress=($model->difficulty*20)+1;
             $bgcolor="";
             break;
           case 1:
             $bgcolor='bg-info';
             break;
           case 2:
             $bgcolor="bg-primary";
             break;
           case 3:
             $bgcolor="bg-warning";
             break;
           case 4:
             $bgcolor="bg-danger";
             break;
           case 5:
           default:
         }
         return '<center>'.JustGage::widget(['id'=>$model->name.'-'.$model->ip,"htmlOptions"=>['style'=>"width:50px; height:40px"],'options'=>['relativeGaugeSize'=>true,'textRenderer'=>'function (val) {return "";}','min'=>0,'max'=>5,'value'=>$model->difficulty,/*'title'=>$model->difficultyText*/]]).'</center>';
       },
     ],
     [
       'attribute'=>'rootable',
       'format'=>'raw',
       'headerOptions' => ['class' => 'text-center',"style"=>'width: 4rem'],
       'contentOptions' => ['class' => 'text-center'],
       'encodeLabel'=>false,
       'label'=>'<abbr title="Target rootable or not?"><i class="fa fa-hashtag" aria-hidden="true"></i></abbr>',
       'value'=>function($model){return intval($model->rootable)==0 ? '':'<abbr title="Rootable"><i class="fa fa-hashtag"></i></abbr>';},
     ],
     [
       'format'=>'raw',
       'encodeLabel'=>false,
       'headerOptions' => ["style"=>'width: 4rem','class' => 'text-center'],
       'contentOptions' => ['class' => 'text-center'],
       'label'=>'<abbr title="Services"><i class="fa fa-fire" aria-hidden="true"></i></abbr>',
       'attribute'=>'total_findings',
       'value'=>function($model) { return sprintf('<i class="fas fa-fire"></i> %d<small>/%d</small>',$model->total_findings,$model->player_findings); },
     ],
     [
       'format'=>'raw',
       'encodeLabel'=>false,
       'headerOptions' => ["style"=>'width: 4rem','class' => 'text-center'],
       'contentOptions' => ['class' => 'text-center'],
       'label'=>'<abbr title="Flags"><i class="fa fa-flag" aria-hidden="true"></i></abbr>',
       'attribute'=>'total_treasures',
       'value'=>function($model) { return sprintf('<i class="fas fa-flag"></i> %d<small>/%d</small>',$model->total_treasures,$model->player_treasures); },
     ],
     [
       'format'=>'raw',
       'encodeLabel'=>false,
       'headerOptions' => ["style"=>'width: 4rem','class' => 'text-center'],
       'contentOptions' => ['class' => 'text-center'],
       'attribute'=>'headshots',
       'label'=>'<abbr title="Number of users who owned all flags and services: Headshots"><i class="fas fa-skull"></i></abbr>',
       'value'=>function($model) { if($model->total_treasures==$model->player_treasures && $model->player_findings==$model->total_findings) return '<i class="fas fa-skull text-primary"></i> '.count($model->headshots); return '<i class="fas fa-skull"></i> '.count($model->headshots); },
     ],
     [
       'format'=>'raw',
       'encodeLabel'=>false,
       'headerOptions' => ['class'=>'text-center d-none d-xl-table-cell','style'=>'width:9rem;'],
       'contentOptions' => ['class'=>'d-none d-xl-table-cell'],
       'attribute'=>'progress',
       'label'=>'Your Progress',
       'value'=>function($model) {
         return sprintf ('<div class="progress" style="height: 14px;"><div class="progress-bar bg-gradual-progress" style="width: %d%%;" role="progressbar" aria-valuenow="%d" aria-valuemin="0" aria-valuemax="100"></div></div>',$model->progress, $model->progress,$model->progress==100 ? '#Headshot': number_format($model->progress).'%');
         return '<div class="progress"></div>';
       },
     ],
     [
       'class'=> 'rce\material\grid\ActionColumn',
       'visible'=>!$personal,
       'headerOptions' => ["style"=>'width: 4rem'],
       'template'=>'{spin} {view}',
       'buttons' => [
         'spin' => function ($url,$model) {
             return Html::a(
                 '<i class="material-icons large">power_settings_new</i>',
                 Url::to(['/target/default/spin','id'=>$model->id]),
                 [
                   'style'=>"font-size: 1.5em;",
                   'title' => 'Restart container',
                   'data-pjax' => '0',
                   'data-method' => 'POST',
                 ]
             );
         },
         'view' => function ($url,$model) {
             return Html::a(
               '<i class="material-icons">remove_red_eye</i>',
                 Url::to(['/target/default/index','id'=>$model->id]),
                 [
                   'style'=>"font-size: 1.5em;",
                   'rel'=>"tooltip",
                   'title' => 'View target',
                   'data-pjax' => '0',
                 ]
             );
         }
     ],
     'visibleButtons' => [
       'spin' => function ($model) {
           return $model->spinable;
         },
     ]

   ]
   ],
]);