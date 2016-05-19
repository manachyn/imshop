<?php

namespace im\config\tests\codeception\common\unit;

use Yii;
use yii\console\controllers\MigrateController;
use yii\db\Transaction;

/**
 * @inheritdoc
 */
class DbTestCase extends \yii\codeception\DbTestCase
{
    /**
     * @inheritdoc
     */
    public $appConfig = '@tests/codeception/config/common/unit.php';

    /**
     * @var Transaction
     */
    protected $transaction;

    /**
     * Get migrations list.
     *
     * @return array
     */
    public function getMigrations()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->transaction = Yii::$app->db->beginTransaction();
        $this->applyMigrations($this->getMigrations());
    }

    /**
     * @inheritdoc
     */
    protected function tearDown()
    {
        $this->revertMigrations(array_reverse($this->getMigrations()));
        if ($this->transaction) {
            $this->transaction->rollback();
        }
        parent::tearDown();
    }

    /**
     * Apply migrations.
     *
     * @param array $migrations
     */
    protected function applyMigrations($migrations = [])
    {
        if ($migrations) {
            $migrateController = new MigrateController('migrate', Yii::$app);
            foreach ($migrations as $migrationPath) {
                $migrateController->migrationPath = $migrationPath;
                $migrateController->runAction('up', ['interactive' => 0]);
            }
        }
    }

    /**
     * Revert migrations.
     *
     * @param array $migrations
     */
    protected function revertMigrations($migrations = [])
    {
        if ($migrations) {
            $migrateController = new MigrateController('migrate', Yii::$app);
            foreach ($migrations as $migrationPath) {

                $migrateController->migrationPath = $migrationPath;
                $migrateController->runAction('down', ['interactive' => 0]);
            }
        }
    }
}

