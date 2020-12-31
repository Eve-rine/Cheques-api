<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $searchModel PIMS\models\BatchPrintSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cheques Print';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cheques-print-index">
<?=
ListView::widget([
    'summary'=>'',
    'dataProvider' => $dataProvider,
    'itemView' => '_list_record'
]);
?>

    

</div>
