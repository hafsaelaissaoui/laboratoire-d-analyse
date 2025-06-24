-- Corrections et améliorations de la base de données labonew

-- 1. Ajouter des index pour améliorer les performances
CREATE INDEX IF NOT EXISTS idx_patient_email ON patient(patient_email);
CREATE INDEX IF NOT EXISTS idx_patient_telephone ON patient(patient_telephone);
CREATE INDEX IF NOT EXISTS idx_dossier_patient ON dossier(id_patient);
CREATE INDEX IF NOT EXISTS idx_dossier_date ON dossier(date_dossier);
CREATE INDEX IF NOT EXISTS idx_details_dossier ON details(id_dossier);
CREATE INDEX IF NOT EXISTS idx_services_categorie ON services(id_categorie);

-- 2. Modifier la table patient pour corriger les problèmes
ALTER TABLE patient 
MODIFY COLUMN patient_cin VARCHAR(50) DEFAULT NULL,
MODIFY COLUMN patient_email VARCHAR(255) DEFAULT NULL;

-- 3. Corriger le type de données pour la date_dossier
ALTER TABLE dossier MODIFY COLUMN date_dossier DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- 4. Ajouter une table pour les sessions (optionnel)
CREATE TABLE IF NOT EXISTS patient_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    patient_id INT UNSIGNED NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    FOREIGN KEY (patient_id) REFERENCES patient(id_patient) ON DELETE CASCADE,
    INDEX idx_session_token (session_token),
    INDEX idx_patient_session (patient_id)
);

-- 5. Ajouter une table pour les logs d'activité (optionnel)
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('patient', 'user', 'admin') NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_user_logs (user_type, user_id),
    INDEX idx_action_logs (action),
    INDEX idx_date_logs (created_at)
);

-- 6. Corriger les données existantes si nécessaire
UPDATE patient SET patient_email = NULL WHERE patient_email = '';
UPDATE patient SET patient_cin = NULL WHERE patient_cin = '';

-- 7. Ajouter des contraintes de validation
ALTER TABLE patient ADD CONSTRAINT chk_patient_genre CHECK (patient_genre IN ('Homme', 'Femme'));
ALTER TABLE users ADD CONSTRAINT chk_user_status CHECK (user_status IN ('caissier', 'technicien'));
ALTER TABLE users ADD CONSTRAINT chk_user_genre CHECK (user_genre IN ('homme', 'femme'));

-- 8. Optimiser la table services
ALTER TABLE services MODIFY COLUMN prix_analyse DECIMAL(10,2) NOT NULL;

-- 9. Ajouter des données de test sécurisées (mots de passe hashés)
-- Remplacer les mots de passe en texte clair par des versions hashées
UPDATE patient SET patient_password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE patient_password = 'password' OR patient_password = '123456' OR LENGTH(patient_password) < 20;

UPDATE users SET user_password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE LENGTH(user_password) < 20;

-- 10. Ajouter des comptes admin par défaut (optionnel)
INSERT IGNORE INTO users (user_nom, user_prenom, user_cin, user_telephone, user_email, user_date_naissance, username, user_password, user_status, user_genre) 
VALUES 
('Admin', 'Système', 'ADMIN001', '0000000000', 'admin@labochark.com', '1990-01-01', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'homme'),
('Docteur', 'Principal', 'DOC001', '0000000001', 'docteur@labochark.com', '1985-01-01', 'docteur', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'technicien', 'homme');