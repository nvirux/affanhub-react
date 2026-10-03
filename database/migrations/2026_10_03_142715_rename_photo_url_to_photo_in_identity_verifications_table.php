<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. If photo_url exists and photo does not exist, rename it
        if (Schema::hasColumn('identity_verifications', 'photo_url') && ! Schema::hasColumn('identity_verifications', 'photo')) {
            Schema::table('identity_verifications', function (Blueprint $table) {
                $table->renameColumn('photo_url', 'photo');
            });
        } elseif (! Schema::hasColumn('identity_verifications', 'photo')) {
            Schema::table('identity_verifications', function (Blueprint $table) {
                $table->longText('photo')->nullable()->after('tracking_id');
            });
        }

        // 2. Ensure photo column is longText
        if (Schema::hasColumn('identity_verifications', 'photo')) {
            Schema::table('identity_verifications', function (Blueprint $table) {
                $table->longText('photo')->nullable()->change();
            });
        }

        // 3. If both photo_url and photo exist (e.g. from partial previous migration), copy photo_url into photo if photo is empty, then drop photo_url
        if (Schema::hasColumn('identity_verifications', 'photo_url') && Schema::hasColumn('identity_verifications', 'photo')) {
            DB::table('identity_verifications')
                ->where(function ($query) {
                    $query->whereNull('photo')->orWhere('photo', '');
                })
                ->whereNotNull('photo_url')
                ->where('photo_url', '!=', '')
                ->update(['photo' => DB::raw('photo_url')]);

            Schema::table('identity_verifications', function (Blueprint $table) {
                $table->dropColumn('photo_url');
            });
        }

        // 4. Data Fix for Production:
        // Ensure any existing rows where photo is empty but data_payload has photo get updated
        DB::table('identity_verifications')
            ->where(function ($query) {
                $query->whereNull('photo')->orWhere('photo', '');
            })
            ->whereNotNull('data_payload')
            ->chunkById(100, function ($rows) {
                foreach ($rows as $row) {
                    if (! empty($row->data_payload)) {
                        $payload = json_decode($row->data_payload, true);
                        if (is_array($payload)) {
                            $photoData = $payload['photo'] ?? $payload['photo_url'] ?? $payload['image'] ?? null;
                            if (! empty($photoData)) {
                                DB::table('identity_verifications')
                                    ->where('id', $row->id)
                                    ->update(['photo' => $photoData]);
                            }
                        }
                    }
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('identity_verifications', 'photo') && ! Schema::hasColumn('identity_verifications', 'photo_url')) {
            Schema::table('identity_verifications', function (Blueprint $table) {
                $table->renameColumn('photo', 'photo_url');
            });
        }
    }
};
