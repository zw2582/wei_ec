<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@picture', dirname(dirname(__DIR__)) . '/picture');
Yii::setAlias('@paoma', dirname(dirname(__DIR__)) . '/paoma');
require_once __DIR__.'/const.php';