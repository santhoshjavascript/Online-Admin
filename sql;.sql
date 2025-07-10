-- Drop existing tables to start fresh (optional, remove if you want to preserve data)
DROP TABLE IF EXISTS sessions, personal_access_tokens, bookmarks, projects, categories, users, migrations;

-- Create the database
CREATE DATABASE IF NOT EXISTS online;
USE online;

-- Create the migrations table
CREATE TABLE migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL,
    batch INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'User', 'Student') NOT NULL,
    is_admin BOOLEAN NOT NULL DEFAULT FALSE,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    remember_token VARCHAR(100) NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the categories table
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the projects table (includes thumbnail)
CREATE TABLE projects (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NULL,
    video_url VARCHAR(255) NULL,
    abstract_url VARCHAR(255) NULL,
    thumbnail VARCHAR(255) NULL,
    status ENUM('Draft', 'Published', 'Archived') NOT NULL,
    category_id BIGINT UNSIGNED NULL,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the bookmarks table
CREATE TABLE bookmarks (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    project_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the sessions table
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    user_id BIGINT UNSIGNED NULL,
    ip_address VARCHAR(45) NULL,
    user_agent TEXT NULL,
    payload LONGTEXT NOT NULL,
    last_activity INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create the personal_access_tokens table
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tokenable_type VARCHAR(255) NOT NULL,
    tokenable_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(255) NOT NULL,
    token VARCHAR(64) NOT NULL UNIQUE,
    abilities TEXT NULL,
    last_used_at TIMESTAMP NULL DEFAULT NULL,
    expires_at TIMESTAMP NULL DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for performance
CREATE INDEX idx_projects_category_id ON projects(category_id);
CREATE INDEX idx_projects_uploaded_by ON projects(uploaded_by);
CREATE INDEX idx_bookmarks_user_id ON bookmarks(user_id);
CREATE INDEX idx_bookmarks_project_id ON bookmarks(project_id);
CREATE INDEX idx_sessions_user_id ON sessions(user_id);
CREATE INDEX idx_sessions_last_activity ON sessions(last_activity);
CREATE INDEX idx_personal_access_tokens_tokenable ON personal_access_tokens(tokenable_type, tokenable_id);

-- Mark problematic migrations as applied
INSERT INTO migrations (migration, batch) VALUES
    ('2025_04_07_010617_add_thumbnail_to_projects_table', 1),
    ('2025_04_07_040840_add_is_admin_to_users_table', 1);

-- Insert seeder data
-- UsersTableSeeder: Zinna (admin)
INSERT INTO users (name, email, password, role, is_admin, created_at, updated_at)
VALUES ('Zinna', 'zinna@gmail.com', '$2y$10$z3j8z8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3', 'Admin', TRUE, NOW(), NOW());

-- DatabaseSeeder: Test User (Student)
INSERT INTO users (name, email, password, role, is_admin, created_at, updated_at)
VALUES ('Test User', 'test@example.com', '$2y$10$z3j8z8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3j8z3', 'Student', FALSE, NOW(), NOW());

-- DatabaseSeeder: Technology category
INSERT INTO categories (name, description, created_at, updated_at)
VALUES ('Technology', NULL, NOW(), NOW());

-- DatabaseSeeder: Sample Project
INSERT INTO projects (title, description, video_url, status, category_id, uploaded_by, created_at, updated_at)
VALUES ('Sample Project', 'A test project.', 'https://example.com/video', 'Published', 1, 2, NOW(), NOW());

-- DatabaseSeeder: Another Project
INSERT INTO projects (title, description, video_url, status, category_id, uploaded_by, created_at, updated_at)
VALUES ('Another Project', 'Another test project.', 'https://example.com/video2', 'Published', 1, 2, NOW(), NOW());