      <div class="container">
          <h1>Connexion</h1>

          <form action="/login" method="post">
              <label for="email">Email :</label>
              <input type="email" name="email" id="email" required>

              <label for="password">Mot de passe :</label>
              <input type="password" name="password" id="password" required>

              <button type="submit">Se connecter</button>
          </form>

          <p>Pas encore inscrit ? <a href="/register">Créer un compte</a></p>
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
              max-width: 400px;
          }

          h1 {
              text-align: center;
              margin-bottom: 25px;
              color: #333;
              font-weight: 600;
          }

          form {
              display: flex;
              flex-direction: column;
          }

          label {
              margin-bottom: 8px;
              color: #555;
              font-weight: 500;
          }

          input {
              padding: 12px;
              margin-bottom: 20px;
              border: 1px solid #ddd;
              border-radius: 4px;
              font-size: 16px;
              transition: border-color 0.3s;
          }

          input:focus {
              border-color: #4a90e2;
              outline: none;
              box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
          }

          button {
              background-color: #4a90e2;
              color: white;
              border: none;
              padding: 12px;
              border-radius: 4px;
              font-size: 16px;
              cursor: pointer;
              transition: background-color 0.3s;
              font-weight: 600;
              margin-top: 10px;
          }

          button:hover {
              background-color: #3a7bc8;
          }

          p {
              text-align: center;
              margin-top: 20px;
              color: #666;
          }

          a {
              color: #4a90e2;
              text-decoration: none;
              font-weight: 500;
          }

          a:hover {
              text-decoration: underline;
          }
      </style>