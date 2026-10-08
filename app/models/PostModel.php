<?php
class PostModel
{
    private const SELECT = 'SELECT p.*, u.username, u.full_name, u.profile_image,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id) AS like_count,
        (SELECT COUNT(*) FROM likes l WHERE l.post_id = p.id AND l.user_id = ?) AS liked,
        (SELECT reaction_type FROM likes l WHERE l.post_id = p.id AND l.user_id = ? LIMIT 1) AS my_reaction,
        (SELECT COUNT(*) FROM posts sh WHERE sh.shared_post_id = p.id) AS share_count,
        sp.id AS shared_orig_id,
        sp.content AS shared_content,
        sp.image AS shared_image,
        sp.created_at AS shared_created_at,
        sp.user_id AS shared_user_id,
        su.username AS shared_username,
        su.full_name AS shared_full_name,
        su.profile_image AS shared_profile_image
        FROM posts p
        JOIN users u ON u.id = p.user_id
        LEFT JOIN posts sp ON sp.id = p.shared_post_id
        LEFT JOIN users su ON su.id = sp.user_id';

    public function feed(int $me): array
    {
        $stmt = db()->prepare(self::SELECT . ' ORDER BY p.created_at DESC, p.id DESC');
        $stmt->execute([$me, $me]);
        return $stmt->fetchAll();
    }

    public function byUser(int $userId, int $me): array
    {
        $stmt = db()->prepare(self::SELECT . ' WHERE p.user_id = ? ORDER BY p.created_at DESC, p.id DESC');
        $stmt->execute([$me, $me, $userId]);
        return $stmt->fetchAll();
    }

    public function search(string $keyword, int $me): array
    {
        $like = '%' . addcslashes($keyword, '%_\\') . '%';
        $stmt = db()->prepare(self::SELECT . ' WHERE p.content LIKE ? ORDER BY p.created_at DESC, p.id DESC LIMIT 30');
        $stmt->execute([$me, $me, $like]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM posts WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findFull(int $id, int $me): ?array
    {
        $stmt = db()->prepare(self::SELECT . ' WHERE p.id = ?');
        $stmt->execute([$me, $me, $id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $userId, string $content, ?string $image = null, ?int $sharedPostId = null): int
    {
        $stmt = db()->prepare('INSERT INTO posts (user_id, content, image, shared_post_id) VALUES (?, ?, ?, ?)');
        $stmt->execute([$userId, $content, $image, $sharedPostId]);
        return (int) db()->lastInsertId();
    }

    public function share(int $userId, int $originalPostId, string $content = ''): int
    {
        return $this->create($userId, $content, null, $originalPostId);
    }

    public function update(int $id, string $content, ?string $image): void
    {
        $stmt = db()->prepare('UPDATE posts SET content = ?, image = ? WHERE id = ?');
        $stmt->execute([$content, $image, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = db()->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$id]);
    }
}
