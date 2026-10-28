CREATE TABLE users (
    id          SERIAL PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id          SERIAL PRIMARY KEY,
    user_id     INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title       VARCHAR(200) NOT NULL,
    content     TEXT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE comments (
    id          SERIAL PRIMARY KEY,
    post_id     INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
    user_id     INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    content     TEXT NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, email, password) VALUES
('admin',   'admin@secureblog.local', 'admin123'),
('alice',   'alice@example.com',      'alice2024'),
('bob',     'bob@example.com',        'password'),
('charlie', 'charlie@example.com',    'charlie');

INSERT INTO posts (user_id, title, content) VALUES
(1, 'Bienvenue sur SecureBlog', 'Ceci est le premier article du blog. N''hésitez pas à commenter !'),
(2, 'Mon premier article', 'Bonjour à tous, je suis Alice et voici mon premier post sur SecureBlog.'),
(3, 'Les bonnes pratiques de développement', 'Aujourd''hui on parle de sécurité web... ou pas.');

INSERT INTO comments (post_id, user_id, content) VALUES
(1, 2, 'Super article, merci pour le partage !'),
(1, 3, 'Hâte de voir la suite.'),
(2, 1, 'Bienvenue Alice !');