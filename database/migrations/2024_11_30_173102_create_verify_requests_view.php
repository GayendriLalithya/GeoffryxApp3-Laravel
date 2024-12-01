<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateVerifyRequestsView extends Migration
{
    public function up()
    {
        DB::statement('
            CREATE OR REPLACE VIEW verify_requests AS
            SELECT
                v.verify_id,
                v.user_id,
                v.nic_no,
                v.nic_front,
                v.nic_back,
                v.professional_type,   -- Include professional_type
                v.status,
                u.name as user_name,
                u.contact_no,
                u.address,
                u.email,
                -- Join the profile_picture table and select the image path
                pp.profile_pic   -- Assuming "profile_picture_path" is the column in the profile_picture table
            FROM verify v
            INNER JOIN users u ON v.user_id = u.user_id
            LEFT JOIN profile_picture pp ON u.user_id = pp.user_id  -- Join the profile_picture table on user_id
            WHERE v.status = "pending"
            AND u.deleted = false
        ');
    }

    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS verify_requests');
    }
}
