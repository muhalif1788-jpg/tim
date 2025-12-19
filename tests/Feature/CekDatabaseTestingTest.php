<?php

namespace Tests\Feature;

use Tests\TestCase;

class CekDatabaseTestingTest extends TestCase
{
    public function test_cek_database_testing()
    {
        dump(config('database.connections.mysql.database'));
        $this->assertTrue(true);
    }
}
