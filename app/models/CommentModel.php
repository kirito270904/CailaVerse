<?php
class CommentModel
{
    public function forPosts(array $postIds): array
    {
        if (!$postIds) {
            return [];
        }
        $marks = implode(',', array_fill(0, count($postIds), '?'));
        $stmt = db()->prepare(
            'SELECT c.*, u.username, u.full_name, u.profile_image, u.last_active
             FROM comments c JOIN users u ON u.id = c.user_id
             WHERE c.post_id IN (' . $marks . ')
             ORDER BY c.created_at ASC, c.id ASC'
        );
        $stmt->execute(array_values($postIds));
        $grouped = [];
        foreach ($stmt->fetchAll() as $row) {
            $grouped[$row['post_id']][] = $row;
        }
        return $grouped;
    }

    public function find(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM comments WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findWithUser(int $id): ?array
    {
        $stmt = db()->prepare(
            'SELECT c.*, u.username, u.full_name, u.profile_image, u.last_active
             FROM comments c JOIN users u ON u.id = c.user_id WHERE c.id = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $postId, int $userId, string $content): int
    {
        $stmt = db()->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)');
        $stmt->execute([$postId, $userId, $content]);
        return (int) db()->lastInsertId();
    }

    public function update(int $id, string $content): void
    {
        $stmt = db()->prepare('UPDATE comments SET content = ? WHERE id = ?');
        $stmt->execute([$content, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = db()->prepare('DELETE FROM comments WHERE id = ?');
        $stmt->execute([$id]);
    }
}
