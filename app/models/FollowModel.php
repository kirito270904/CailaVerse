<?php
class FollowModel
{
    public function toggle(int $followerId, int $followingId): bool
    {
        if ($followerId === $followingId) {
            return false;
        }

        $stmt = db()->prepare('SELECT id FROM follows WHERE follower_id = ? AND following_id = ?');
        $stmt->execute([$followerId, $followingId]);
        if ($stmt->fetch()) {
            $del = db()->prepare('DELETE FROM follows WHERE follower_id = ? AND following_id = ?');
            $del->execute([$followerId, $followingId]);
            return false;
        }

        $ins = db()->prepare('INSERT INTO follows (follower_id, following_id) VALUES (?, ?)');
        $ins->execute([$followerId, $followingId]);
        return true;
    }

    public function isFollowing(int $followerId, int $followingId): bool
    {
        $stmt = db()->prepare('SELECT id FROM follows WHERE follower_id = ? AND following_id = ?');
        $stmt->execute([$followerId, $followingId]);
        return (bool) $stmt->fetch();
    }

    public function followersCount(int $userId): int
    {
        $stmt = db()->prepare('SELECT COUNT(*) FROM follows WHERE following_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function followingCount(int $userId): int
    {
        $stmt = db()->prepare('SELECT COUNT(*) FROM follows WHERE follower_id = ?');
        $stmt->execute([$userId]);
        return (int) $stmt->fetchColumn();
    }

    public function followers(int $userId, int $limit = 8): array
    {
        $stmt = db()->prepare(
            'SELECT u.id, u.username, u.full_name, u.profile_image
             FROM follows f
             JOIN users u ON u.id = f.follower_id
             WHERE f.following_id = ?
             ORDER BY f.created_at DESC LIMIT ' . (int) $limit
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}
