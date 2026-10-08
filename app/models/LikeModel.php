<?php
class LikeModel
{
    public const VALID_REACTIONS = ['like', 'love', 'care', 'haha', 'wow', 'sad', 'angry'];

    public function react(int $postId, int $userId, string $type = 'like'): array
    {
        if (!in_array($type, self::VALID_REACTIONS, true)) {
            $type = 'like';
        }

        $stmt = db()->prepare('SELECT reaction_type FROM likes WHERE post_id = ? AND user_id = ?');
        $stmt->execute([$postId, $userId]);
        $existing = $stmt->fetchColumn();

        if ($existing !== false) {
            if ($existing === $type) {
                // Clicking the same reaction removes it
                $del = db()->prepare('DELETE FROM likes WHERE post_id = ? AND user_id = ?');
                $del->execute([$postId, $userId]);
                $userReaction = null;
            } else {
                // Switching reaction
                $upd = db()->prepare('UPDATE likes SET reaction_type = ? WHERE post_id = ? AND user_id = ?');
                $upd->execute([$type, $postId, $userId]);
                $userReaction = $type;
            }
        } else {
            // New reaction
            $ins = db()->prepare('INSERT INTO likes (post_id, user_id, reaction_type) VALUES (?, ?, ?)');
            $ins->execute([$postId, $userId, $type]);
            $userReaction = $type;
        }

        return [
            'reacted'       => $userReaction !== null,
            'reaction_type' => $userReaction,
            'count'         => $this->count($postId),
            'top_reactions' => $this->topReactions($postId),
        ];
    }

    public function toggle(int $postId, int $userId): bool
    {
        $res = $this->react($postId, $userId, 'like');
        return $res['reacted'];
    }

    public function count(int $postId): int
    {
        $stmt = db()->prepare('SELECT COUNT(*) FROM likes WHERE post_id = ?');
        $stmt->execute([$postId]);
        return (int) $stmt->fetchColumn();
    }

    public function userReaction(int $postId, int $userId): ?string
    {
        $stmt = db()->prepare('SELECT reaction_type FROM likes WHERE post_id = ? AND user_id = ?');
        $stmt->execute([$postId, $userId]);
        $res = $stmt->fetchColumn();
        return $res !== false ? (string) $res : null;
    }

    public function topReactions(int $postId): array
    {
        $stmt = db()->prepare('SELECT reaction_type, COUNT(*) as cnt FROM likes WHERE post_id = ? GROUP BY reaction_type ORDER BY cnt DESC LIMIT 3');
        $stmt->execute([$postId]);
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR) ?: [];
    }
}
