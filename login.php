<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/login.css">
    <link rel="shortcut icon" href="./media/idenx.png" type="image/x-icon">
    <title>Diagrams MF</title>
</head>
<body>

    <header>
        <div class="container">
            <p class="logo">Diagrams Generator</p>
            <nav>
                <a href="./sigin.html">Sign In</a>
                <a href="./index.html">Go Back</a>
            </nav>
        </div>
    </header>

    <section id="hero">
        <h1>Log In Page</h1>
        <form action="./com/user.php" method="post">
            <section id="credenciales">
                <ul>
                    <li>
                        <label for="host">Host</label>
                        <input id="host" name="txtHost" type="text" maxlength="30" >
                    </li>
                    <li>
                        <label for="port">Port</label>
                        <input id="host" name="txtPort" type="text" maxlength="30" >
                    </li>
                    <li>
                        <label for="dbname">D.B. Name</label>
                        <input id="dbname" name="txtDbname" type="text" maxlength="30" >
                    </li>
                    <li> 
                        <label for="user">User</label>
                        <input id="user" name="txtUser" type="text" maxlength="30" >
                    </li>
                    <li>
                        <label for="password">Password</label>
                        <input id="password" name="txtPassword" type="password" maxlength="30" >
                    </li>
                </ul>
            </section>
            <button>Log In</button>
        </form>
    </section>

    <footer>
        <div class="container">
            <p>&copy; Diagrams Generator 2022</p>
        </div>
    </footer>
    
</body>
</html>