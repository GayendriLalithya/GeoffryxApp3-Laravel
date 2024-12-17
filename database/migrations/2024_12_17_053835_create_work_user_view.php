<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateWorkUserView extends Migration
{
    public function up()
    {
        DB::statement("
            CREATE VIEW view_user_projects AS
            SELECT 
                work.*,
                users.name AS client_name,
                users.contact_no AS client_contact
            FROM work
            INNER JOIN users ON work.user_id = users.user_id
            ORDER BY work.created_at DESC;
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS view_user_projects");
    }
}
