<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-posts');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $post): bool
    {
        if ((int) $user->id === (int) $post->user_id) {
            return true;
        }
        return $user->hasPermissionTo('edit-posts');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $post): bool
    {
        if ((int) $user->id === (int) $post->user_id) {
            return true;
        }
        return $user->hasPermissionTo('delete-posts');
    }

    /**
     * Controlled views
     */
    public function view(?User $user, Post $post): bool
    {
        $status = $post->status?->status;

        if ($status === 'published'){
            return true;
        }

        if (!$user){
            return false;
        }

        if ($status === 'draft'){
            return $user->id === $post->user_id;
        }

        if ($status === 'submitted'){
            return $user->id === $post->user_id || $user->hasPermissionTo('publish-posts');
        }

        return false;
    }
}
