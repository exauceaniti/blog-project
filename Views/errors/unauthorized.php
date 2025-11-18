


        <div class="container">
            <h1><?= $title ?></h1>
            <p><?= $message ?></p>
            <a href="/login">Se connecter</a>
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
            padding: 40px;
            width: 100%;
            max-width: 500px;
            text-align: center;
            border-top: 4px solid #e74c3c;
        }
        
        h1 {
            color: #e74c3c;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 28px;
        }
        
        .error-icon {
            font-size: 48px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        
        .error-message {
            color: #555;
            font-size: 18px;
            line-height: 1.5;
            margin-bottom: 30px;
            padding: 0 20px;
        }
        
        .back-link {
            display: inline-block;
            background-color: #4a90e2;
            color: white;
            padding: 12px 30px;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        
        .back-link:hover {
            background-color: #3a7bc8;
        }
        
        @media (max-width: 480px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .error-message {
                font-size: 16px;
                padding: 0 10px;
            }
        }
    </style>