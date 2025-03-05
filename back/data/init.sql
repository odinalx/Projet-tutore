-- Création des tables

-- Table des utilisateurs
CREATE TABLE IF NOT EXISTS users (
    id UUID PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des sections
CREATE TABLE IF NOT EXISTS sections (
    id UUID PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    category VARCHAR(50),
    price DECIMAL(10, 2),
    members INTEGER DEFAULT 0,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table de relation utilisateurs-sections
CREATE TABLE IF NOT EXISTS user_sections (
    user_id UUID REFERENCES users(id),
    section_id UUID REFERENCES sections(id),
    joined_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (user_id, section_id)
);

-- Table des paiements
CREATE TABLE IF NOT EXISTS payments (
    id UUID PRIMARY KEY,
    user_id UUID REFERENCES users(id),
    section_id UUID REFERENCES sections(id),
    amount DECIMAL(10, 2) NOT NULL,
    description TEXT,
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des activités
CREATE TABLE IF NOT EXISTS activities (
    id UUID PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    section_id UUID REFERENCES sections(id),
    location_id UUID,
    start_date TIMESTAMP NOT NULL,
    end_date TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insertion de données de test

-- Utilisateurs
INSERT INTO users (id, email, password, first_name, last_name, role)
VALUES 
    ('11111111-1111-1111-1111-111111111111', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin', 'User', 'admin'),
    ('22222222-2222-2222-2222-222222222222', 'user@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Jean', 'Dupont', 'user');

-- Sections
INSERT INTO sections (id, name, slug, description, category, price, members, image)
VALUES 
    ('33333333-3333-3333-3333-333333333333', 'Football', 'football', 'Section de football pour tous les âges', 'Sport', 150.00, 25, 'football.jpg'),
    ('44444444-4444-4444-4444-444444444444', 'Basketball', 'basketball', 'Venez découvrir le basketball', 'Sport', 130.00, 15, 'basketball.jpg'),
    ('55555555-5555-5555-5555-555555555555', 'Peinture', 'peinture', 'Atelier de peinture pour débutants et confirmés', 'Activité manuelle', 120.00, 10, 'peinture.jpg');

-- Relations utilisateurs-sections
INSERT INTO user_sections (user_id, section_id)
VALUES 
    ('22222222-2222-2222-2222-222222222222', '33333333-3333-3333-3333-333333333333'),
    ('22222222-2222-2222-2222-222222222222', '55555555-5555-5555-5555-555555555555');

-- Paiements
INSERT INTO payments (id, user_id, section_id, amount, description)
VALUES 
    ('66666666-6666-6666-6666-666666666666', '22222222-2222-2222-2222-222222222222', '33333333-3333-3333-3333-333333333333', 150.00, 'Cotisation annuelle - Football'),
    ('77777777-7777-7777-7777-777777777777', '22222222-2222-2222-2222-222222222222', '55555555-5555-5555-5555-555555555555', 120.00, 'Cotisation annuelle - Peinture'); 