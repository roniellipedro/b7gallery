<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>B7Gallery</title>
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500&family=Open+Sans:ital@0;1&family=Oswald:wght@400;700&display=swap"
        rel="stylesheet">

</head>

<body>
    <header>
        <div class="wrapper">
            <a href="/" class="logo">B7<span>Gallery</span></a>
            <div class="hero-area">
                <div class="hero-area-left">
                    <h1>Envie agora as suas melhores fotografias.</h1>
                    <form method="POST" action="{{ route('upload') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="input-file-container">
                            <input type="file" name="image" />
                            <img src="./assets/icons/Frame.png" alt="Botão de upload" />
                        </div>
                        <input type="text" name="title" placeholder="Escreva um título para a foto" />
                        <input type="submit" value="Enviar" />
                    </form>
                </div>
                <div class="hero-area-right">
                    <img src="./assets/images/img-banner.png" alt="Banner de exemplo" />
                </div>
            </div>
        </div>
    </header>
    <main class="gallery-container wrapper">

        @foreach ($images as $image)
            <x-image :id="$image->id" :url="asset('storage/uploads/' . $image['hashname'])" :title="$image['title']" />
        @endforeach

    </main>

    <footer class="wrapper">
        <a href="/" class="logo">B7<span>Gallery</span></a>
        <p>Powered by B7Web</p>
    </footer>
</body>

</html>
