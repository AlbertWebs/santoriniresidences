<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const OLD_MESSAGE = 'Your request has been received. A member of the Santorini team will be in touch personally.';

    private const NEW_MESSAGE = 'Your request is being processed. Someone from the Santorini team will get back to you soon.';

    public function up(): void
    {
        DB::table('lead_forms')
            ->where('success_message', self::OLD_MESSAGE)
            ->update(['success_message' => self::NEW_MESSAGE]);
    }

    public function down(): void
    {
        DB::table('lead_forms')
            ->where('success_message', self::NEW_MESSAGE)
            ->update(['success_message' => self::OLD_MESSAGE]);
    }
};
