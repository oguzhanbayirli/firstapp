<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class UserService
{
    /**
     * Register a new user
     *
     * @param array $data Array containing username, email, and password
     * @return User
     */
    public function register(array $data): User
    {
        $data['username'] = strip_tags(trim($data['username']));
        $data['email'] = strip_tags(trim($data['email']));
        $data['password'] = Hash::make($data['password']);

        return User::create($data);
    }

    /**
     * Upload and process user avatar
     *
     * @param User $user
     * @param UploadedFile $file
     * @return string Filename of uploaded avatar
     * @throws \Exception If upload fails
     */
    public function uploadAvatar(User $user, UploadedFile $file): string
    {
        try {
            // Generate unique filename
            $filename = $user->id . '-' . uniqid() . '.jpg';

            // Create image manager instance
            $manager = new ImageManager(new Driver());
            
            // Read and process image
            $image = $manager->read($file);
            $image->cover(120, 120);
            
            // Encode as JPG
            $encoded = $image->toJpeg(75);

            // Store in public disk
            Storage::disk('public')->put("avatars/{$filename}", $encoded);

            // Delete old avatar if exists and not default
            $this->deleteOldAvatar($user);

            // Update user avatar
            $user->avatar = $filename;
            $user->save();

            return $filename;
        } catch (\Exception $e) {
            // Re-throw with context
            throw new \Exception("Failed to upload avatar: " . $e->getMessage());
        }
    }

    /**
     * Delete user's old avatar
     */
    protected function deleteOldAvatar(User $user): void
    {
        $oldAvatar = $user->avatar;
        
        // Don't delete default avatar
        if ($oldAvatar && !str_starts_with($oldAvatar, 'fallback-avatar')) {
            Storage::disk('public')->delete("avatars/{$oldAvatar}");
        }
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
     *
     * @param string $username
     * @return User|null
     */
    public function findByUsername(string $username): ?User
    {
        $username = strip_tags(trim($username));
        
        if (empty($username)) {
            return null;
        }
        
        return User::where('username', $username)->first();
    }
}
