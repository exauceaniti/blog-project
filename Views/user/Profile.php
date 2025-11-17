 <div class="container">
        <h1>Mon Profil</h1>
        
        <?php if (!empty($user)): ?>
            <div class="profile-info">
                <div class="info-item">
                    <strong>Nom :</strong>
                    <span><?= htmlspecialchars($user->nom) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Email :</strong>
                    <span><?= htmlspecialchars($user->email) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Rôle :</strong>
                    <span><?= htmlspecialchars($user->role) ?></span>
                </div>
                
                <div class="info-item">
                    <strong>Date d'inscription :</strong>
                    <span><?= htmlspecialchars($user->date_inscription) ?></span>
                </div>
            </div>
            
            <a href="/logout" class="logout-link">Se déconnecter</a>
        <?php else: ?>
            <div class="error-message">
                <p>Utilisateur introuvable.</p>
            </div>
        <?php endif; ?>
    </div>


<style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-weight: 600;
            border-bottom: 2px solid #4a90e2;
            padding-bottom: 10px;
        }
        
        .profile-info {
            margin-bottom: 25px;
        }
        
        .info-item {
            margin-bottom: 15px;
            padding: 12px;
            background-color: #f9f9f9;
            border-radius: 6px;
            border-left: 4px solid #4a90e2;
        }
        
        .info-item strong {
            color: #333;
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-item span {
            color: #555;
            font-size: 16px;
        }
        
        .logout-link {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 12px 24px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s;
            text-align: center;
            width: 100%;
            margin-top: 10px;
        }
        
        .logout-link:hover {
            background-color: #c0392b;
        }
        
        .error-message {
            text-align: center;
            color: #e74c3c;
            padding: 20px;
            background-color: #fdf2f2;
            border-radius: 6px;
            border: 1px solid #f5c6cb;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>