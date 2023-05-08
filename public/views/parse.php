<head>
    <title>XML PARSER</title>
    <link rel="stylesheet" type="text/css" href="public/css/parse.css">
</head>

<body>
    <div class="base-container">
        <main>
            <section class="xml-form">
                <h1>To parse your file you need to place it in public/uploads folder and use its name in the form on the right side of this page (eg. feed.xml), also specify please your desired output file (eg. feed_out.xml)</h1>
                <form action="parseFile" method="POST">
                    <div class="messages">
                        <?php
                        if (isset($active)) {
                            echo "Number of active orders: " . $active . "<br>";
                        }
                        if (isset($paused)) {
                            echo "Number of paused orders: " . $paused . "<br>";
                        }
                        ?>
                    </div>
                    <input name="input_file" type="text" placeholder="feed.xml">
                    <input name="output_file" type="text" placeholder="feed_out.xml">
                    <button type="submit">parse</button>
                </form>
            </section>
        </main>
    </div>
</body>