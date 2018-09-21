<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $order_id
 * @property string $trade_id
 * @property string $qr_id
 * @property int $trade_status
 * @property int $starttime
 * @property int $endtime
 * @property double $money
 */
class Orders extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trade_status', 'starttime', 'endtime'], 'integer'],
            [['money'], 'number'],
            [['trade_id', 'qr_id'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'trade_id' => 'Trade ID',
            'qr_id' => 'Qr ID',
            'trade_status' => 'Trade Status',
            'starttime' => 'Starttime',
            'endtime' => 'Endtime',
            'money' => 'Money',
        ];
    }
}
