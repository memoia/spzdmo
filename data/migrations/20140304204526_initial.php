<?php

use Phinx\Migration\AbstractMigration;

class Initial extends AbstractMigration
{
    public function up()
    {
        $this->execute(file_get_contents(__DIR__.'/schema.sql'));
    }
}
