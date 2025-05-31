


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Chèques</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Gestion des Chèques</h1>

        <!-- Formulaire d'ajout -->
        <div class="form-section">
            <h2>Ajouter un Chèque</h2>
            <form id="chequeForm">
                <input type="text" placeholder="Numéro du chèque" required>
                <input type="number" placeholder="Montant" required>
                <select>
                    <option value="En attente">En attente</option>
                    <option value="Encaisse">Encaisse</option>
                    <option value="Rejeté">Rejeté</option>
                </select>
                <button type="submit">Ajouter</button>
            </form>
        </div>

        <!-- Liste des chèques -->
        <h2>Liste des Chèques</h2>
        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="chequeList">
                <tr>
                    <td>123456</td>
                    <td>50,000 FCFA</td>
                    <td>En attente</td>
                    <td><button class="delete-btn">Supprimer</button></td>
                </tr>
            </tbody>
        </table>
    </div>

    <script src="script.js"></script>
</body>
</html>


<style>
                /* body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
        } */

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background: #007bff;
            color: white;
        }

        button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            background: #28a745;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

</style>


<script>
                    document.getElementById("chequeForm").addEventListener("submit", function(event) {
                event.preventDefault();

                let numero = document.querySelector("input[type=text]").value;
                let montant = document.querySelector("input[type=number]").value;
                let statut = document.querySelector("select").value;

                let row = `<tr>
                            <td>${numero}</td>
                            <td>${montant} FCFA</td>
                            <td>${statut}</td>
                            <td><button class="delete-btn">Supprimer</button></td>
                        </tr>`;

                document.getElementById("chequeList").innerHTML += row;
            });

            document.addEventListener("click", function(event) {
                if (event.target.classList.contains("delete-btn")) {
                    event.target.parentElement.parentElement.remove();
                }
            });

</script>
