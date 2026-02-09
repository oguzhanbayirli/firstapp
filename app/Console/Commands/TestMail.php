<?php

namespace App\Console\Commands;

use App\Mail\NewFollowerEmail;
use App\Mail\NewPostEmail;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email?} {--type=welcome}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test email to verify mail configuration. Options: welcome, new-post, new-follower';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?? 'hello@example.com';
        $type = $this->option('type');

        $this->info("Sending {$type} test email to {$email}...");

        try {
            match($type) {
                'welcome' => $this->sendWelcomeEmail($email),
                'new-post' => $this->sendNewPostEmail($email),
                'new-follower' => $this->sendNewFollowerEmail($email),
                default => throw new \InvalidArgumentException("Unknown mail type: {$type}"),
            };

            $this->info("✅ Email sent successfully!");
            $this->line("Check your email inbox or visit https://mailtrap.io/ if using Mailtrap.");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Failed to send email: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Send welcome email test
     */
    private function sendWelcomeEmail(string $email): void
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'username' => 'testuser',
                'password' => bcrypt('password'),
            ]
        );

        Mail::to($email)->send(new WelcomeEmail($user));
    }

    /**
     * Send new post email test
     */
    private function sendNewPostEmail(string $email): void
    {
        $post = \App\Models\Post::first() ?? \App\Models\Post::factory()->create();
        
        Mail::to($email)->send(new NewPostEmail($post));
    }

    /**
     * Send new follower email test
     */
    private function sendNewFollowerEmail(string $email): void
    {
        $follower = User::first() ?? User::factory()->create();
        $followedUser = User::where('id', '!=', $follower->id)->first() ?? User::factory()->create();

        Mail::to($email)->send(new NewFollowerEmail($follower, $followedUser));
    }
}
