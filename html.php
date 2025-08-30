<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HTML Element of the Day</title>
    <link rel="icon" href="/favicon.ico" />
    <meta name="description" value="A random HTML element, chosen every day, and available as a web feed." />
    <link rel="alternate" href="https://granary.io/url?input=html&output=jsonfeed&url=https://random.jamesg.blog/html.php" type="application/xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: "Public Sans", serif;
            box-sizing: border-box;
        }
        & {
            background-color: #462749;
            color: #BAD1CD;
            border-top: 0.25rem solid #BAD1CD;
        }
        main {
            max-width: 35rem;
            margin: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        li {
            display: flex;
            gap: 1rem;
            flex-direction: row;
            flex-wrap: wrap;
        }
        li:after {
            content: "⁂";
            text-align: center;
            flex-basis: 100%;
            margin-top: 1rem;
            margin-bottom: 1rem;
        }
        li p {
            margin: 0;
        }
        h1 {
            margin-top: 5rem;
            text-wrap: balance;
        }
        li a:before {
            content: "# ";
        }
        a { 
            color: #8b5e84ff;
        }
        img {
            float: right;
        }
        ul {
            margin: 0;
            margin-top: 3rem;
            list-style: none;
            padding: 0;
        }
        @media screen and (max-width: 500px) {
            img {
                float: none;
                margin-top: 1rem;
            }
            h1 {
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <main class="h-feed">
        <img src="https://editor.jamesg.blog/content/images/2025/08/JamesCoffeeBlog_Mascot_14.png" height="105" width="165" />
        <h1 class="p-name">HTML Element of the Day</h1>
        <p class="p-note description">A random HTML element, chosen each day.</p>
        <p><a href="https://subscribeopenly.net/subscribe/?url=https://random.jamesg.blog/html.php">Subscribe to the web feed.</a></p>
        <ul>
            <?php
            $category = "HTML_ELEMENT_OF_THE_DAY";
            $choices = file(__DIR__ . "/../elements.txt");
            $preamble = "The HTML element of the day is ";
            $db = new SQLite3(__DIR__ . "/../random.sqlite", SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

            $db->query("CREATE TABLE IF NOT EXISTS items (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                date TIMESTAMP NOT NULL,
                chosen_item TEXT NOT NULL,
                category TEXT NOT NULL
            )");

            $today = $db->prepare("SELECT * FROM items WHERE date = :date");
            $today->bindValue(":date", date("Y-m-d"));
            $today = $today->execute();

            if ($today->fetchArray(SQLITE3_ASSOC) === false) {
                $statement = $db->prepare("INSERT INTO items (date, chosen_item, category) VALUES (:date, :chosen_item, :category);");
                $statement->bindValue(":date", date("Y-m-d"));
                $statement->bindValue(":chosen_item", $choices[array_rand($choices)]);
                $statement->bindValue(":category", $category);
                $statement->execute();
            }

            $today = $db->prepare("SELECT * FROM items ORDER BY date DESC LIMIT 30;");
            $today = $today->execute();

            while ($item = $today->fetchArray(SQLITE3_ASSOC)) {
                echo "<li class='h-entry' id='" . date("Y-m-d") . "'><a class='u-url' href='" . "#" . date("Y-m-d") . "<date class='p-published' value='". date("Y-m-d") . "'>"
                . date("Y-m-d") . "</date></a>" . "<p class='e-content'>" . $preamble . htmlspecialchars($item["chosen_item"]) . ".</p></li>";
            } 
            ?>
        </ul>
        <footer>
            <p>Made with love of the web by <a href="https://jamesg.blog">capjamesg</a>.</p>
        </footer>
    </main>
</body>
</html>