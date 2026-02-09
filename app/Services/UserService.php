<?php

namespace App\Services;

use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserService
{
    public function register(array $data): User
    {
        $data = $this->sanitizeCredentials($data) + ['password' => Hash::make($data['password'])];
        $user = User::create($data);
        Mail::to($user->email)->send(new WelcomeEmail($user));
        return $user;
    }

    /**
     * Upload and process user avatar
     */
    public function uploadAvatar(User $user, UploadedFile $file): string
    {
        $filename = $this->processAvatarFile($file, $user->id);
        $this->deleteOldAvatar($user);
        $user->update(['avatar' => $filename]);
        return $filename;
    }

    /**
     * Process avatar image and save to storage
     */
    protected function processAvatarFile(UploadedFile $file, int $userId): string
    {
        $filename = $userId . '-' . uniqid() . '.jpg';
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file)->cover(120, 120);
        Storage::disk('public')->put("avatars/{$filename}", $image->toJpeg(75));
        return $filename;
    }

    /**
     * Delete user's old avatar
     */
    protected function deleteOldAvatar(User $user): void
    {
        $avatar = $user->avatar;
        if ($avatar && !str_starts_with($avatar, 'fallback-avatar')) {
            Storage::disk('public')->delete("avatars/{$avatar}");
        }
    }

    /**
     * Sanitize and validate credentials
     */
    protected function sanitizeCredentials(array $data): array
    {
        return [
            'username' => strip_tags(trim($data['username'])),
            'email' => strip_tags(trim($data['email'])),
        ];
    }

    /**
     * Attempt login with credentials
     *
     * @param array $credentials Array containing loginusername and loginpassword
     * @return bool True if login successful, false otherwise
     */
    public function attemptLogin(array $credentials): bool
    {
        return Auth::attempt($credentials);
    }

    /**
     * Logout current user
     */
    public function logout(): void
    {
        Auth::logout();
    }

    /**
     * Get user by username
     */
    public function findByUsername(string $username): ?User
    {
        $username = strip_tags(trim($username));
        return empty($username) ? null : User::firstWhere('username', $username);
    }
}
