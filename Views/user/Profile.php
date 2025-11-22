<!-- Views/user/profile.php -->
<div class="container">
    <div class="page-header">
        <div class="page-header-content">
            <h1 class="page-title">Mon Profil</h1>
            <div class="page-actions">
                <a href="/profile/edit" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Modifier le profil
                </a>
            </div>
        </div>
    </div>

    <div class="content-area">
        <!-- Messages Flash -->
        <!-- Body -->
        <!-- Arrangement pour les messages flash -->
        <div class="auth-body">
            <?php if ($flash['hasError']): ?>
                <div class="alert alert-error">
                    <div class="alert-content">
                        <div class="alert-icon">⚠️</div>
                        <div class="alert-text">
                            <div class="alert-message"><?= $flash['error'] ?></div>
                        </div>
                        <button class="alert-close">&times;</button>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($flash['hasSuccess']): ?>
                <div class="alert alert-success">
                    <div class="alert-content">
                        <div class="alert-icon">✓</div>
                        <div class="alert-text">
                            <div class="alert-message"><?= $flash['success'] ?></div>
                        </div>
                        <button class="alert-close">&times;</button>
                    </div>
                </div>
            <?php endif; ?>


            <div class="grid grid-1 lg:grid-3 gap-6">
                <!-- Carte Informations -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user"></i>
                            Informations personnelles
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="profile-info">
                            <div class="info-item">
                                <label class="info-label">Nom d'utilisateur</label>
                                <div class="info-value"><?= htmlspecialchars($user->username) ?></div>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Email</label>
                                <div class="info-value"><?= htmlspecialchars($user->email) ?></div>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Rôle</label>
                                <div class="info-value">
                                    <span class="badge badge-<?= $user->role === 'admin' ? 'success' : 'info' ?>">
                                        <?= ucfirst($user->role) ?>
                                    </span>
                                </div>
                            </div>
                            <div class="info-item">
                                <label class="info-label">Membre depuis</label>
                                <div class="info-value">
                                    <?= date('d/m/Y', strtotime($user->created_at)) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Statistiques -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar"></i>
                            Mes statistiques
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="stats-grid">
                            <div class="stat-card secondary">
                                <div class="stat-icon">📝</div>
                                <div class="stat-content">
                                    <div class="stat-value">12</div>
                                    <div class="stat-label">Articles publiés</div>
                                </div>
                            </div>
                            <div class="stat-card success">
                                <div class="stat-icon">💬</div>
                                <div class="stat-content">
                                    <div class="stat-value">47</div>
                                    <div class="stat-label">Commentaires</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Carte Actions rapides -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bolt"></i>
                            Actions rapides
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="action-grid">
                            <a href="/articles/create" class="action-card">
                                <div class="action-icon">✏️</div>
                                <div class="action-title">Nouvel article</div>
                                <div class="action-description">Rédiger un nouvel article</div>
                            </a>
                            <a href="/profile/settings" class="action-card">
                                <div class="action-icon">⚙️</div>
                                <div class="action-title">Paramètres</div>
                                <div class="action-description">Gérer vos préférences</div>
                            </a>
                            <a href="/logout" class="action-card" onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                                <div class="action-icon">🚪</div>
                                <div class="action-title">Déconnexion</div>
                                <div class="action-description">Quitter votre session</div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dernières activités -->
            <div class="card mt-6">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-history"></i>
                        Activités récentes
                    </h3>
                </div>
                <div class="card-body">
                    <div class="activities-list">
                        <div class="activity-item">
                            <div class="activity-icon">📝</div>
                            <div class="activity-content">
                                <div class="activity-title">Vous avez publié un article</div>
                                <div class="activity-meta">Il y a 2 heures</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">💬</div>
                            <div class="activity-content">
                                <div class="activity-title">Vous avez commenté un article</div>
                                <div class="activity-meta">Il y a 1 jour</div>
                            </div>
                        </div>
                        <div class="activity-item">
                            <div class="activity-icon">👍</div>
                            <div class="activity-content">
                                <div class="activity-title">Vous avez aimé un article</div>
                                <div class="activity-meta">Il y a 3 jours</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>