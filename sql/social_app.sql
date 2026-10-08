DROP DATABASE IF EXISTS social_app;
CREATE DATABASE social_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE social_app;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    bio VARCHAR(255) DEFAULT NULL,
    profile_image VARCHAR(255) DEFAULT NULL,
    last_active TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    shared_post_id INT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_posts_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_posts_shared FOREIGN KEY (shared_post_id) REFERENCES posts(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    content VARCHAR(500) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    reaction_type VARCHAR(20) NOT NULL DEFAULT 'like',
    UNIQUE KEY uq_like (post_id, user_id),
    CONSTRAINT fk_likes_post FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_likes_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE follows (
    id INT AUTO_INCREMENT PRIMARY KEY,
    follower_id INT NOT NULL,
    following_id INT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_follow (follower_id, following_id),
    CONSTRAINT fk_follows_follower FOREIGN KEY (follower_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_follows_following FOREIGN KEY (following_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sample users (password for all: password)
INSERT INTO users (username, password, full_name, bio) VALUES
('john', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Michael Caila', 'Founder & developer of CailaVerse. Welcome to the network! 🚀'),
('juan', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Juan Dela Cruz', 'BSIT student and CailaVerse member.'),
('maria', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria Santos', 'Love coding and coffee.'),
('pedro', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Pedro Reyes', 'Web developer in the making.');

INSERT INTO posts (user_id, content, created_at) VALUES
(1, 'Hello CailaVerse! This is my first post.', '2026-10-01 08:00:00'),
(2, 'Finished my PHP MVC project today. So happy!', '2026-10-02 09:30:00'),
(3, 'Anyone up for a study group on MySQL?', '2026-10-03 14:15:00');

INSERT INTO comments (post_id, user_id, content, created_at) VALUES
(1, 2, 'Welcome, Juan!', '2026-10-01 08:10:00'),
(2, 1, 'Congrats Maria!', '2026-10-02 10:00:00'),
(3, 2, 'Count me in.', '2026-10-03 15:00:00');

INSERT INTO likes (post_id, user_id, reaction_type) VALUES
(1, 2, 'love'), (1, 3, 'like'), (2, 1, 'haha'), (2, 3, 'like'), (3, 1, 'like');

-- Sample follows (John follows everyone; Juan and Maria follow each other)
INSERT INTO follows (follower_id, following_id) VALUES
(1, 2), (1, 3), (1, 4), (2, 3), (3, 2);

