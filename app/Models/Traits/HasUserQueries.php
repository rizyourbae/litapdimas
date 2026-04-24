<?php

namespace App\Models\Traits;

/**
 * HasUserQueries Trait
 * 
 * Provides reusable query builder methods untuk UserModel
 * Mengurangi duplikasi query logic di service layer
 * 
 * Mengikuti pattern "scope" dari Laravel/Eloquent ported ke CodeIgniter 4
 * Usage dalam service:
 *   $this->userModel->withRoles()->withProfileData()->find($id)
 *   $this->userModel->search('john')->filterByRole(3)->paginate()
 */
trait HasUserQueries
{
    /**
     * Include user roles dalam query result
     * Digunakan via service method post-processing
     * 
     * @return self
     */
    public function withRoles(): self
    {
        // Note: CI4 doesn't support eager loading like Laravel
        // Ini adalah placeholder untuk dokumentasi query intent
        // Actual roles diload di service method
        return $this;
    }

    /**
     * Include user profil dalam query result
     * Digunakan via service method post-processing
     * 
     * @return self
     */
    public function withProfileData(): self
    {
        // Note: Profil diload di service method
        return $this;
    }

    /**
     * Get users with roles included (helper method untuk complex join)
     * 
     * @param array $filters Role ID, search term, active status
     * @return array
     */
    public function getUsersWithRoles(array $filters = []): array
    {
        $builder = $this->select('users.*, GROUP_CONCAT(roles.name SEPARATOR ", ") as role_names')
            ->join('user_roles', 'user_roles.user_id = users.id', 'left')
            ->join('roles', 'roles.id = user_roles.role_id', 'left')
            ->groupBy('users.id');

        return $this->applyFilters($builder, $filters)->findAll();
    }

    /**
     * Get user with roles dan profil included
     * 
     * @param int $userId
     * @return array|null
     */
    public function getUserWithDetails(int $userId): ?array
    {
        $user = $this->find($userId);
        if (!$user) {
            return null;
        }

        // Load roles (model method)
        $userRoleModel = new \App\Models\Auth\UserRoleModel();
        $user['roles'] = $userRoleModel->where('user_id', $userId)->findColumn('role_id') ?? [];

        // Load profil (model method)
        $userProfileModel = new \App\Models\User\UserProfileModel();
        $user['profil'] = $userProfileModel->where('user_id', $userId)->first();

        return $user;
    }

    /**
     * Get user by UUID dengan roles dan profil
     * 
     * @param string $uuid
     * @return array|null
     */
    public function getUserByUuidWithDetails(string $uuid): ?array
    {
        $user = $this->where('uuid', $uuid)->first();
        if (!$user) {
            return null;
        }

        return $this->getUserWithDetails($user['id']);
    }

    /**
     * Search users by name or email
     * 
     * @param string $term Search term
     * @return self Builder untuk chaining
     */
    public function search(string $term): self
    {
        $this->groupStart()
            ->like('nama_lengkap', $term)
            ->orLike('email', $term)
            ->orLike('username', $term)
            ->groupEnd();

        return $this;
    }

    /**
     * Filter by role
     * 
     * @param int $roleId
     * @return array Users dengan role tersebut
     */
    public function filterByRole(int $roleId): array
    {
        return $this->join('user_roles', 'user_roles.user_id = users.id', 'inner')
            ->where('user_roles.role_id', $roleId)
            ->groupBy('users.id')
            ->findAll();
    }

    /**
     * Filter by status aktif/tidak
     * 
     * @param bool $aktif
     * @return self Builder untuk chaining
     */
    public function filterByStatus(bool $aktif): self
    {
        $this->where('aktif', $aktif ? 1 : 0);
        return $this;
    }

    /**
     * Only active users
     * 
     * @return self Builder untuk chaining
     */
    public function active(): self
    {
        return $this->filterByStatus(true);
    }

    /**
     * Only inactive users
     * 
     * @return self Builder untuk chaining
     */
    public function inactive(): self
    {
        return $this->filterByStatus(false);
    }

    /**
     * Apply multiple filters at once
     * 
     * @param mixed $builder Query builder (optional)
     * @param array $filters [role_id, aktif, search]
     * @return mixed Builder
     */
    protected function applyFilters($builder = null, array $filters = [])
    {
        $builder = $builder ?? $this;

        // Filter: role
        if (!empty($filters['role_id'])) {
            $builder->where('user_roles.role_id', $filters['role_id']);
        }

        // Filter: status aktif/non
        if (isset($filters['aktif'])) {
            $builder->where('users.aktif', $filters['aktif']);
        }

        // Search: nama_lengkap, email
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('users.nama_lengkap', $filters['search'])
                ->orLike('users.email', $filters['search'])
                ->groupEnd();
        }

        return $builder;
    }
}
