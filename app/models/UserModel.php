<?php
class UserModel
{
    public function create(string $username, string $hash, string $fullName, ?string $image): int
    {
        $stmt = db()->prepare('INSERT INTO users (username, password, full_name, profile_image, last_active) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$username, $hash, $fullName, $image]);
        return (int) db()->lastInsertId();
    }

    public function findByUsername(string $username): ?array
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE username = ?');
        $stmt->execute([$username]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function touchActive(int $id): void
    {
        static $touched = false;
        if (!$touched) {
            $touched = true;
            $stmt = db()->prepare('UPDATE users SET last_active = NOW() WHERE id = ?');
            $stmt->execute([$id]);
        }
    }

    public function update(int $id, string $fullName, string $bio, ?string $image): void
    {
        $stmt = db()->prepare('UPDATE users SET full_name = ?, bio = ?, profile_image = ?, last_active = NOW() WHERE id = ?');
        $stmt->execute([$fullName, $bio, $image, $id]);
    }

    public function search(string $keyword, int $meId = 0): array
    {
        $like = '%' . addcslashes($keyword, '%_\\') . '%';
        $stmt = db()->prepare(
            'SELECT u.id, u.username, u.full_name, u.bio, u.profile_image, u.last_active,
             (SELECT COUNT(*) FROM follows f WHERE f.follower_id = ? AND f.following_id = u.id) AS is_following
             FROM users u
             WHERE (u.username LIKE ? OR u.full_name LIKE ?) AND u.id <> ?
             ORDER BY u.full_name LIMIT 30'
        );
        $stmt->execute([$meId, $like, $like, $meId]);
        return $stmt->fetchAll();
    }

    public function suggestions(int $exceptId, int $limit = 5): array
    {
        $stmt = db()->prepare(
            'SELECT u.id, u.username, u.full_name, u.profile_image, u.last_active,
             (SELECT COUNT(*) FROM follows f WHERE f.follower_id = ? AND f.following_id = u.id) AS is_following
             FROM users u WHERE u.id <> ? ORDER BY u.last_active DESC, u.created_at DESC, u.id DESC LIMIT ' . (int) $limit
        );
        $stmt->execute([$exceptId, $exceptId]);
        return $stmt->fetchAll();
    }
}
