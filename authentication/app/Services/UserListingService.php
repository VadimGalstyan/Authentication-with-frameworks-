<?php

namespace App\Services;

use App\DataTransferObjects\UserFilter;
use App\Repositories\RoleRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Collection;

enum AdminPage : int
{
    case UsersPerPage = 15;
}                                                                           

class UserListingService
{

    public function __construct(
        private UserRepository $users,
        private RoleRepository $roles,
    ) {}

    public function listForAdmin(array $input): array
    {
        $roles = $this->roles->all();

        $verified = $this->cleanVerified(
            $input['verified'] ?? null
        );

        $filter = new UserFilter(
            search: $this->cleanSearch($input['q'] ?? null),
            role: $this->cleanRole(
                $roles,
                $input['role'] ?? null
            ),
            verified: $verified === null
                ? null
                : $verified === 'yes',
        );

        return [
            'users' => $this->users
                ->paginateByFilter($filter, AdminPage::UsersPerPage->value)
                ->withQueryString(),
            'roles' => $roles,
            'search' => $filter->search,
            'roleFilter' => $filter->role,
            'verified' => $verified,
        ];
    }

    private function cleanSearch(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    private function cleanRole(Collection $roles,mixed $value): ?string 
    {
        return is_string($value) && $roles->contains('name', $value)
            ? $value
            : null;
    }

    private function cleanVerified(mixed $value): ?string
    {
        return in_array($value, ['yes', 'no'], true)
            ? $value
            : null;
    }
}