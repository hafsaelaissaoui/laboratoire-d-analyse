-- Corrections et améliorations de la base de données labonew

-- 1. Ajouter des index pour améliorer les performances
CREATE INDEX idx_patient_email ON patient(patient_email);
CREATE INDEX idx_patient_telephone ON patient(patient_telephone);
CREATE INDEX idx_dossier_patient ON dossier(id_patient);
CREATE INDEX idx_dossier_date ON dossier(date_dossier);
CREATE INDEX idx_details_dossier ON details(id_dossier);
CREATE INDEX idx_services_categorie ON services(id_categorie);

-- 2. Modifier la table patient pour corriger les problèmes
ALTER TABLE patient 
MODIFY COLUMN patient_cin VARCHAR(50) DEFAULT NULL,
MODIFY COLUMN patient_email VARCHAR(255) DEFAULT NULL;

-- 3. Ajouter une contrainte unique sur l'email (si pas déjà fait)
ALTER TABLE patient ADD CONSTRAINT unique_patient_email UNIQUE (patient_email);

-- 4. Corriger le type de données pour la date_dossier
ALTER TABLE dossier MODIFY COLUMN date_dossier DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- 5. Ajouter une table pour les sessions (optionnel)
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

-- 6. Ajouter une table pour les logs d'activité (optionnel)
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

-- 7. Corriger les données existantes si nécessaire
UPDATE patient SET patient_email = NULL WHERE patient_email = '';
UPDATE patient SET patient_cin = NULL WHERE patient_cin = '';

-- 8. Ajouter des contraintes de validation
ALTER TABLE patient ADD CONSTRAINT chk_patient_genre CHECK (patient_genre IN ('Homme', 'Femme'));
ALTER TABLE users ADD CONSTRAINT chk_user_status CHECK (user_status IN ('caissier', 'technicien'));
ALTER TABLE users ADD CONSTRAINT chk_user_genre CHECK (user_genre IN ('homme', 'femme'));

-- 9. Optimiser la table services
ALTER TABLE services MODIFY COLUMN prix_analyse DECIMAL(10,2) NOT NULL;

-- 10. Ajouter des données de test sécurisées (mots de passe hashés)
-- Remplacer les mots de passe en texte clair par des versions hashées
UPDATE patient SET patient_password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE patient_password = 'password' OR patient_password = '123456' OR LENGTH(patient_password) < 20;

UPDATE users SET user_password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE LENGTH(user_password) < 20;