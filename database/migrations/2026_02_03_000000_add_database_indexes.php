<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations - Add indexes for performance optimization
     */
    public function up(): void
    {
        // Index posts by user_id for quick user's posts lookup
        Schema::table('posts', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('created_at');
            $table->fullText(['title', 'body']); // For full-text search optimization
        });

        // Index follow table for quick follower/following lookup
        Schema::table('follow', function (Blueprint $table) {
            $table->index('followed_user_id');
            $table->index('created_at');
        });

        // Index users table for faster lookups
        Schema::table('users', function (Blueprint $table) {
            $table->index('username');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex('posts_user_id_index');
            $table->dropIndex('posts_created_at_index');
            $table->dropFullText('posts_title_body_fulltext');
        });

        Schema::table('follow', function (Blueprint $table) {
            $table->dropIndex('follow_followed_user_id_index');
            $table->dropIndex('follow_created_at_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_username_index');
            $table->dropIndex('users_created_at_index');
        });
    }
};
