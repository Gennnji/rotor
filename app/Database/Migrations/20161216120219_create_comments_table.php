<?php

use Phinx\Migration\AbstractMigration;

class CreateCommentsTable extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        if (! $this->hasTable('comments')) {
            $table = $this->table('comments', ['engine' => 'MyISAM', 'collation' => 'utf8mb4_unicode_ci']);
            $table->addColumn('user', 'string', ['limit' => 20])
                ->addColumn('relate_type', 'string', ['limit' => 50])
                ->addColumn('relate_category_id', 'integer', ['signed' => false])
                ->addColumn('relate_id', 'integer', ['signed' => false])
                ->addColumn('text', 'text', ['null' => true])
                ->addColumn('ip', 'string', ['limit' => 15])
                ->addColumn('brow', 'string', ['limit' => 25])
                ->addColumn('time', 'integer', ['signed' => false])
                ->addIndex(['relate_type', 'relate_id'], ['name' => 'relate_type'])
                ->addIndex('time')
                ->create();
        }
    }
}
