<?php
require 'connexion.php';

$sql = "SELECT * FROM films";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Films</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Liste des Films</h1>
        <nav>
            <a href="page.php">Accueil</a>
            <a href="#">Ajouter un Film</a>
            <a href="liste_films.php">Liste des Films</a>
            <a href="#">Contact</a>
        </nav>
    </header>

    <main>
        <h2>Votre Collection de Films</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Année</th>
                    <th>Synopsis</th>
                    <th>Réalisateur</th>
                    <th>Genre</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['Id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['Title']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['year']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['synopsis']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['director']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['genre']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2024 Ma Collection de Films. Tous droits réservés.</p>
    </footer>
</body>
</html>
