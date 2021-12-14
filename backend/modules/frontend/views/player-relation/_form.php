<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\frontend\models\Player;
/* @var $this yii\web\View */
/* @var $model app\modules\frontend\models\PlayerRelation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="player-relation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'player_id')->dropDownList(ArrayHelper::map(Player::find()->where(['active'=>1,'status'=>10])->orderBy(['username'=>SORT_ASC])->all(), 'id', 'username'), ['prompt'=>'Select the player'])->Label('Player') ?>
    <?= $form->field($model, 'referred_id')->dropDownList(ArrayHelper::map(Player::find()->orderBy(['username'=>SORT_ASC])->all(), 'id', 'username'), ['prompt'=>'Select the player'])->Label('Referred player') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>