<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interiorgram - Interiology</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
</head>
<body style="margin: 0; padding: 0; font-family: 'Poppins', sans-serif; background-color: #FFFDF9;">

    <nav style="background-color: #371E0A; padding: 15px 50px; display: flex; justify-content: space-between; align-items: center;">
        <div style="color: #FFD9C0; font-size: 24px; font-weight: bold;">Interiology</div>
        <div style="display: flex; gap: 30px;">
            <a href="/" style="color: #FFF; text-decoration: none;">Beranda</a>
            <a href="/kustom" style="color: #FFF; text-decoration: none;">Kustom Ruangan</a>
            <a href="/interiorgram" style="color: #FFD9C0; text-decoration: none; font-weight: bold;">Interiorgram</a>
        </div>
    </nav>

    <div style="display: flex; min-height: 90vh;">
        
        @if($posts->isEmpty())
            <div style="flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #888;">
                <h2>Belum ada inspirasi interior nih...</h2>
                <p>Yuk, jadi yang pertama mendesain di fitur Kustom Ruangan!</p>
            </div>
        @else
            @foreach($posts as $post)
                <div style="display: flex; width: 100%; border-bottom: 2px solid #F0E6DF; padding: 20px 0;">
                    
                    <div style="width: 25%; background-color: #5E5045; color: #FFF; padding: 40px 20px; text-align: center; display: flex; flex-direction: column; align-items: center;">
                        <div style="width: 120px; height: 120px; background-color: #DDD; border-radius: 50%; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; font-size: 40px;">
                            👤
                        </div>
                        <h3 style="margin: 5px 0; color: #FFD9C0;">{{ $post->user?->name ?? 'Pengguna' }}</h3>
                        
                        <p style="font-size: 14px; color: #E8D8CE; font-style: italic; margin-top: 15px; padding: 0 10px; line-height: 1.5;">
                            "{{ $post->caption }}"
                        </p>
                    </div>

                    <div style="width: 75%; padding: 20px 40px; display: flex; align-items: center; justify-content: center; background-color: #FFFDF9;">
                        <div style="width: 100%; max-width: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                            <img src="{{ \Storage::url($post->image) }}" alt="Desain Kustom Ruangan" style="width: 100%; height: auto; display: block; object-fit: cover;">
                        </div>
                    </div>

                </div>
            @endforeach
        @endif

    </div>

</body>
</html>
