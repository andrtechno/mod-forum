<?php

namespace panix\mod\forum\models\query;

use panix\engine\behaviors\nestedsets\NestedSetsQueryBehavior;

class CategoriesQuery extends \yii\db\ActiveQuery {

    public function behaviors() {
        return [
            [
                'class' => NestedSetsQueryBehavior::class,
            ]
        ];
    }

    public function published($state = 1) {
        return $this->andWhere(['switch' => $state]);
    }

    public function excludeRoot() {
        $this->andWhere(['!=', 'id', 1]);
        return $this;
    }

}
