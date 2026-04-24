<?php

namespace App\Interfaces\User;

/**
 * UserServiceInterface
 * Contract untuk user management service
 * Mendefinisikan method publik yang harus diimplementasikan
 */
interface UserServiceInterface
{
    /**
     * Get all users with their roles
     * @param array $filters
     * @return array
     */
    public function getUsersWithRoles(array $filters = []): array;

    /**
     * Get user by UUID with roles and profil
     * @param string $uuid
     * @return array|null
     */
    public function getUserByUuid(string $uuid): ?array;

    /**
     * Get user by ID with roles and profil
     * @param int $id
     * @return array|null
     */
    public function getUserById(int $id): ?array;

    /**
     * Create new user with roles and profil
     * @param array $data
     * @return int|null User ID if success, null if failed
     */
    public function createUser(array $data): ?int;

    /**
     * Update existing user
     * @param int $userId
     * @param array $data
     * @return bool
     */
    public function updateUser(int $userId, array $data): bool;

    /**
     * Soft delete user
     * @param int $id
     * @return bool
     */
    public function deleteUser(int $id): bool;

    /**
     * Restore soft-deleted user
     * @param int $id
     * @return bool
     */
    public function restoreUser(int $id): bool;

    /**
     * Reset user password
     * @param int $id
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword(int $id, string $newPassword): bool;

    /**
     * Get all available roles
     * @return array
     */
    public function getAllRoles(): array;

    /**
     * Get master data (dropdown data untuk form)
     * @return array
     */
    public function getMasterData(): array;

    /**
     * Get last error info for debugging
     * @return array|null
     */
    public function getLastError(): ?array;
}
