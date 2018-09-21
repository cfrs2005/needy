<?php

use yii\db\Migration;

/**
 * Class m180920_103142_orders_table
 */
class m180920_103142_orders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('orders', [
            'order_id' => 'pk',
            'trade_id' => 'varchar(64)',
            'qr_id' => 'varchar(64)',
            'trade_status' => 'int2',
            'starttime' => 'int4',
            'endtime' => 'int4',
            'money' => 'float',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180920_103142_orders_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180920_103142_orders_table cannot be reverted.\n";

        return false;
    }
    */
}
